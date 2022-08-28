<?php
//Prevent Direct URL Access to PHP Form Files
// if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
//     header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
//     die ("<h2>Access Denied!</h2> This file is protected and not available to public.");
//     }
    
session_start(); 
if(!isset($_SESSION['user']))
{
    header("Location:../login"); 
}
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