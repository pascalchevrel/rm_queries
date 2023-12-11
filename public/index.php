<?php
    require_once __DIR__ . '/../app/data.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Release Links and Queries</title>
    <link rel="shortcut icon" type="image/svg+xml" href="./img/experiments.svg"/>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">

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

    <style>

        .container, table#version_numbers {
            min-width: 100%;
        }

        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        #version_numbers {
        text-align: center;
        }

        .message {
            text-align: center;
        }

        #betas, #nightlies {
            display: none;
            width: 50%;
            margin: 1em auto;
            text-align: center;
        }

        #betas ul, #nightlies ul {
            display: flex;
            padding: 0;
            list-style: none;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        #betas ul li, #nightlies ul li{
            background-color: white;
            padding: 0 4px;
            margin: 4px;
            border: 1px solid darkgray;
        }

        .list-group-item {
            padding-top: 0.3rem;
            padding-bottom: 0.3rem;
        }

        .list-group-item a {
            display: inline-block;
            min-width:  90%;
        }
    </style>
</head>
<body>
    <div class="table-responsive">

    <table id="version_numbers" class="table table-bordered table-sm w-auto mx-auto">
        <tbody>
            <tr>
                <th class="table-dark" onclick="show('nightlies');">Nightly <small>&#x2B07;</small></th>
                <th class="table-dark">Dev Edition</th>
                <th class="table-dark" onclick="show('betas');">Beta <small>&#x2B07;</small></th>
                <th class="table-dark">Release</th>
                <?php if (ESR_NEXT): ?>
                <th class="table-dark">ESR</th>
                <th class="table-dark">ESR Next</th>
                <?php else: ?>
                <th class="table-dark">ESR</th>
                <?php endif; ?>
            </tr>
            <tr>
                <td class="table-primary"><?=FIREFOX_NIGHTLY?></td>
                <td class="table-primary"><?=DEV_EDITION?></td>
                <td class="table-primary"><?=FIREFOX_BETA?></td>
                <td class="table-primary"><?=FIREFOX_RELEASE?></td>
                <?php if (ESR_NEXT): ?>
                <td class="table-primary"><?=ESR?></td>
                <td class="table-primary"><?=ESR_NEXT?></td>
                <?php else: ?>
                <td class="table-primary"><?=ESR?></td>
                <?php endif; ?>
            </tr>
        </tbody>
    </table>
    </div>

    <div id="nightlies" class="border bg-light toggle">
        <h5>Patches landed for each nightly</h5>
        <?=getBugsPerNightly($firefox_versions)?>
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
                    <li class="<?=$li_default?>"><?=$link($security_nightly, "Security bugs for ${main_nightly}?")?></li>
                    <li class="<?=$li_default?>"><?=$link($pending_sec_approval, "Open bugs with sec-approval?")?></li>
                    <li class="<?=$li_default?>"><?=$link($approved_sec_approval, "Open bugs with sec-approval+")?></li>
                    <li class="<?=$li_default?>"><?=$link($recent_sec_bugs_nightly, "Recently fixed security bugs, last " . $recent_sec . ' days')?></li>
                    <li class="<?=$li_default?>"><?=$link($enhancement_nightly, "Enhancements for ${main_nightly}")?></li>
                    <li class="<?=$li_default?>"><?=$link($many_people_CCed_nightly, "Fixed and many people CCed on ${main_nightly}")?></li>
                </ul>
            </div>

            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-primary list-group-item-warning">BETA</li>
                    <li class="<?=$li_default?>"><?=$link($regressions_beta, 'Open regressions')?></li>
                     <li class="list-group-item d-flex justify-content-between align-items-center"><?=$link($relnotes_beta,'Release Note Requests')?><span class="bugcount" id="RelnotesBeta"></span></li>

                    <li class="list-group-item d-flex justify-content-between align-items-center"><?=$link($uplift_beta, 'Uplift requests')?><span class="bugcount" id="UpliftsBeta"></span></li>
                    <li class="<?=$li_default?>"><?=$link($uplift_beta_pending, 'Uplifts not landed, bug active')?></li>
                    <li class="<?=$li_default?>"><?=$link($malfunction_beta, 'Software defect (crash, leak, assertion…)')?></li>
                    <?=$rest_list_item_link('TrackingBeta',  $tracking_question_beta, "Tracking? for ${main_beta}")?>
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
                    <li class="<?=$li_default?>"><?=$link($fixed_regressions_candidates_beta, "Uplift fixed regressions affecting ${main_beta}?")?></li>
                    <li class="<?=$li_default?>"><?=$link($security_beta, "Security bugs for ${main_beta}?")?></li>
                    <li class="<?=$li_default?>"><?=$link($enhancement_beta, "Enhancements for ${main_beta}")?></li>
                    <li class="<?=$li_default?>"><?=$link($many_people_CCed_beta, "Fixed and many people CCed on ${main_beta}")?></li>
                    <li class="<?=$li_default?>"><?=$link($webcompat_beta, "Fixed Webcompat in ${main_nightly}")?></li>
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

                    <li class="<?=$li_default?>"><?=$link($uplift_release, 'Uplift requests')?><span class="bugcount" id="UpliftsRelease"></span></li>
                    <li class="<?=$li_default?>"><?=$link($uplift_release_pending, 'Uplifts not landed, bug active')?></li>
                    <li class="<?=$li_default?>"><?=$link($malfunction_release, 'Software defect (crash, leak, assertion…)')?></li>
                    <?=$rest_list_item_link('TrackingRelease',  $tracking_question_release, "Tracking? for release (${main_release})")?>
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
                    <li class="<?=$li_default?>"><?=$link($fixed_regressions_candidates_release, "Uplift fixed regressions affecting ${main_release}?")?></li>
                    <li class="<?=$li_default?>"><?=$link($security_release, "Security bugs for ${main_release}?")?></li>
                    <li class="<?=$li_default?>"><?=$link($enhancement_release, "Enhancements for ${main_release}")?></li>
                    <li class="<?=$li_default?>"><?=$link($many_people_CCed_release, "Fixed and many people CCed on ${main_release}")?></li>
                    <li class="<?=$li_default?>"><?=$link($webcompat_release, "Fixed Webcompat in ${main_beta}")?></li>
                </ul>
            </div>
        </div>

        <div class="row mt-2">

            <?php if (ESR_NEXT): ?>
            <div class="col-4">
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-primary">ESR <?=$old_esr?></li>
                    <li class="<?=$li_default?>"><?=$old_esr_link($link($regressions_esr, 'Open regressions'))?></li>
                    <li class="<?=$li_default?>"><?=$old_esr_link($link($relnotes_esr, 'Release Note Requests'))?></li>
                    <li class="<?=$li_default?>"><?=$old_esr_link($link($uplift_esr, 'Uplift requests'))?></li>
                    <li class="<?=$li_default?>"><?=$old_esr_link($link($uplift_esr_pending, 'Uplifts not landed, bug active'))?></li>
                    <?=$rest_list_item_link('TrackingESR',  $tracking_question_esr, "tracking? for ESR ${old_esr}")?>
                    <li class="<?=$li_default?>"><?=$old_esr_link($link($tracking_plus_esr, "tracking+ for ESR ${old_esr}"))?></li>
                    <li class="<?=$li_default?>"><?=$old_esr_link($link($tracking_plus_unfixed_esr, "tracking+ not fixed for ESR ${old_esr}"))?></li>
                </ul>
            </div>
            <?php endif; ?>

            <div class="col-4">
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-primary">ESR <?=ESR_NEXT? $main_esr : ""?></li>
                    <li class="<?=$li_default?>"><?=$link($regressions_esr, 'Open regressions')?></li>
                    <li class="<?=$li_default?>"><?=$link($relnotes_esr, 'Release Note Requests')?></li>
                    <li class="<?=$li_default?>"><?=$link($uplift_esr, 'Uplift requests')?><span class="bugcount" id="UpliftsESR"></span></li>
                    <li class="<?=$li_default?>"><?=$link($uplift_esr_pending, 'Uplifts not landed, bug active')?></li>
                    <?=$rest_list_item_link('TrackingESR', $tracking_question_esr, "tracking? for ESR ${main_esr}")?>
                    <li class="<?=$li_default?>"><?=$link($tracking_plus_esr, "tracking+ for ESR ${main_esr}")?></li>
                    <li class="<?=$li_default?>"><?=$link($tracking_plus_unfixed_esr, "tracking+ not fixed for ESR ${main_esr}")?></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
