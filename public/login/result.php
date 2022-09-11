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

session_start(); 
if(isset($_SESSION['user']))
{
    header("Location:../appointment"); 
}

/* Get user input which name is "id" and "pwd" 
(assume the id and pwd has correct format) */
$email = $_POST["email"]; 
$password = $_POST["password"];

$_POST = array(); // clear all post data

if (!$email && !$password) {
    die("<h2>Please input email and password</h2>");
}

 // Set a flag to assume all user input follow the format
$allDataCorrect = true;

// Check all data whether follow the format
if(!preg_match("/\w+@[a-zA-Z0-9_]+?\.[a-zA-Z]{2,6}/", $email))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Email is invalid<br><br>"; 
}

if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&-_])[A-Za-z\d@$!%*#?&-_]{8,}$/", $password))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Password should be composed with at least 8 alphanumeric characters, 1 uppercase letter, 1 lowercase letter and 1 @$!%*#?&-_ symbol <br><br>"; 
}

if(!$allDataCorrect)
{
    die("<h3> $errMsg </h3>");
}

include "../../config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
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

$_SESSION['user'] = $email;
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

// check if user is verified
if (!$verified_db) {
    die("<p>Please verified your email first 
    <a href='../verify'>Go to verify page</a></p>
    <a href='../userdetail'>see your user detail</a>");
}

echo "<h2>login success!</h2>";

// Close connection
mysqli_close($conn);

?>

<a href="../appointment">go to appointment page</a>
</body>
</html>

