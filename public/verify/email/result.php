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

// Validation: Checking entered captcha code with the generated captcha code
if(strcasecmp($_SESSION['captcha'], $_POST["captcha"]) != 0){
    // Note: the captcha code is compared case insensitively.
    // if you want case sensitive match, check above with strcmp()
    die("<p>Entered captcha code does not match! Kindly try again.</p>");
}

$_POST = array(); // clear all post data

$email = $_SESSION['user'];

include "../../../config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

$otp = strval(rand(100000, 999999));
$insert_otp_sql = $conn->prepare("INSERT INTO OTP (Email, otp, CreatedAt) VALUES (?, ?, now())");
$insert_otp_sql->bind_param("ss", $email, $otp);
$insert_otp_sql->execute();

mailTo($email,
    "OTP of register on HKID Appointment system",
    "OTP: $otp <br>please verified in 30 mins",
    "<p>A verift email has been sent to your email address</p>",
    "sent fail"
);

echo "<a href='../'>Go back to verify page</a>";

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