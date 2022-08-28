<?php
//Prevent Direct URL Access to PHP Form Files
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
die ("<h2>Access Denied!</h2> This file is protected and not available to public.");
}

session_start(); 
if(!isset($_SESSION['user']))
{
    header("Location:../login"); 
}

?>

<html>
<head>
<title>Login Result</title>
</head>
<body>
<?php

include "../../config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
} 

$email = $_SESSION['user'];

$location = $_POST["location"];
$date = $_POST["date"];
$time = $_POST["time"];

$datetime = $date." ".$time;

// // Write prepare statements to retrieve the columns salt and hash with the corresponding user  
// $search_sql = $conn->prepare("SELECT `Id` FROM Users WHERE email=?");
// $search_sql->bind_param("s", $email);
// $search_sql->execute();
// $search_sql->store_result();

// // If login name can be found in table "userhash"
// if($search_sql->num_rows < 1) {       
//     die("<h2>User is not exist, authentication failed</h2>");
// }

// // Write a statement to generate a hash by using SHA512 algorithm and store it into variable $pwdhash (salt + password)
// $search_sql->bind_result($userid_db);
// $search_sql-> fetch();
$search_sql = $conn->prepare("SELECT `Location`,`DateTime` FROM Appointments WHERE user=?");
$search_sql->bind_param("s", $email);
$search_sql->execute();
$search_sql->store_result();

if ($search_sql > 0) {
    $search_sql->bind_result($location_db,$datetime_db);
    $search_sql-> fetch();
    echo "<p>you have already made an appointment, below is your appointment detail</p><p>$location_db - $datetime_db</p>";
    echo "<a href='../logout'>logout</a>";
    die();
}

$insert_appointment_sql = $conn->prepare("INSERT INTO `Appointments` (`User`,`Location`,`DateTime`,`CreatedAt`) VALUES (?,?,?,now())");
$insert_appointment_sql->bind_param("sss", $email, $location, $datetime);
$insert_appointment_sql->execute();

echo "<h2>Appointment success!</h2>";
echo "<p>$location - $date $time</p>";

// Close connection
mysqli_close($conn);

?>

<a href="../logout">logout</a>
</body>
</html>

