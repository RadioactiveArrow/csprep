<?php
if (isset($_POST['res-submit'])) {
    require 'dbh.php';
    $studentID = strtoupper($_POST['id']);
    $token = $_GET['token'];
    $password = $_POST['p'];
    $password2 = $_POST['p2'];

    //checks if any fields are empty but html should do this already so idk sue me
    if (empty($password) || empty($password2)) {
        header("Location: ../reset.php?error=emptyfields");
        exit();
    }

    if ($password != $password2) {
        header("Location: ../reset.php?error=passfail");
        exit();
    }


    $sql = "SELECT * FROM users WHERE vkey = ?";
    $stmt = mysqli_stmt_init($connect);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../reset.php?error=sql");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($r = mysqli_fetch_assoc($result)) {
            if (isset($r['userUID'])) {
                $username = strtoupper($r['userUID']);
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $vkey = password_hash(time() . $username, PASSWORD_DEFAULT);

                $sql = "UPDATE users SET userPass = ?, vkey = ? WHERE userUID = ?";
                $stmt = mysqli_stmt_init($connect);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../reset.php?error=sql");
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "sss", $hash, $vkey, $username);
                    mysqli_stmt_execute($stmt);
                    header("Location: ../reset.php?success=true");
                }
            }
        } else {
            header("Location: ../reset.php?error=nouser");
            exit();
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);
} else {
    header("Location: ../reset.php");
}
