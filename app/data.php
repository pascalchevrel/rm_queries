<?php

$stub_search_bz = 'https://bugzilla.mozilla.org/buglist.cgi?query_format=advanced';

if ($bugzilla_rest ??= false) {
    $stub_search_bz = 'https://bugzilla.mozilla.org/rest/bug?';
}

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
    . '&v2=stalled%2Cintermittent-failure'
    . '&o2=nowords'
    . '&f2=keywords'
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
$relnotes_esr     = $relnotes_stub('_esr' . $main_esr);

// Uplifts requests
$uplift_stub    = $stub_search_bz . '&o1=substring&f1=flagtypes.name';
$uplift_beta    = $uplift_stub . '&v1=approval-mozilla-beta%3F';
$uplift_release = $uplift_stub . '&v1=approval-mozilla-release%3F';
$uplift_esr     = $uplift_stub . '&v1=approval-mozilla-esr' . $main_esr . '%3F';

// Uplifts requests accepted, not landed last week,
$uplift_stub_pending = $uplift_stub
    . '&v2=affected%2Cfix-optional%2C%3F'
    . '&o2=anyexact'
    . '&chfieldfrom=-2w'
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

// ESR uplifts accepted, not landed
$uplift_esr_pending =
    $uplift_stub_pending
    . '&f2=cf_status_firefox_esr' . $main_esr
    . '&v1=approval-mozilla-esr' . $main_esr . '%2B';

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
    . '&product=Fenix'
    . '&product=Geckoview'
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

// Recently fixed sec bugs on Nightly

$recent_sec = 3;
$recent_sec_bugs_nightly =
    $stub_search_bz
    . '&resolution=FIXED'
    . '&chfield=resolution'
    . '&chfieldfrom=' . $recent_sec .'d'
    . '&chfieldto=Now'
    . '&f1=bug_group'
    . '&o1=substring'
    . '&v1=sec'
    . '&o2=anywordssubstr'
    . '&v2=verified%2C%20fixed'
    . '&f2=cf_status_firefox' . $main_nightly;
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

// Fixed bugs with a lot of people CCed, potential for a release note
$many_people_CCed_stub =
    $stub_search_bz
    . '&f2=cc_count'
    . '&o2=greaterthaneq'
    . '&v2=20'
    . '&o1=anywordssubstr'
    . '&v1=verified%2C%20fixed'
    . '&f1=cf_status_firefox';

$many_people_CCed_nightly = $many_people_CCed_stub . $main_nightly;
$many_people_CCed_beta    = $many_people_CCed_stub . $main_beta;
$many_people_CCed_release = $many_people_CCed_stub . $main_release;

// Webcompat
$webcompat_stub =
    $stub_search_bz
    . '&f1=cf_webcompat_priority'
    . '&o1=anyexact'
    . '&v1=P1%2CP2%2CP3'
    . '&o2=nowordssubstr'
    . '&v2=disabled%2Cwontfix%2Cfixed%2Cverified'
    . '&o3=anywordssubstr'
    . '&v3=fixed%2Cverified'
    . '&bug_status=RESOLVED'
    . '&bug_status=VERIFIED';
    // chfield=resolution

$webcompat_beta =
    $webcompat_stub
    . '&f2=cf_status_firefox' . (string) $main_beta
    . '&f3=cf_status_firefox' . (string) $main_nightly;

$webcompat_release =
    $webcompat_stub
    . '&f2=cf_status_firefox' . (string) $main_release
    . '&f3=cf_status_firefox' . (string) $main_beta;

$webcompat_dupes_2m =
    $stub_search_bz
    . '&chfield=%5BBug%20creation%5D&chfieldfrom=-2m&chfieldto=Now'
    . '&f1=cf_webcompat_priority'
    . '&o1=anyexact'
    . '&v1=P1%2CP2%2CP3'
    . '&resolution=DUPLICATE';


// Client side dynamic counters
$link = function($url, $text, $title = true) {
    $title = $title ? '&title=' . rawurlencode($text) : '';
    return '<a href="' . $url . $title . '" target="_blank" rel="noopener">' . $text . '</a>';
};

// Dynamic link with REST XHR request, outputs as a <li> tag
$rest_list_item_link = function($id, $url, $text, $title = true) use ($link) {
    return '<li class="list-group-item d-flex justify-content-between align-items-center">'
        . $link($url, $text, $title)
        . '<span class="bugcount" id="'
        . $id
        .'"></span></li>'
        . "\n";
};

$old_esr_link = function($url) use ($old_esr, $main_esr) {
     return str_replace($main_esr,  $old_esr, $url);
};

$top_crashes_stub = 'https://crash-stats.mozilla.com/topcrashers/?process_type=any';

