<?php
require_once "utils.php";

$firefox_versions = getRemoteFile(
	'https://product-details.mozilla.org/1.0/firefox_versions.json',
	'firefox_versions_local.json'
);

$global_message = '';

define('ESR', $firefox_versions["FIREFOX_ESR"]);
define('ESR_NEXT', $firefox_versions["FIREFOX_ESR_NEXT"]);
define('FIREFOX_NIGHTLY', $firefox_versions["FIREFOX_NIGHTLY"]);
define('DEV_EDITION', $firefox_versions["FIREFOX_DEVEDITION"]);
define('FIREFOX_BETA', $firefox_versions["LATEST_FIREFOX_RELEASED_DEVEL_VERSION"]);
define('FIREFOX_RELEASE', $firefox_versions["LATEST_FIREFOX_VERSION"]);

$main_nightly = (int) FIREFOX_NIGHTLY;
$main_beta    = (int) FIREFOX_BETA;
$main_release = (int) FIREFOX_RELEASE;
$main_esr 	  =	(int) (ESR_NEXT != "" ? ESR_NEXT : ESR);
$last_beta 	  = (int) str_replace($main_beta .'.0b', '', FIREFOX_BETA);

if ((int) $firefox_versions["FIREFOX_NIGHTLY"] > (int) $firefox_versions["FIREFOX_DEVEDITION"]) {
	// We are past merge day
	if ((int) $firefox_versions["FIREFOX_DEVEDITION"] > (int) $firefox_versions["LATEST_FIREFOX_RELEASED_DEVEL_VERSION"]) {
		// But beta 3 has not been released yet
		$global_message = '<b>Nightly merged recently, beta 3 not released yet!</b>';
		$main_beta = (int) DEV_EDITION;
	}
}


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
$regressions_esr     = $regressions_stub . '_esr' . $main_esr;

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
$relnotes_esr = $relnotes_stub('_esr' . $main_esr);

// Uplifts requests
$uplift_stub	= $stub_search_bz . '&o1=substring&f1=flagtypes.name';
$uplift_beta	= $uplift_stub . '&v1=approval-mozilla-beta%3F';
$uplift_release = $uplift_stub . '&v1=approval-mozilla-release%3F';
$uplift_esr     = $uplift_stub . '&v1=approval-mozilla-esr' . $main_esr . '%3F';

// Uplifts requests accepted, not landed last week,
$uplift_stub_pending = $uplift_stub
	. '&v2=affected%2Cfix-optional%2C%3F'
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
	. '&o4=substring'
	. '&v1=affected%20optional'
	. '&v4=sec'
	. '&v6=%2B%20%3F%20blocking'
	. '&o3=anywords'
	. '&v3=crash%20regression%20leak%20topcrash%20assertion%20dataloss%20topcrash-linux%20topcrash-mac%20topcrash-win'
	. '&resolution=FIXED'
	. '&o6=anywordssubstr'
	. '&f4=bug_group'
	. '&f3=keywords'
	. '&f2=OP'
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
$tracking_question_esr     = $tracking_question_stub . '_esr' . $main_esr;

// Tracking+, blocking, still open
$tracking_plus_unfixed_stub =
	$stub_search_bz
	. '&o1=anywordssubstr'
	. '&v1=%2B%2Cblocking'
	. '&f3=resolution'
	. '&o3=nowordssubstr'
	. '&v3=INVALID%2CWORKSFORME%2CDUPLICATE'
	. '&o2=nowordssubstr'
	. '&v2=fixed%2Cwontfix%2Cdisabled%2Cverified';

$tracking_plus_unfixed_nightly =
	$tracking_plus_unfixed_stub
	. '&f1=cf_tracking_firefox' . $main_nightly
	. '&f2=cf_status_firefox' . $main_nightly;

$tracking_plus_unfixed_beta =
	$tracking_plus_unfixed_stub
	. '&f1=cf_tracking_firefox' . $main_beta
	. '&f2=cf_status_firefox' . $main_beta;

$tracking_plus_unfixed_release =
	$tracking_plus_unfixed_stub
	. '&f1=cf_tracking_firefox' . $main_release
	. '&f2=cf_status_firefox' . $main_release;

$tracking_plus_esr =
	$stub_search_bz
	. '&f1=cf_tracking_firefox_esr' . $main_esr
	. '&o1=substring'
	. '&v1=' . $main_beta . '%2B';

$tracking_plus_unfixed_esr =
	$stub_search_bz
	. '&f1=cf_tracking_firefox_esr' . $main_esr
	. '&o1=substring'
	. '&v1=' . $main_beta . '%2B'
	. '&f2=cf_status_firefox_esr' . $main_esr
	. '&o2=nowordssubstr'
	. '&v2=fixed%2Cwontfix%2Cdisabled%2Cverified';


// Tracking +, blocking, unassigned
$tracking_plus_unassigned_stub =
	$stub_search_bz
	. '&o1=anywordssubstr'
	. '&o2=substring'
	. '&v1=%2B%2Cblocking'
	. '&v2=nobody%40mozilla.org'
	. '&o3=nowordssubstr'
	. '&v3=fixed%20verified%20wontfix%20disabled'
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


$resolved_fix_optional_stub =
	$stub_search_bz
	. '&o1=equals'
	. '&v1=fix-optional'
	. '&resolution=FIXED'
	. '&f1=cf_status_firefox';

$resolved_fix_optional_beta = $resolved_fix_optional_stub . $main_beta;
$resolved_fix_optional_release = $resolved_fix_optional_stub . $main_release;


// Pending needinfo > 3 days
$ni_days = 3;
$ni_days_nightly = 6;
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
	. '&v1=needinfo%3F';

