<?php
if(isset($_POST['reg-submit'])) {
    require 'dbh.php';
    $name = $_POST['e'];
    $email = $_POST['e'];
    $username = $_POST['u'];
    $password = $_POST['p'];
    $password2 = $_POST['p2'];

    //checks if any fields are empty but html should do this already so idk sue me
    if(empty($name) || empty($email) || empty($username) || empty($password) || empty($password2)) {
        header("Location: ../signup.php?error=emptyfields");
        exit();
    }

    if($password != $password2) {
        header("Location: ../signup.php?error=passfail");
        exit();
    }

    $sql= "SELECT userUID FROM users WHERE userUID=? OR userMail=?";
    $stmt= mysqli_stmt_init($connect);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=sql");
        exit();
    } 
    else {
        mysqli_stmt_bind_param($stmt,"ss",$username,$email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $existcount = mysqli_stmt_num_rows($stmt);
        if($existcount!=0) {
            header("Location: ../signup.php?error=exists");
            exit();
        }
    }

    //prepared sql for login
    $sql = "INSERT INTO users (userUID, userMail, userName, admin, userPass, verified, vkey) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt= mysqli_stmt_init($connect);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=sql");
        exit();
    } else {
        $hash= password_hash($password, PASSWORD_DEFAULT);
        $vkey= password_hash(time().$username, PASSWORD_DEFAULT);
	$admin = 0;
	$verified = 1;
        mysqli_stmt_bind_param($stmt,"sssisis",$username,$email,$name,$admin,$hash,$verified,$vkey);
        mysqli_stmt_execute($stmt);
        header("Location: ../login.php?success=true");
    }
    mysqli_stmt_close($stmt);
    mysqli_close($connect);
}
else {
    header("Location: ./signup.php");
}
