<?php

function getPracticeTests()
{
    require 'dbh.php';

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
            $sql = "SELECT * FROM usertests WHERE userID=? and testID=?";
            $stmt = mysqli_stmt_init($connect);
            $taken = false;
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "ii", $_SESSION['userID'], $r['testID']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $taken = true;
                }
            }
            $r['taken'] = $taken;
            array_push($tests, $r);
        }
    }

    return $tests;
}
