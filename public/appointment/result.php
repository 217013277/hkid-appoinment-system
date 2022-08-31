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

$_POST = array(); // clear all post data

$datetime = $date." ".$time;

$search_sql = $conn->prepare("SELECT `Location`,`DateTime` FROM Appointments WHERE user=?");
$search_sql->bind_param("s", $email);
$search_sql->execute();
$search_sql->store_result();

if ($search_sql->num_rows > 0) {
    $search_sql->bind_result($location_db,$datetime_db);
    $search_sql-> fetch();
    $location_db = htmlspecialchars($location_db);
    $datetime_db = htmlspecialchars($datetime_db);
    echo "<p>Fail to made appointment, you already have an appointment, below is your appointment detail</p><p>$location_db - $datetime_db</p>";
    echo "<a href='../userdetail'>Your user detail</a>
    <a href='../logout'>logout</a>";
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
<a href='../userdetail'>Your user detail</a>
<a href="../logout">logoutn</a>
</body>
</html>

