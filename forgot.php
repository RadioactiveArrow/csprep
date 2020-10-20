<?php
// mail($to_email_address,$subject,$message,[$headers],[$parameters]);
?>

<?php
require 'header.php';
if(isset($_SESSION['testID'])) {
    unset($_SESSION['testID']);
    unset($_SESSION['qNum']);
    unset($_SESSION['qEx']);
    unset($_SESSION['correctAns']);
}
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSPrep | Login</title>
    <link rel="stylesheet" href="styles/log.css">
</head>

<body class="log">
    <div class="sign-container">
        <div class="text">
            <h1 class="sign-header">Forgot Password</h1>
            <h3 class="other">We'll send an email to the account we have on file</h3>
        </div>
        <form id="log" action="./includes/forgot.inc.php" method="post">
            <input type="id" name="id" class="activator" autocomplete="username email" required />
            <p>Student ID</p>
            <button class="button register" name="forgot-submit" href="#">Send!</button>
            <p class="message error">
                <?php
                if (isset($_GET['error'])) {
                    echo "&#9888&nbsp;Error: ";
                    if ($_GET['error'] == 'emptyfields')
                        echo "One or more fields are empty.";
                    else if ($_GET['error'] == 'nouser')
                        echo "This user doesn't exist.";
                    else if ($_GET['error'] == 'wrong')
                        echo "Wrong username or password.";
                    else if ($_GET['error'] == 'sql')
                        echo "SQL Error";
                }
                ?>
            </p>
            <p class="message success">
                <?php
                if (isset($_GET['success'])) {
                    echo "Success! An email has been sent to the address you signed up with";
                }
                ?>
            </p>
        </form>
    </div>
    <a href="index.php"><img class="logo-large" src="media/header/logo.png" /></a>
</body>