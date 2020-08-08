<?php
require 'header.php';
require './includes/dbh.php';
if(!isset($_SESSION['userID'])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Practice</title>
    <link rel="stylesheet" href="styles/practice.css">
</head>

<body>
    <div class="page">
        <div class="spacer"></div>
        <div class="link-container">
            <h2><a class="headertext" href="practice.php">Practice</a></h2>
            <h2><a class="headertext" href="scores.php">Scores</a></h2>
            <h1><a class="headertext">Rank</a></h1>
        </div>
        <h1 style="margin: 0 5% 0 5%;"><a class="headertext">This page is not done yet.</a></h1>
    </div>
</body>

</html>
