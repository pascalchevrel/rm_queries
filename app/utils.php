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