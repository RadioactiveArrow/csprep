<?php
if (isset($_POST['reg-submit'])) {
    require 'dbh.php';
    $studentID = strtoupper($_POST['id']);
    $password = $_POST['p'];
    $password2 = $_POST['p2'];

    //checks if any fields are empty but html should do this already so idk sue me
    if (empty($studentID) || empty($password) || empty($password2)) {
        header("Location: ../signup.php?error=emptyfields");
        exit();
    }

    if ($password != $password2) {
        header("Location: ../signup.php?error=passfail");
        exit();
    }

    //check is user already exists in this DB
    $sql = "SELECT userUID FROM users WHERE userUID=?";
    $stmt = mysqli_stmt_init($connect);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=sql");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $studentID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $existcount = mysqli_stmt_num_rows($stmt);
        if ($existcount != 0) {
            header("Location: ../signup.php?error=exists");
            exit();
        }
    }

    //prepared SQL for registration
    $sql = "INSERT INTO users (userUID, userMail, userName, admin, userPass, verified, vkey) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($connect);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=sql");
        exit();
    } else {
        //pulls membership data to verify that user is a member of club
        $sql2 = "SELECT * FROM members WHERE student_id = ? LIMIT 1";
        $stmt2 = mysqli_stmt_init($connect);
        if (!mysqli_stmt_prepare($stmt2, $sql2)) {
            header("Location: ../signup.php?error=sql");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt2, "s", $studentID);
            mysqli_stmt_execute($stmt2);
            $result = mysqli_stmt_get_result($stmt2);
            if ($r = mysqli_fetch_assoc($result)) {
                $email = $r['email'];
                $name = $r['name'];
                $admin = 0;
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $verified = 1;
                $vkey = password_hash(time() . $username, PASSWORD_DEFAULT);
        
                mysqli_stmt_bind_param($stmt, "sssisis", $studentID, $email, $name, $admin, $hash, $verified, $vkey);
                mysqli_stmt_execute($stmt);
                header("Location: ../login.php?success=true");
            } else {
                header("Location: ../signup.php?error=nomember");
                exit();
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($connect);
} else {
    header("Location: ./signup.php");
}
