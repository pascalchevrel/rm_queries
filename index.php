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
    <a href="https://bugzilla.mozilla.org/page.cgi?id=release_tracking_report.html">Release Tracking Report</a>

    <table id="version_numbers" class="table table-striped table-bordered table-sm w-auto" style="margin-left: 1em">
        <tbody>
            <tr>
                <th>Nightly</th>
                <td><?=NIGHTLY?></td>
            </tr>
            <tr>
                <th>Dev Edition</th>
                <td><?=DEV_EDITION?></td>
            </tr>
            <tr>
                <th>Beta</th>
                <td><?=BETA?></td>
            </tr>
            <tr>
                <th>Release</th>
                <td><?=RELEASE?></td>
            </tr>
            <tr>
                <th>Old ESR</th>
                <td><?=ESR?></td>
            </tr>
            <tr>
                <th>ESR</th>
                <td><?=ESR_NEXT?></td>
            </tr>
        </tbody>
    </table>

    <div class="container">
        <div class="row">
        <div class="col">
            <h3>NIGHTLY</h3>
            <ul>
                <li><a href="">Regressions</a></li>
                <li><a href="<?=$relnotes_nightly?>">Release Note Requests</a></li>
                <li>--</li>
                <li><a href="<?=$malfunction_nightly?>">Software defect (crash, leak, assertion…)</a></li>
            </ul>
        </div>

        <div class="col">
            <h3>BETA</h3>
            <ul>
                <li><a href="">Regressions</a></li>
                <li><a href="<?=$relnotes_beta?>">Release Note Requests</a></li>
                <li><a href="<?=$uplift_beta?>">Uplift requests</a></li>
                <li><a href="<?=$malfunction_beta?>">Software defect (crash, leak, assertion…)</a></li>
            </ul>
        </div>

        <div class="col">
            <h3>RELEASE</h3>
            <ul>
                <li><a href="">Regressions</a></li>
                <li><a href="<?=$relnotes_release?>">Release Note Requests</a></li>
                <li><a href="<?=$uplift_release?>">Uplift requests</a></li>
                <li><a href="<?=$malfunction_release?>">Software defect (crash, leak, assertion…)</a></li>
            </ul>
        </div>
        </div>
    </div>
</body>
</html>
