<?php
//Prevent Direct URL Access to PHP Form Files
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
die ("<h2>Access Denied!</h2> This file is protected and not available to public.");
}
?>

<html>
<head>
<title>Login Result</title>
</head>
<body>
<?php

include "../config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
} 

/* Get user input which name is "id" and "pwd" 
(assume the id and pwd has correct format) */
$email = $_POST["email"]; 
$password = $_POST["password"];
$date = $_POST["date"];
$time = $_POST["time"];
$location = $_POST["location"];

if (!$email && !$password) {
    die("<h2>Please input email and password</h2>");
}

// save login attempt to prevent brute force attack
$ip = $_SERVER["REMOTE_ADDR"];
$action = "login";
$insert_log_sql = $conn->prepare("INSERT INTO `AuthLog` (`address`,`email`,`action`,`timestamp`)VALUES (?,?,?,now())");
$insert_log_sql->bind_param("sss", $ip, $email, $action);
$insert_log_sql->execute();

// check login attempt
$search_log_sql = $conn->prepare("SELECT COUNT(*) FROM `AuthLog` WHERE `email`=? AND `action`= '$action' AND `timestamp` > (now() - interval 5 minute)");
$search_log_sql->bind_param("s", $email);
$search_log_sql->execute();
$search_log_sql->store_result();
$search_log_sql->bind_result($countLog);
$search_log_sql->fetch();

if($countLog > 10)
{
    die("<h2>Too many failed login attempts, please try again later.</h2>");
}

// Write prepare statements to retrieve the columns salt and hash with the corresponding user  
$search_sql = $conn->prepare("SELECT `Id`, `salt`, `hash`, `verified` FROM Users WHERE email=?");
$search_sql->bind_param("s", $email);
$search_sql->execute();
$search_sql->store_result();

// If login name can be found in table "userhash"
if($search_sql->num_rows < 1) {       
    die("<h2>Email not exist, authentication failed</h2>");
}

// Write a statement to generate a hash by using SHA512 algorithm and store it into variable $pwdhash (salt + password)
$search_sql->bind_result($userid_db, $salt_db, $hash_db, $verified_db);
$search_sql-> fetch();

$passwordHash = hash("sha512", $salt_db . $password);

$test = "abc123**";
$passwordHashTest = hash("sha512", $salt_db . $test);

/* Write a statement to compare the string content of $pwdhash and the hash value retrieved from database is equal (hint: use build in function strcmp) */
if(strcmp($hash_db, $passwordHash) != 0) {
    die("<h2>The password is wrong, authentication failed</h2>");
}

// check if user is verified
if (!$verified_db) {
    die("<p>Please verified your email first</p>
    <a href='verify_form.php'>Go to verify page</a>");
}

$datetime = $date." ".$time;

$insert_appointment_sql = $conn->prepare("INSERT INTO `Appointments` (`UserId`,`Location`,`DateTime`,`CreatedAt`)VALUES (?,?,?,now())");
$insert_appointment_sql->bind_param("iss", $userid_db, $location, $datetime);
$insert_appointment_sql->execute();

echo "<h2>Appointment success!</h2>";
echo "<p>$date $time - $location</p>";

// Close connection
mysqli_close($conn);

?>
</body>
</html>

