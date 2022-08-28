<?php
session_start(); 
if(!isset($_SESSION['user']))
{
    header("Location:../login"); 
}
?>

<html>
<head>
<title>Verify Form</title>
</head>
<body>
<h1>Verify Form</h1>
</body>
</html>