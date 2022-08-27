<?php
//Prevent Direct URL Access to PHP Form Files
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
die ("<h2>Access Denied!</h2> This file is protected and not available to public.");
}
?>
<html>
<head>
<title>Register Result</title>
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

// Get user input from the form submitted before
$otp = $_POST["otp"];

// Set a flag to assume all user input follow the format
$allDataCorrect = true;

// Check all data whether follow the format
// if(!preg_match("/[a-zA-Z0-9_]{16}/", $otp))
// {
//     $allDataCorrect = false;
//     $errMsg = $errMsg . "OTP format is invalid<br><br>"; 
// }

if(!$allDataCorrect)
{
    die("<h3> $errMsg </h3>");
}

// Search user table to see whether user name is exist
$search_sql = $conn->prepare("SELECT Email FROM OTPs WHERE otp=?");
$search_sql->bind_param("s", $otp);
$search_sql->execute();
$search_sql->store_result();
$search_sql->bind_result($otp_email);
$search_sql->fetch();

if($search_sql->num_rows < 1) 
{
    die("<h2>Your OTP is not correct</h2>");
}

echo "<p>verified $otp_email</p>";

$update_user_sql = $conn->prepare("UPDATE Users SET `Verified` = 1 WHERE `Email` = '$otp_email'");
$update_user_sql->execute();

?>

<a href="appointment_form.php">Go to appointment page</a>
</body>
</html>