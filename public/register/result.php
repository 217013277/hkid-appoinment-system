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
session_start();
$captchaStatus = false;
$status = '';

// Validation: Checking entered captcha code with the generated captcha code
if(strcasecmp($_SESSION['captcha'], $_POST["captcha"]) != 0){
    // Note: the captcha code is compared case insensitively.
    // if you want case sensitive match, check above with strcmp()
    $captchaStatus = false;
    $status = "<p style='color:#FFFFFF; font-size:20px'>
    <span style='background-color:#FF0000;'>Entered captcha code does not match! 
    Kindly try again.</span></p>";

}else{
    $captchaStatus = true;
    $status = "<p style='color:#FFFFFF; font-size:20px'>
    <span style='background-color:#46ab4a;'>Your captcha code is match.</span>
    </p>";	
    }

    $_SESSION['captcha'] = rand();

if (!$captchaStatus)
{
	die($status);
}

include "../../config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

// Get user input from the form submitted before
$email = $_POST["email"];
$password = $_POST["password"];
$engName = $_POST["engName"];
$chiName = $_POST["chiName"];
$gender = $_POST["gender"];
$dateOfBirth = $_POST["dateOfBirth"];
$address = $_POST["address"];
$placeOfBirth = $_POST["placeOfBirth"];
$occupation = $_POST["occupation"];
$hkid = $_POST["hkid"];

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

if(!preg_match("/[A-Z][a-zA-Z]{2,}/", $engName))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Name should be composed with English characters start with capital letter<br><br>"; 
}

if(!preg_match("/\p{Han}{2,}+/u", $chiName))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Name should be composed with at least 2 Chinese characters <br><br>"; 
}

if(!preg_match("/^(?:Male|Female)$/", $gender))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Name should be composed with at least 2 Chinese characters <br><br>"; 
}

if(!preg_match("/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/", $dateOfBirth))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Please input valid date of birth<br><br>"; 

}

if(!preg_match("/^[A-Z]{1,2}[0-9]{6}\([0-9A]\)$/", $hkid))
{
    $allDataCorrect = false;
    $errMsg = $errMsg . "Please input valid hkid e.g. A123456(7)<br><br>"; 
}
  
if(!$allDataCorrect)
{
    die("<h3> $errMsg </h3>");
}

// Search user table to see whether user name is exist
$search_sql = $conn->prepare("SELECT * FROM Users WHERE Email=? OR HKID LIKE ?");
$search_sql->bind_param("ss", $email, $hkid);
$search_sql->execute();
$search_sql->store_result();

// If login name can be found in table "user", forbid user register process
if($search_sql->num_rows > 0) 
{
    die("<h2>The email or hkid are registered by others. Please use other user name</h2>");
}

// $search_sql->bind_result($hkid_db);
// $search_sql-> fetch();

$salt = generateSalt(16);

$hash = hash("sha512", $salt . $password);

$dateOfBirth_dt=date('Y-m-d H:i:s',strtotime($dateOfBirth)); 

$insert_sql = $conn->prepare("INSERT INTO Users (Email, Salt, Hash, NameEnglish, NameChinese, Gender, Address, DateOfBirth, PlaceOfBirth, Occupation, HKID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$insert_sql->bind_param("sssssssssss", $email, $salt, $hash, $engName, $chiName, $gender, $address, $dateOfBirth_dt, $placeOfBirth, $occupation, $hkid);
$insert_sql->execute();

echo "<h2>Registration Success!!</h2>";

$otp = strval(rand(10000, 99999));
$insert_otp_sql = $conn->prepare("INSERT INTO OTP (Email, otp, CreatedAt) VALUES (?, ?, now())");
$insert_otp_sql->bind_param("ss", $email, $otp);
$insert_otp_sql->execute();

mailTo($email,
"OTP of register on HKID Appointment system",
"OTP: $otp <br>please verified in 30 mins",
"<p>A verift email has been sent to your email address</p>",
"sent fail"
);

// Close connection
mysqli_close($conn);

// This function generate a random string with particular length
function generateSalt($length)
{
    $rand_str = "";
    $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    
    for ($i=0;$i <= $length; $i++) {
        $rand_str = $rand_str . $chars[rand(0, strlen($chars)-1)];
    }
    
    return $rand_str;
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

<a href="../login">Go to login page</a>

</body>
</html>
