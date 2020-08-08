<?php
session_start();
require 'dbh.php';

$qData = array();
$errors = array();
if (isset($_SESSION['correctAns']) && !empty($_POST['ans'])) {
    $qData['explain'] = decrypt($_SESSION['qEx']);
    $correct = $qData['answer'] = decrypt($_SESSION['correctAns']);
    $choice = $qData['choice'] = $_POST['ans'];
    if ($choice == $correct) {
        $qData['correct'] = true;
    } else {
        $qData['correct'] = false;
    }

    if (isset($qData['correct'])) {
        $optNum;
        switch ($choice) {
            case 'a':
                $optNum = 0;
                break;
            case 'b':
                $optNum = 1;
                break;
            case 'c':
                $optNum = 2;
                break;
            case 'd':
                $optNum = 3;
                break;
            case 'e':
                $optNum = 4;
                break;
        }

        $userID = $_SESSION['userID'];
        $testID = $_SESSION['testID'];
        $questionID = $qData['qID'] = $_POST['qID'];
        $questionNum = $_SESSION['qNum'];
        $answerID = $qData['ansID'] = $_POST['options'][$optNum]['aID'];
        $userCorrect = $qData['correct'];

        $sql = "SELECT * FROM useranswers WHERE testID=? AND questionID=? AND userID=? LIMIT 1";
        $stmt = mysqli_stmt_init($connect);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $errors['sqlA1'] = true;
        } else {
            mysqli_stmt_bind_param($stmt, "iii", $testID, $questionID, $userID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $existcount = mysqli_stmt_num_rows($stmt);
            if ($existcount != 0) {
                $qData['existingEntry'] = true;
            } else {
                $qData['existingEntry'] = false;
                $sql = "INSERT INTO useranswers (userID, testID, questionID, questionNum, answerID, userCorrect) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($connect);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    $errors['sqlA2'] = true;
                } else {
                    mysqli_stmt_bind_param($stmt, "iiiiii", $userID, $testID, $questionID, $questionNum, $answerID, $userCorrect);
                    mysqli_stmt_execute($stmt);
                }
            }
        }
    }
}

//Retrieve last uncompleted question
$sql = "SELECT questionNum FROM useranswers WHERE userID=? AND testID=? ORDER BY questionNum DESC LIMIT 1";
$stmt = mysqli_stmt_init($connect);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $errors['sqlcomp'] = true;
} else {
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION['userID'],$testID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($r = mysqli_fetch_assoc($result)) {
        $qNumTest = $qData['qNumTest'] = $r['questionNum'];
    }
}

$sql = "SELECT testQNum FROM tests WHERE testID=?";
$stmt = mysqli_stmt_init($connect);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo mysqli_error($connect);
    exit();
} else {
    mysqli_stmt_bind_param($stmt,"i",$_SESSION['testID']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($r = mysqli_fetch_assoc($result)) {
        $testcount = $r['testQNum'];
    }
    else {
        echo"bruh";
    }
}

$sql = "SELECT * FROM useranswers WHERE testID=? AND userID=?";
$stmt = mysqli_stmt_init($connect);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo mysqli_error($connect);
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION['testID'], $_SESSION['userID']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $total = $qData['total'] = mysqli_stmt_num_rows($stmt);
    if ($total != 0) {
        $sql = "SELECT * FROM useranswers WHERE testID=? AND userID=? AND userCorrect=1";
        $stmt = mysqli_stmt_init($connect);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo mysqli_error($connect);
        } else {
            mysqli_stmt_bind_param($stmt, "ii", $_SESSION['testID'],$_SESSION['userID']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $correct = $qData['count'] = mysqli_stmt_num_rows($stmt);
        }
    } else {
        $r['sqltotal'] = "error";
    }
}

if($total == $testcount) {
    $qData['complete'] = true;
    $sql = "INSERT INTO usertests (userID,testID,score,datesub) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($connect);
    if(mysqli_stmt_prepare($stmt, $sql)) {
        $score = $qData['score'] = (int)(($correct/$total)*100);
        $date = $qData['date'] = date("m.d.y");
        mysqli_stmt_bind_param($stmt,"iiis",$_SESSION['userID'],$_SESSION['testID'],$score,$date);
        mysqli_stmt_execute($stmt);
    }
} else{
    $qData['complete'] = false;
}

$qData['errors'] = $errors;
echo json_encode($qData);

function decrypt($string)
{
    $key = $_SESSION['key'];
    $iv = $_SESSION['userID'] . $_SESSION['userUID'];

    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $key);
    $iv = substr(hash('sha256', $iv), 0, 16);

    return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
}
