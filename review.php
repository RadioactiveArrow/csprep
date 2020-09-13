<?php
require 'header.php';
require './includes/dbh.php';

if (isset($_SESSION['userUID'])) {
    if (isset($_SESSION['testID'])) {
        unset($_SESSION['testID']);
        unset($_SESSION['qNum']);
        unset($_SESSION['qEx']);
    }

    $sql = "SELECT * FROM usertests WHERE userID=? and testID=?";
    $stmt = mysqli_stmt_init($connect);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $_SESSION['userID'], $_GET['id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $taken = true;
        } else {
            $taken = false;
        }
    } else {
        $taken = false;
    }
    if (!$taken) {
        header("Location: ./practice.php");
    } else {
        $sql = "SELECT * FROM tests WHERE testID=? LIMIT 1";
        $stmt = mysqli_stmt_init($connect);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "<script type='text/javascript'>alert('SQL ERROR 1');</script>";
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($r = mysqli_fetch_assoc($result)) {
                $testID = $r['testID'];
                $testName = $r['testName'];
                $testDiff = $r['testDiff'];
                $testCount = $r['testQNum'];
            }
        }
    }
} else {
    header("Location: ./index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=<device-width>, initial-scale=1.0">
    <title>Review Question</title>

    <link rel="stylesheet" href="styles/prism.css">
    <link rel="stylesheet" href="styles/quiz.css">
    <link rel="stylesheet" href="styles/mcradio.css">
    <link rel="stylesheet" href="type/css/iconmonstr-iconic-font.css">

    <script src="scripts/index.js"></script>
    <script src="scripts/prism.js"></script>

    <!-- Explanation Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="styles/test.css" />
</head>

<body class="review">
    <div class="page">
        <div class="spacer"></div>

        <?php
        for ($i = 1; $i <= $testCount; $i++) {
            $sql = "SELECT * FROM questions WHERE testID=? AND questionNum=?";
            $stmt = mysqli_stmt_init($connect);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "ii", $_GET['id'], $i);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if ($r = mysqli_fetch_assoc($result)) {
                    $questionID = $r['questionID'];
                    $qBlock = base64_decode($r['questionBlockEncoded']);
                    $qText = $r['questionText'];
                    $questionExplain = $r['questionExplanation'];

                    $sql = "SELECT * FROM answers WHERE questionID=?";
                    $stmt = mysqli_stmt_init($connect);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, "i", $questionID);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $answers = array();
                        while ($r = mysqli_fetch_assoc($result)) {
                            array_push($answers, $r);
                        }
                    }
                }
            }
        ?>
            <div class="question">
                <div class="code">
                    <pre><code spellcheck="false" class="language-java line-numbers"><?php echo $qBlock; ?></code></pre>
                </div>
                <form>
                    <h1><em><?php echo $i; ?>)</em> <?php echo $qText; ?></h1>
                    <input type="radio" id=<?php echo "a" . $i; ?> name="radio"><label class="ans" for="a" onclick="select('a')"><?php echo $answers[0]['ansText']; ?></label>
                    <input type="radio" id=<?php echo "b" . $i; ?> name="radio"><label class="ans revcorrect" for="b" onclick="select('b')"><?php echo $answers[1]['ansText']; ?></label>
                    <input type="radio" id=<?php echo "c" . $i; ?> name="radio"><label class="ans" for="c" onclick="select('c')"><?php echo $answers[2]['ansText']; ?></label>
                    <input type="radio" id=<?php echo "d" . $i; ?> name="radio"><label class="ans" for="d" onclick="select('d')"><?php echo $answers[3]['ansText']; ?></label>
                    <input type="radio" id=<?php echo "e" . $i; ?> name="radio"><label class="ans" for="e" onclick="select('e')"><?php echo $answers[4]['ansText']; ?></label>
                    <input type="hidden" id=<?php echo "ans" . $i; ?> name="choice" value="" />
                </form>
                <p class="explain padded" href=<?php echo "#explain" . $i ?>>Explanation</>
                <p class="explainbody"><?php echo $questionExplain; ?></p>
                <div id=<?php echo "explain" . $i ?> class="modal">
                    <h1 class="mod">Explanation</h1>
                    <p class="mod"></p>
                    <a class="button" href="#" rel="modal:close">Okay.</a>
                </div>
            </div>
            <?php echo $i < $testCount ? '<hr class="thin">' : ''?>
        <?php
        }
        ?>
    </div>
    <div class="spacer"></div>
</body>

</html>