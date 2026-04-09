<?php

/**
 * Utility alias to file_get_contents() that also put request in a
 * debug global array
 */
function gfc(string $url): string|false {
    $GLOBALS['urls'][] = $url;
    $context = stream_context_create(
        [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:133.0) Gecko/20100101 Firefox/133.0\r\n" .
                            "Snap-Device-Series: 16\r\n",
                "timeout" => 10,
            ]
        ]
    );
    return file_get_contents($url, false, $context);
}

function getRemoteFile($url, $cache_file, $time = 10800, $header = null) {
    $GLOBALS['urls'][] = $url;
    $cache_file = CACHE . $cache_file;

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
                                    "Snap-Device-Series: 16\r\n",
                        "timeout" => 10,
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

/**
 * Fetch multiple URLs in parallel using curl_multi.
 * Each request: ['url' => string, 'cache_file' => string, 'time' => int, 'headers' => string[]]
 * Only fetches requests whose cache file is stale. Writes raw responses to cache files.
 */
function fetchParallel(array $requests): void {
    $to_fetch = [];
    foreach ($requests as $i => $req) {
        $cache_ok = file_exists($req['cache_file']) && time() - $req['time'] < filemtime($req['cache_file']);
        if (! $cache_ok) {
            $to_fetch[$i] = $req;
            $GLOBALS['urls'][] = $req['url'];
        }
    }

    if (empty($to_fetch)) {
        return;
    }

    $ua      = 'Mozilla/5.0 (X11; Linux x86_64; rv:133.0) Gecko/20100101 Firefox/133.0';
    $mh      = curl_multi_init();
    $handles = [];

    foreach ($to_fetch as $i => $req) {
        $ch      = curl_init($req['url']);
        $headers = ['User-Agent: ' . $ua];
        if (! empty($req['headers'])) {
            $headers = array_merge($headers, $req['headers']);
        }
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        $handles[$i] = $ch;
        curl_multi_add_handle($mh, $ch);
    }

    do {
        $status = curl_multi_exec($mh, $running);
        if ($running) {
            curl_multi_select($mh);
        }
    } while ($running > 0 && $status === CURLM_OK);

    foreach ($handles as $i => $ch) {
        $content = curl_multi_getcontent($ch);
        if ($content !== false && $content !== '') {
            file_put_contents($to_fetch[$i]['cache_file'], $content);
        }
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }

    curl_multi_close($mh);
}

function HTMLList(array $elements) : string {
    return '<ul><li>' . implode('</li><li>', $elements) . '</li></ul>';
}

/**
 * Sanitize a string for security before template use.
 * This is in addition to twig default sanitizinf for cases
 * where we may want to disable it.
 */
function secureText(string $string): string {
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