<?php
    require_once __DIR__ . '/../app/data.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Release Links and Queries</title>
    <link rel="shortcut icon" type="image/svg+xml" href="./img/experiments.svg"/>
    <link rel="stylesheet" href="./styles/bootstrap-4.3.1-dist/css/bootstrap.min.css">
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
    <style>
    .container, table#version_numbers {
        min-width: 100%;
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

    .col {
        max-width: 25%;
    }
    </style>
</head>
<body>
    <table id="version_numbers" class="table table-bordered table-sm w-auto mx-auto" style="margin-left: 1em">
        <tbody>
            <tr>
                <th class="table-dark" onclick="show('nightlies');">Nightly <small>&#x2B07;</small></th>
                <td class="table-primary"><?=FIREFOX_NIGHTLY?></td>
                <th class="table-dark">Dev Edition</th>
                <td class="table-primary"><?=DEV_EDITION?></td>
                <th class="table-dark" onclick="show('betas');">Beta <small>&#x2B07;</small></th>
                <td class="table-primary"><?=FIREFOX_BETA?></td>
                <th class="table-dark">Release</th>
                <td class="table-primary"><?=FIREFOX_RELEASE?></td>
                <?php if (ESR_NEXT): ?>
                <th class="table-dark">Old ESR</th>
                <td class="table-primary"><?=ESR?></td>
                <th class="table-dark">ESR</th>
                <td class="table-primary"><?=ESR_NEXT?></td>
                <?php else: ?>
                <th class="table-dark">ESR</th>
                <td class="table-primary"><?=ESR?></td>
                <?php endif; ?>
            </tr>
        </tbody>
    </table>
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
    <div class="message"><?=$global_message?></div>
    <div class="container">
        <div class="row">
            <div class="col">
                <h3 class="text-center">NIGHTLY</h3>
                <ul>
                    <li><?=$link($regressions_nightly,'Open regressions')?></li>
                    <li><?=$link($relnotes_nightly,'Release Note Requests')?></li>
                    <li><?=$link("https://bugzilla.mozilla.org/buglist.cgi?chfield=%5BBug%20creation%5D&chfieldfrom=-24h&chfieldto=Now&classification=Client%20Software&classification=Developer%20Infrastructure&classification=Components&f1=reporter&f2=reporter&f3=reporter&o1=notequals&o2=notequals&o3=notequals&product=Core&product=DevTools&product=External%20Software%20Affecting%20Firefox&product=Firefox&product=Firefox%20Build%20System&product=Firefox%20for%20Android&product=Firefox%20for%20Echo%20Show&product=Firefox%20for%20FireTV&product=Firefox%20for%20iOS&product=Focus&product=Focus-iOS&product=NSPR&product=NSS&product=Toolkit&product=WebExtensions&resolution=---&v1=intermittent-bug-filer%40mozilla.bugs&v2=%25group.editbugs%25&v3=%25group.mozilla-corporation%25&list_id=14573209",'Bugs filed today by users')?></li>
                    <li>--</li>
                    <li><?=$link($malfunction_nightly, 'Software defect (crash, leak, assertion…)')?></li>
                    <li><?=$link($tracking_question_nightly, 'tracking? for Nightly')?></li>
                    <li><?=$link($tracking_plus_unfixed_nightly, 'tracking+ not fixed')?></li>
                    <li><?=$link($tracking_plus_unassigned_nightly, 'tracking+ unassigned')?></li>
                    <?php
                     if ($nightly_top_crashes_deved !== false) {
                        echo '<li>' . $link($nightly_top_crashes_deved, 'Devedition (b1,b2) recent crashes (3 days)', false) . '</li>';
                    }
                    ?>
                    <li><?=$link($nightly_top_crashes_firefox, 'Firefox recent crashes (3 days)', false)?></li>
                    <li><?=$link($pending_needinfo_nightly, 'needinfo? > ' . $ni_days_nightly . ' days')?></li>
                    <li><?=$link($recently_fixed_crashes, 'Crashes fixed in the last ' . $last_days_crashes .' days')?></li>
                    <li><?=$link($security_nightly, "Security bugs for ${main_nightly}?")?></li>
                </ul>
            </div>

            <div class="col">
                <h3 class="text-center">BETA</h3>
                <ul>
                    <li><?=$link($regressions_beta, 'Open regressions')?></li>
                    <li><?=$link($relnotes_beta, 'Release Note Requests')?></li>
                    <li><?=$link($uplift_beta, 'Uplift requests')?></li>
                    <li><?=$link($uplift_beta_pending, 'Uplifts not landed, bug active')?></li>
                    <li><?=$link($malfunction_beta, 'Software defect (crash, leak, assertion…)')?></li>
                    <li><?=$link($tracking_question_beta, "tracking? for ${main_beta}")?></li>
                    <li><?=$link($tracking_plus_unfixed_beta, 'tracking+ not fixed')?></li>
                    <li><?=$link($tracking_plus_unassigned_beta, 'tracking+ unassigned')?></li>
                    <li><?=$link($beta_top_crashes_firefox_last_beta, 'Firefox last beta crashes (7 days)', false)?></li>
                    <li><?=$link($beta_top_crashes_firefox, 'Firefox recent crashes (7 days)', false)?></li>
                    <li><?=$link($resolved_fix_optional_beta, 'Fixed fix-optionals')?></li>
                    <li><?=$link($pending_needinfo_beta, 'needinfo? > ' . $ni_days . ' days')?></li>
                    <li><?=$link($fixed_regressions_candidates_beta, "Uplift fixed regressions affecting ${main_beta}?")?></li>
                    <li><?=$link($security_beta, "Security bugs for ${main_beta}?")?></li>
                </ul>
            </div>

            <div class="col">
                <h3 class="text-center">RELEASE</h3>

                <ul>
                    <li><?=$link($regressions_release, 'Open regressions')?></li>
                    <li><?=$link($relnotes_release, 'Release Note Requests')?></li>
                    <li><?=$link($uplift_release, 'Uplift requests')?></li>
                    <li><?=$link($uplift_release_pending, 'Uplifts not landed, bug active')?></li>
                    <li><?=$link($malfunction_release, 'Software defect (crash, leak, assertion…)')?></li>
                    <li><?=$link($tracking_question_release, "tracking? for release (${main_release})")?></li>
                    <li><?=$link($tracking_plus_unfixed_release, 'tracking+ not fixed')?></li>
                    <li><?=$link($tracking_plus_unassigned_release, 'tracking+ unassigned')?></li>
                    <li><?=$link($release_top_crashes_firefox, 'Firefox recent crashes (14 days)', false)?></li>
                    <li><?=$link($resolved_fix_optional_release, 'Fixed fix-optionals')?></li>
                    <li><?=$link($pending_needinfo_release, 'needinfo? > ' . $ni_days . ' days')?>  </li>
                    <li><?=$link($fixed_regressions_candidates_release, "Uplift fixed regressions affecting ${main_release}?")?></li>
                    <li><?=$link($security_release, "Security bugs for ${main_release}?")?></li>
                </ul>
            </div>

            <div class="col">
                <h3 class="text-center">ESR</h3>
                <ul>
                    <li><?=$link($regressions_esr, 'Open regressions')?></li>
                    <li><?=$link($relnotes_esr, 'Release Note Requests')?></li>
                    <li><?=$link($uplift_esr, 'Uplift requests')?></li>
                    <li><?=$link($tracking_question_esr, "tracking? for ESR ${main_esr}")?></li>
                    <li><?=$link($tracking_plus_esr, "tracking+ for ESR ${main_esr}")?></li>
                    <li><?=$link($tracking_plus_unfixed_esr, "tracking+ not fixed for ESR ${main_esr}")?></li>
                </ul>
            </div>
        </div>

    </div>
</body>
</html>
