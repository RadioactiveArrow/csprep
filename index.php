<?php
require 'header.php';
if(isset($_SESSION['testID'])) {
    unset($_SESSION['testID']);
    unset($_SESSION['qNum']);
    unset($_SESSION['qEx']);
    unset($_SESSION['correctAns']);
}
if(isset($_SESSION['userUID'])) {
    header("Location: ./practice.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/prism.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
    <script src="scripts/index.js"></script>
    <script src="scripts/prism.js"></script>

</head>

<body class="parallax">
    <div class="spacer"></div>
    <div class="hero" data-aos="fade-up" data-aos-duration="1000">
        <h1>
            <bold>UIL Comp Sci</bold> Practice
        </h1>
        <div class="btn-container" data-aos="fade-up" data-aos-duration="1000">
            <a class="button" href="signup.php">Sign Up</a>
            <a class="button" href="login.php">Log In</a>
        </div>
    </div>
    <div class="columns">
        <div class="col1" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" data-aos-once="true">
            <img class="terminal cimg" src="media/index/terminal.svg">
            <h3 class="colhead">Real Coding Questions</h3>
        </div>
        <div class="col2" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400" data-aos-once="true">
            <img class="question cimg" src="media/index/help.svg">
            <h3 class="colhead">Answers With Explanations</h3>
        </div>
        <div class="col3" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600" data-aos-once="true">
            <img class="papers cimg" src="media/index/test.svg">
            <h3 class="colhead">Score Data and Analysis</h3>
        </div>
    </div>
    <div class="end">
        <h2 class="foothead" data-aos="" data-aos-duration="500" data-aos-once="true">Prepare for upcoming competitions with real life tests and daily practice questions.</h2>
        <div class="scrollanchor" />
    </div>
    <div class="footer">
        <img class="blogo" src="media/index/logo2020.svg">
        <ul class="links">
            <li><a class="footer-content" href="#">Home</a></li>
            <li><a class="footer-content" href="#">About</a></li>
            <li><a class="footer-content" href="signup.php">Signup</a></li>
            <li><a class="footer-content" href="login.php">Login</a></li>
        </ul>
    </div>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>