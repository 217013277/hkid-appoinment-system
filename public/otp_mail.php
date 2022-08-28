<html>
<head>
<title>Send OTP email</title>
</head>
<body>
<?php

include "../config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

$otp = generateSalt(16);
$insert_otp_sql = $conn->prepare("INSERT INTO OTPs (Email, otp, CreatedAt) VALUES (?, ?, now())");
$insert_otp_sql->bind_param("ss", $email, $otp);
$insert_otp_sql->execute();

mailTo($email,
"OTP of register on HKID Appointment system",
"OTP: $otp <br>please verified in 30 mins",
"<p>A verift email has been sent to your email address</p>",
"sent fail"
);

mysqli_close($conn);