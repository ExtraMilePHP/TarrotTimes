<?php
session_start();
error_reporting(0);
include_once 'dao/config.php';
if ($_SESSION['userId'] == "") {
    header('location:index.php');
}

include_once("../add_report.php");
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set("Asia/Kolkata");
}
$stage = 1;
$timestamp = date('Y-m-d H:i:s');
if (!isset($_COOKIE['gettime'])) {
    setcookie("gettime", $timestamp, time() + (86400 * 30), "/");
    $hours = 0;
    $minutes = 0;
    $seconds = 0;
} else {
    if ($stage == 1) { 
        setcookie("gettime", $timestamp, time() + (86400 * 30), "/");
        $previousTime = $_COOKIE["gettime"];
        $time = new DateTime($previousTime);
        $timediff = $time->diff(new DateTime());
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
    } else {
        $previousTime = $_COOKIE["gettime"];
        $time = new DateTime($previousTime);
        $timediff = $time->diff(new DateTime());
        $hours = $timediff->format('%h');
        $minutes = $timediff->format('%i');
        $seconds = $timediff->format('%s');
    }
}


// $name = $_SESSION['firstName'];
$organizationId = $_SESSION['organizationId'];
$sessionId = $_SESSION['sessionId'];
$userid = $_SESSION["userId"];


if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set("Asia/Kolkata");
}
$timestamp = date('Y-m-d H:i:s');
$email = $_SESSION["email"];
$name = $_SESSION['firstName'] . " " . $_SESSION['lastName'];
// $_SESSION['roles'] = "demo";
$roles = $_SESSION['roles'];
$gameId = $_SESSION['gameId'];

$find = "select * from stat where userid='$userid' and organizationId='$organizationId' and sessionid ='$sessionId'";

if ($sessionId == "demobypass") {
    $find = execute_query( $find);
    $find1 = mysqli_num_rows($find);
    $find2 = mysqli_fetch_object($find);

    if ($find1 > 0) {
    } else {

        $query = "INSERT INTO `stat`(`userid`,`name`,`user_type`,`email`,`organizationId`,`sessionId`,`points`, `timestamp_start`) VALUES('$userid','$name','$roles','$email','$organizationId','$sessionId','0','$timestamp')";

        if (execute_query( $query)) {
            $userid = $_SESSION['userId'];
            // $email = $_SESSION['userId'];
            $query2 = "UPDATE `stat` SET `reportid`='demoreport' WHERE userid='$userid'";

            execute_query( $query2);
        } else {
            exit("result_message=Error");
        }
    }
} else {

    $find = "select * from stat where userid='$userid' and organizationId='$organizationId' and sessionId ='$sessionId'";
    $find = execute_query( $find);
    $find1 = mysqli_num_rows($find);
    $find2 = mysqli_fetch_object($find);
   

    if ($find1 > 0) {
    } else {
        $query = "INSERT INTO `stat`(`userid`,`name`,`user_type`,`email`,`organizationId`,`sessionId`,`points`, `timestamp_start`) VALUES('$userid','$name','$roles','$email','$organizationId','$sessionId','0','$timestamp')";
        //echo $query;
        if (execute_query( $query)) {


            if ($roles == "GUEST_USER") {
                function successResponse($tools)
                {
                    global $con, $userid, $organizationId, $sessionId;
                    $userid = $_SESSION["userId"];
                    $reportid = $tools["reportId"];
                    $query1 = "UPDATE `stat` SET `reportid`='$reportid' WHERE userid='$userid' and organizationId='$organizationId' and sessionId ='$sessionId'";
                    //echo $query1;
                    if (!execute_query( $query1)) {
                        echo mysqli_error($con);
                    };
                }
                $data = ["gameId" => $gameId, "name" => $name, "sessionId" => $sessionId, "userId" => $userid, "organizationId" => $organizationId, "points" => 0, "time" => "NA", "ans" => ""];
                addReportGuest($data);
            } else {

                function successResponse($tools)
                {
                    global $con, $userid, $organizationId, $sessionId;
                    $userid = $_SESSION["userId"];
                    $reportid = $tools["reportId"];
                    $query1 = "UPDATE `stat` SET `reportid`='$reportid' WHERE userid='$userid' and organizationId='$organizationId' and sessionId ='$sessionId'";
                    if (!execute_query( $query1)) {
                        echo mysqli_error($con);
                    };
                }
                $data = ["points" => 0, "time" => "NA"];
                addReport($data);
            }
        } else {
            exit("result_message=Error");
        }
    }
}



