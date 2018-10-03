<?php include 'data.php'?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Release Links and Queries</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script>
    function show(id) {
        elt = document.getElementById(id);
        display = getComputedStyle(elt, null).display;
        elt.style.display = (display == "none") ? "block" : "none";
    }
    </script>
    <style>
    #betas {
        display: none;
        width: 50%;
        margin: 1em auto;
        text-align: center;
    }

    #betas ul {
        display: flex;
        padding: 0;
        list-style: none;
    }

    #betas ul li {
        flex:content;
    }
    </style>
</head>
<body>
	<a href="https://github.com/pascalchevrel/rm_queries"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_orange_ff7600.png" alt="Fork me on GitHub"></a>
    <table id="version_numbers" class="table table-bordered tabe-sm w-auto mx-auto" style="margin-left: 1em">
        <tbody>
            <tr>
                <th class="table-dark">Nightly</th>
                <td class="table-primary"><?=NIGHTLY?></td>
                <th class="table-dark">Dev Edition</th>
                <td class="table-primary"><?=DEV_EDITION?></td>
                <th class="table-dark" onclick="show('betas');">Beta</th>
                <td class="table-primary"><?=BETA?></td>
                <th class="table-dark">Release</th>
                <td class="table-primary"><?=RELEASE?></td>
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
    <div id="betas" class="border bg-light">
        <h5>Patches uplifted for each beta</h3>
        <ul>
<?php
for ($i = 2; $i <= $last_beta + 1; $i++) {
    $hg_link =
        'https://hg.mozilla.org/releases/mozilla-beta/pushloghtml?fromchange=FIREFOX_63_0b'
        . ($i-1)
        . '_RELEASE&amp;tochange='
        . ($i-1 == $last_beta ? 'tip': 'FIREFOX_63_0b'. $i . '_RELEASE');

    print '            <li>' . $link($hg_link,'Beta' . $i, $title = false ) . "</li>\n";
}
?>
        </ul>
    </div>

    <div class="container">
        <div class="row">
        <div class="col">
            <h3 class="text-center">NIGHTLY</h3>
            <ul>
                <li><?=$link($regressions_nightly,'Open regressions')?></li>
                <li><?=$link($relnotes_nightly,'Release Note Requests')?></li>
                <li>--</li>
                <li>--</li>
                <li><?=$link($malfunction_nightly, 'Software defect (crash, leak, assertion…)')?></li>
                <li><?=$link($tracking_question_nightly, 'tracking?')?></li>
                <li><?=$link($tracking_plus_nightly, 'tracking+')?></li>
                <li><?=$link($tracking_plus_open_nightly, 'tracking+ not fixed')?></li>
                <li><?=$link($tracking_plus_unassigned_nightly, 'tracking+ unassigned')?></li>
                <li>--</li>
                <li><?=$link($pending_needinfo_nightly, 'needinfo? > ' . $ni_days . ' days')?></li>
                <li><?=$link($recently_fixed_crashes, 'Crashes fixed in the last ' . $last_days_crashes .' days')?></li>

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
                <li><?=$link($tracking_question_beta, 'tracking?')?></li>
                <li><?=$link($tracking_plus_beta, 'tracking+')?></li>
                <li><?=$link($tracking_plus_open_beta, 'tracking+ not fixed')?></li>
                <li><?=$link($tracking_plus_unassigned_beta, 'tracking+ unassigned')?></li>
                <li><?=$link($resolved_fix_optional_beta, 'Fixed fix-optionals')?></li>
                <li><?=$link($pending_needinfo_beta, 'needinfo? > ' . $ni_days . ' days')?></li>
                <li><?=$link($fixed_regressions_candidates_beta, "Uplift fixed regressions affecting ${main_beta}?")?></li>
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
                <li><?=$link($tracking_question_release, 'tracking?')?></li>
                <li><?=$link($tracking_plus_release, 'tracking+')?></li>
                <li><?=$link($tracking_plus_open_release, 'tracking+ not fixed')?></li>
                <li><?=$link($tracking_plus_unassigned_release, 'tracking+ unassigned')?></li>
                <li><?=$link($resolved_fix_optional_release, 'Fixed fix-optionals')?></li>
                <li><?=$link($pending_needinfo_release, 'needinfo? > ' . $ni_days . ' days')?>  </li>
                <li><?=$link($fixed_regressions_candidates_release, "Uplift fixed regressions affecting ${main_release}?")?></li>
            </ul>
        </div>
        </div>
    </div>
</body>
</html>
