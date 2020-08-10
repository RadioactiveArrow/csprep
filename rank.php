<?php
require 'header.php';
require './includes/dbh.php';
require './includes/ranks.inc.php';

if (isset($_SESSION['userID'])) {
    $users = getRankings();
} else {
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
    </div>
    <table class="quiztable">
        <tr>
            <th>Rank</th>
            <th>Username</th>
            <th>Points</th>
        </tr>
        <?php
        for ($i = 0; $i < sizeof($users); $i++) {
        ?>
            <tr class="<?php echo $_SESSION['userUID'] == $users[$i]['userUID'] ? "user" : "other";?>">
                <td>#<?php echo $i+1 ?></td>
                <td class="center"><?php echo $users[$i]['userUID']; ?></td>
                <td class="center"><?php echo $users[$i]['total']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</body>

</html>