?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/sweetalert.min.js"></script>
    <title>Tarrot Times</title>
    <style>
    /* Hippie / Banjaran / Tarot aesthetic: warm parchment, gold accents, hand-drawn borders, subtle grain */
    @font-face {
        font-family: 'MorrisRomanBlack';
        src: url('fonts/MorrisRomanBlack.otf');
    }

    * {
        margin: 0;
        padding: 0;
    }

    :root {
        --bg-gradient: linear-gradient(180deg, #1b1323 0%, #241724 50%, #2e1f2b 100%);
        --paper: #f6efe6;
        /* parchment */
        --muted: #d9cbb7;
        --gold: #ecc976;
        --accent: #a86fb5;
        /* mystical purple */
        --card-w: 180px;
        --card-h: 260px;
        /* made deck larger */
        --shadow: 0 14px 40px rgba(0, 0, 0, 0.6);
    }

    * {
        box-sizing: border-box
    }

    body {
        /* height: 100vh; */
        background: var(--bg-gradient);
        background-image: url(img/Tarrot_bg_desk.png);
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-attachment: fixed;
        color: black;
        /* font-family: 'MorrisRomanBlack'; */
        font-family: 'Calibre';
        /* display: flex;
        justify-content: center */
    }

    .main-content {
        display: flex;
        justify-content: center
    }

    .app {
        width: 100%;
        max-width: 1200px
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px
    }

    h1 {
        margin: 0;
        color: black;
        font-size: 22px;
        letter-spacing: 1px;
        font-size: 27px;
        font-weight: 600;
        font-family: 'MorrisRomanBlack';
    }

    .subtitle {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.7);
    }

    .controls {
        display: flex;
        gap: 10px;
        align-items: center
    }

    .btn {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.06);
        padding: 8px 12px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 700;
        text-decoration: underline;
        font-size: 16px;
    }


    .btn.primary {

        color: #000000ff;
        border: 0;

    }

    .stage {
        margin-top: 18px;
        display: grid;
        grid-template-columns: 1fr 520px;
        gap: 18px
    }

    /* table area: parchment panel + embroidered border */
    .table {
        /* background: linear-gradient(180deg, rgba(255, 250, 240, 0.95), rgba(249, 244, 235, 0.95)); */
        padding: 22px;
        border-radius: 18px;
        /* box-shadow: var(--shadow); */
        /* border: 6px solid rgba(209, 162, 74, 0.06); */
        position: relative;
        overflow: hidden
    }

    .table:before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 16px;
        pointer-events: none;
        background-image: radial-gradient(rgba(0, 0, 0, 0.02) 1px, transparent 1px);
        background-size: 12px 12px;
        mix-blend-mode: multiply;
    }

    .table .grid-15 {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px
    }

    /* deck animation box - now centered over the table grid */
    .deck-area {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        z-index: 120;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center
    }

    .deck-stack {
        width: var(--card-w);
        height: var(--card-h);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        box-shadow: 0 18px 60px rgba(0, 0, 0, 0.6);
        opacity: 0;
        transform-origin: center;
        position: relative;
        background-size: cover;
        background-position: center;
        transition: opacity 420ms ease, transform 420ms ease
    }

    .deck-stack.show {
        opacity: 1
    }

    /* ensure each layer uses the cardback */
    .deck-stack .card-layer {
        position: absolute;
        inset: 0;
        border-radius: 12px;
        background-image: url('images/CardBacks.jpg');
        background-size: cover;
        background-position: center;
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.5);
    }

    .deck-stack .card-layer:nth-child(1) {
        z-index: 6
    }

    .deck-stack .card-layer:nth-child(2) {
        z-index: 5;
        transform: translate(8px, 8px) rotate(6deg);
        opacity: 0.96
    }

    .deck-stack .card-layer:nth-child(3) {
        z-index: 4;
        transform: translate(16px, 16px) rotate(-6deg);
        opacity: 0.9
    }

    .deck-stack .card-layer:nth-child(4) {
        z-index: 3;
        transform: translate(24px, 24px) rotate(3deg);
        opacity: 0.85
    }

    .deck-stack .card-layer:nth-child(5) {
        z-index: 2;
        transform: translate(32px, 32px) rotate(-3deg);
        opacity: 0.78
    }

    .deck-stack .card-layer:nth-child(6) {
        z-index: 1;
        transform: translate(40px, 40px) rotate(10deg);
        opacity: 0.7
    }

    /* card style: vintage border, paper center, subtle glow on hover */
    .card-tile {
        height: 220px;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        transform-style: preserve-3d;
        transition: transform .28s cubic-bezier(.2, .9, .3, 1), box-shadow .28s ease, filter .28s ease;
        perspective: 1000px;
        opacity: 0
    }

    /* restore the vintage hover interaction - subtle lift, rotated tilt, warmer shadow and gentle glow */
    .card-tile:not(.flipped):hover {
        transform: translateY(-12px) rotateZ(-3deg) scale(1.03);
        box-shadow: 0 28px 70px rgba(0, 0, 0, 0.6);
        filter: brightness(1.02) saturate(1.05)
    }

    .card-inner {
        width: 100%;
        height: 100%;
        position: relative;
        transform-style: preserve-3d;
        transition: transform .8s cubic-bezier(.2, .9, .3, 1)
    }

    .flipped .card-inner {
        transform: rotateY(180deg)
    }

    /* deal animation for cards */
    .card-tile.deal {
        animation: dealCard 620ms cubic-bezier(.2, .9, .3, 1) both
    }

    @keyframes dealCard {
        0% {
            transform: translateY(-160px) rotate(-10deg) scale(.95);
            opacity: 0
        }

        70% {
            transform: translateY(6px) rotate(6deg) scale(1.01);
            opacity: 1
        }

        100% {
            transform: translateY(0) rotate(0) scale(1);
            opacity: 1
        }
    }

    .card-face,
    .card-back {
        position: absolute;
        inset: 0;
        border-radius: 10px;
        backface-visibility: hidden;
        display: flex;
        flex-direction: column
    }

    .card-back {
        background: linear-gradient(180deg, #6f3fa0, #1f6b6b);
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 16px;
        background-image: url('images/CardBacks.jpg');
        background-size: cover
    }

    .card-face {
        background: linear-gradient(180deg, #fffaf0, #f6efe6);
        padding: 8px;
        border: 3px solid rgba(0, 0, 0, 0.04);
        transform: rotateY(180deg);
    }

    .card-face .frame {
        border: 2px solid rgba(0, 0, 0, 0.06);
        padding: 6px;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.4));
    }

    .card-face img {
        max-width: 100%;
        max-height: 100%;
        filter: contrast(.98) saturate(1.05)
    }

    /* mystical ornaments */
    .corner-orn {
        position: absolute;
        width: 80px;
        height: 80px;
        opacity: 0.08;
    }

    .orn-left {
        left: -10px;
        top: -8px;
    }

    .orn-right {
        right: -8px;
        bottom: -10px;
        transform: rotate(180deg)
    }

    /* sidebar: parchment card with gold filigree */
    .sidebar {
        padding: 18px;
        border-radius: 14px;
        /* background: linear-gradient(180deg, rgba(20, 14, 20, 0.6), rgba(20, 14, 20, 0.4));
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.03) */
    }

    .slots {
        display: flex;
        gap: 12px;
        justify-content: space-between;
        margin-bottom: 12px;
        font-family: 'MorrisRomanBlack';
    }

    .slot {
        flex: 1;
        height: 160px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, rgba(255, 250, 240, 0.08), rgba(255, 250, 240, 0.02));
        position: relative;
        overflow: hidden;
        border: 1px dashed rgba(255, 255, 255, 0.04)
    }

    #slot1 {
        background-color: #56705b;
        color: #ecc976;
    }

    #slot2 {
        background-color: #706e56;
        color: #ecc976;
    }

    #slot3 {
        background-color: #a86b7b;
        color: #ecc976;
    }

    .slot .label {
        position: absolute;
        top: 10px;
        color: #ecc976;
        font-weight: 700;
        text-align: center;
        font-size: xx-large;
    }

    .slot .inner {
        width: 150px;
        height: 210px;
        display: flex;
        align-items: center;
        justify-content: center
    }

    .reading {
        margin-top: 12px;
        background: linear-gradient(180deg, rgba(255, 250, 230, 0.98), rgba(255, 245, 225, 0.98));
        padding: 14px;
        border-radius: 10px;
        height: 340px;
        overflow: auto;
        color: #2b1f1f;
        border: 2px solid rgba(209, 162, 74, 0.12)
    }

    .reading h3 {
        Margin: 4px 0;
        color: var(--accent)
    }

    .reading .muted {
        color: #5b4b3a;
        font-size: 13px
    }

    .small {
        font-size: 13px
    }

    .tag {
        display: inline-block;
        background: rgba(209, 162, 74, 0.12);
        color: var(--gold);
        padding: 6px 8px;
        border-radius: 8px;
        font-weight: 700;
        margin-right: 6px
    }



    /* responsive */
    @media (max-width:980px) {
        .stage {
            grid-template-columns: 1fr
        }

        .grid-15 {
            grid-template-columns: repeat(3, 1fr)
        }
    }

    /* Center stage like reference */
    .stage {
        margin-top: 100px;
        /* display: flex; */
        justify-content: center;
        align-items: center;
        gap: 24px;
    }

    /* shrink and center the table grid */
    .table {
        flex: 0 0 auto;
        width: 500px;
        /* fixed width like your reference */
        width: 600px;
        min-height: 409px;
        margin: 25px 20px;
    }

    /* grid of 15 cards - smaller, tighter layout */
    .table .grid-15 {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        /* reduce gap */
        justify-items: center;
    }

    /* card tiles consistent size */
    .card-tile {
        width: 90px;
        height: 151px;
        opacity: 0;
    }

    /* sidebar fixed width and aligned neatly */
    .sidebar {
        width: 500px;
        ;
        flex-shrink: 0;
    }

    /* slots stacked evenly */
    .slots {
        display: flex;
        gap: 40px;
        justify-content: space-between;
    }

    .slot {
        flex: 1;
        height: 140px;
        /* smaller slots like reference */
    }

    .slot .inner {
        width: 100px;
        height: 140px;
    }

    /* reading box smaller & balanced */
    .reading {
        margin-top: 12px;
        height: 280px;
    }

    .card-select {

        position: absolute;
        top: 13%;
        left: 23%;
    }

    .controls {
        position: absolute;
        top: 13%;
        left: 60%;
    }

    .swal-footer {
        text-align: center;
    }

    @media screen and (max-width: 768px) {
        body {
            width: 100%;
            height: 100%;
            /* overflow: hidden; */
            background-color: white;
            background-image: url(img/Tarrot_mobile.png);
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .stage {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            /* stack table + sidebar */
            align-items: center;
            gap: 20px;
        }

        .table {
            width: 100%;
            max-width: 360px;
            /* fit phone screens */
            margin: 0 auto;
            padding: 10px;
        }

        .table .grid-15 {
            grid-template-columns: repeat(3, 1fr);
            /* smaller grid */
            gap: 8px;
        }

        .card-tile {
            width: 80px;
            height: 120px;
        }

        .sidebar {
            width: 100%;
            max-width: 360px;
            padding: 12px;
        }

        .slots {
            flex-direction: column;
            /* stack slots vertically */
            gap: 12px;
        }

        .slot {
            height: 120px;
        }

        .slot .inner {
            width: 90px;
            height: 120px;
        }

        .reading {
            height: auto;
            max-height: 240px;
            font-size: 14px;
        }

        /* .controls,
        .card-select {
            position: static;
            margin: 10px auto;
            justify-content: center;
        } */

        .controls {
            flex-wrap: wrap;
            gap: 8px;
            position: absolute;
            top: 13%;
            left: 11%;
        }

        .card-select {
            position: absolute;
            top: 17%;
            left: 31%;
        }

        h1 {
            font-size: 20px;
            text-align: center;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 116px;
        }

        .slot .label {
            position: absolute;
            top: 7px;
            color: #ecc976;
            font-weight: 700;
            text-align: center;
            font-size: xx-large;
        }

        .slot .inner {
            width: 90px;
            height: 155px;
        }


    }

    .swal2-popup.swal2-modal.swal2-show {
        /* background-image: url(img/correct.png); */
        background-color: transparent !important;
        background-position: center !important;
        background-size: 100% 100% !important;
        background-repeat: no-repeat;
        width: 490px !important;
        height: 370px !important;
    }

    button.swal2-confirm.swal2-styled {
        background-color: transparent !important;
        background-image: url(img/ok.png) !important;
        background-position: center !important;
        background-size: 100% 100% !important;
        background-repeat: no-repeat;
    }

    .swal2-popup .swal2-header,
    .swal2-popup .swal2-content,
    .swal2-popup .swal2-actions {
        top: 50px;
        position: relative;
    }


    .my-popup {
        background-size: 100% 100% !important;
        background-position: center !important;
        */ background-image: url(img/correct.png) !important;
        color: #000 !important;
        padding: 45px !important;
        ;
    }



    .my-popup .swal2-confirm {
        background-image: url(img/correct.png) !important;
        background-size: 100% 100% !important;
        color: transparent !important;
        border: none !important;
        width: 70px;
        padding: 10px !important;
    }

    .my-confirm-button img {
        width: 30px;
        /* Adjust the size of the image */
        height: auto;
    }

    div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-confirm):focus-visible {
        outline: none !important;
        border: none !important;
        box-shadow: none !important;
    }


    .swal-title {
        color: #ecc976;
        font-weight: 600;
        text-transform: none;
        position: relative;
        display: block;
        padding: 13px 16px;
        font-size: 27px;
        line-height: normal;
        text-align: center;
        margin-bottom: 0;
        font-family: 'MorrisRomanBlack';
    }

    .swal-overlay--show-modal .swal-modal {
        opacity: 1;
        pointer-events: auto;
        box-sizing: border-box;
        -webkit-animation: showSweetAlert .3s;
        animation: showSweetAlert .3s;
        will-change: transform;
        background-color: black;
        color: #ecc976 !important;
    }

    .swal-button {
        background-color: #ecc976;
        color: black;
        border: none;
        box-shadow: none;
        border-radius: 5px;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 24px;
        margin: 0;
        cursor: pointer;
    }

    .swal-icon swal-icon--success {
        display: none;
    }

    .swal-icon {
        width: 85px;
        height: 80px;
        border-width: 4px;
        border-style: solid;
        border-radius: 50%;
        padding: 0;
        position: relative;
        box-sizing: content-box;
        margin: 20px auto;
        display: none;
    }

    .swal-footer {
        text-align: center;
        font-family: 'MorrisRomanBlack';
    }
    </style>
