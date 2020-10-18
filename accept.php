<?php
require 'header.php';
require './includes/dbh.php';
require './includes/admin.inc.php';

if (isset($_SESSION['userID']) && $_SESSION['admin'] = true) {
    accept($_GET['id']);
    header("Location: practice.php");
} else {
    header("Location: practice.php");
}