<html>
<head>
<title>ip log</title>
</head>
<body>
<?php

// Create connection
$conn = new mysqli("localhost", "root", "mysql", "test");

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

$ip = $_SERVER["REMOTE_ADDR"];
$action = "logged";
mysqli_query($conn, "INSERT INTO `ip` (`address`,`action`,`timestamp`)VALUES ('$ip','$action',CURRENT_TIMESTAMP)");
$result = mysqli_query($conn, "SELECT COUNT(*) FROM `ip` WHERE `address` LIKE '$ip' AND `timestamp` > (now() - interval 5 minute)");
$count = mysqli_fetch_array($result, MYSQLI_NUM);

if($count[0] > 10){
  echo "Your are allowed 10 attempts in 5 minutes";
}
?>
</body>
</html>
