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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/sweetalert.min.js"></script>
    <title>Tarrot Times</title>
    <style>
    :root {
        --paper: #f5f1e6;
        --ink: #2d2a26;
        --border: #4b3f2f;
        --accent: #b9a06b;
        /* Change this if your path is different */
        --card-back: url('./images/CardBacks.jpg');
    }

    @font-face {
        font-family: 'MorrisRomanBlack';
        src: url('fonts/MorrisRomanBlack.otf');
    }

    html,
    body {
        height: 100%;
    }

    body {
        margin: 0;
        font-family: "Georgia", serif;
        color: var(--ink);
        background: var(--paper);
        background-image: url(img/Tarrot_bg_desk.png);
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-attachment: fixed;
        font-family: 'MorrisRomanBlack';
    }

    .wrap {
        display: grid;
        grid-template-columns: 420px 1fr;
        gap: 24px;
        /* min-height: 100vh; */
        padding: 28px;
        box-sizing: border-box;
    }

    header {
        grid-column: 1 / -1;
        text-align: center;
    }

    h1 {
        margin: 0 0 6px;
        font-size: 2rem;
        text-shadow: 1px 1px #e0d6c0;
    }

    .sub {
        opacity: .85;
    }

    /* LEFT: 3x3 board area */
    .board {
        position: relative;
        border: 3px solid var(--border);
        border-radius: 12px;
        background: rgba(255, 255, 255, .6);
        box-shadow: 0 4px 18px rgba(0, 0, 0, .15) inset, 0 2px 10px rgba(0, 0, 0, .15);
        padding: 16px;
        overflow: hidden;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }

    /* Tarot Card */
    .card {
        width: 120px;
        height: 180px;
        perspective: 1000px;
        margin: 0 auto;
        position: relative;
        opacity: 0;
        /* revealed post-shuffle */
    }

    .card-inner {
        position: absolute;
        inset: 0;
        transform-style: preserve-3d;
        transition: transform .6s ease;
    }

    .card.is-flipped .card-inner {
        transform: rotateY(180deg);
    }

    .face {
        position: absolute;
        inset: 0;
        border-radius: 10px;
        border: 2px solid var(--border);
        backface-visibility: hidden;
        box-shadow: 2px 2px 8px rgba(0, 0, 0, .25);
    }

    .face.back {
        background: var(--accent) center/cover no-repeat;
        background-image: var(--card-back);
        /* your provided image */
        filter: contrast(.95) saturate(.9);
    }

    .face.front {
        transform: rotateY(180deg);
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-color: #fffef8;
    }

    .front-badge {
        font-size: 26px;
        line-height: 1;
    }

    /* RIGHT: reading panel */
    .reading {
        border: 3px solid var(--border);
        border-radius: 12px;
        padding: 18px;
        background: rgba(255, 255, 255, .72);
        box-shadow: 0 2px 10px rgba(0, 0, 0, .12);
    }

    .slot {
        border: 2px dashed rgba(0, 0, 0, .2);
        border-radius: 10px;
        padding: 5px;
        min-height: 84px;
        background: rgba(255, 255, 255, .6);
        margin-bottom: 12px;
    }

    .reveal {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: 14px;
        margin: 12px 0;
    }

    .title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 6px;
    }

    .link {
        margin-top: 6px;
        font-style: italic;
        opacity: .9;
    }

    .controls {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-top: 6px;
    }

    button {
        font-family: inherit;
        border: 2px solid var(--border);
        background: #fff8e6;
        cursor: pointer;
        padding: 10px 14px;
        border-radius: 10px;
        box-shadow: 0 2px 0 rgba(0, 0, 0, .15);
    }

    button:disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    /* Shuffle animation */
    .stack {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 120px;
        height: 180px;
        margin: -90px 0 0 -60px;
    }

    .stack .card {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 1;
        transform-origin: center center;
    }

    .shuffle-start .card {
        animation: riffle 900ms ease-in-out var(--delay, 0ms) 2 alternate;
    }

    @keyframes riffle {
        0% {
            transform: translate(0, 0) rotate(0deg);
        }

        50% {
            transform: translate(var(--dx, 18px), var(--dy, -26px)) rotate(var(--rot, 8deg));
        }

        100% {
            transform: translate(calc(var(--dx) * -1), calc(var(--dy) * -1)) rotate(calc(var(--rot) * -1));
        }
    }

    .fade-stack {
        animation: fadeOut .8s ease forwards;
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: scale(.92);
        }
    }

    .hint {
        font-size: .9rem;
        opacity: .8;
        margin-top: 6px;
    }


    .wrap {
        display: grid;
        grid-template-columns: 360px 800px;
        /* right side slightly wider */
        justify-content: center;
        gap: 20px;
        padding: 20px;
        /* min-height: 100vh; */
        box-sizing: border-box;
        height: auto;
    }

    .reading {
        width: 100%;
        height: 100%;
        /* matches card grid height */
        margin: 0 auto;
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: 14px;
        /* background: rgba(255, 255, 255, 0.72); */
        background: #faf4e4;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }


    .card {
        width: 100px;
        height: 150px;
        perspective: 800px;
        /* slightly smaller */
        margin: 0 auto;
        position: relative;
        opacity: 0;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        /* reduced gap */
    }

    .reading {
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: 14px;
        /* slightly smaller padding */
        /* background: rgba(255, 255, 255, .72); */
        background: #faf4e4;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
    }

    .title-head {
        text-align: center;
        margin-top: 6%;
        font-family: 'MorrisRomanBlack';
    }

    .upperaction {
        margin-top: 0px !important;
    }

    @media (min-width: 600px) and (max-width: 1200px) {

        .wrap {
            display: grid;
            grid-template-columns: 360px 400px;
            /* right side slightly wider */
            justify-content: center;
            gap: 20px;
            padding: 20px;
            /* min-height: 100vh; */
            box-sizing: border-box;
            height: auto;
        }

    }

    @media screen and (max-width: 480px) {

        /* Further reduce the card size */
        .card {
            width: 70px;
            height: 100px;
        }

        /* Adjust the grid layout */
        .grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            /* Even smaller gap */
        }

        /* Adjust the header */
        .title-head h1 {
            font-size: 2.2rem;
            /* Make the header smaller */
        }

        /* Smaller text for hints */
        .hint {
            font-size: 0.7rem;
        }

        /* Adjust buttons */
        button {
            font-size: 0.8rem;
            padding: 6px 10px;
            /* Smaller button padding */
        }

        /* Reduce padding in the reading section */
        .reading {
            padding: 8px;
        }

        .slot {
            font-size: 0.7rem;
            /* Smaller font size in slots */
        }

        .wrap {
            display: grid;
            grid-template-columns: 360px 800px;
            justify-content: center;
            gap: 20px;
            padding: 12px;
        }
    }

    h1 {
        margin: 0 0 6px;
        font-size: 25px;
        text-shadow: 1px 1px #e0d6c0;
    }

    .msg {

        text-align: left;
        font-size: 16px;

    }

    @media screen and (min-width:320px) and (max-width:1100px) and (orientation:landscape) {}

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

        .wrap {
            display: flex;
            flex-direction: column;
        }

        .title-head {
            text-align: center;
            margin-top: 20%;
            font-family: 'MorrisRomanBlack';
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            min-height: 500px;
            /* Adjust this value as needed */
        }

        .card {
            width: 85px;
            height: 140px;
            perspective: 800px;
            margin: 0 auto;
            position: relative;
            opacity: 0;
        }

        h1 {

            font-size: 18px;

        }

    }
    </style>
