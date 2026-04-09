<?php

/**
 * Extract Firefox version from a Microsoft Store API endpoint
 *
 * RANDOM NOTES AND POINTERS:
 * https://github.com/StoreDev/StoreLib
 * https://github.com/ThomasPe/MS-Store-API/issues/9
 * https://github.com/ThomasPe/MS-Store-API?tab=readme-ov-file
 * I couldn't find any version information provided by the Microsoft Store, I found this hidden  API endpoint though:
 * echo '{productIds: "9nzvdkpmr9rd"}' | curl --json @- 'https://storeedgefd.dsx.mp.microsoft.com/v8.0/sdk/products?market=US&locale=en-US&deviceFamily=Windows.Desktop'
 * I also found: https://storeedgefd.dsx.mp.microsoft.com/v9.0/packageManifests/9nzvdkpmr9rd
 * via this blog post: https://skiptotheendpoint.co.uk/under-the-hood-pt-2-microsoft-store-apps/
 * From https://store.rg-adguard.net/ which has an HTML API and scraps the MS Store
 */
function getWindowsStoreVersion($time = 3600): string {
    $parsed_cache = CACHE . 'microsoft_store.txt';
    $raw_cache    = CACHE . 'microsoft_store_raw.json';

    $parsed_fresh = file_exists($parsed_cache) && time() - $time < filemtime($parsed_cache);
    if ($parsed_fresh) {
        return file_get_contents($parsed_cache);
    }

    $raw_fresh = file_exists($raw_cache) && time() - $time < filemtime($raw_cache);
    if ($raw_fresh) {
        $raw = file_get_contents($raw_cache);
    } else {
        $raw = gfc(External::Microsoft_Store->value);
        file_put_contents($raw_cache, $raw);
    }

    $version = '';
    $data    = json_decode($raw, true);
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

    file_put_contents($parsed_cache, $version);
    return file_get_contents($parsed_cache);
}

/**
 * Scrap the Android version from HTML
 */
function getAndroidVersion(string $appName, int $time = 3600): string {
    $parsed_cache = CACHE . 'play_store_' . $appName . '.txt';
    $raw_cache    = CACHE . 'play_store_raw_' . $appName . '.html';

    $parsed_fresh = file_exists($parsed_cache) && time() - $time < filemtime($parsed_cache);
    if ($parsed_fresh) {
        return file_get_contents($parsed_cache);
    }

    $raw_fresh = file_exists($raw_cache) && time() - $time < filemtime($raw_cache);
    if ($raw_fresh) {
        $html = file_get_contents($raw_cache);
    } else {
        $html = gfc(External::Play_Store->value . $appName);
        file_put_contents($raw_cache, $html);
    }

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

    $version = (empty($matches) || count($matches) > 1) ? 'n/a' : substr(current($matches), 4);
    file_put_contents($parsed_cache, $version);
    return $version;
}

/**
 * Scrap the XIAOMI version from HTML
 */
function getXiaomiStoreVersion(string $appName, int $time = 3600): string {
    $parsed_cache = CACHE . 'xiaomi_store_' . $appName . '.txt';
    $raw_cache    = CACHE . 'xiaomi_store_raw_' . $appName . '.html';

    $parsed_fresh = file_exists($parsed_cache) && time() - $time < filemtime($parsed_cache);
    if ($parsed_fresh) {
        return file_get_contents($parsed_cache);
    }

    $raw_fresh = file_exists($raw_cache) && time() - $time < filemtime($raw_cache);
    if ($raw_fresh) {
        $html = file_get_contents($raw_cache);
    } else {
        $html = gfc(External::Xiaomi_Store->value . $appName);
        file_put_contents($raw_cache, $html);
    }

    $matches = [];
    // Try XXX.x.x first
    preg_match('/Version\:\d+\.\d+\.\d+/', $html, $matches);
    if (empty($matches)) {
        // Try xxx.x
        preg_match('/Version\:\d+\.\d+/', $html, $matches);
    }

    $version = (empty($matches) || count($matches) > 1) ? 'n/a' : substr(current($matches), 8);
    file_put_contents($parsed_cache, $version);
    return $version;
}

/**
 * I don't have a solution yet for Huawei
 * 1/ They don't have a public API without authentication with our developer account
 * 2/ Their app listing is all JS generated and have anti-scraping measures
 * 3/ I haven't found other sources cataloguing the Huawei Store
 * 4/ There is no stable apk download link to try and get the first chunk of data to obtain a manifest.json
 *
 * There is a hack suggested by ChatGPT which requires obtaining a token from one of their APIs to
 * simulate that an xhr request to their api for the product listing is coming from their
 * own store, but I get a 403 90% of the time, not reliable at all.
 */
function getHuaweiStoreVersion(): string {
    return 'n/a';
}

/**
 * Scrap the iOS version from HTML
 */
