<?php
session_start(); 
if(!isset($_SESSION['user']))
{
    header("Location:../login"); 
    die();
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    header("Location:../logout");
    die();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stampÍ
?>
<html>
<head>
<title>Verify your email</title>
</head>
<body>
<form action="result.php" method="post">
<h1>Verify your email</h1>
OTP: <input name="otp" type="text" size="30" maxlength="16"><br><br>
<input name="submit" type="submit" value="submit">

<p>click here to receive a <a href="./email">verfy email</a></p>
</form>
</body>
<footer>
<a href="../logout">logout</a>
</footer>
</html>