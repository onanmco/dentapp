<?php

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.css">
    <link rel="stylesheet" href="/assets/css/popup.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="/assets/css/calendar.css">
    <title>Randevu API 0.3 PA</title>
</head>

<body>
    <div class="row justify-content-center mt-5">
        <div class="col-10">
            <div id="calendar_nav"></div>
            <table id="calendar" data-calendar_height="60"></table>
        </div>
    </div>

    <script src="/assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/jquery.validate.js"></script>
    <script src="/assets/js/additional-methods.js"></script>
    <script src="/assets/js/popup.js"></script>
    <script src="/assets/js/calendar.js"></script>
    <script>
        <?php app\utility\Popup::printAll() ?>
    </script>

</body>

</html>