</head>

<body>
    <?php include ("../actions-default.php");
    back("game.php"); ?>

    <div class="title-head">
        <h1>Want to know what your Digital Future holds? Select any three cards to know more…</h1>
    </div>


    <div class="wrap">

        <!-- <header>
            <h1>Select any three cards</h1>
            <div class="sub">Old‑traveller deck • Draw exactly three cards</div>
        </header> -->

        <!-- LEFT: shuffle stack overlays, then settle into grid -->
        <section class="board">
            <div id="stack" class="stack"></div>
            <div id="grid" class="grid" aria-label="Tarot board"></div>
            <div class="hint" id="hint">Shuffling the deck…</div>
        </section>

        <!-- RIGHT: reading panel -->
        <aside class="reading">
            <div class="controls">
                <button id="reshuffle">🔁 Reshuffle</button>
                <span id="picked">Picked: 0 / 3</span>
            </div>
            <div id="readingSlots">
                <div class="slot" id="slot1">Card 1 will appear here…</div>
                <div class="slot" id="slot2">Card 2 will appear here…</div>
                <div class="slot" id="slot3">Card 3 will appear here…</div>
            </div>
            <div id="results"></div>
            <p class="msg"> Look for emails from Quess Academy to explore our Digital First: Everday Excellence Learning
                Series on
                AI tools.</p>
        </aside>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Card data (10 total, we deal 9 each round)
    const CARD_POOL = [{
            badge: "🧠",
            title: "The Card of Insight",
            img: "./images/The_Card_of_Insight.png",
            msg: "“The algorithms whisper: patterns are emerging. Trust the data, and clarity will follow.”",
            link: "Your future asks you to explore Fireflies — to uncover insights from conversations and meetings."
        },
        {
            badge: "⚙️",
            title: "The Card of Automation",
            img: "./images/The_Card_of_Automation.png",
            msg: "“Repetitive tasks fade into the past. Your future is optimized — let the bots handle the mundane.”",
            link: "Your future invites you to try Microsoft Copilot — to automate workflows effortlessly."
        },
        {
            badge: "🌐",
            title: "The Card of Connection",
            img: "./images/The_Card_of_Connection.png",
            msg: "“AI bridges minds across continents. Collaboration will spark innovation in unexpected places.”",
            link: "Your future nudges you toward Otter — to enable real‑time transcription and shared understanding."
        },
        {
            badge: "🪄",
            title: "The Card of Augmentation",
            img: "./images/The_Card_of_Augmentation.png",
            msg: "“You are not being replaced — you are being enhanced. Your digital twin awaits.”",
            link: "Your future pushes you to explore GenAI platforms — to enhance your creativity and productivity."
        },
        {
            badge: "🧭",
            title: "The Card of Ethical Compass",
            img: "./images/The_Card_of_Ethical_Compass.png",
            msg: "“The machine learns, but you guide. Your values shape the future of intelligence.”",
            link: "Your future reminds you to use ChatGPT wisely — shaping AI with responsible prompting and ethical use."
        },
        {
            badge: "🔍",
            title: "The Card of Discovery",
            img: "./images/The_Card_of_Discovery.png",
            msg: "“Hidden insights lie beneath the surface. AI will illuminate what intuition alone cannot.”",
            link: "Your future leads you to ExcelAI — to surface trends and patterns from complex data."
        },
        {
            badge: "🕰️",
            title: "The Card of Acceleration",
            img: "./images/The_Card_of_Accelaration.png",
            msg: "“Time bends in your favor. What once took months will now take moments.”",
            link: "Your future points you toward Gamma — to create presentations at lightning speed."
        },
        {
            badge: "🧬",
            title: "The Card of Evolution",
            img: "./images/The_Card_of_Evolution.png",
            msg: "“You are evolving with the machine. Together, you will redefine excellence.”",
            link: "Your future calls for Copilot + ChatGPT — a duo driving everyday excellence."
        },
        {
            badge: "🛡️",
            title: "The Card of Trust",
            img: "./images/The_Card_of_Trust.png",
            msg: "“Transparency is your shield. In the age of AI, trust is the new currency.”",
            link: "Your future tells you to explore Loom — building trust through clear, async communication."
        },
        {
            badge: "🎯",
            title: "The Card of Precision",
            img: "./images/The_Card_of_Precision.png",
            msg: "“Your decisions will be sharper, your actions more impactful. AI brings the edge.”",
            link: "Your future wants you to use ExcelAI + Fireflies — precision in numbers and conversations."
        },
    ];

    const grid = document.getElementById('grid');
    const stack = document.getElementById('stack');
    const results = document.getElementById('results');
    const hint = document.getElementById('hint');
    const pickedEl = document.getElementById('picked');
    const btnReshuffle = document.getElementById('reshuffle');

    let dealt = []; // 9 card objects
    let picked = []; // selected 3

    function shuffleArray(arr) {
        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [arr[i], arr[j]] = [arr[j], arr[i]];
        }
        return arr;
    }

    let shuffleTimer; // keep reference for timeout

    function dealNine() {
        picked = [];
        updatePicked();
        results.innerHTML = '';
        document.getElementById('slot1').textContent = 'Card 1 will appear here…';
        document.getElementById('slot2').textContent = 'Card 2 will appear here…';
        document.getElementById('slot3').textContent = 'Card 3 will appear here…';

        // Reset containers
        stack.innerHTML = '';
        grid.innerHTML = '';
        hint.textContent = 'Shuffling the deck…';

        // choose 9 out of pool randomly
        dealt = shuffleArray([...CARD_POOL]).slice(0, 9);

        const stackFrag = document.createDocumentFragment();
        for (let i = 0; i < 9; i++) {
            const c = createCardElement(dealt[i], true);
            c.style.setProperty('--delay', `${(i % 3) * 110}ms`);
            c.style.setProperty('--dx', `${(Math.random() * 40 + 10) | 0}px`);
            c.style.setProperty('--dy', `${(-Math.random() * 40 - 10) | 0}px`);
            c.style.setProperty('--rot', `${(Math.random() * 14 + 4).toFixed(1)}deg`);
            c.classList.add('card');
            stackFrag.appendChild(c);
        }
        stack.appendChild(stackFrag);

        // play riffle animation
        stack.classList.add('shuffle-start');

        // clear any previous timer
        if (shuffleTimer) clearTimeout(shuffleTimer);

        // After animation, fade stack and place to grid
        shuffleTimer = setTimeout(() => {
            stack.classList.remove('shuffle-start');
            stack.classList.add('fade-stack');
            hint.textContent = 'Pick any three cards.';

            const gridFrag = document.createDocumentFragment();
            dealt.forEach((cardData, idx) => {
                const g = createCardElement(cardData, true);
                g.style.opacity = '0';
                gridFrag.appendChild(g);

                setTimeout(() => {
                    g.style.transition = 'opacity .35s ease';
                    g.style.opacity = '1';
                }, 120 + idx * 60);
            });
            grid.appendChild(gridFrag);

            setTimeout(() => {
                stack.innerHTML = '';
                stack.classList.remove('fade-stack');
            }, 800);
        }, 1200);
    }

    btnReshuffle.addEventListener('click', () => {
        // reset grid & stack immediately
        stack.innerHTML = '';
        grid.innerHTML = '';
        dealNine();
    });


    function createCardElement(cardData, facedown) {
        const wrap = document.createElement('div');
        wrap.className = 'card';
        wrap.setAttribute('tabindex', '0');
        wrap.ariaLabel = 'Tarot card';

        const inner = document.createElement('div');
        inner.className = 'card-inner';

        const back = document.createElement('div');
        back.className = 'face back';

        const front = document.createElement('div');
        front.className = 'face front';
        // Use the image provided for this card's face
        if (cardData.img) {
            front.style.backgroundImage = `url('${cardData.img}')`;
        }

        inner.appendChild(back);
        inner.appendChild(front);
        wrap.appendChild(inner);

        if (!facedown) {
            wrap.classList.add('is-flipped');
        }

        wrap.addEventListener('click', () => onPick(wrap, cardData));
        wrap.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                onPick(wrap, cardData);
            }
        });
        return wrap;
    }

    function onPick(el, cardData) {
        if (picked.length >= 3) return;
        if (el.classList.contains('is-flipped')) return; // already picked

        el.classList.add('is-flipped');
        picked.push(cardData);
        updatePicked();
        const i = picked.length;
        const slot = document.getElementById('slot' + i);
        slot.innerHTML = renderReveal(cardData);

        if (picked.length === 3) {
            hint.textContent = 'Reading ready. Reshuffle to try again.';
            lockRemaining();
            saveReadingToDatabase(); // Add this line
        }
    }

    function lockRemaining() {
        // disable clicks on any unpicked cards
        Array.from(grid.children).forEach(c => {
            if (!c.classList.contains('is-flipped')) {
                c.style.pointerEvents = 'none';
                c.style.opacity = '.6';
            }
        });
    }

    function updatePicked() {
        pickedEl.textContent = `Picked: ${picked.length} / 3`;
    }

    function renderReveal(c) {
        return `<div class="reveal">
        <div class="title">${c.badge} ${c.title}</div>
        <div>${c.msg}</div>
        <div class="link">${c.link}</div>
      </div>`;
    }

    btnReshuffle.addEventListener('click', () => {
        // enable again
        Array.from(grid.children).forEach(c => {
            c.style.pointerEvents = '';
            c.style.opacity = '';
        });
        dealNine();
    });

    // Preload images to avoid flip flicker
    (function preload() {
        CARD_POOL.forEach(c => {
            if (c.img) {
                const im = new Image();
                im.src = c.img;
            }
        });
    })();


    // Add this function to your JavaScript
    function saveReadingToDatabase() {
        if (picked.length !== 3) return;

        // Prepare data to send
        const readingData = {
            cards: picked.map(card => card.title),
            reading: picked.map(card =>
                `${card.title}: ${card.msg} ${card.link}`
            ).join('\n\n')
        };

        // Send to server
        fetch('saveReading.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(readingData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Reading saved successfully');
                } else {
                    console.error('Error saving reading:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // boot
    dealNine();
    </script>
</body>

</html>