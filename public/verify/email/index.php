<?php

session_start(); 
if(!isset($_SESSION['user']))
{
    header("Location:../login"); 
}

include "../../config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 



function mailTo($receiver, $subject, $content, $success, $fail){

include "../../config.php";

#
# Based on PHPMailer v6.0.5
# ref.: https://github.com/PHPMailer/PHPMailer
#

require "../PHPMailer/src/PHPMailer.php";
require "../PHPMailer/src/Exception.php";
require "../PHPMailer/src/SMTP.php";

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