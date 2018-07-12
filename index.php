<?php include 'data.php'?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Release Links and Queries</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <style>

    </style>
</head>
<body>
    <table id="version_numbers" class="table table-bordered tabe-sm w-auto mx-auto" style="margin-left: 1em">
        <tbody>
            <tr>
                <th class="table-dark">Nightly</th>
                <td class="table-primary"><?=NIGHTLY?></td>
                <th class="table-dark">Dev Edition</th>
                <td class="table-primary"><?=DEV_EDITION?></td>
                <th class="table-dark">Beta</th>
                <td class="table-primary"><?=BETA?></td>
                <th class="table-dark">Release</th>
                <td class="table-primary"><?=RELEASE?></td>
                <th class="table-dark">Old ESR</th>
                <td class="table-primary"><?=ESR?></td>
                <th class="table-dark">ESR</th>
                <td class="table-primary"><?=ESR_NEXT?></td>
            </tr>
        </tbody>
    </table>

    <div class="container">
        <div class="row">
        <div class="col">
            <h3 class="text-center">NIGHTLY</h3>
            <ul>
                <li><a href="<?=$regressions_nightly?>">Regressions</a></li>
                <li><a href="<?=$relnotes_nightly?>">Release Note Requests</a></li>
                <li>--</li>
                <li><a href="<?=$malfunction_nightly?>">Software defect (crash, leak, assertion…)</a></li>
                <li><a href="<?=$tracking_plus_nightly?>">tracking+</a></li>
                <li><a href="<?=$tracking_plus_open_nightly?>">tracking+ not fixed</a></li>
                <li><a href="<?=$tracking_plus_unassigned_nightly?>">tracking+ unassigned</a></li>
                <li><a href="<?=$pending_needinfo_nightly?>">needinfo? > <?=$ni_days?> days</a></li>

            </ul>
        </div>

        <div class="col">
            <h3 class="text-center">BETA</h3>
            <ul>
                <li><a href="<?=$regressions_beta?>">Regressions</a></li>
                <li><a href="<?=$relnotes_beta?>">Release Note Requests</a></li>
                <li><a href="<?=$uplift_beta?>">Uplift requests</a></li>
                <li><a href="<?=$malfunction_beta?>">Software defect (crash, leak, assertion…)</a></li>
                <li><a href="<?=$tracking_plus_beta?>">tracking+</a></li>
                <li><a href="<?=$tracking_plus_open_beta?>">tracking+ not fixed</a></li>
                <li><a href="<?=$tracking_plus_unassigned_beta?>">tracking+ unassigned</a></li>
                <li><a href="<?=$pending_needinfo_beta?>">needinfo? > <?=$ni_days?> days</a></li>
            </ul>
        </div>

        <div class="col">
            <h3 class="text-center">RELEASE</h3>
            <ul>
                <li><a href="<?=$regressions_release?>">Regressions</a></li>
                <li><a href="<?=$relnotes_release?>">Release Note Requests</a></li>
                <li><a href="<?=$uplift_release?>">Uplift requests</a></li>
                <li><a href="<?=$malfunction_release?>">Software defect (crash, leak, assertion…)</a></li>
                <li><a href="<?=$tracking_plus_release?>">tracking+</a></li>
                <li><a href="<?=$tracking_plus_open_release?>">tracking+ not fixed</a></li>
                <li><a href="<?=$tracking_plus_unassigned_release?>">tracking+ unassigned</a></li>
                <li><a href="<?=$pending_needinfo_release?>">needinfo? > <?=$ni_days?> days</a></li>
            </ul>
        </div>
        </div>
    </div>
</body>
</html>

