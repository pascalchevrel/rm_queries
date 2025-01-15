<?php

use \Dom\HTMLDocument as HTML;

$GLOBALS['urls'] = [];

enum External: string
{
    case PD_desktop       = 'https://product-details.mozilla.org/1.0/firefox_versions.json';
    case PD_android       = 'https://product-details.mozilla.org/1.0/mobile_versions.json';
    case Balrog           = 'https://aus-api.mozilla.org/api/v1/rules/';
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



function getWindowsStoreVersion($time = 3600): string {
    $cache_file = __DIR__ . '/../cache/' . 'microsoft_store.txt';
    // Serve from cache if it is younger than $cache_time
    $cache_ok = file_exists($cache_file) && time() - $time < filemtime($cache_file);

    if (! $cache_ok) {
        $version = '';
        $url = "https://displaycatalog.mp.microsoft.com/v7.0/products/lookup?fieldsTemplate=InstallAgent&market=US&languages=en-US,en,neutral&alternateId=PackageFamilyName&value=Mozilla.Firefox_n80bbvh6b1yt2";
        $data = json_decode(file_get_contents($url), true);
        $version = $data['Products'][0]['DisplaySkuAvailabilities'][0]['Sku']['Properties']['Packages'][0]['PackageFullName'];

        if (is_null($version)) {
            $version = 'n/a';
        } else {
            preg_match('/\d+\.\d+\.\d+/', $version, $matches);
            if (is_null($matches[0])) {
                $version = 'n/a';
            } else {
                $version = $matches[0];
                if (str_ends_with($version, '.0.0')) {
                    $version = str_replace('.0.0', '.0', $version);
                }
            }
        }
        file_put_contents($cache_file, $version);
    }

    return file_get_contents($cache_file);
}
function getWindowsStoreVersionOLD($time = 3600): string {
    $cache_file = __DIR__ . '/../cache/' . 'microsoft_store.txt';
    // Serve from cache if it is younger than $cache_time
    $cache_ok = file_exists($cache_file) && time() - $time < filemtime($cache_file);

    if (! $cache_ok) {
        $version = '';
        $url = "http://dl.delivery.mp.microsoft.com/filestreamingservice/files/797ba8fb-7b8b-404b-9473-32731ceef9a7";
        $headers = get_headers($url);
        $headers = array_filter($headers, fn($a) => str_starts_with($a, 'Content-Disposition'));
        $headers = array_values($headers)[0];

        if (is_null($headers)) {
            $version = 'n/a';
        } else {
            preg_match('/\d+\.\d+\.\d+/', $headers, $matches);
            if (is_null($matches[0])) {
                $version = 'n/a';
            } else {
                $version = $matches[0];
                if (str_ends_with($version, '.0.0')) {
                    $version = str_replace('.0.0', '.0', $version);
                }
            }
        }
        file_put_contents($cache_file, $version);
    }

    return file_get_contents($cache_file);
}

function getWindowsStoreVersionOLDER($time = 3600): string {
    $cache_file = __DIR__ . '/../cache/' . 'microsoft_store.txt';
    // Serve from cache if it is younger than $cache_time
    $cache_ok = file_exists($cache_file) && time() - $time < filemtime($cache_file);

    if (! $cache_ok) {
        $version = '';
        $query = [
            'type' => 'url',
            'url'  => 'https://apps.microsoft.com/detail/9nzvdkpmr9rd',
            'ring' => 'Retail',
            'lang' => 'en-US',
        ];
        $context = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n" .
                             "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0\r\n" .
                             "Accept-Language: en-US,en;q=0.5\r\n",
                'content' => http_build_query($query),
            ]
        ];

        $data = file_get_contents('https://store.rg-adguard.net/api/GetFiles', false, stream_context_create($context));

        if ($data === false) {
            $version = 'n/a';
        } else {
            $dom = HTML::createFromString($data, LIBXML_NOERROR);
            $version = $dom->querySelector('td > a')->textContent;
            preg_match('/\d+\.\d+\.\d+/', $version, $matches);

            if (is_null($matches[0])) {
                $version = 'n/a';
            } else {
                $version = $matches[0];
                if (str_ends_with($version, '.0.0')) {
                    $version = str_replace('.0.0', '.0', $version);
                }
            }
        }
        file_put_contents($cache_file, $version);
    }

    return file_get_contents($cache_file);
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
                        "header" => "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:133.0) Gecko/20100101 Firefox/133.0\r\n" .
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