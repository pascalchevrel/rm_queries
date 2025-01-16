<?php
$bugzilla_rest = true;
require_once __DIR__ . '/../../app/init.php';
// require_once __DIR__ . '/../../app/data.php';

header("access-control-allow-origin: *");
header("Content-type: application/json; charset=UTF-8");
?>
{
  "bugQueries": [
    {
      "id": "UpliftsBeta",
      "url": "<?=$uplift_beta?>"
    },
    {
      "id": "UpliftsRelease",
      "url": "<?=$uplift_release?>"
    },
    {
      "id": "UpliftsESR",
      "url": "<?=$uplift_esr?>"
    },
    {
      "id": "RelnotesNightly",
      "url": "<?=$relnotes_nightly?>"
    },
    {
      "id": "RelnotesBeta",
      "url": "<?=$relnotes_beta?>"
    },
    {
      "id": "RelnotesRelease",
      "url": "<?=$relnotes_release?>"
    },
    {
      "id": "TrackingNightly",
      "url": "<?=$tracking_question_nightly?>"
    },
    {
      "id": "TrackingBeta",
      "url": "<?=$tracking_question_beta?>"
    },
    {
      "id": "TrackingRelease",
      "url": "<?=$tracking_question_release?>"
    },
    {
      "id": "TrackingESR",
      "url": "<?=$tracking_question_esr?>"
    }
  ],
  "refreshMinutes": 30
}