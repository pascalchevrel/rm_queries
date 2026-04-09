<?php

/**
 * Server-side caching proxy for Bugzilla bug count queries.
 * Caches each Bugzilla REST API count response for 15 minutes so that
 * repeated page loads don't hit Bugzilla on every request.
 *
 * Usage: bugcount.php?url=<url-encoded Bugzilla REST URL with count_only=1>
 */

require_once dirname(__DIR__) . '/app/init.php';

header('Content-Type: application/json; charset=UTF-8');

$url = $_GET['url'] ?? '';

if (! str_starts_with($url, 'https://bugzilla.mozilla.org/rest/bug?')) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid URL']);
    exit;
}

$cache_file = CACHE . 'bz_count_' . md5($url) . '.json';
$ttl        = 900; // 15 minutes
$cache_ok   = file_exists($cache_file) && time() - $ttl < filemtime($cache_file);

if (! $cache_ok) {
    $context = stream_context_create([
        'http' => [
            'method'  => 'GET',
            'header'  => 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:133.0) Gecko/20100101 Firefox/133.0',
            'timeout' => 10,
        ],
    ]);
    $response = file_get_contents($url, false, $context);
    if ($response !== false) {
        file_put_contents($cache_file, $response);
    }
}

if (file_exists($cache_file)) {
    echo file_get_contents($cache_file);
} else {
    http_response_code(502);
    echo json_encode(['error' => 'Failed to fetch bug count']);
}
