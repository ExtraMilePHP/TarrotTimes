<!doctype html>
<html>

<head>

    <title>Tarrot Times</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link href="css/style-one.css" type="text/css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/sweetalert.min.js"></script>
    <link href="css/room.css" rel="stylesheet">
    <link href="css/begin.css" rel="stylesheet">

    <?php
 session_start();
 ob_start();
error_reporting(0);
// $whiteLogo = 1;
include_once 'dao/config.php';
include_once("../login-default.php");
// echo $loginSuccess;
$userid = $_SESSION['userId'];
$organizationId = $_SESSION['organizationId'];
$sessionId = $_SESSION['sessionId'];



?>

    <style>
    @font-face {
        font-family: 'MorrisRomanBlack';
        src: url('fonts/MorrisRomanBlack.otf');
    }

    .bg {
        width: 100%;
        height: 100%;
        /* overflow: hidden; */
        background-color: white;
        background-image: url(img/Tarrot_bg_desk.png);
        background-repeat: no-repeat;
        background-size: 100% 100%;
    }

    .btn-begin {
        /* width: 100%; */
        position: absolute;
        margin-top: -130px;
        /* margin-left: -92px; */
    }

    .full-screen {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: white;
        color: white;
        text-align: center;
        line-height: 3vh;
        z-index: 10000;
        font-size: 24px;
        color: black;
    }

    .title-head {
        text-align: center;
        margin-top: 6%;
        font-family: 'MorrisRomanBlack';
    }

    @media screen and (max-width: 768px) {
        .bg {
            width: 100%;
            height: 100%;
            /* overflow: hidden; */
            background-color: white;
            background-image: url(img/Tarrot_mobile.png);
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .bg-btn {
            position: absolute;
            bottom: 20%;
            left: 40%;
            width: 20%;
        }

        .title-head {
            text-align: center;
            margin-top: 25%;
            font-family: 'MorrisRomanBlack';
        }

    }
    </style>
</head>

<body class="bg">

    <?php include("../actions-default.php");
    back($base_url); ?>

    <div class="title-head">
        <h1>Digital First Week</h1>
    </div>

    <div class="container-fluid content" style="">
        <div class="row homepage" style="">
            <div class="col-sm-3 col-md-4 col-lg-4 col-xs-12 auto logo">
                <img src="img/Tarrot_Logo.png" style="width:100%;">

            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-2 col-md-2 col-lg-2 col-xs-4 auto btn bg-btn btn-begin">

            <?php if(isset($loginSuccess)): ?>
            <a href="game.php">
                <!-- <button class="btn btn-info begin">BEGIN PLAY</button> -->
                <img src="img/beginplay.png?v=5" id="beginplay" style="width: 100%;" alt="Begin Play">
            </a>
            <?php endif; ?>
        </div>

    </div>


    </div>

    <!-- <div class="full-screen">
        Please rotate your device for the best experience
    </div>
    <script>
    if (window.innerHeight > window.innerWidth) {
        // swal("Please rotate your device for the best experience");
        $(".full-screen").css("display", "flex");
        ishorizonal = false;
        // $(".bg").css("display", "none");
    } else {
        $(".full-screen").css("display", "none");
        ishorizonal = true;
    }
    setInterval(() => {
        if (window.innerHeight > window.innerWidth) {
            // swal("Please rotate your device for the best experience");
            $(".full-screen").css("display", "flex");
            ishorizonal = false;
            // $(".bg").css("display", "none");
        } else {
            $(".full-screen").css("display", "none");
            ishorizonal = true;
        }
    }, 1000);
    </script> -->


</body>

</html>