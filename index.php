<?php
$cache_time   = 18000; // 5 hours
$cache_source = 'https://product-details.mozilla.org/1.0/firefox_versions.json';
$cache_file   = 'firefox_versions_local.json';

// Serve from the cache if it is younger than $cache_time
$cache_ok = file_exists($cache_file) && time() - $cache_time < filemtime($cache_file);

if (! $cache_ok) {
	file_put_contents($cache_file, file_get_contents($cache_source, true));
}

$firefox_versions = json_decode(file_get_contents($cache_file), true);

define('ESR', $firefox_versions["FIREFOX_ESR"]);
define('ESR_NEXT', $firefox_versions["FIREFOX_ESR_NEXT"]);
define('NIGHTLY', $firefox_versions["FIREFOX_NIGHTLY"]);
define('DEV_EDITION', $firefox_versions["FIREFOX_DEVEDITION"]);
define('BETA', $firefox_versions["LATEST_FIREFOX_RELEASED_DEVEL_VERSION"]);
define('RELEASE', $firefox_versions["LATEST_FIREFOX_VERSION"]);

$main_nightly = (int) NIGHTLY;
$main_beta 	  = (int) BETA;
$main_release = (int) RELEASE;

$stub_search_bz = 'https://bugzilla.mozilla.org/buglist.cgi?query_format=advanced';

// Release notes
$relnotes_stub =
	$stub_search_bz
	. '&o1=equals'
	. '&v1=%3F'
	. '&o2=anywords'
	. '&v2=affected%2Cfixed%2Cverified%2Cfix-optional'
	. '&f1=cf_tracking_firefox_relnote'
	. '&f2=cf_status_firefox';

$relnotes_nightly = $relnotes_stub . $main_nightly;
$relnotes_beta    = $relnotes_stub . $main_beta;
$relnotes_release = $relnotes_stub . $main_release;

// Uplifts requests
$uplift_stub 	= $stub_search_bz . '&o1=substring&f1=flagtypes.name';
$uplift_beta 	= $uplift_stub . '&v1=approval-mozilla-beta%3F';
$uplift_release = $uplift_stub . '&v1=approval-mozilla-release%3F';;


// Ryan query: crash, leak, security, dataloss, assertion
$malfunction_stub =
	$stub_search_bz
	. '&o5=anywordssubstr'
	. '&j2=OR'
	. '&o1=anywords'
	. '&v5=%2B%20%3F'
	. '&o4=substring'
	. '&v1=affected%20optional'
	. '&v4=sec'
	. '&v6=%2B%20%3F%20blocking'
	. '&o3=anywords'
	. '&v3=crash%20regression%20leak%20topcrash%20assertion%20dataloss'
	. '&resolution=FIXED'
	. '&o6=anywordssubstr'
	. '&f4=bug_group'
	. '&f3=keywords'
	. '&f2=OP'
	. '&f5=cf_blocking_fennec'
	. '&f7=CP';

$malfunction_nightly =
	$malfunction_stub
	. '&f6=cf_tracking_firefox' . $main_nightly
	. '&f1=cf_status_firefox' . $main_nightly;

$malfunction_beta =
	$malfunction_stub
	. '&f6=cf_tracking_firefox' . $main_beta
	. '&f1=cf_status_firefox' . $main_beta;

$malfunction_release =
	$malfunction_stub
	. '&f6=cf_tracking_firefox' . $main_release
	. '&f1=cf_status_firefox' . $main_release;

