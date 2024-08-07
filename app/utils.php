<?php

$GLOBALS['urls'] = [];

enum External: string
{
    case PD_desktop       = 'https://product-details.mozilla.org/1.0/firefox_versions.json';
    case PD_android       = 'https://product-details.mozilla.org/1.0/mobile_versions.json';
    case Flatpak_release  = 'https://flathub.org/api/v2/appstream/org.mozilla.firefox';
    case Snap_release     = 'https://api.snapcraft.io/v2/snaps/info/firefox';
    case Play_Store       = 'https://play.google.com/store/apps/details?id=';
    case Samsung_release  = 'https://galaxystore.samsung.com/api/detail/org.mozilla.firefox';
    case Apple_release    = 'https://apps.apple.com/us/app/firefox-private-safe-browser/id989804926';
    case Maven_AS_nightly = 'https://maven.mozilla.org/maven2/org/mozilla/appservices/nightly/full-megazord/maven-metadata.xml';
}

function gfc(string $url): string {
    $GLOBALS['urls'][] = $url;
    return file_get_contents($url);
}

function getRemoteFile($url, $cache_file, $time = 10800, $header = null) {
    $GLOBALS['urls'][] = $url;
    $cache_file = __DIR__ . '/../cache/' . $cache_file;

    // Serve from cache if it is younger than $cache_time
    $cache_ok = file_exists($cache_file) && time() - $time < filemtime($cache_file);

    if (! $cache_ok) {
        $context = null;
        if (! is_null($header)) {
            $context = stream_context_create(
                [
                    "http" => [
                        "method" => "GET",
                        "header" => "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0\r\n" .
                                    "Snap-Device-Series: 16\r\n"
                    ]
                ]
            );
        }

        file_put_contents($cache_file, file_get_contents($url, false, $context));
    }

    return file_get_contents($cache_file);
}

function getRemoteJson($url, $cache_file, $time = 10800, $header = null) {
    return json_decode(getRemoteFile($url, $cache_file, $time, $header), true);
}

function HTMLList(array $elements) : string {
    return '<ul><li>' . implode('</li><li>', $elements) . '</li></ul>';
}

function getBugsPerNightly($firefox_versions) : string {
    $tomorrow = new DateTime('tomorrow');
    $day = new DateTime($firefox_versions["LAST_MERGE_DATE"]);
    $main_nightly = (int) FIREFOX_NIGHTLY;

    $bz_link = 'https://bugzilla.mozilla.org/buglist.cgi? '
    . '&query_format=advanced'
    . '&chfield=cf_status_firefox' . $main_nightly
    . '&chfieldvalue=fixed'
    . '&classification=Client%20Software'
    . '&classification=Components'
    . '&resolution=FIXED'
    . '&order=component'
    . '&f1=product'
    . '&o1=notequals'
    . '&v1=Testing';

    $nightlies = [];

    while ($day->format('Y-m-d') !=  $tomorrow->format('Y-m-d')) {
        $d = '&chfieldfrom=' . $day->format('Y-m-d') . '&chfieldto=' . $day->format('Y-m-d');
        $title = '&title=Nightly%20Bugs%20' . $day->format('Y-m-d');
        $nightlies[] = '<a href="' . $bz_link . $d . $title . '" target="_blank" rel="noopener">' . $day->format('Y-m-d') . '</a>';
        $day->modify('+1 day');
    }

    return HTMLList($nightlies);
}

/**
 * Sanitize a string for security before template use.
 * This is in addition to twig default sanitizinf for cases
 * where we may want to disable it.
 */
function secureText(string $string): string
{
    // CRLF XSS
    $string = str_replace(['%0D', '%0A'], '', $string);
    // We want to convert line breaks into spaces
    $string = str_replace("\n", ' ', $string);
    // Escape HTML tags and remove ASCII characters below 32
    return filter_var(
        $string,
        FILTER_SANITIZE_SPECIAL_CHARS,
        FILTER_FLAG_STRIP_LOW
    );
}

/**
 * Scrap the Android version from HTML
 */
function getAndroidVersion(string $appName): string {
    $html = gfc(External::Play_Store->value . $appName);

    $matches = [];
    if ($appName == 'org.mozilla.firefox_beta') {
        preg_match('/\[\[\[\"\d+\.\d+b\d+/', $html, $matches);
    } else {
        // Try XXX.x.x first
        preg_match('/\[\[\[\"\d+\.\d+\.\d+/', $html, $matches);
        if (empty($matches)) {
            // Try xxx.x
            preg_match('/\[\[\[\"\d+\.\d+/', $html, $matches);
        }
    }

    if (empty($matches) || count($matches) > 1) {
        return 'N/A';
    }

    return substr(current($matches), 4);
}

/**
 * Scrap the iOS version from HTML
 */
function getAppleStoreVersion(): string {

    $html = gfc(External::Apple_release->value);

    $matches = [];
    preg_match('/Version \d+\.\d+/', $html, $matches);

    if (empty($matches) || count($matches) > 1) {
        return 'N/A';
    }

    return substr(current($matches), 8);
}


function getLatestMavenVersion(): string {
    $xml = gfc(External::Maven_AS_nightly->value);

    $matches = [];
    preg_match('/<latest>(.+?)<\/latest>/', $xml, $matches);

    if (empty($matches) || count($matches) > 2) {
        return 'N/A';
    }

    return $matches[1];
}