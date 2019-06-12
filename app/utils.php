<?php

function getRemoteFile($url, $cache_file, $time = 10800) {

	$cache_file = __DIR__ . '/../cache/' . $cache_file;

	// Serve from cache if it is younger than $cache_time
	$cache_ok = file_exists($cache_file) && time() - $time < filemtime($cache_file);

	if (! $cache_ok) {
		file_put_contents($cache_file, file_get_contents($url, true));
	}

	return json_decode(file_get_contents($cache_file), true);
}