$pending_needinfo_nightly = $pending_needinfo_stub . '&v2=' . $ni_days_nightly . '&f3=cf_status_firefox' . $main_nightly;
$pending_needinfo_beta    = $pending_needinfo_stub . '&v2=' . $ni_days . '&f3=cf_status_firefox' . $main_beta;
$pending_needinfo_release = $pending_needinfo_stub . '&v2=' . $ni_days . '&f3=cf_status_firefox' . $main_release;

// Recently fixed crashes
$last_days_crashes = 21;
$recently_fixed_crashes =
	$stub_search_bz
	. '&keywords=crash'
	. '&chfield=resolution'
	. '&chfieldvalue=FIXED'
	. '&f1=cf_status_firefox' . $main_beta
	. '&o1=nowordssubstr'
	. '&v1=fixed%2Cunaffected%2Cwontfix%2Cverified%2Cdisabled'
	. '&chfieldfrom=' . $last_days_crashes . 'd'
	. '&chfieldto=Now'
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
	. '&product=WebExtensions';


// Bugs fixed but not uplifted =potential uplifts
$fixed_regressions_candidates_stub =
	$stub_search_bz
	. '&keywords=regression'
	. '&keywords_type=allwords'
	. '&o1=equals'
	. '&v1=affected'
	. '&resolution=FIXED'
	. '&f1=cf_status_firefox';

// Security bugs
$security_stub =
	$stub_search_bz
	. '&o3=anywords'
	. '&v3=sec-critical%20sec-high%20sec-moderate%20sec-low'
	. '&f3=keywords'
	. '&v1=affected'
	. '&o1=equals'
	. '&bug_status=UNCONFIRMED'
	. '&bug_status=NEW'
	. '&bug_status=ASSIGNED'
	. '&bug_status=REOPENED'
	. '&f1=cf_status_firefox';

$security_nightly = $security_stub . $main_nightly;
$security_beta    = $security_stub . $main_beta;
$security_release = $security_stub . $main_release;

$fixed_regressions_candidates_beta = $fixed_regressions_candidates_stub . $main_beta;
$fixed_regressions_candidates_release = $fixed_regressions_candidates_stub . $main_release;

// Enhancements
$enhancement_stub =
	$stub_search_bz
	. '&bug_type=enhancement'
	. '&o1=anywordssubstr'
	. '&v1=verified%2C%20fixed'
	. '&f1=cf_status_firefox';

$enhancement_nightly = $enhancement_stub . $main_nightly;
$enhancement_beta    = $enhancement_stub . $main_beta;
$enhancement_release = $enhancement_stub . $main_release;

$link = function($url, $text, $title = true) {
	$title = $title ? '&title=' . rawurlencode($text) : '';
	return '<a href="' . $url . $title . '" target="_blank" rel="noopener">' . $text . '</a>';
};


// Proton
$stub_proton_uplifts =
	$stub_search_bz
	. '&status_whiteboard_type=anywordssubstr'
	. '&status_whiteboard=%5Bproton-uplift%5D';

$proton_uplifts = $stub_proton_uplifts;

$proton_uplifts_landed =
	$stub_proton_uplifts
	. '&o1=anywordssubstr'
	. '&v1=verified%2Cfixed'
	. '&f1=cf_status_firefox89';

$proton_uplifts_pending =
	$stub_proton_uplifts
	. '&o1=nowordssubstr'
	. '&v1=verified%2Cfixed%2Cwontfix'
	. '&f1=cf_status_firefox89'
	. '&o2=substring'
	. '&f2=flagtypes.name'
	. '&v2=approval-mozilla-beta%3F';;

$proton_uplifts_pending_notag =
	$stub_search_bz
	. '&f3=status_whiteboard'
	. '&o3=notsubstring'
	. '&v3=%5Bproton-uplift%5D'
	. '&f1=cf_status_firefox89'
	. '&o1=nowordssubstr'
	. '&v1=verified%2Cfixed'
	. '&f2=flagtypes.name'
	. '&o2=substring'
	. '&v2=approval-mozilla-beta%3F';

$proton_potential_uplifts =
	$stub_proton_uplifts
	. '&f1=cf_status_firefox90'
	. '&o1=anywordssubstr'
	. '&v1=verified%2C%20fixed'
	. '&f2=cf_status_firefox89'
	. '&o2=nowordssubstr'
	. '&v2=verified%2Cfixed%2Cwontfix'
	. '&status_whiteboard=proton-'
	. '&o3=notsubstring'
	. '&f3=flagtypes.name'
	. '&v3=approval-mozilla-beta%3F';

// END of Proton

$top_crashes_firefox_stub = 'https://crash-stats.mozilla.com/topcrashers/?process_type=any';

$nightly_top_crashes_firefox = $top_crashes_firefox_stub . '&product=Firefox&days=3&version=' . FIREFOX_NIGHTLY . '&_range_type=build';
$beta_top_crashes_firefox = $top_crashes_firefox_stub . '&product=Firefox&days=7';
$beta_top_crashes_firefox_last_beta = $beta_top_crashes_firefox . '&version=' . $main_beta. '.0b' . $last_beta;

for ($i = 1; $i <= $last_beta; $i++) {
	$beta_top_crashes_firefox .=  '&version=' . $main_beta. '.0b' . $i;
}

$nightly_top_crashes_deved = false;
if ($main_beta == $main_nightly) {
	$nightly_top_crashes_deved =
		$top_crashes_firefox_stub
		. '&product=Firefox&days=3&version='
		. $main_nightly . '.0b1'
		. '&version='
		. $main_nightly . '.0b2';
}

$release_top_crashes_firefox = $top_crashes_firefox_stub . '&product=Firefox&days=14&version=' . FIREFOX_RELEASE;