$nightly_top_crashes_firefox = $top_crashes_stub . '&product=Firefox&days=3&version=' . FIREFOX_NIGHTLY . '&_range_type=build';
$beta_top_crashes_firefox = $top_crashes_stub . '&product=Firefox&days=7';
$beta_top_crashes_firefox_last_beta = $beta_top_crashes_firefox . '&version=' . $main_beta. '.0b' . $last_beta;

$nightly_top_crashes_fenix = $top_crashes_stub . '&product=Fenix&days=3&version=' . FIREFOX_NIGHTLY . '&_range_type=build';
$beta_top_crashes_fenix = $top_crashes_stub . '&product=Fenix&days=7';
$beta_top_crashes_fenix_last_beta = $beta_top_crashes_fenix . '&version=' . $main_beta. '.0b' . $last_beta;

for ($i = 1; $i <= $last_beta; $i++) {
    $beta_top_crashes_firefox .=  '&version=' . $main_beta. '.0b' . $i;
}

for ($i = 1; $i <= $last_beta; $i++) {
    $beta_top_crashes_fenix .=  '&version=' . $main_beta. '.0b' . $i;
}

$release_top_crashes_firefox = $top_crashes_stub . '&product=Firefox&days=14&version=' . FIREFOX_RELEASE;
$release_top_crashes_fenix = $top_crashes_stub . '&product=Fenix&days=14&version=' . FENIX_RELEASE;

$reported_today_by_users =
    $stub_search_bz
    . '&chfield=%5BBug%20creation%5D'
    . '&chfieldfrom=-24h'
    . '&chfieldto=Now'
    . '&classification=Client%20Software'
    . '&classification=Developer%20Infrastructure'
    . '&classification=Components'
    . '&f1=reporter'
    . '&f2=reporter'
    . '&f3=reporter'
    . '&o1=notequals'
    . '&o2=notequals'
    . '&o3=notequals'
    . '&product=Core'
    . '&product=DevTools'
    . '&product=External%20Software%20Affecting%20Firefox'
    . '&product=Fenix'
    . '&product=Firefox'
    . '&product=Firefox%20Build%20System'
    . '&product=Firefox%20for%20Android'
    . '&product=Firefox%20for%20Echo%20Show'
    . '&product=Firefox%20for%20FireTV'
    . '&product=Firefox%20for%20iOS'
    . '&product=Focus'
    . '&product=Focus-iOS'
    . '&product=Geckoview'
    . '&product=NSPR'
    . '&product=NSS'
    . '&product=Toolkit'
    . '&product=WebExtensions'
    . '&resolution=---'
    . '&v1=intermittent-bug-filer%40mozilla.bugs'
    . '&v2=%25group.editbugs%25'
    . '&v3=%25group.mozilla-corporation%';

$fixed_a11y_issues_beta =
    $stub_search_bz
    . '&o1=anywords'
    . '&v1=affected%2C%20fix-optional'
    . '&f1=cf_status_firefox_beta'
    . '&status_whiteboard=access-s1%2Caccess-s2%2Caccess-s3'
    . '&status_whiteboard_type=anywords'
    . '&resolution=FIXED';


/*
    This query looks for bugs with no pending beta uplift request but with mentions in comments of the word uplift.
    Bugbot request for uplifting is ignore.
    We look at bugs which had this uplift mention in the last 2 weeks.
    We only look at bugs that are fixed on nightly.
 */
$beta_uplift_chatter =
    $stub_search_bz
    . '&o1=anywords'
    . '&v1=affected%2C%20fix-optional'
    . '&f1=cf_status_firefox_beta'
    . '&o2=notsubstring'
    . '&v2=approval-mozilla-beta%3F'
    . '&f2=flagtypes.name'
    . '&f3=OP'
    . '&j3=AND_G'
    . '&o4=substring'
    . '&v4=uplift'
    . '&f4=longdesc'
    . '&o5=notsubstring'
    . '&v5=%20is%20this%20bug%20important%20enough%20to%20require%20an%20uplift'
    . '&f5=longdesc'
    . '&o6=changedafter'
    . '&v6=2w'
    . '&f6=longdesc'
    . '&f7=CP'
    . '&resolution=FIXED';

// Open bugs with sec-approval?
$pending_sec_approval =
    $stub_search_bz
    . '&o1=substring'
    . '&v1=sec-approval%3F'
    . '&f1=flagtypes.name';

// Recent bugs (3 months) with sec-approval+
$approved_sec_approval =
    $stub_search_bz
    . '&o1=substring'
    . '&v1=sec-approval%2B'
    . '&f1=flagtypes.name'
    . '&chfieldfrom=-3m'
    . '&chfieldto=Now'
    . '&resolution=---';

