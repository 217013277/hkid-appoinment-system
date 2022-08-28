<?php
session_start(); 
if(isset($_SESSION['user']))
{
    header("Location:../appointment_form.php"); 
}
?>
<html>
<head>
<title>Login Form</title>
</head>
<body>
<form action="result.php" method="post">
<h1>Login</h1>
Email: <input name="email" type="text" size="30" maxlength="100"><br><br>
Password: <input name="password" type="text" size="30" maxlength="100"><br><br>
<input name="submit" type="submit" value="submit">
</form>
</body>
<footer>
<a href="../register">Go to register page</a>
</footer>
</html>