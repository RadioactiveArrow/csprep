<?php
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TompkinsCSPrep</title>

    <link rel="stylesheet" href="type/ionicfont/ionicons.css">
    <link rel="stylesheet" href="type/inter/inter.css">
    <link rel="stylesheet" href="type/league-mono/league.css">
    <link rel="stylesheet" href="styles/header.css">

    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="scripts/header.js"></script>
</head>

<body onscroll="onScroll()">
    <div class="header transparent">
        <div class="main-container">
            <?php
            if (!isset($_SESSION['userUID']))
                echo '<a href="index.php"><img class="logo" src="media/header/logo.svg"/></a>';
            else
                echo '<a href="practice.php"><img class="logo" src="media/header/logo.svg"/></a>';
            ?>
        </div>
        <ul class="right-container">
            <li>
                <?php
                if (!isset($_SESSION['userUID']))
                    echo '<a class="content underline" href="signup.php">Sign Up</a>';
                else if ($_SESSION['admin'] == false)
                    echo '<a class="content underline" href="practice.php">Home</a>';
                else if ($_SESSION['admin'] == true) {
                    echo '<a class="content underline" href="creator.php">Creator</a>';
                }
                ?>

            </li>
            <li>
                <?php
                if (!isset($_SESSION['userUID']))
                    echo '<a class="content underline" href="login.php">Log In</a>';
                else
                    echo '<a class="content underline" id="logout" href="index.php">Log Out</a>';
                ?>
            </li>
        </ul>
    </div>
    <!-- <div class="spacer"></div> -->
</body>