// FlatHub Status
$flathub_firefox = getRemoteJson(
    External::Flatpak_release->value,
    'flathub_firefox_release.json',
    900
);

$flathub_release = secureText($flathub_firefox['releases'][0]['version']);

$flathub_status = 'text-secondary';
if ($flathub_release != FIREFOX_RELEASE) {
    $flathub_status = 'text-danger';
}


// Snapcraft has a public json a bit more involved
$snapcraft_release = getRemoteJson(
    url: External::Snap_release->value,
    cache_file: 'snapcraft_firefox_release.json',
    time: 900,
    header: 'Snap-Device-Series: 16'
)['channel-map'];

$snapcraft = [];
foreach($snapcraft_release as $channels) {
    if ($channels['channel']['architecture'] =='amd64') {
        if ($channels['channel']['name'] == 'stable' && $channels['channel']['track'] == 'latest') {
            $snapcraft['release'] = explode('-', $channels['version'])[0];
        }

        if ($channels['channel']['name'] == 'beta' && $channels['channel']['track'] == 'latest') {
            $snapcraft['beta'] = explode('-', $channels['version'])[0];
        }

        if ($channels['channel']['name'] == 'esr/stable') {
            $snapcraft['esr'] = explode('-', $channels['version'])[0];
        }

        if ($channels['channel']['name'] == 'candidate') {
            $snapcraft['stable_candidate'] = explode('-', $channels['version'])[0];
        }

        if ($channels['channel']['name'] == 'esr/candidate') {
            $snapcraft['esr_candidate'] = explode('-', $channels['version'])[0];
        }
    }
}

$snap_status = [
    'release'          => 'text-secondary',
    'beta'             => 'text-secondary',
    'esr'              => 'text-secondary',
    'esr_candidate'    => 'text-secondary',
    'stable_candidate' => 'text-secondary',
];

if ($snapcraft['release'] != FIREFOX_RELEASE) {
    $snap_status['release'] = 'text-danger';
}

// We want to make sure that our release candidate is proposed by snap builds
$beta_is_rc = false;
if ($main_beta >= 9) {
    // Usually we have 9 betas, let's check balrog to see if we shipped our RC
    $beta_balrog = getRemoteJson(
    External::Balrog->value . 'firefox-beta',
    'firefox_beta_local.json',
    900
    )['mapping'];

    $beta_balrog = explode('.', $beta_balrog)[1];
    if (! str_contains($beta_balrog, '0b')) {
        $beta_is_rc = true;
    }
    unset($beta_balrog);
}

$snap_stable_candidate_missing = false;
if ($beta_is_rc) {
    // $snapcra$snapcraft['stable_candidate']
    $clean_snap = (int) explode('.', $snapcraft['stable_candidate'])[0];
    if ($clean_snap < $main_beta) {
        $snap_stable_candidate_missing = true;
        $snap_status['stable_candidate'] = 'text-danger';
    }
    unset($clean_snap);
}

if ($snapcraft['beta'] != FIREFOX_BETA) {
    $snap_status['beta'] = 'text-danger';
}

if ($snapcraft['esr'] != ESR) {
    $snap_status['esr'] = 'text-danger';
}

$play_store_release = getAndroidVersion('org.mozilla.firefox');
$play_store_beta = getAndroidVersion('org.mozilla.firefox_beta');

$play_status = [
    'release' => 'text-secondary',
    'beta'    => 'text-secondary',
];

if ($play_store_release != FENIX_RELEASE) {
    $play_status['release'] = 'text-danger';
}

if ($play_store_beta != FIREFOX_BETA) {
    $play_status['beta'] = 'text-danger';
}

// Samsung Store has a public json for listings
$samsung_release = getRemoteJson(
    url: External::Samsung_release->value,
    cache_file: 'samsung_firefox_release.json',
    time: 900,
)['DetailMain']['contentBinaryVersion'];

$samsung_status = 'text-secondary';

if ($samsung_release != FENIX_RELEASE) {
    $samsung_status = 'text-danger';
}

// We can't compare that version with what we ship because we don't have it in product-details
$apple_store_release = getAppleStoreVersion();
$microsoft_store_release = getWindowsStoreVersion(time: 900);
$microsoft_store_status = ($microsoft_store_release == FIREFOX_RELEASE)
    ? 'text-secondary'
    : 'text-danger';

$maven = getLatestMavenVersion();
$maven_status = 'text-secondary';
$maven_title = '';
$maven_date = DateTime::createFromFormat(
    'Ymd',
    substr(explode('.', $maven)[1], 0, 8)
);


if ($maven_date->diff(new DateTime())->days >= 2) {
    $maven_status = 'text-danger';
    $maven_title = 'Last Application Services build is >= 2 days';
}
