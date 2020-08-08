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
            array_push($tests, $r);
        }
    }
}
else {
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
                <th>Quiz Name</th>
                <th>Questions</th>
                <th>Difficulty</th>
            </tr>
            <?php
            for ($i = 0; $i < sizeof($tests); $i++) {
                $sql = "SELECT * FROM usertests WHERE userID=? and testID=?";
                $stmt = mysqli_stmt_init($connect);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ii", $_SESSION['userID'], $tests[$i]['testID']);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        $taken = true;
                    } else {
                        $taken = false;
                    }
                }
		else {
			$taken = false;
		}
            ?>
                <tr>
                    <td><?php echo $tests[$i]['testName']; ?></td>
                    <td class="center"><?php echo $tests[$i]['testQNum']; ?></td>
                    <td><?php echo $tests[$i]['testDiff']; ?></td>
                    <td><a class="button <?php echo $taken ? "review" : "take"?>" href="<?php echo $taken ? "review.php?id=".$tests[$i]['testID'] : "quiz.php?id=".$tests[$i]['testID']?>"><?php echo $taken ? "Review" : "Take Test";?></a></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>

</html>
