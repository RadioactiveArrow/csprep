<?php

function reject($id) {
    require 'dbh.php';

    $sql = "DELETE FROM tests WHERE testID = $id";
    mysqli_query($connect, $sql);
    $sql = "DELETE FROM answers WHERE testID = $id";
    mysqli_query($connect, $sql);
    $sql = "DELETE FROM usertests WHERE testID = $id";
    mysqli_query($connect, $sql);
    $sql = "DELETE FROM useranswers WHERE testID = $id";
    mysqli_query($connect, $sql);
    $sql = "DELETE FROM questions WHERE testID = $id";
    mysqli_query($connect, $sql);
}

function accept($id) {
    require 'dbh.php';

    $sql = "UPDATE tests SET active = 1 WHERE testID = $id";
    mysqli_query($connect, $sql);
}