</head>

<body>
    <?php include ("../actions-default.php");
    back("game.php"); ?>

    <div class="container-fluid">
        <div class="main-content">

            <div class="app">
                <header>
                    <div class="card-select">
                        <h1>Select any three cards</h1>
                        <!-- <div class="subtitle">A hippie, hand-drawn style three-card spread — Past • Present • Future</div> -->
                    </div>
                    <div class="controls">
                        <button id="shuffleBtn" class="btn primary">Shuffle & Lay 15</button>
                        <button id="resetBtn" class="btn">Reset</button>
                        <button id="exportBtn" class="btn">Copy Reading</button>
                        <label class="btn" for="shuffleFileInput" style="cursor:pointe; display:none;">Upload Shuffle
                            Sound</label>
                        <input id="shuffleFileInput" type="file" accept="audio/*" style="display:none">
                    </div>
                </header>

                <div class="stage">
                    <section class="table">
                        <svg class="corner-orn orn-left" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true">
                            <defs>
                                <linearGradient id="g1" x1="0" x2="1">
                                    <stop offset="0%" stop-color="#d1a24a" stop-opacity="0.95" />
                                    <stop offset="100%" stop-color="#a86fb5" stop-opacity="0.9" />
                                </linearGradient>
                            </defs>
                            <g transform="translate(8,8) scale(0.8)">
                                <circle cx="60" cy="60" r="34" fill="none" stroke="url(#g1)" stroke-width="2"
                                    opacity="0.12" />
                                <path d="M60 22 L66 44 L88 50 L70 64 L76 86 L60 74 L44 86 L50 64 L32 50 L54 44 Z"
                                    fill="none" stroke="url(#g1)" stroke-width="1.6" opacity="0.12" />
                                <circle cx="60" cy="60" r="4" fill="url(#g1)" opacity="0.12" />
                                <g stroke="url(#g1)" stroke-width="1.2" opacity="0.08">
                                    <path d="M60 6 L60 20" />
                                    <path d="M6 60 L20 60" />
                                    <path d="M104 60 L118 60" />
                                    <path d="M60 104 L60 118" />
                                </g>
                            </g>
                        </svg>

                        <svg class="corner-orn orn-right" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true">
                            <defs>
                                <linearGradient id="g2" x1="0" x2="1">
                                    <stop offset="0%" stop-color="#d1a24a" stop-opacity="0.95" />
                                    <stop offset="100%" stop-color="#a86fb5" stop-opacity="0.9" />
                                </linearGradient>
                            </defs>
                            <g transform="translate(8,8) scale(0.8) rotate(180 60 60)">
                                <circle cx="60" cy="60" r="34" fill="none" stroke="url(#g2)" stroke-width="2"
                                    opacity="0.12" />
                                <path d="M60 22 L66 44 L88 50 L70 64 L76 86 L60 74 L44 86 L50 64 L32 50 L54 44 Z"
                                    fill="none" stroke="url(#g2)" stroke-width="1.6" opacity="0.12" />
                                <circle cx="60" cy="60" r="4" fill="url(#g2)" opacity="0.12" />
                                <g stroke="url(#g2)" stroke-width="1.2" opacity="0.08">
                                    <path d="M60 6 L60 20" />
                                    <path d="M6 60 L20 60" />
                                    <path d="M104 60 L118 60" />
                                    <path d="M60 104 L60 118" />
                                </g>
                            </g>
                        </svg>

                        <!-- deck visual for shuffle animation (6 layered divs so we can animate each like a small stack shuffle) -->
                        <div class="deck-area">
                            <div id="deckStack" class="deck-stack" aria-hidden="true">
                                <div class="card-layer"></div>
                                <div class="card-layer"></div>
                                <div class="card-layer"></div>
                                <div class="card-layer"></div>
                                <div class="card-layer"></div>
                                <div class="card-layer"></div>
                            </div>
                        </div>

                        <div class="grid-15" id="grid"></div>
                    </section>

                    <aside class="sidebar">
                        <div class="slots">
                            <div class="slot" data-pos="past" id="slot1">
                                <div class="label">Past</div>
                                <div class="inner" id="inner1">(pick a card)</div>
                            </div>
                            <div class="slot" data-pos="present" id="slot2">
                                <div class="label">Present</div>
                                <div class="inner" id="inner2">(pick a card)</div>
                            </div>
                            <div class="slot" data-pos="future" id="slot3">
                                <div class="label">Future</div>
                                <div class="inner" id="inner3">(pick a card)</div>
                            </div>
                        </div>
                        <h1>Here is your echoes of destiny</h1>

                        <div class="reading" id="reading">
                            <h3>Your Reading</h3>
                            <div id="readingContent" class="muted">Shuffle to begin. When you pick 3 cards, a detailed
                                interpretation
                                appears here with a poetic, Banjaran-toned synthesis and action step.</div>
                        </div>
                    </aside>
                </div>
            </div>

            <!-- audio (user can upload a file; default path is sounds/shuffle.mp3) -->
            <audio id="shuffleSound" preload="auto">
                <!-- <source src="sounds/shuffle.mp3" type="audio/mpeg"> -->
                <!-- You can replace the file at sounds/shuffle.mp3 or upload your own using the control -->
            </audio>
        </div>

    </div>


    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script>
    // Deck (names + meanings + image paths)
    const FULL_DECK = [{
            id: 0,
            name: "The Fool",
            upright: "Beginnings; spontaneity; taking a leap of faith.",
            reversed: "Recklessness; holding back; fear of the unknown.",
            img: "images/00-TheFool.jpg"
        },
        {
            id: 1,
            name: "The Magician",
            upright: "Using skills and resources to manifest goals.",
            reversed: "Manipulation; untapped potential; scattered energy.",
            img: "images/01-TheMagician.jpg"
        },
        {
            id: 2,
            name: "The High Priestess",
            upright: "Intuition; inner voice; hidden knowledge.",
            reversed: "Secrets revealed; disconnection from intuition.",
            img: "images/02-TheHighPriestess.jpg"
        },
        {
            id: 3,
            name: "The Empress",
            upright: "Abundance; creativity; nurturing relationships.",
            reversed: "Dependency; creative block; smothering care.",
            img: "images/03-TheEmpress.jpg"
        },
        {
            id: 4,
            name: "The Emperor",
            upright: "Structure; leadership; authority.",
            reversed: "Rigidity; control issues; power struggles.",
            img: "images/04-TheEmperor.jpg"
        },
        {
            id: 5,
            name: "The Hierophant",
            upright: "Tradition; mentorship; shared values.",
            reversed: "Questioning tradition; unconventional path.",
            img: "images/05-TheHierophant.jpg"
        },
        {
            id: 6,
            name: "The Lovers",
            upright: "Partnership; values alignment; meaningful choice.",
            reversed: "Misalignment; relationship tension; difficult choice.",
            img: "images/06-TheLovers.jpg"
        },
        {
            id: 7,
            name: "The Chariot",
            upright: "Drive; victory through discipline.",
            reversed: "Loss of control; scattered focus.",
            img: "images/07-TheChariot.jpg"
        },
        {
            id: 8,
            name: "Strength",
            upright: "Courage; compassion; inner resilience.",
            reversed: "Self-doubt; impulsiveness; low energy.",
            img: "images/08-Strength.jpg"
        },
        {
            id: 9,
            name: "The Hermit",
            upright: "Soul-searching; solitude; inner guidance.",
            reversed: "Loneliness; avoidance of introspection.",
            img: "images/09-TheHermit.jpg"
        },
        {
            id: 10,
            name: "Wheel of Fortune",
            upright: "Cycles; turning point; luck.",
            reversed: "Resistance to change; ups and downs.",
            img: "images/10-WheelOfFortune.jpg"
        },
        {
            id: 11,
            name: "Justice",
            upright: "Fairness; accountability; cause and effect.",
            reversed: "Bias; unfair outcome; legal complications.",
            img: "images/11-Justice.jpg"
        },
        {
            id: 12,
            name: "The Hanged Man",
            upright: "Pause; new perspective; surrender.",
            reversed: "Stalling; needless sacrifice; impatience.",
            img: "images/12-TheHangedMan.jpg"
        },
        {
            id: 13,
            name: "Death",
            upright: "Transformation; endings that lead to new beginnings.",
            reversed: "Resistance to change; stagnation.",
            img: "images/13-Death.jpg"
        },
        {
            id: 14,
            name: "Temperance",
            upright: "Balance; healing; blending forces.",
            reversed: "Extremes; imbalance; disharmony.",
            img: "images/14-Temperance.jpg"
        },
        {
            id: 15,
            name: "The Devil",
            upright: "Shadow work; attachments; temptation.",
            reversed: "Breaking free; awareness of chains.",
            img: "images/15-TheDevil.jpg"
        },
        {
            id: 16,
            name: "The Tower",
            upright: "Sudden upheaval; revelation; clearing foundations.",
            reversed: "Avoided disaster; slow recovery after shock.",
            img: "images/16-TheTower.jpg"
        },
        {
            id: 17,
            name: "The Star",
            upright: "Hope; renewal; spiritual clarity.",
            reversed: "Lost faith; needing restoration.",
            img: "images/17-TheStar.jpg"
        },
        {
            id: 18,
            name: "The Moon",
            upright: "Subconscious; intuition; illusions.",
            reversed: "Clarity emerging; overcoming fear.",
            img: "images/18-TheMoon.jpg"
        },
        {
            id: 19,
            name: "The Sun",
            upright: "Joy; vitality; success.",
            reversed: "Temporary setbacks; caution against arrogance.",
            img: "images/19-TheSun.jpg"
        },
        {
            id: 20,
            name: "Judgement",
            upright: "Awakening; calling; important decision.",
            reversed: "Self-doubt; delayed reckoning.",
            img: "images/20-Judgement.jpg"
        },
        {
            id: 21,
            name: "The World",
            upright: "Completion; fulfilment; integration.",
            reversed: "Incomplete cycles; postponed achievement.",
            img: "images/21-TheWorld.jpg"
        },

        {
            id: 22,
            name: "Ace of Cups",
            upright: "Emotional renewal; new love or compassion.",
            reversed: "Blocked emotions; missed opportunity.",
            img: "images/Cups01.jpg"
        },
        {
            id: 23,
            name: "Two of Cups",
            upright: "Partnership; mutual attraction.",
            reversed: "Break in connection; imbalance.",
            img: "images/Cups02.jpg"
        },
        {
            id: 24,
            name: "Three of Cups",
            upright: "Community; celebration; friendship.",
            reversed: "Overindulgence; gossip.",
            img: "images/Cups03.jpg"
        },
        {
            id: 25,
            name: "Four of Cups",
            upright: "Apathy; contemplation; reevaluation.",
            reversed: "New opportunities; shifting perspective.",
            img: "images/Cups04.jpg"
        },
        {
            id: 26,
            name: "Five of Cups",
            upright: "Loss; grief; focus on the negative.",
            reversed: "Acceptance; healing; moving on.",
            img: "images/Cups05.jpg"
        },
        {
            id: 27,
            name: "Six of Cups",
            upright: "Nostalgia; kindness; past connections.",
            reversed: "Stuck in the past; needing closure.",
            img: "images/Cups06.jpg"
        },
        {
            id: 28,
            name: "Seven of Cups",
            upright: "Choices; imagination; many options.",
            reversed: "Illusion; need for clarity.",
            img: "images/Cups07.jpg"
        },
        {
            id: 29,
            name: "Eight of Cups",
            upright: "Walking away; spiritual searching.",
            reversed: "Avoiding necessary endings.",
            img: "images/Cups08.jpg"
        },
        {
            id: 30,
            name: "Nine of Cups",
            upright: "Satisfaction; wish fulfilment.",
            reversed: "Complacency; shallow pleasure.",
            img: "images/Cups09.jpg"
        },
        {
            id: 31,
            name: "Ten of Cups",
            upright: "Emotional fulfilment; family harmony.",
            reversed: "Family tension; unrealistic expectations.",
            img: "images/Cups10.jpg"
        },
        {
            id: 32,
            name: "Page of Cups",
            upright: "Creative message; gentle surprise.",
            reversed: "Emotional immaturity; blocked creativity.",
            img: "images/Cups11.jpg"
        },
        {
            id: 33,
            name: "Knight of Cups",
            upright: "Romantic proposal; following heart.",
            reversed: "Idealism; disappointment.",
            img: "images/Cups12.jpg"
        },
        {
            id: 34,
            name: "Queen of Cups",
            upright: "Compassion; emotional intelligence.",
            reversed: "Over-sensitivity; blurred boundaries.",
            img: "images/Cups13.jpg"
        },
        {
            id: 35,
            name: "King of Cups",
            upright: "Emotional balance; wise counsel.",
            reversed: "Emotional manipulation; bottled feelings.",
            img: "images/Cups14.jpg"
        },

        {
            id: 36,
            name: "Ace of Pentacles",
            upright: "Material opportunity; new venture.",
            reversed: "Missed opportunity; poor timing.",
            img: "images/Pentacles01.jpg"
        },
        {
            id: 37,
            name: "Two of Pentacles",
            upright: "Balance; juggling priorities.",
            reversed: "Overwhelm; dropping balls.",
            img: "images/Pentacles02.jpg"
        },
        {
            id: 38,
            name: "Three of Pentacles",
            upright: "Collaboration; skilled work.",
            reversed: "Lack of teamwork; low standards.",
            img: "images/Pentacles03.jpg"
        },
        {
            id: 39,
            name: "Four of Pentacles",
            upright: "Stability; holding on to resources.",
            reversed: "Stinginess; fear of loss.",
            img: "images/Pentacles04.jpg"
        },
        {
            id: 40,
            name: "Five of Pentacles",
            upright: "Hardship; feeling excluded.",
            reversed: "Recovery; help arrives.",
            img: "images/Pentacles05.jpg"
        },
        {
            id: 41,
            name: "Six of Pentacles",
            upright: "Generosity; shared resources.",
            reversed: "Strings attached; inequality.",
            img: "images/Pentacles06.jpg"
        },
        {
            id: 42,
            name: "Seven of Pentacles",
            upright: "Assessment; long-term view.",
            reversed: "Impatience; wanting immediate results.",
            img: "images/Pentacles07.jpg"
        },
        {
            id: 43,
            name: "Eight of Pentacles",
            upright: "Apprenticeship; mastery through practice.",
            reversed: "Perfectionism; lack of focus.",
            img: "images/Pentacles08.jpg"
        },
        {
            id: 44,
            name: "Nine of Pentacles",
            upright: "Self-sufficiency; refined comfort.",
            reversed: "Over-dependence; false security.",
            img: "images/Pentacles09.jpg"
        },
        {
            id: 45,
            name: "Ten of Pentacles",
            upright: "Legacy; family wealth; security.",
            reversed: "Family disputes; unstable inheritance.",
            img: "images/Pentacles10.jpg"
        },
        {
            id: 46,
            name: "Page of Pentacles",
            upright: "Study; practical message.",
            reversed: "Lack of progress; scattered efforts.",
            img: "images/Pentacles11.jpg"
        },
        {
            id: 47,
            name: "Knight of Pentacles",
            upright: "Steady progress; reliability.",
            reversed: "Stagnation; overly cautious.",
            img: "images/Pentacles12.jpg"
        },
        {
            id: 48,
            name: "Queen of Pentacles",
            upright: "Nurturing provider; practical kindness.",
            reversed: "Dependent; neglecting self.",
            img: "images/Pentacles13.jpg"
        },
        {
            id: 49,
            name: "King of Pentacles",
            upright: "Wealth; stable leadership; abundance.",
            reversed: "Materialism; unethical behaviour.",
            img: "images/Pentacles14.jpg"
        },

        {
            id: 50,
            name: "Ace of Swords",
            upright: "Clarity; new idea; truth revealed.",
            reversed: "Confusion; clouded judgement.",
            img: "images/Swords01.jpg"
        },
        {
            id: 51,
            name: "Two of Swords",
            upright: "Difficult choice; stalemate.",
            reversed: "Indecision resolved; information arrives.",
            img: "images/Swords02.jpg"
        },
        {
            id: 52,
            name: "Three of Swords",
            upright: "Heartbreak; painful truth.",
            reversed: "Recovery; forgiveness.",
            img: "images/Swords03.jpg"
        },
        {
            id: 53,
            name: "Four of Swords",
            upright: "Rest; recuperation; contemplation.",
            reversed: "Restlessness; need to act.",
            img: "images/Swords04.jpg"
        },
        {
            id: 54,
            name: "Five of Swords",
            upright: "Conflict; hollow victory.",
            reversed: "Making amends; choosing peace.",
            img: "images/Swords05.jpg"
        },
        {
            id: 55,
            name: "Six of Swords",
            upright: "Transition; moving away from difficulty.",
            reversed: "Resistance to change; stuck in past.",
            img: "images/Swords06.jpg"
        },
        {
            id: 56,
            name: "Seven of Swords",
            upright: "Strategy; stealth; tricky situation.",
            reversed: "Confession; being caught; transparency.",
            img: "images/Swords07.jpg"
        },
        {
            id: 57,
            name: "Eight of Swords",
            upright: "Feeling trapped; limiting beliefs.",
            reversed: "Releasing fear; new perspective.",
            img: "images/Swords08.jpg"
        },
        {
            id: 58,
            name: "Nine of Swords",
            upright: "Anxiety; sleepless nights; worry.",
            reversed: "Lessening worry; reaching out for help.",
            img: "images/Swords09.jpg"
        },
        {
            id: 59,
            name: "Ten of Swords",
            upright: "End of a cycle; hitting rock bottom.",
            reversed: "Recovery begins; pain lessens.",
            img: "images/Swords10.jpg"
        },
        {
            id: 60,
            name: "Page of Swords",
            upright: "Curiosity; mental agility.",
            reversed: "Gossip; scattered thoughts.",
            img: "images/Swords11.jpg"
        },
        {
            id: 61,
            name: "Knight of Swords",
            upright: "Decisive action; rushing forward.",
            reversed: "Reckless words; hasty decisions.",
            img: "images/Swords12.jpg"
        },
        {
            id: 62,
            name: "Queen of Swords",
            upright: "Clear boundaries; honest communication.",
            reversed: "Coldness; over-critical nature.",
            img: "images/Swords13.jpg"
        },
        {
            id: 63,
            name: "King of Swords",
            upright: "Analytical mind; authority in thought.",
            reversed: "Abuse of power; harsh judgement.",
            img: "images/Swords14.jpg"
        },

        {
            id: 64,
            name: "Ace of Wands",
            upright: "Inspiration; new creative spark.",
            reversed: "Delays; lack of motivation.",
            img: "images/Wands01.jpg"
        },
        {
            id: 65,
            name: "Two of Wands",
            upright: "Planning; future vision.",
            reversed: "Fear of the unknown; indecision.",
            img: "images/Wands02.jpg"
        },
        {
            id: 66,
            name: "Three of Wands",
            upright: "Expansion; opportunity on the horizon.",
            reversed: "Obstacles to growth; delays.",
            img: "images/Wands03.jpg"
        },
        {
            id: 67,
            name: "Four of Wands",
            upright: "Celebration; homecoming; stability.",
            reversed: "Instability at home; inner celebration blocked.",
            img: "images/Wands04.jpg"
        },
        {
            id: 68,
            name: "Five of Wands",
            upright: "Competition; testing ideas.",
            reversed: "Avoiding conflict; internal struggle.",
            img: "images/Wands05.jpg"
        },
        {
            id: 69,
            name: "Six of Wands",
            upright: "Victory; public recognition.",
            reversed: "Ego issues; lack of acknowledgment.",
            img: "images/Wands06.jpg"
        },
        {
            id: 70,
            name: "Seven of Wands",
            upright: "Defending position; perseverance.",
            reversed: "Overwhelm; feeling under attack.",
            img: "images/Wands07.jpg"
        },
        {
            id: 71,
            name: "Eight of Wands",
            upright: "Swift action; movement; communication.",
            reversed: "Delays; miscommunication.",
            img: "images/Wands08.jpg"
        },
        {
            id: 72,
            name: "Nine of Wands",
            upright: "Resilience; last stretch of effort.",
            reversed: "Burnout; need to rest.",
            img: "images/Wands09.jpg"
        },
        {
            id: 73,
            name: "Ten of Wands",
            upright: "Burden; responsibility; completion through effort.",
            reversed: "Overload; drop tasks; ask for help.",
            img: "images/Wands10.jpg"
        },
        {
            id: 74,
            name: "Page of Wands",
            upright: "Curiosity; messages of opportunity.",
            reversed: "Immaturity; scattered energy.",
            img: "images/Wands11.jpg"
        },
        {
            id: 75,
            name: "Knight of Wands",
            upright: "Passion; adventurous energy.",
            reversed: "Impulsiveness; reckless action.",
            img: "images/Wands12.jpg"
        },
        {
            id: 76,
            name: "Queen of Wands",
            upright: "Confidence; warmth; leadership.",
            reversed: "Hot temper; jealous tendencies.",
            img: "images/Wands13.jpg"
        },
        {
            id: 77,
            name: "King of Wands",
            upright: "Visionary leader; charismatic drive.",
            reversed: "Domineering; impatience.",
            img: "images/Wands14.jpg"
        }
    ];

    // state
    let layoutCards = [];
    let selected = [];
    const gridEl = document.getElementById('grid');
    const shuffleBtn = document.getElementById('shuffleBtn');
    const resetBtn = document.getElementById('resetBtn');
    const exportBtn = document.getElementById('exportBtn');
    // disable copy button initially
    exportBtn.disabled = true;
    const deckStack = document.getElementById('deckStack');
    const slots = [document.getElementById('slot1'), document.getElementById('slot2'), document.getElementById(
        'slot3')];
    const readingContent = document.getElementById('readingContent');
    const shuffleSound = document.getElementById('shuffleSound');
    const shuffleFileInput = document.getElementById('shuffleFileInput');

    // if no custom upload, default to sounds/shuffle.mp3 (you can replace that file in your project)
    try {
        if (!shuffleSound.src || shuffleSound.src.trim() === '') {
            // shuffleSound.src = 'sounds/shuffle.mp3';
        }
        // ensure the element tries to preload it
        shuffleSound.load();
    } catch (e) {
        /* ignore if src can't be set in some embed situations */
    }

    // allow user to upload their shuffle sound; we set it as the audio source and load it
    shuffleFileInput.addEventListener('change', (e) => {
        const f = e.target.files && e.target.files[0];
        if (!f) return;
        const url = URL.createObjectURL(f);
        shuffleSound.src = url;
        try {
            shuffleSound.load();
        } catch (e) {}
    });

    function shuffleArray(a) {
        for (let i = a.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [a[i], a[j]] = [a[j], a[i]];
        }
        return a;
    }

    // layOut15 leaves rendering to renderGrid which now applies the deal animation
    function layOut15() {
        const pool = FULL_DECK.map(c => ({
            ...c
        }));
        shuffleArray(pool);
        layoutCards = pool.slice(0, 15);
        renderGrid();
        selected = [];
        slots.forEach(s => {
            s.querySelector('.inner').innerHTML = '(pick a card)';
            delete s.dataset.card;
            delete s.dataset.rev;
            delete s.dataset.index
        });
        readingContent.innerHTML = 'Pick any 3 cards.'
    }

    function renderGrid() {
        gridEl.innerHTML = '';
        layoutCards.forEach((card, idx) => {
            const el = document.createElement('div');
            el.className = 'card-tile deal';
            el.style.animationDelay = `${idx * 80}ms`;
            el.innerHTML =
                `<div class="card-inner"><div class="card-back"></div><div class="card-face"><div class="frame"><img src="${card.img}" alt="${card.name}"></div></div></div>`;
            el.addEventListener('click', () => onGridClick(idx, el));
            gridEl.appendChild(el);
        });
    }

    // Swal.fire({
    //     title: "",
    //     width: 500,
    //     background: `url(img/correct.png)`,
    //     showCloseButton: false,
    //     customClass: {
    //         popup: 'my-popup',
    //         confirmButton: 'my-confirm-button'
    //     }
    // })

    function onGridClick(idx, el) {
        if (selected.length >= 3) {
            // alert('You already picked 3 cards.');
            swal({
                title: 'You already picked 3 cards.',
                // background: '#fff url(img/correct.png)'
            });


            return;
        }
        if (el.classList.contains('flipped')) return;
        const card = layoutCards[idx];
        const reversed = Math.random() < 0.35;
        el.classList.add('flipped');
        const face = el.querySelector('.card-face img');
        if (reversed) face.style.transform = 'rotate(180deg)';
        const emptySlot = slots.find(s => !s.dataset.card);
        if (emptySlot) assignToSlot(emptySlot, {
            card,
            reversed,
            idx
        });
    }

    function assignToSlot(slotEl, pick) {
        slotEl.dataset.card = pick.card.id;
        slotEl.dataset.rev = pick.reversed ? '1' : '0';
        slotEl.dataset.index = pick.idx;
        slotEl.querySelector('.inner').innerHTML =
            `<div style="text-align:center"><strong style="color:var(--gold)">${pick.card.name}</strong><div class="small" style="margin-top:6px">${pick.reversed ? '<em>(reversed)</em>' : ''}</div></div>`;
        selected.push({
            pos: slotEl.dataset.pos,
            card: pick.card,
            reversed: pick.reversed,
            index: pick.idx
        });
        if (selected.length === 3) produceReading();
    }

    function produceReading() {
        const order = ['past', 'present', 'future'];
        const byPos = {};
        selected.forEach(s => byPos[s.pos] = s);
        let html = '';
        html += '<div style="margin-bottom:8px;color:#5b4b3a">Detailed card-by-card interpretation:</div>';
        order.forEach(pos => {
            const entry = byPos[pos];
            if (!entry) {
                html += `<div><strong>${capitalize(pos)}:</strong> (no card)</div>`;
                return;
            }
            html +=
                `<div style="margin-bottom:12px"><strong style="color:var(--accent)">${capitalize(pos)} — ${entry.card.name} ${entry.reversed ? '<em>(reversed)</em>' : ''}</strong>`;
            html +=
                `<div class="small" style="margin-top:6px;color:#3b2f26">${entry.reversed ? entry.card.reversed : entry.card.upright}</div>`;
            html +=
                `<div class="small" style="margin-top:8px;color:#3b2f26">${expandedInterpretation(entry.card, entry.reversed, pos)}</div>`;
            html += '</div>';
        });
        html += '<hr />';
        html +=
            '<div style="margin-bottom:8px;color:#5b4b3a"><strong style="color:var(--accent)">Synthesis & guidance:</strong></div>';
        html += `<div class="small" style="color:#3b2f26">${synthesize(selected)}</div>`;
        readingContent.innerHTML = html;

        // ✅ Enable the Copy button now
        exportBtn.disabled = false;

        // --- NEW: send to server ---
        const payload = {
            cards: selected.map(s => ({
                name: s.card.name,
                reversed: s.reversed,
                pos: s.pos
            })),
            reading: readingContent.innerText
        };

        fetch('saveReading.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                console.log('Saved:', data);
            })
            .catch(err => console.error('Save failed:', err));
    }

    function capitalize(s) {
        return s.charAt(0).toUpperCase() + s.slice(1)
    }

    function expandedInterpretation(card, reversed, pos) {
        const base = (reversed ? card.reversed : card.upright);
        let suit = '';
        if (card.name.match(/Wands/i)) suit = 'Wands (action)';
        if (card.name.match(/Cups/i)) suit = 'Cups (emotion)';
        if (card.name.match(/Swords/i)) suit = 'Swords (thoughts)';
        if (card.name.match(/Pentacles/i)) suit = 'Pentacles (material)';
        if (!suit) suit = 'Major Arcana — a major life theme';
        const posNote = pos === 'past' ? 'This shaped your path to here.' : (pos === 'present' ?
            'This shapes your current moment.' : 'This points to what may unfold.');
        return `${base} ${posNote} (${suit}). Consider how this message connects to your recent choices and what small step you can take.`
    }

    function synthesize(picks) {
        if (picks.length < 3) return 'Pick 3 cards for a full synthesis.';
        const names = picks.map(p => p.card.name);
        const majors = picks.filter(p => p.card.id <= 21);
        const reversedCount = picks.filter(p => p.reversed).length;
        let lines = [];
        if (majors.length >= 2) lines.push('Major Arcana dominate — significant life themes are active.');
        const suitCounts = {};
        picks.forEach(p => {
            const n = p.card.name;
            if (/Wands/i.test(n)) suitCounts['Wands'] = (suitCounts['Wands'] || 0) + 1;
            if (/Cups/i.test(n)) suitCounts['Cups'] = (suitCounts['Cups'] || 0) + 1;
            if (/Swords/i.test(n)) suitCounts['Swords'] = (suitCounts['Swords'] || 0) + 1;
            if (/Pentacles/i.test(n)) suitCounts['Pentacles'] = (suitCounts['Pentacles'] || 0) + 1;
        });
        const dom = Object.keys(suitCounts).reduce((a, b) => suitCounts[a] >= (suitCounts[b] || 0) ? a : b, null);
        if (dom) lines.push(`<strong>Suit emphasis:</strong> ${dom}. Focus on ${suitSummary(dom)}.`);
        if (reversedCount >= 2) lines.push(
            'Multiple reversed cards indicate internal blocks; introspection recommended.');
        else if (reversedCount === 1) lines.push('A reversed card suggests a specific area to review.');
        if (names.includes('Death') || names.includes('The Tower')) lines.push(
            'Transformation/upheaval is present — allow old structures to fall away.');
        if (names.includes('The Star') || names.includes('The Sun')) lines.push(
            'Positive momentum: hope and recovery are likely.');
        lines.push(
            '<br><strong>Action:</strong> choose one concrete step to take within the next 48 hours that aligns with the message.'
        );
        return lines.join(' ')
    }

    function suitSummary(s) {
        if (s === 'Wands') return 'action and creative initiative';
        if (s === 'Cups') return 'relationships and emotions';
        if (s === 'Swords') return 'decisions and communication';
        if (s === 'Pentacles') return 'work and resources';
        return ''
    }

    // Animate the layered deck using the Web Animations API so it actually "shuffles" visually
    function animateDeckShuffle() {
        if (!deckStack) return Promise.resolve();
        deckStack.style.opacity = 1; // ensure visible
        deckStack.classList.add('show');
        const layers = Array.from(deckStack.querySelectorAll('.card-layer'));
        const animPromises = [];

        // start sound if available
        let audioPromise = Promise.resolve();
        try {
            // attempt to play the audio only if a source exists
            if (shuffleSound && (shuffleSound.src || shuffleSound.querySelector('source'))) {
                // ensure loaded
                try {
                    shuffleSound.load();
                } catch (e) {}
                const playPromise = shuffleSound.play();
                if (playPromise && playPromise.then) {
                    audioPromise = new Promise((res) => {
                        let settled = false;
                        const onEnd = () => {
                            if (!settled) {
                                settled = true;
                                shuffleSound.removeEventListener('ended', onEnd);
                                res();
                            }
                        };
                        shuffleSound.addEventListener('ended', onEnd);
                        // if play fails or is blocked, the promise will reject — catch and fallback
                        playPromise.catch(() => {
                            if (!settled) {
                                settled = true;
                                shuffleSound.removeEventListener('ended', onEnd);
                                res();
                            }
                        });
                        // safety fallback in case 'ended' never fires
                        setTimeout(() => {
                            if (!settled) {
                                settled = true;
                                shuffleSound.removeEventListener('ended', onEnd);
                                res();
                            }
                        }, 2600);
                    });
                }
            }
        } catch (e) {
            /* ignore audio errors and fallback to delay below */
        }

        // if audioPromise still resolved immediately, give a minimum visual delay so shuffle feels substantial
        const minAudioPromise = new Promise(res => setTimeout(res, 2200));
        audioPromise = Promise.race([audioPromise, minAudioPromise]);

        layers.forEach((layer, i) => {
            // different small offsets per layer to create a shifting stack effect
            const x = (i - (layers.length / 2)) * 10; // spread
            const rotate = (i % 2 === 0) ? -18 : 14;
            const keyframes = [{
                    transform: `translate(${x}px, ${Math.abs(x)}px) rotate(${rotate}deg)`,
                    offset: 0
                },
                {
                    transform: `translate(${x + ((i % 2) ? -38 : 38)}px, ${-44 + i * 2}px) rotate(${rotate * -1}deg)`,
                    offset: 0.28
                },
                {
                    transform: `translate(${x + ((i % 2) ? 28 : -28)}px, ${8 + i}px) rotate(${rotate * 0.6}deg)`,
                    offset: 0.62
                },
                {
                    transform: `translate(0px, 0px) rotate(0deg)`,
                    offset: 1
                }
            ];
            const timing = {
                duration: 2000,
                easing: 'cubic-bezier(.2,.9,.3,1)',
                delay: i * 80,
                fill: 'forwards'
            };
            try {
                const anim = layer.animate(keyframes, timing);
                animPromises.push(anim.finished);
            } catch (e) {
                // fallback - no WA support
                animPromises.push(new Promise(res => setTimeout(res, 2000 + i * 80)));
            }
        });

        // resolve when all finished AND audio (if any) finished
        return Promise.all([Promise.all(animPromises), audioPromise]).then(() => {
            // fade out the deck (visual) before dealing
            return new Promise((resolve) => {
                deckStack.style.transition = 'opacity 420ms ease, transform 420ms ease';
                deckStack.style.opacity = 0;
                // hide after fade
                setTimeout(() => {
                    deckStack.classList.remove('show');
                    resolve();
                }, 480);
            });
        }).then(() => {
            // cleanup transforms
            layers.forEach(l => {
                l.style.transform = '';
            });
        });
    }

    // --- Shuffle button animates the deck visual using animateDeckShuffle(), then deals cards with staggered animation ---
    shuffleBtn.addEventListener('click', () => {
        readingContent.innerHTML = 'Shuffling the deck...';
        exportBtn.disabled = true; // make sure copy is disabled whenever shuffle is pressed

        // run our animated shuffle (>=2s), then deal the 15 cards
        const fallbackTimeout = setTimeout(() => {
            // safety: if animation doesn't complete, still lay out cards
            layOut15();
        }, 3200);

        animateDeckShuffle().then(() => {
            clearTimeout(fallbackTimeout);
            // short rest before dealing so it feels rhythmical
            setTimeout(() => layOut15(), 160);
        }).catch(() => {
            clearTimeout(fallbackTimeout);
            layOut15();
        });
    });

    resetBtn.addEventListener('click', () => {
        layoutCards = [];
        selected = [];
        gridEl.innerHTML = '';
        slots.forEach(s => {
            delete s.dataset.card;
            delete s.dataset.rev;
            delete s.dataset.index;
            s.querySelector('.inner').innerHTML = '(pick a card)'
        });
        readingContent.innerHTML = 'Reset. Click "Shuffle & Lay 15" to begin.'
        exportBtn.disabled = true; // disable again after reset
    });
    exportBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(readingContent.innerText).then(() => {
            swal({
                title: 'Reading copied!',
                icon: 'success'
                // background: '#fff url(img/correct.png)'  // optional if you want background
            });
        });
    });


    // initial layout (no shuffle animation on load)
    layOut15();
    </script>
</body>

</html>