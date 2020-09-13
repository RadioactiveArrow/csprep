<?php
require 'header.php';
require './includes/dbh.php';

if (isset($_SESSION['userID'])) {
    $tests = array();
    $sql = "SELECT * FROM tests";
    $stmt = mysqli_stmt_init($connect);
    if (isset($_SESSION['testID'])) {
        unset($_SESSION['testID']);
        unset($_SESSION['qNum']);
        unset($_SESSION['qEx']);
    }
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo mysqli_error($connect);
        exit();
    } else {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($r = mysqli_fetch_assoc($result)) {
            $sql = "SELECT * FROM useranswers WHERE testID=? AND userID=?";
            $stmt = mysqli_stmt_init($connect);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo mysqli_error($connect);
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ii", $r['testID'], $_SESSION['userID']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $total = mysqli_stmt_num_rows($stmt);
                if ($total != 0) {
                    $sql = "SELECT * FROM useranswers WHERE testID=? AND userID=? AND userCorrect=1";
                    $stmt = mysqli_stmt_init($connect);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo mysqli_error($connect);
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "ii", $r['testID'],$_SESSION['userID']);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                        $correct = mysqli_stmt_num_rows($stmt);
                        $score = ($correct / $r['testQNum'])*100;
                        $r['score'] = (int)$score."%";
                    }
                } else {
                    $r['score'] = "NA";
                }
            }

            array_push($tests, $r);
        }
    }
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
            <h1><a class="headertext">Scores</a></h1>
            <h2><a class="headertext" href="rank.php">Rank</a></h2>
        </div>
        <table class="quiztable">
            <tr>
                <th>Quiz Name</th>
                <th>Score</th>
                <th>Difficulty</th>
            </tr>
            <?php
            for ($i = 0; $i < sizeof($tests); $i++) {
                if($tests[$i]['score'] != "NA") {
            ?>
                <tr>
                    <td><?php echo $tests[$i]['testName']; ?></td>
                    <td class="center"><?php echo $tests[$i]['score']; ?></td>
                    <td><?php echo $tests[$i]['testDiff']; ?></td>
                    <!-- <td><a class="button register" href="quizajax.php?id=<?php /*echo $tests[$i]['testID'];*/ ?>">Practice</a></td> -->
                </tr>
            <?php
                }
            }
            ?>
        </table>
    </div>
</body>

</html>
