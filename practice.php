<?php
require 'header.php';
require './includes/dbh.php';
require './includes/practice.inc.php';

if (isset($_SESSION['userID'])) {
    $tests = getPracticeTests();
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
            <h1><a class="headertext">Practice</a></h1>
            <h2><a class="headertext" href="scores.php">Scores</a></h2>
            <h2><a class="headertext" href="rank.php">Rank</a></h2>
        </div>
        <table class="quiztable">
            <tr>
                <th>Test</th>
                <th>Questions</th>
                <th class="non-essential">Difficulty</th>
            </tr>
            <?php
            for ($i = 0; $i < sizeof($tests); $i++) {
            ?>
                <tr>
                    <td><?php echo $tests[$i]['testName']; ?></td>
                    <td><?php echo $tests[$i]['testQNum']; ?></td>
                    <td class="non-essential"><?php echo $tests[$i]['testDiff']; ?></td>
                    <td><a class="button <?php echo $tests[$i]['taken'] ? "review" : "take" ?>" href="<?php echo $tests[$i]['taken'] ? "review.php?id=" . $tests[$i]['testID'] : "quiz.php?id=" . $tests[$i]['testID'] ?>"><?php echo $tests[$i]['taken'] ? "Review" : "Take Test"; ?></a></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>

</html>