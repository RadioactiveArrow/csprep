<?php
session_start();
$qData = array();

$qData['old'] = $_SESSION['qNum'];
$qData['ans'] = $_POST['answered'];

if(isset($_SESSION['qNum']) && $_POST['answered'] == "true") {
    $_SESSION['qNum']++;
}
$qData['num'] = $_SESSION['qNum'];
echo json_encode($qData);
