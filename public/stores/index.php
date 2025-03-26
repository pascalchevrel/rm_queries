<?php require_once dirname(__DIR__, 2) . '/app/init.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Firefox version number on Stores</title>
    <link rel="shortcut icon" type="image/svg+xml" href="./img/experiments.svg"/>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body id="store">
<div class="container d-flex align-items-center min-vh-100 text-center">
    <div class="legend position-absolute">
        <ul class="list-group ">
            <li class="list-group-item d-flex justify-content-between align-items-center mobile">Mobile store</li>
            <li class="list-group-item d-flex justify-content-between align-items-center laptop">Computer store</li>
            <li class="list-group-item d-flex justify-content-between align-items-center text-danger">Store not updated yet</li>
        </ul>
    </div>
  <div class="text-center w-50 m-auto">
    <?php
        $page_id = 'store';
        $message = "Google & Microsoft need 100% rollout to serve the latest version number to new users";
        $message = <<<TEXT
        Google & Microsoft need 100% rollout to serve the latest version number to new users<br>
        Keep in mind that stores can take up to 48h to review and accept our submissions.
        TEXT;
        include APP . 'stores_template.php';
    ?>
  </div>
</div>


</body>
</html>
