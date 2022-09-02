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

// Close connection
mysqli_close($conn);

echo "<h2>Appointment success!</h2>";
echo "<p>$location - $date $time</p>";

mailTo($email,
    "HKID Appointment detail",
    "<p>$location - $date $time</p>",
    "<p>Appointment detail has been sent to your email address</p>",
    "sent fail"
);

?>
<a href='../userdetail'>Your user detail</a>
<a href="../logout">logout</a>
</body>
</html>

<?php

function mailTo($receiver, $subject, $content, $success, $fail){

    include "../../../config.php";
    
    #
    # Based on PHPMailer v6.0.5
    # ref.: https://github.com/PHPMailer/PHPMailer
    #
    
    require "../../../PHPMailer/src/PHPMailer.php";
    require "../../../PHPMailer/src/Exception.php";
    require "../../../PHPMailer/src/SMTP.php";
    
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    try {
        //Server settings
        # $mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = $emailhost; // Specify SMTP server
        $mail->SMTPAuth = $emailsmtpauth; // Enable SMTP authentication
        $mail->Username = $emailusername; // SMTP username
        $mail->Password = $emailpassword; // SMTP password
        $mail->SMTPSecure = $emailsmtpsecure; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $emailport; // TCP port to connect to
    
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        );
    
        //Recipients
        $mail->setFrom($emailusername, 'Test Sender');
        $mail->addAddress($receiver, 'Test receipt'); // Add a recipient
        # $mail->addAddress('ellen@example.com'); // Name is optional
        # $mail->addReplyTo('info@example.com', 'Information');
        # $mail->addCC('cc@example.com');
        # $mail->addBCC('bcc@example.com');
    
        //Attachments
        # $mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
        # $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name
    
        //Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'This is an appointment letter';
        $mail->Body = $content;
    
        $mail->send();
        echo $success;
    } catch (Exception $e) {
        echo $fail;
        echo 'Mailer Error: ', $mail->ErrorInfo;
        }
    }
?>
