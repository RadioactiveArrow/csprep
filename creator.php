<?php
require 'header.php';
require './includes/dbh.php';
if(isset($_SESSION['testID'])) {
    unset($_SESSION['testID']);
    unset($_SESSION['qNum']);
    unset($_SESSION['qEx']);
    unset($_SESSION['correctAns']);
}
if(!isset($_SESSION['admin']) || $_SESSION['admin']==false) {
    header("Location: ./practice.php");
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=<device-width>, initial-scale=1.0">
    <title>Quiz Maker</title>

    <script src="scripts/index.js"></script>
    <script src="scripts/prism.js"></script>

    <link rel="stylesheet" href="styles/createquiz.css">

</head>

<body class="quizgen">
    <div class="page">
        <div class="spacer"></div>
        <div class="titlebox">
            <h1>Quiz Maker</h1>
            <h2>For admin use only</h2>
        </div>
        <form id="quizdata" action="" method="get">
            <fieldset class="datafield">
                <legend>Quiz Config:</legend>
                <div class="testname block">
                    <label>Quiz Name</label>
                    <input type="title" name="title" placeholder="Practice Test #1" value=<?php if (isset($_GET['title'])) {
                                                                                                echo $_GET['title'];
                                                                                            } ?>></input>
                </div>
                <div class="testdiff block">
                    <label>Quiz Difficulty</label>
                    <select name="difficulty">
                        <option value="Easy" <?php if (isset($_GET['difficulty']) && "1" == $_GET['difficulty']) {
                                                echo "selected";
                                            } ?>>Easy</option>
                        <option value="Medium" <?php if (isset($_GET['difficulty']) && "2" == $_GET['difficulty']) {
                                                echo "selected";
                                            } ?>>Medium</option>
                        <option value="Hard" <?php if (isset($_GET['difficulty']) && "3" == $_GET['difficulty']) {
                                                echo "selected";
                                            } ?>>Hard</option>
                    </select>
                </div>
                <div class="qcount block">
                    <label>Question Count</label>
                    <select name="count">
                        <?php
                        for ($i = 1; $i <= 100; $i++) {
                        ?>
                            <option <?php if (isset($_GET['count']) && $i == $_GET['count']) {
                                        echo "selected";
                                    } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <button class="">Apply Config Settings (Resets Questions)</button>
            </fieldset>
        </form>
        <form id="quizqs" action="" method="post">
            <fieldset class="quizfield">
                <legend>Questions:</legend>
                <?php
                if (isset($_GET["count"])) {
                    $testName = $_GET['title'];
                    $testDiff = $_GET['difficulty'];
                    $testQNum = (int) $_GET['count'];
                    for ($i = 1; $i <= $_GET['count']; $i++) {
                ?>
                        <div class="question block">
                            <label class="block number">#<?php echo $i; ?></label>
                            <label>Code block:</label>
                            <textarea class="block" name="code<?php echo $i; ?>" rows="10" cols="30" placeholder="Paste formatted code from an IDE in this field. Make sure pasted text is formatted exactly as you want it to appear in the quiz."></textarea>
                            <label>Question Text:</label>
                            <input class="block" type="title" name="qstring<?php echo $i; ?>" value="What is the output of the above code?"></input>
                            <label>Correct Answer:</label>
                            <select name="correct<?php echo $i; ?>">
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                                <option value="e">E</option>
                            </select>
                            <label>Answer A:</label>
                            <input class="answer" type="title" name="a<?php echo $i; ?>"></input>
                            <label>Answer B:</label>
                            <input class="answer" type="title" name="b<?php echo $i; ?>"></input>
                            <label>Answer C:</label>
                            <input class="answer" type="title" name="c<?php echo $i; ?>"></input>
                            <label>Answer D:</label>
                            <input class="answer" type="title" name="d<?php echo $i; ?>"></input>
                            <label>Answer E:</label>
                            <input class="answer" type="title" name="e<?php echo $i; ?>"></input>
                            <label>Explanation (Optional):</label>
                            <textarea class="block explain" name="explain<?php echo $i; ?>" rows="10" cols="30" placeholder="The answer is A because ..."></textarea>
                        </div>
                <?php
                    }
                }
                ?>
                <button <?php if (!isset($_GET["count"])) {
                            echo "disabled";
                        } ?> class="block mid" name="create_quiz">Create Quiz!</button>

                <?php
                //Adds quiz to SQL Database
                //Checks if form submitted
                if (isset($_POST["create_quiz"])) {
                    $sql = "SELECT testID FROM tests WHERE testName=?";
                    $stmt = mysqli_stmt_init($connect);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "<p class='message error'>&#9888&nbsp;Error: SQL Error</p>";
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $testName);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                        $existcount = mysqli_stmt_num_rows($stmt);
                        if ($existcount != 0) {
                            echo "<p class='message error'>&#9888&nbsp;Error: Quiz with identical title exists</p>";
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, "s", $testName);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_store_result($stmt);
                            $existcount = mysqli_stmt_num_rows($stmt);
                            if ($existcount != 0) {
                                echo "<p class='message error'>&#9888&nbsp;Error: Quiz with identical title exists</p>";
                                exit();
                            } else if ($existcount == 0) {
                                $sql = "INSERT INTO tests (testName, testDiff, testQNum) VALUES (?, ?, ?)";
                                $stmt = mysqli_stmt_init($connect);
                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                    echo "<p class='message error'>&#9888&nbsp;Error: SQL Error 2</p>";
                                    exit();
                                } else {
                                    mysqli_stmt_bind_param($stmt, "ssi", $testName, $testDiff, $testQNum);
                                    mysqli_stmt_execute($stmt);
                                    $testID = mysqli_insert_id($connect);
        
                                    for ($i = 1; $i <= $testQNum; $i++) { //Runs through every question
                                        if (empty($_POST['qstring' . $i]) || empty($_POST['correct' . $i])) {
                                            echo "<p class='message error'>&#9888&nbsp;Error: One or more fields is empty</p>";
                                            exit();
                                        } else {
                                            $sql = "INSERT INTO questions (testID, questionNum, questionBlockEncoded, questionText, questionExplanation) VALUES (?, ?, ?, ?, ?)";
                                            $stmt = mysqli_stmt_init($connect);
                                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                echo "<p class='message error'>&#9888&nbsp;Error: SQL Error 3: " . mysqli_stmt_error($stmt) . "</p>";
                                                exit();
                                            } else {
                                                $encoded =  base64_encode($_POST['code' . $i]);
                                                $qtext = $_POST['qstring' . $i];
                                                $explain = $_POST['explain' . $i];
                                                mysqli_stmt_bind_param($stmt, "iisss", $testID, $i, $encoded, $qtext, $explain);
                                                mysqli_stmt_execute($stmt);

                                                $questionID = mysqli_insert_id($connect);

                                                $atext = $_POST['a' . $i];
                                                $btext = $_POST['b' . $i];;
                                                $ctext = $_POST['c' . $i];;
                                                $dtext = $_POST['d' . $i];;
                                                $etext = $_POST['e' . $i];;

                                                $acor = $_POST['correct' . $i] == 'a' ? 1 : 0; 
                                                $bcor = $_POST['correct' . $i] == 'b' ? 1 : 0; 
                                                $ccor = $_POST['correct' . $i] == 'c' ? 1 : 0; 
                                                $dcor = $_POST['correct' . $i] == 'd' ? 1 : 0; 
                                                $ecor = $_POST['correct' . $i] == 'e' ? 1 : 0; 

                                                $a = "a";
                                                $b = "b";
                                                $c = "c";
                                                $d = "d";
                                                $e = "e";

                                                $sql = "INSERT INTO answers (questionID, testID, ansText, ansLetter, ansCorrect) VALUES (?, ?, ?, ?, ?)";
                                                
                                                $stmt = mysqli_stmt_init($connect);
                                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                    echo "<p class='message error'>&#9888&nbsp;Error: SQL Error 4: " . mysqli_stmt_error($stmt) . "</p>";
                                                    exit();
                                                }
                                                else {
                                                    mysqli_stmt_bind_param($stmt, "iissi",$questionID,$testID,$atext,$a,$acor);
                                                    mysqli_stmt_execute($stmt);
                                                    echo mysqli_stmt_error($stmt);
                                                }

                                                $stmt = mysqli_stmt_init($connect);
                                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                    echo "<p class='message error'>&#9888&nbsp;Error: SQL Error 4: " . mysqli_stmt_error($stmt) . "</p>";
                                                    exit();
                                                }
                                                else {
                                                    mysqli_stmt_bind_param($stmt, "iissi",$questionID,$testID,$btext,$b,$bcor);
                                                    mysqli_stmt_execute($stmt);
                                                }

                                                $stmt = mysqli_stmt_init($connect);
                                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                    echo "<p class='message error'>&#9888&nbsp;Error: SQL Error 4: " . mysqli_stmt_error($stmt) . "</p>";
                                                    exit();
                                                }
                                                else {
                                                    mysqli_stmt_bind_param($stmt, "iissi",$questionID,$testID,$ctext,$c,$ccor);
                                                    mysqli_stmt_execute($stmt);
                                                }

                                                $stmt = mysqli_stmt_init($connect);
                                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                    echo "<p class='message error'>&#9888&nbsp;Error: SQL Error 4: " . mysqli_stmt_error($stmt) . "</p>";
                                                    exit();
                                                }
                                                else {
                                                    mysqli_stmt_bind_param($stmt, "iissi",$questionID,$testID,$dtext,$d,$dcor);
                                                    mysqli_stmt_execute($stmt);
                                                }

                                                $stmt = mysqli_stmt_init($connect);
                                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                    echo "<p class='message error'>&#9888&nbsp;Error: SQL Error 4: " . mysqli_stmt_error($stmt) . "</p>";
                                                    exit();
                                                }
                                                else {
                                                    mysqli_stmt_bind_param($stmt, "iissi",$questionID,$testID,$etext,$e,$ecor);
                                                    mysqli_stmt_execute($stmt);
                                                }
                                            }
                                        }
                                    }
                                    echo "<p class='message success'>&#10003&nbsp;Success: Quiz Created!</p>";
                                }
                            }
                        }
                    }
                }
                ?>
            </fieldset>
        </form>
    </div>
</body>

</html>