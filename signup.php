<?php
require 'header.php';
if(isset($_SESSION['testID'])) {
    unset($_SESSION['testID']);
    unset($_SESSION['qNum']);
    unset($_SESSION['qEx']);
}
?>


<head>
    <title>CSPrep | Signup</title>

    <link rel="stylesheet" href="type/inter/inter.css">
    <link rel="stylesheet" href="type/league-mono/league.css">
    <link rel="stylesheet" href="styles/log.css">
</head>

<body class="log">
    <div class="sign-container">
        <div class="text">
            <h1 class="sign-header">Sign Up</h1>
            <h3 class="other">Have an account? <a class="other-link" href="login.php">Log in now!</a></h3>
        </div>
        <form id="log" action="./includes/signup.inc.php" method="post">
            <input type="id" name="id" class="activator" autocomplete="id" required />
            <p>Student ID</p>
            <input type="password" name="p" autocomplete="off" class="activator" autocomplete="current-password" required />
            <p>Password</p>
            <input type="password" name="p2" autocomplete="off" class="activator" autocomplete="current-password" required />
            <p>Repeat Password</p>
            <button class="button register" name="reg-submit" href="#">Sign up</button>
            <p class="message error">
                <?php
                if(isset($_GET['error'])) {
                    echo "&#9888&nbsp;Error: ";
                    if($_GET['error'] == 'emptyfields')
                        echo "One or more fields are empty.";
                    else if($_GET['error'] == 'exists')
                        echo "This user already exists.";
                    else if($_GET['error'] == 'sql')
                        echo "SQL Error";
                    else if($_GET['error'] == 'nomember') 
                        echo "You are not a member of the club! <a href='https://forms.gle/fsf77kAQkbEH2KXXA'>Join here.</a>";
                    else if($_GET['error'] == 'passfail')
                        echo "Passwords don't match";
                }
                ?>    
            </p>
        </form>
    </div>
    <a href="index.php"><img class="logo-large" src="media/header/logo.png" /></a>
</body>

