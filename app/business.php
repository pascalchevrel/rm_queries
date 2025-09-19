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
    $cache_file = CACHE . 'microsoft_store.txt';
    // Serve from cache if it is younger than $cache_time
    $cache_ok = file_exists($cache_file) && time() - $time < filemtime($cache_file);

    if (! $cache_ok) {
        $version = '';
        $data = json_decode(gfc(External::Microsoft_Store->value), true);
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
        return 'n/a';
    }

    return substr(current($matches), 4);
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
function getAppleStoreVersion(string $app): string {
    if (! in_array($app, ['firefox', 'focus', 'klar'])) {
        return 'n/a';
    }

    $product = [
        'firefox' => 'firefox-private-safe-browser/id989804926',
        'focus'   => 'firefox-focus-privacy-browser/id1055677337',
        'klar'    => 'https://apps.apple.com/de/app/klar-by-firefox/id1073435754',
    ];

    $url = $app == 'klar'
        ? $product[$app]
        : External::Apple_Store->value . $product[$app];

    $html = gfc($url);

    $matches = [];
    preg_match('/Version \d+\.\d+/', $html, $matches);

    if (empty($matches) || count($matches) > 1) {
        return 'n/a';
    }

    return substr(current($matches), 8);
}

function getLatestMavenVersion(): string {
    $xml = gfc(External::Maven_AS_nightly->value);

    $matches = [];
    preg_match('/<latest>(.+?)<\/latest>/', $xml, $matches);

    if (empty($matches) || count($matches) > 2) {
        return 'n/a';
    }

    return $matches[1];
}