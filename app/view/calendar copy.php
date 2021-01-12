<?php
$timestamp = 1609412400000 / 1000;
echo date('Y-m-d H:i:s', $timestamp);
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
    <title>Randevu API AT-TC-14</title>
</head>

<body>
    <div id="calendar_nav" class="row justify-content-center mt-5 mb-2">
        <div class="col-10 d-flex justify-content-between align-items-center p-0 m-0">
            <div class="buttons col-4 d-flex justify-content-start">
                <button class="btn btn-light border rounded-circle d-flex justify-content-center align-items-center" style="width: 30px; height: 30px;">
                    <i class="fas fa-angle-left"></i>
                </button>
                <button class="btn btn-light border rounded-circle d-flex justify-content-center align-items-center ml-2" style="width: 30px; height: 30px;">
                    <i class="fas fa-angle-right"></i>
                </button>
            </div>
            <div class="col-4 text-center">
                <h6 class="text-muted p-0 m-0"></h6>
            </div>
            <div class="buttons col-4 d-flex justify-content-end">
                <button class="btn btn-light border d-flex justify-content-center align-items-center text-muted font-weight-bold" style="height: 30px;">
                    Günlük
                </button>
                <button class="btn btn-light border d-flex justify-content-center align-items-center text-muted font-weight-bold ml-2" style="height: 30px;">
                    Haftalık
                </button>
                <button class="btn btn-light border d-flex justify-content-center align-items-center text-muted font-weight-bold ml-2" style="height: 30px;">
                    Aylık
                </button>
            </div>
        </div>
    </div>
    <div id="calendar" class="row justify-content-center">
        <div id="calendar_title" class="p-0">
            <div class="row w-100"></div>
        </div>
        <div id="calendar_table" class="col-10 p-0">
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