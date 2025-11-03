<?php require_once dirname(__DIR__, 2) . '/app/init.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Firefox version number on Stores</title>
    <meta http-equiv="refresh" content="3600">
    <link rel="shortcut icon" type="image/svg+xml" href="./img/experiments.svg"/>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/main.css?version=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:ital,wght@0,400;0,700;1,400;1,700&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
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
        include APP . 'stores_template.php';
    ?>
  </div>
</div>


</body>
</html>
