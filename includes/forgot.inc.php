<?php
if (isset($_POST['forgot-submit'])) {
    require 'dbh.php';
    $studentID = strtoupper($_POST['id']);

    //checks if any fields are empty but html should do this already so idk sue me
    if (empty($studentID)) {
        header("Location: ../forgot.php?error=emptyfields");
        exit();
    }

    //check is user already exists in this DB
    $sql = "SELECT email FROM members WHERE student_id=?";
    $stmt = mysqli_stmt_init($connect);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../forgot.php?error=sql");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $studentID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($r = $result->fetch_assoc()) {
            mail($r['email'],"Password Reset Request from TompkinsCS","Hi");
        } else {
            header("Location: ../forgot.php?error=nouser");
            exit();
        }
    }

    //prepared SQL for registration
    // $sql = "INSERT INTO users (userUID, userMail, userName, admin, userPass, verified, vkey) VALUES (?, ?, ?, ?, ?, ?, ?)";
    // $stmt = mysqli_stmt_init($connect);
    // if (!mysqli_stmt_prepare($stmt, $sql)) {
    //     header("Location: ../signup.php?error=sql");
    //     exit();
    // } else {
    //     //pulls membership data to verify that user is a member of club
    //     $sql2 = "SELECT * FROM members WHERE student_id = ? LIMIT 1";
    //     $stmt2 = mysqli_stmt_init($connect);
    //     if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    //         header("Location: ../signup.php?error=sql");
    //         exit();
    //     } else {
    //         mysqli_stmt_bind_param($stmt2, "s", $studentID);
    //         mysqli_stmt_execute($stmt2);
    //         $result = mysqli_stmt_get_result($stmt2);
    //         if ($r = mysqli_fetch_assoc($result)) {
    //             $email = $r['email'];
    //             $name = $r['name'];
    //             $admin = $r['admin'];
    //             $hash = password_hash($password, PASSWORD_DEFAULT);
    //             $verified = 1;
    //             $vkey = password_hash(time() . $username, PASSWORD_DEFAULT);

    //             mysqli_stmt_bind_param($stmt, "sssisis", $studentID, $email, $name, $admin, $hash, $verified, $vkey);
    //             mysqli_stmt_execute($stmt);
    //             header("Location: ../login.php?success=true");
    //         } else {
    //             header("Location: ../signup.php?error=nomember");
    //             exit();
    //         }
    //     }
    // }
    mysqli_stmt_close($stmt);
    mysqli_close($connect);
} else {
    header("Location: ./forgot.php");
}
