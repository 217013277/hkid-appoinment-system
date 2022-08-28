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
$email = $_POST["email"];
$otp = $_POST["otp"];

// Set a flag to assume all user input follow the format
$allDataCorrect = true;

// Check all data whether follow the format
if(!preg_match("/\w+@[a-zA-Z0-9_]+?\.[a-zA-Z]{2,6}/", $email))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Email is invalid<br><br>"; 
}

if(!$allDataCorrect) {
    die("<h3> $errMsg </h3>");
}

// Search user table to see whether user name is exist
$search_sql = $conn->prepare("SELECT CreatedAt FROM OTP WHERE email=? AND otp=? AND Used = 0 ORDER BY CreatedAt DESC LIMIT 1");
$search_sql->bind_param("ss", $email, $otp);
$search_sql->execute();
$search_sql->store_result();
$search_sql->bind_result($CreatedAt_db);
$search_sql->fetch();

if($search_sql->num_rows < 1) 
{
    die("<h2>Your OTP is not correct.</h2>");
}

date_default_timezone_set('Asia/Hong_Kong');
$expiryTime = date('Y-m-d H:i:s', strtotime('-30 minutes'));

if($CreatedAt_db < $expiryTime) {
    die("<h2>Your OTP is expired.</h2>");
}

echo "<p>$email is now verified.</p>";

// $update_user_sql = $conn->prepare("UPDATE Users SET `Verified` = 1 WHERE `Email` = '$email'");
// $update_user_sql = $conn->prepare("UPDATE OTP SET `Used` = 1 WHERE `Email` = '$email'");
$update_user_sql->execute();

// Close connection
mysqli_close($conn);

echo "<p>You may now make an appointment.</p>";

?>

<a href="appointment_form.php">Go to appointment page</a>
</body>
</html>