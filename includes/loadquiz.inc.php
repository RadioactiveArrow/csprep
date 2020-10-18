<?php
session_start();
require 'dbh.php';

$qData = array();
$errors = array();

if (isset($_SESSION['userUID'])) {
    if (isset($_POST['testID']) && !isset($_SESSION['qNum'])) {
        $_SESSION['testID'] = $_POST['testID'];
        $_SESSION['qNum'] = 1;
    }
    if (isset($_SESSION['testID'])) {
        $sql = "SELECT * FROM tests WHERE testID=? LIMIT 1"; //Load Test
        $stmt = mysqli_stmt_init($connect);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $errors['sql1'] = true;
        } else {
            mysqli_stmt_bind_param($stmt, "i", $_SESSION['testID']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($r = mysqli_fetch_assoc($result)) {
                //Retrieve test configuration data
                $testID = $qData['testID'] = $r['testID'];
                $qNum = $qData['qNum'] = $_SESSION['qNum'];
                $qData['testName'] = $r['testName'];
                $qData['testDiff'] = $r['testDiff'];
                $qData['testQCount'] = $r['testQNum'];
                $qData['userID'] = $_SESSION['userID'];

                //Retrieve last uncompleted question
                $sql = "SELECT questionNum FROM useranswers WHERE userID=? AND testID=? ORDER BY questionNum DESC LIMIT 1";
                $stmt = mysqli_stmt_init($connect);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    $errors['sqlLast'] = true;
                } else {
                    mysqli_stmt_bind_param($stmt, "ii", $_SESSION['userID'],$testID);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if ($r = mysqli_fetch_assoc($result)) {
                        $qNum = $qData['qNum'] = $_SESSION['qNum'] = $r['questionNum']+1;
                    }
                }
                
                if($qData['qNum'] > $qData['testQCount']) {
                    $qData['complete'] = true;
                } else{
                    $qData['complete'] = false;
                }

                $sql = "SELECT * FROM questions WHERE testID=? AND questionNum=?"; //Load Question
                $stmt = mysqli_stmt_init($connect);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    $errors['sql2'] = true;
                } else {
                    mysqli_stmt_bind_param($stmt, "ii", $testID, $qNum);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if ($r = mysqli_fetch_assoc($result)) {
                        $qID = $qData['qID'] = $r['questionID'];
                        $qCodeBlock = $qData['qCode'] = base64_decode($r['questionBlockEncoded']);
                        $qText = $qData['qText'] = $r['questionText'];
                        $qExplain = $_SESSION['qEx'] = encrypt($r['questionExplanation']);

                        $sql = "SELECT * FROM answers WHERE questionID=? ORDER BY ansLetter";
                        $stmt = mysqli_stmt_init($connect);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "<script type='text/javascript'>alert('SQL ERROR 3');</script>";
                        } else {
                            mysqli_stmt_bind_param($stmt, "i", $qID);
                            mysqli_stmt_execute($stmt);
                            $answers = array();
                            $result = mysqli_stmt_get_result($stmt);
                            while ($r = mysqli_fetch_assoc($result)) {
                                $ans = array();
                                $ans['letter'] = $r['ansLetter'];
                                $ans['text'] = $r['ansText'];
                                $ans['aID'] = $r['ansID'];
                                if ($r['ansCorrect'] == 1) {
                                    $_SESSION['correctAns'] = encrypt($r['ansLetter']);
                                }
                                array_push($answers, $ans);
                            }
                            $qData['answers'] = $answers;
                        }
                    } else {
                        $errors['sql3'] = true;
                    }
                }
            }
        }
    } else {
        $errors['sql4'] = true;
    }
} else {
    $errors['sql5'] = true;
}
$qData['errors'] = $errors;
echo json_encode($qData);

function encrypt($string)
{
    $key = $_SESSION['key'];
    $iv = $_SESSION['userID'] . $_SESSION['userUID'];

    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $key);
    $iv = substr(hash('sha256', $iv), 0, 16);

    return base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
}
