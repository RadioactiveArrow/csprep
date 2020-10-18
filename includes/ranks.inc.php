<?php
getRankings();

function scoreSort($a, $b) {
    if ($a['total'] == $b['total']) {
        return 0;
    }
    return ($a['total'] > $b['total']) ? -1 : 1;
}

function getRankings()
{
    require 'dbh.php';

    $users = array();
    $sql = "SELECT useranswers.userID, useranswers.userCorrect, users.userUID FROM useranswers INNER JOIN users ON useranswers.userID = users.userID WHERE users.admin = 0 ";
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
            if(!isset($users[$r['userUID']]))
                $users[$r['userUID']] = array('userUID' => $r['userUID'], 'total' => 0, 'rank' => null);
                $users[$r['userUID']]['total'] += ($r['userCorrect']*10);
        }
        usort($users,"scoreSort");
    }

    return $users;
}
