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

// The only endpoint we are willing to proxy.
const BZ_REST_BASE = 'https://bugzilla.mozilla.org/rest/bug?';

// Decode &amp; entities and normalise the leading '?&' before any validation,
// so the checks (and the cache key) see the real URL.
$url = html_entity_decode($_GET['url'] ?? '');
$url = str_replace('?&', '?', $url);

// Reject malformed input rather than forwarding it to Bugzilla (which would
// answer with a 400/500 and cost us a round-trip). Guards against:
//   - a doubled base URL: rest/bug?https://.../rest/bug?...
//   - a stripped query (unencoded '&' splitting the param off): bare rest/bug?
//   - any non-Bugzilla URL (this is not an open proxy)
$query     = substr($url, strlen(BZ_REST_BASE));
$malformed = ! str_starts_with($url, BZ_REST_BASE)   // wrong/missing base
    || str_contains($query, BZ_REST_BASE)            // base appears twice
    || ! str_contains($query, '=');                  // no actual filter params

if ($malformed) {
    error_log('bugcount: rejected malformed url=' . $url);
    http_response_code(400);
    echo json_encode(['error' => 'Malformed Bugzilla query URL']);
    exit;
}

$cache_file = CACHE . 'bz_count_' . md5($url) . '.json';
$ttl        = 600; // 10 minutes
$cache_ok   = file_exists($cache_file) && time() - $ttl < filemtime($cache_file);

if (! $cache_ok) {
    $context = stream_context_create([
        'http' => [
            'method'        => 'GET',
            'header'        => 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:153.0) Gecko/20100101 Firefox/153.0',
            'timeout'       => 25,
            // Return the body even on 4xx/5xx so we can read Bugzilla's real
            // status instead of a bare `false`.
            'ignore_errors' => true,
        ],
    ]);

    $response = @file_get_contents($url, false, $context);

    // Extract the HTTP status from the response headers (null on a connection
    // failure, where $http_response_header is unset).
    $status = null;
    if (isset($http_response_header[0])
        && preg_match('#\s(\d{3})\s#', $http_response_header[0], $m)) {
        $status = (int) $m[1];
    }

    if ($response !== false && $status >= 200 && $status < 300) {
        file_put_contents($cache_file, $response);
    } else {
        // Log the real cause so it's visible in `heroku logs`. We then fall
        // through to serve stale cache if we have any.
        error_log(sprintf(
            'bugcount: fetch failed (status=%s) for url=%s',
            $status ?? 'connection-error',
            $url,
        ));
    }
}

if (file_exists($cache_file)) {
    // Serve cached value — fresh, or stale if Bugzilla just errored.
    echo file_get_contents($cache_file);
} else {
    http_response_code(502);
    echo json_encode([
        'error'  => 'Failed to fetch bug count',
        'status' => $status ?? null,
    ]);
}
