<?php
session_start(); 
if(isset($_SESSION['user']))
{
    header("Location:../appointment"); 
}
?>
<html>
<head>
<title>Login Form</title>
</head>
<body>
<form action="result.php" method="post">
<h1>Login</h1>
Email: <input name="email" type="email" size="30" maxlength="100" required><br><br>
Password: <input name="password" type="password" size="30" maxlength="100" required><br><br>
<input name="submit" type="submit" value="submit">
</form>
</body>
<footer>
<a href="../register">Go to register page</a>
</footer>
</html>