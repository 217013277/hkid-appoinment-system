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

include "../../config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

$email = $_SESSION['user'];

$user_sql = $conn->prepare("SELECT NameEnglish, NameChinese, Gender, Address, DateOfBirth, PlaceOfBirth, Occupation, HKID FROM Users WHERE Email=?");
$user_sql->bind_param("s", $email);

$user_sql->execute();
$user_sql->store_result();
$user_sql->bind_result($NameEnglish,$NameChinese, $Gender, $Address, $DateOfBirth, $PlaceOfBirth, $Occupation, $HKID);
$user_sql-> fetch();

$iv = substr($HKID, 0, 32);
$HKID = substr($HKID, 32);
$HKID = openssl_decrypt($HKID, $cipher, $key, $option, $iv);

$appointment_sql = $conn->prepare("SELECT `Location`,`DateTime` FROM Appointments WHERE user=?");
$appointment_sql->bind_param("s", $email);
$appointment_sql->execute();
$appointment_sql->store_result();

if ($appointment_sql->num_rows > 0) {
    $appointment_sql->bind_result($location_db,$datetime_db);
    $appointment_sql-> fetch();
}
?>
<html>
<head>
<title>Your detail</title>
</head>
<body>

<?php 
echo "<h2>User information</h2>
<p>English name: $NameEnglish<br>
Chinese name: $NameChinese<br>
Gender: $Gender<br>
Addesss: $Address<br>
Date of Birth: $DateOfBirth<br>
Place of Birth: $PlaceOfBirth<br>
Occupation: $Occupation<br>
HKID: $HKID</p>
<h2>appointment detail</h2>
<p>Location: $location_db<br>
Date and Time: $datetime_db</p>";
?>

</body>
<footer>
</footer>
</html>