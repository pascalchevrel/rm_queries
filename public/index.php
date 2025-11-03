<?php require_once dirname(__DIR__, 1) . '/app/init.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Release Links and Queries</title>
    <meta http-equiv="refresh" content="3600">
    <link rel="shortcut icon" type="image/svg+xml" href="./img/experiments.svg"/>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/main.css?version=1">

    <script>
        function show(id) {
            elt = document.getElementById(id);
            eltdisplay = getComputedStyle(elt, null).display;
            elts = document.querySelectorAll('.toggle');

            for (i = 0; i < elts .length; ++i) {
                elts[i].style.display = "none";
            }

            elt.style.display = (eltdisplay == "none") ? "block" : "none";
        }
    </script>

    <script src="js/releasehealth.js" defer></script>

</head>
<body>
    <div class="table-responsive">

    <table id="version_numbers" class="table table-bordered table-sm w-auto mx-auto">
        <tbody>
            <tr>
                <th class="table-dark">Nightly</th>
                <th class="table-dark">Dev Edition</th>
                <th class="table-dark" onclick="show('betas');">Beta <small>&#x2B07;</small></th>
                <th class="table-dark">Release</th>
                <?php if (ESR_115): ?>
                <th class="table-dark">ESR 115</th>
                <?php endif; ?>
                <?php if (ESR): ?>
                <th class="table-dark">ESR</th>
                <?php endif; ?>
                <?php if (ESR_NEXT): ?>
                <th class="table-dark">ESR Next</th>
                <?php endif; ?>
            </tr>
            <tr>
                <td class="table-primary"><?=FIREFOX_NIGHTLY?></td>
                <td class="table-primary"><?=DEV_EDITION?></td>
                <td class="table-primary"><?=FIREFOX_BETA?></td>
                <td class="table-primary"><?=FIREFOX_RELEASE?></td>

                <?php if (ESR_115): ?>
                <td class="table-primary"><?=ESR_115?></td>
                <?php endif; ?>
                <?php if (ESR): ?>
                <td class="table-primary"><?=ESR?></td>
                <?php endif; ?>
                <?php if (ESR_NEXT): ?>
                <td class="table-primary"><?=ESR_NEXT?></td>
                <?php endif; ?>
            </tr>
        </tbody>
    </table>
    </div>

    <div id="betas" class="border bg-light toggle">
        <h5>Patches uplifted for each beta</h5>
        <ul>