function getAppleStoreVersion(string $app, int $time = 3600): string {
    if (! in_array($app, ['firefox', 'focus', 'klar'])) {
        return 'n/a';
    }

    $parsed_cache = CACHE . 'apple_store_' . $app . '.txt';
    $raw_cache    = CACHE . 'apple_store_raw_' . $app . '.html';

    $parsed_fresh = file_exists($parsed_cache) && time() - $time < filemtime($parsed_cache);
    if ($parsed_fresh) {
        return file_get_contents($parsed_cache);
    }

    $raw_fresh = file_exists($raw_cache) && time() - $time < filemtime($raw_cache);
    if ($raw_fresh) {
        $html = file_get_contents($raw_cache);
    } else {
        $product = [
            'firefox' => 'firefox-private-safe-browser/id989804926',
            'focus'   => 'firefox-focus-privacy-browser/id1055677337',
            'klar'    => 'https://apps.apple.com/de/app/klar-by-firefox/id1073435754',
        ];

        $url  = $app == 'klar'
            ? $product[$app]
            : External::Apple_Store->value . $product[$app];
        $html = gfc($url);
        file_put_contents($raw_cache, $html);
    }

    $matches = [];
    preg_match('/Version \d+\.\d+/', $html, $matches);

    $version = (empty($matches) || count($matches) > 1) ? 'n/a' : substr(current($matches), 8);
    file_put_contents($parsed_cache, $version);
    return $version;
}

function getLatestMavenVersion(int $time = 3600): string {
    $parsed_cache = CACHE . 'maven_as_nightly.txt';
    $raw_cache    = CACHE . 'maven_as_nightly_raw.xml';

    $parsed_fresh = file_exists($parsed_cache) && time() - $time < filemtime($parsed_cache);
    if ($parsed_fresh) {
        return file_get_contents($parsed_cache);
    }

    $raw_fresh = file_exists($raw_cache) && time() - $time < filemtime($raw_cache);
    if ($raw_fresh) {
        $xml = file_get_contents($raw_cache);
    } else {
        $xml = gfc(External::Maven_AS_nightly->value);
        file_put_contents($raw_cache, $xml);
    }

    $matches = [];
    preg_match('/<latest>(.+?)<\/latest>/', $xml, $matches);

    $version = (empty($matches) || count($matches) > 2) ? 'n/a' : $matches[1];
    file_put_contents($parsed_cache, $version);
    return $version;
}

/**
 * Pre-fetch all store data in parallel so that subsequent calls to
 * getAndroidVersion, getAppleStoreVersion, getXiaomiStoreVersion,
 * getLatestMavenVersion, getWindowsStoreVersion, and getRemoteJson-based
 * store calls all serve from cache without any network wait.
 */
function prefetchStoreData(int $time = 3600): void {
    fetchParallel([
        // Play Store (raw HTML cache)
        ['url' => External::Play_Store->value . 'org.mozilla.firefox',      'cache_file' => CACHE . 'play_store_raw_org.mozilla.firefox.html',      'time' => $time],
        ['url' => External::Play_Store->value . 'org.mozilla.firefox_beta', 'cache_file' => CACHE . 'play_store_raw_org.mozilla.firefox_beta.html', 'time' => $time],
        ['url' => External::Play_Store->value . 'org.mozilla.focus',        'cache_file' => CACHE . 'play_store_raw_org.mozilla.focus.html',        'time' => $time],
        ['url' => External::Play_Store->value . 'org.mozilla.klar',         'cache_file' => CACHE . 'play_store_raw_org.mozilla.klar.html',         'time' => $time],
        // Apple Store (raw HTML cache)
        ['url' => External::Apple_Store->value . 'firefox-private-safe-browser/id989804926', 'cache_file' => CACHE . 'apple_store_raw_firefox.html', 'time' => $time],
        ['url' => External::Apple_Store->value . 'firefox-focus-privacy-browser/id1055677337', 'cache_file' => CACHE . 'apple_store_raw_focus.html', 'time' => $time],
        ['url' => 'https://apps.apple.com/de/app/klar-by-firefox/id1073435754', 'cache_file' => CACHE . 'apple_store_raw_klar.html', 'time' => $time],
        // Xiaomi Store (raw HTML cache)
        ['url' => External::Xiaomi_Store->value . 'org.mozilla.firefox', 'cache_file' => CACHE . 'xiaomi_store_raw_org.mozilla.firefox.html', 'time' => $time],
        // Maven (raw XML cache)
        ['url' => External::Maven_AS_nightly->value, 'cache_file' => CACHE . 'maven_as_nightly_raw.xml', 'time' => $time],
        // Microsoft Store (raw JSON cache)
        ['url' => External::Microsoft_Store->value, 'cache_file' => CACHE . 'microsoft_store_raw.json', 'time' => 900],
        // JSON endpoints — same cache files used by getRemoteJson, so hits are served from cache
        ['url' => External::Flatpak_release->value,        'cache_file' => CACHE . 'flathub_firefox_release.json',  'time' => 900],
        ['url' => External::Snap_release->value,           'cache_file' => CACHE . 'snapcraft_firefox_release.json', 'time' => 900, 'headers' => ['Snap-Device-Series: 16']],
        ['url' => External::Samsung_firefox->value,        'cache_file' => CACHE . 'samsung_firefox_release.json',  'time' => 900],
        ['url' => External::Samsung_focus->value,          'cache_file' => CACHE . 'samsung_focus_release.json',    'time' => 900],
        ['url' => External::Balrog->value . 'firefox-beta','cache_file' => CACHE . 'firefox_beta_local.json',       'time' => 900],
    ]);
}
