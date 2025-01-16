<?php

/**
 * Utility alias to file_get_contents() that also put request in a
 * debug global array
 */
function gfc(string $url): string|false {
    $GLOBALS['urls'][] = $url;
    return file_get_contents($url);
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