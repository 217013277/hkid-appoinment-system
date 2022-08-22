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
mysqli_query($conn, "INSERT INTO `ip` (`address` ,`timestamp`)VALUES ('$ip',CURRENT_TIMESTAMP)");
$result = mysqli_query($conn, "SELECT COUNT(*) FROM `ip` WHERE `address` LIKE '$ip' AND `timestamp` > (now() - interval 10 minute)");
$count = mysqli_fetch_array($result, MYSQLI_NUM);

if($count[0] > 3){
  echo "Your are allowed 3 attempts in 10 minutes";
}
?>
</body>
</html>
