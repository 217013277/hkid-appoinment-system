<?php
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