<?php
for ($i = 2; $i <= $last_beta + 1; $i++) {
    $beta_previous = ($i - 1) < 3 ? 'DEVEDITION_' : 'FIREFOX_';
    $beta_current_type = $i < 3 ? 'DEVEDITION_' : 'FIREFOX_';

    $beta_previous .= $main_beta . '_0b' . ($i-1) . '_RELEASE';
    $beta_current = ($i-1 == $last_beta)
        ? 'tip'
        : $main_beta . '_0b' . $i . '_RELEASE';

    if ($beta_current != 'tip') {
        $beta_current = $beta_current_type . $beta_current;
    }

    $hg_link =
        'https://hg.mozilla.org/releases/mozilla-beta/pushloghtml?fromchange='
        . $beta_previous
        . '&amp;tochange='
        . $beta_current;
    print '            <li>' . $link($hg_link,'Beta' . $i, $title = false ) . "</li>\n";
}
?>
        </ul>
    </div>
    <?php
        $li_default = 'list-group-item list-group-item-action ';
    ?>

    <div class="container mx-auto">
        <div class="row">
            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-info">NIGHTLY</li>
                    <li class="<?=$li_default?>"><?=$link($regressions_nightly,'Open regressions')?></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center"><?=$link($relnotes_nightly,'Release Note Requests')?><span class="bugcount" id="RelnotesNightly"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center"><?=$link($orphaned_relnotes,'Orphaned Release Notes')?><span class="bugcount" id="OrphanedRelnotes"></span></li>
                    <li class="<?=$li_default?>"><?=$link($reported_today_by_users,'Bugs filed today by users')?></li>
                    <li class="<?=$li_default?>"><?=$link($malfunction_nightly, 'Software defect (crash, leak, assertion…)')?></li>

                    <?=$rest_list_item_link('TrackingNightly', $tracking_question_nightly, 'Tracking? for Nightly')?>

                    <li class="<?=$li_default?>"><?=$link($tracking_plus_unfixed_nightly, 'Tracking+ not fixed')?></li>
                    <li class="<?=$li_default?>"><?=$link($tracking_plus_unassigned_nightly, 'Tracking+ unassigned')?></li>
                    <li class="<?=$li_default?> d-flex justify-content-evenly p-0 ps-3" title="3 days">
                        <span class="w-50 border-end mt-1 mb-1">
                            <?=$link($nightly_top_crashes_firefox, 'Firefox recent crashes', false)?>
                        </span>
                        <span class="w-50  mt-1 mb-1 ps-3">
                            <?=$link($nightly_top_crashes_fenix, 'Fenix recent crashes', false)?>
                        </span>
                    </li>
                    <li class="<?=$li_default?>"><?=$link($pending_needinfo_nightly, 'Needinfo? > ' . $ni_days_nightly . ' days')?></li>
                    <li class="<?=$li_default?>"><?=$link($recently_fixed_crashes, 'Crashes fixed, last ' . $last_days_crashes .' days')?></li>
                    <li class="<?=$li_default?>"><?=$link($security_nightly, "Security bugs for {$main_nightly}?")?></li>
                    <li class="<?=$li_default?>"><?=$link($security_nightly_uplift, "Security bugs for beta uplift")?></li>
                    <li class="<?=$li_default?>"><?=$link($pending_sec_approval, "Open bugs with sec-approval?")?></li>
                    <li class="<?=$li_default?>"><?=$link($approved_sec_approval, "Open bugs with sec-approval+")?></li>
                    <li class="<?=$li_default?>"><?=$link($recent_sec_bugs_nightly, "Recently fixed security bugs, last " . $recent_sec . ' days')?></li>
                    <li class="<?=$li_default?>"><?=$link($enhancement_nightly, "Enhancements for {$main_nightly}")?></li>
                    <li class="<?=$li_default?>"><?=$link($many_people_CCed_nightly, "Fixed and many people CCed on {$main_nightly}")?></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Maven Application Services: <span class="<?=$maven_status?>" title="<?=$maven_title?>"><?=$maven?></span>
                    </li>
                </ul>
            </div>

            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-primary list-group-item-warning">BETA</li>
                    <li class="<?=$li_default?>"><?=$link($regressions_beta, 'Open regressions')?></li>
                     <li class="list-group-item d-flex justify-content-between align-items-center"><?=$link($relnotes_beta,'Release Note Requests')?><span class="bugcount" id="RelnotesBeta"></span></li>

                    <li class="list-group-item d-flex justify-content-between align-items-center"><?=$link($uplift_beta, 'Uplift requests', alt_text:'Uplift requests for Beta')?><span class="bugcount" id="UpliftsBeta"></span></li>
                    <li class="<?=$li_default?>"><?=$link($uplift_beta_pending, 'Uplifts not landed, bug active')?></li>
                    <li class="<?=$li_default?>"><?=$link($malfunction_beta, 'Software defect (crash, leak, assertion…)')?></li>
                    <?=$rest_list_item_link('TrackingBeta',  $tracking_question_beta, "Tracking? for {$main_beta}")?>
                    <li class="<?=$li_default?>"><?=$link($tracking_plus_unfixed_beta, 'Tracking+ not fixed')?></li>
                    <li class="<?=$li_default?>"><?=$link($tracking_plus_unassigned_beta, 'Tracking+ unassigned')?></li>
                    <li class="<?=$li_default?> d-flex justify-content-evenly p-0 ps-3" title="7 days">
                        <span class="w-50 border-end mt-1 mb-1">
                            <?=$link($beta_top_crashes_firefox, 'Firefox betas crashes', false)?>
                        </span>
                        <span class="w-50 mt-1 mb-1 ps-3">
                            <?=$link($beta_top_crashes_fenix, 'Fenix betas crashes', false)?>
                        </span>
                    </li>
                    <li class="<?=$li_default?> d-flex justify-content-evenly p-0 ps-3">
                        <span class="w-50 border-end mt-1 mb-1">
                            <?=$link($beta_top_crashes_firefox_last_beta, 'Firefox Last beta crashes', false)?>
                        </span>
                        <span class="w-50  mt-1 mb-1 ps-3">
                            <?=$link($beta_top_crashes_fenix_last_beta, 'Fenix Last beta crashes', false)?>
                        </span>
                    </li>
                    <li class="<?=$li_default?>"><?=$link($resolved_fix_optional_beta, 'Fixed fix-optionals')?></li>
                    <li class="<?=$li_default?>"><?=$link($pending_needinfo_beta, 'Needinfo? > ' . $ni_days . ' days')?></li>
                    <li class="<?=$li_default?>"><?=$link($fixed_regressions_candidates_beta, "Uplift fixed regressions affecting {$main_beta}?")?></li>
                    <li class="<?=$li_default?>"><?=$link($security_beta, "Security bugs for {$main_beta}?")?></li>
                    <li class="<?=$li_default?>"><?=$link($enhancement_beta, "Enhancements for {$main_beta}")?></li>
                    <li class="<?=$li_default?>"><?=$link($many_people_CCed_beta, "Fixed and many people CCed on {$main_beta}")?></li>
                    <li class="<?=$li_default?>"><?=$link($webcompat_beta, "Fixed Webcompat in {$main_nightly}")?></li>
                    <li class="<?=$li_default?>"><?=$link($webcompat_dupes_2m, "Webcompat marked as dupes")?></li>
                    <li class="<?=$li_default?>"><?=$link($fixed_a11y_issues_beta, "Uplift fixed Accessibility issues?")?></li>
                    <li class="<?=$li_default?>"><?=$link($beta_uplift_chatter, "Uplift chatter")?></li>
                </ul>
            </div>

            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-primary  list-group-item-success">RELEASE</li>
                    <li class="<?=$li_default?>"><?=$link($regressions_release, 'Open regressions')?></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center"><?=$link($relnotes_release,'Release Note Requests')?><span class="bugcount" id="RelnotesRelease"></span></li>

                    <li class="<?=$li_default?>"><?=$link($uplift_release, 'Uplift requests', alt_text:'Uplift requests for Release')?><span class="bugcount" id="UpliftsRelease"></span></li>
                    <li class="<?=$li_default?>"><?=$link($uplift_release_pending, 'Uplifts not landed, bug active')?></li>
                    <li class="<?=$li_default?>"><?=$link($malfunction_release, 'Software defect (crash, leak, assertion…)')?></li>
                    <?=$rest_list_item_link('TrackingRelease',  $tracking_question_release, "Tracking? for release ({$main_release})")?>
                    <li class="<?=$li_default?>"><?=$link($tracking_plus_unfixed_release, 'Tracking+ not fixed')?></li>
                    <li class="<?=$li_default?>"><?=$link($tracking_plus_unassigned_release, 'Tracking+ unassigned')?></li>
                   <li class="<?=$li_default?> d-flex justify-content-evenly p-0 ps-3">
                        <span class="w-50 border-end mt-1 mb-1" title="14 days">
                            <?=$link($release_top_crashes_firefox, 'Firefox top crashes', false)?>
                        </span>
                        <span class="w-50 mt-1 mb-1 ps-3">
                            <?=$link($release_top_crashes_fenix, 'Fenix top crashes', false)?>
                        </span>
                    </li>
                    <li class="<?=$li_default?>"><?=$link($resolved_fix_optional_release, 'Fixed fix-optionals')?></li>
                    <li class="<?=$li_default?>"><?=$link($pending_needinfo_release, 'Needinfo? > ' . $ni_days . ' days')?>  </li>
                    <li class="<?=$li_default?>"><?=$link($fixed_regressions_candidates_release, "Uplift fixed regressions affecting {$main_release}?")?></li>
                    <li class="<?=$li_default?>"><?=$link($security_release, "Security bugs for {$main_release}?")?></li>
                    <li class="<?=$li_default?>"><?=$link($enhancement_release, "Enhancements for {$main_release}")?></li>
                    <li class="<?=$li_default?>"><?=$link($many_people_CCed_release, "Fixed and many people CCed on {$main_release}")?></li>
                    <li class="<?=$li_default?>"><?=$link($webcompat_release, "Fixed Webcompat in {$main_beta}")?></li>
                    <?php if ($snap_stable_candidate_missing) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Missing Snap Release candidate: <span class="<?=$snap_status['stable_candidate']?>"><?=$snapcraft["stable_candidate"]?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="row mt-2">

            <?php if (ESR_115): ?>
            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-primary">ESR <?= (int) ESR_115 ?></li>
                    <li class="<?=$li_default?>"><?=$esr_link($link($regressions_esr, 'Open regressions'), $esr_115)?></li>
                    <li class="<?=$li_default?>"><?=$esr_link($link($relnotes_esr, 'Release Note Requests'), $esr_115)?></li>
                    <li class="<?=$li_default?>"><?=$esr_link($link($uplift_esr, 'Uplift requests', alt_text:'Uplift requests for ESR ' . $esr_115), $esr_115)?><span class="bugcount" id="UpliftsESR115"></span></li>
                    <li class="<?=$li_default?>"><?=$esr_link($link($uplift_esr_pending, 'Uplifts not landed, bug active'), $esr_115)?></li>
                    <?=$esr_link($rest_list_item_link('TrackingESR115', $tracking_question_esr_115, "tracking? for ESR {$esr_115}"), $esr_115)?>
                    <li class="<?=$li_default?>"><?=$esr_link($link($tracking_plus_esr, "tracking+ for ESR {$esr_115}"), $esr_115)?></li>
                    <li class="<?=$li_default?>"><?=$esr_link($link($tracking_plus_unfixed_esr, "tracking+ not fixed for ESR {$esr_115}"), $esr_115)?></li>
                </ul>
            </div>
            <?php endif; ?>

            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-primary">ESR <?=$esr?></li>
                    <li class="<?=$li_default?>"><?=$link($regressions_esr, 'Open regressions')?></li>
                    <li class="<?=$li_default?>"><?=$link($relnotes_esr, 'Release Note Requests')?></li>
                    <li class="<?=$li_default?>"><?=$link($uplift_esr, 'Uplift requests', alt_text:'Uplift requests for ESR ' . $esr)?><span class="bugcount" id="UpliftsESR"></span></li>
                    <li class="<?=$li_default?>"><?=$link($uplift_esr_pending, 'Uplifts not landed, bug active')?></li>
                    <?=$rest_list_item_link('TrackingESR', $tracking_question_esr, "tracking? for ESR {$esr}")?>
                    <li class="<?=$li_default?>"><?=$link($tracking_plus_esr, "tracking+ for ESR {$esr}")?></li>
                    <li class="<?=$li_default?>"><?=$link($tracking_plus_unfixed_esr, "tracking+ not fixed for ESR {$esr}")?></li>
                </ul>
            </div>

            <?php if (ESR_NEXT): ?>
            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-primary">ESR  <?= (int) ESR_NEXT ?></li>
                    <li class="<?=$li_default?>"><?=$esr_link($link($regressions_esr, 'Open regressions'), $esr_next)?></li>
                    <li class="<?=$li_default?>"><?=$esr_link($link($relnotes_esr, 'Release Note Requests'), $esr_next)?></li>
                    <li class="<?=$li_default?>"><?=$esr_link($link($uplift_esr, 'Uplift requests', alt_text:'Uplift requests for ESR ' . $esr_next), $esr_next)?><span class="bugcount" id="UpliftsESRNext"></span></li>
                    <li class="<?=$li_default?>"><?=$esr_link($link($uplift_esr_pending, 'Uplifts not landed, bug active'), $esr_next)?></li>
                    <?=$esr_link($rest_list_item_link('TrackingESRNext',  $tracking_question_esr_next, "tracking? for ESR {$esr_next}"), $esr_next)?>
                    <li class="<?=$li_default?>"><?=$esr_link($link($tracking_plus_esr, "tracking+ for ESR {$esr_next}"), $esr_next)?></li>
                    <li class="<?=$li_default?>"><?=$esr_link($link($tracking_plus_unfixed_esr, "tracking+ not fixed for ESR {$esr_next}"), $esr_next)?></li>
                </ul>
            </div>
            <?php endif; ?>


            <div class="col">
            <?php include APP . 'stores_template.php'; ?>
            </div>
        </div>
    </div>
</body>
</html>
