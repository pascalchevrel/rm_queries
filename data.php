<?php
$cache_time   = 18000; // 5 hours
$cache_source = 'https://product-details.mozilla.org/1.0/firefox_versions.json';
$cache_file   = 'firefox_versions_local.json';

// Serve from cache if it is younger than $cache_time
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
$main_beta    = (int) BETA;
$main_release = (int) RELEASE;

$stub_search_bz = 'https://bugzilla.mozilla.org/buglist.cgi?query_format=advanced';

// Regressions
$regressions_stub =
	$stub_search_bz
	. '&keywords=regression'
	. '&keywords_type=allwords'
	. '&v1=affected'
	. '&o1=equals'
	. '&bug_status=UNCONFIRMED'
	. '&bug_status=NEW'
	. '&bug_status=ASSIGNED'
	. '&bug_status=REOPENED'
	. '&f1=cf_status_firefox';

$regressions_nightly = $regressions_stub . $main_nightly;
$regressions_beta    = $regressions_stub . $main_beta;
$regressions_release = $regressions_stub . $main_release;

// Release notes
$relnotes_stub = function($version) use($stub_search_bz) {
	return $stub_search_bz
	. '&f1=cf_tracking_firefox_relnote'
	. '&o1=equals'
	. '&v1=%3F'
	. '&f2=cf_status_firefox' . $version
	. '&o2=anywords'
	. '&v2=affected%2Cfixed%2Cverified%2Cfix-optional'
	. '&f3=cf_status_firefox' . $version
	. '&o3=notsubstring'
	. '&v3=disabled';
};

$relnotes_nightly = $relnotes_stub($main_nightly);
$relnotes_beta    = $relnotes_stub($main_beta);
$relnotes_release = $relnotes_stub($main_release);

// Uplifts requests
$uplift_stub	= $stub_search_bz . '&o1=substring&f1=flagtypes.name';
$uplift_beta	= $uplift_stub . '&v1=approval-mozilla-beta%3F';
$uplift_release = $uplift_stub . '&v1=approval-mozilla-release%3F';

// Uplifts requests accepted, not landed last week,
$uplift_stub_pending = $uplift_stub
	. '&v2=affected%2Cfix-optional'
	. '&o2=anyexact'
	. '&chfieldfrom=-1w'
	. '&chfieldto=Now';

// Beta uplifts accepted, not landed
$uplift_beta_pending =
	$uplift_stub_pending
	. '&f2=cf_status_firefox' . $main_beta
	. '&v1=approval-mozilla-beta%2B';

// Release uplifts accepted, not landed
$uplift_release_pending =
	$uplift_stub_pending
	. '&f2=cf_status_firefox' . $main_release
	. '&v1=approval-mozilla-release%2B';



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

// Tracking?
$tracking_question_stub =
	$stub_search_bz
	. '&o1=equals'
	. '&v1=%3F'
	. '&f1=cf_tracking_firefox';

$tracking_question_nightly = $tracking_question_stub . $main_nightly;
$tracking_question_beta    = $tracking_question_stub . $main_beta;
$tracking_question_release = $tracking_question_stub . $main_release;

// Tracking+
$tracking_plus_stub =
	$stub_search_bz
	. '&o1=equals'
	. '&v1=%2B'
	. '&f1=cf_tracking_firefox';

$tracking_plus_nightly = $tracking_plus_stub . $main_nightly;
$tracking_plus_beta    = $tracking_plus_stub . $main_beta;
$tracking_plus_release = $tracking_plus_stub . $main_release;

// Tracking+, still open
$open = '&bug_status=UNCONFIRMED&bug_status=NEW&bug_status=ASSIGNED&bug_status=REOPENED';
$tracking_plus_open_nightly = $tracking_plus_nightly. $open;
$tracking_plus_open_beta    = $tracking_plus_beta . $open;
$tracking_plus_open_release = $tracking_plus_release . $open;

// Tracking +, unassigned
$tracking_plus_unassigned_stub =
	$stub_search_bz
	. '&o1=anywordssubstr'
	. '&o2=substring'
	. '&v1=%2B%20blocking'
	. '&v2=nobody%40mozilla.org'
	. '&o3=nowordssubstr'
	. '&v3=fixed%20verified'
	. '&resolution=---'
	. '&resolution=FIXED'
	. '&f2=assigned_to';

$tracking_plus_unassigned_nightly =
	$tracking_plus_unassigned_stub
	. '&f3=cf_status_firefox' . $main_nightly
	. '&f1=cf_tracking_firefox' . $main_nightly;

$tracking_plus_unassigned_beta =
	$tracking_plus_unassigned_stub
	. '&f3=cf_status_firefox' . $main_beta
	. '&f1=cf_tracking_firefox' . $main_beta;

$tracking_plus_unassigned_release =
	$tracking_plus_unassigned_stub
	. '&f3=cf_status_firefox' . $main_release
	. '&f1=cf_tracking_firefox' . $main_release;

// Pending needinfo > 3 days
$ni_days = 3;
$pending_needinfo_stub =
	$stub_search_bz
	. '&f1=flagtypes.name'
	. '&o3=equals'
	. '&v3=affected'
	. '&o1=substring'
	. '&resolution=---'
	. '&o2=greaterthan'
	. '&f2=days_elapsed'
	. '&bug_status=UNCONFIRMED'
	. '&bug_status=NEW'
	. '&bug_status=ASSIGNED'
	. '&bug_status=REOPENED'
	. '&v1=needinfo%3F'
	. '&v2=' . $ni_days
	. '&f3=cf_status_firefox';

$pending_needinfo_nightly = $pending_needinfo_stub . $main_nightly;
$pending_needinfo_beta    = $pending_needinfo_stub . $main_beta;
$pending_needinfo_release = $pending_needinfo_stub . $main_release;

// Recently fixed crashes
$last_days = 7;
$recently_fixed_crashes =
	$stub_search_bz
	. '&keywords=crash'
	. '&chfield=resolution'
	. '&chfieldvalue=FIXED'
	. '&f1=cf_status_firefox' . $main_beta
	. '&o1=nowordssubstr'
	. '&v1=fixed%2Cunaffected%2Cwontfix'
	. '&chfieldfrom=' . $last_days . 'd'
	. '&classification=Client%20Software'
	. '&classification=Components'
	. '&product=Core'
	. '&product=DevTools'
	. '&product=External%20Software%20Affecting%20Firefox'
	. '&product=Firefox'
	. '&product=Firefox%20for%20Android'
	. '&product=NSPR'
	. '&product=NSS'
	. '&product=Toolkit'
	. '&product=WebExtensions'
	. '&chfieldto=Now';

$link = function($url, $text) {
	return '<a href="' . $url . '" target=”_blank”>' . $text . '</a>';
};
