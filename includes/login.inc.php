<?php
if (isset($_POST['login-submit'])) {
    require 'dbh.php';

    $mailname = $_POST['umail'];
    $pass = $_POST['password'];

    if (empty($mailname) || empty($pass)) { #if this runs give up
        header("Location: ../login.php?error=emptyfields");
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE userUID=? OR userMail=?";
        $stmt = mysqli_stmt_init($connect);
        if (!mysqli_stmt_prepare($stmt, $sql)) { #sql statement failed at living
            header("Location: ../login.php?error=sql");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $mailname, $mailname);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($r = mysqli_fetch_assoc($result)) {
            $passwordGood = password_verify($pass, $r['userPass']);
            if ($passwordGood == true) {
                session_start();
                $_SESSION['userID'] = $r['userID'];
                $_SESSION['userUID'] = $r['userUID'];  
                $_SESSION['userMail'] =  $r['userMail']; 
                $_SESSION['userName'] = $r['userName']; 
                $_SESSION['admin'] = (boolean)$r['admin'];
                $_SESSION['key'] = bin2hex(openssl_random_pseudo_bytes(10)); 
                header("Location: ../practice.php");
            } else {
                header("Location: ../login.php?error=wrong");
                exit();
            }
        } else {
            header("Location: ../login.php?error=nouser");
            exit();
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($connect);
} else {
    header("Location: ../login.php?error=sql");
    exit();
}
?>