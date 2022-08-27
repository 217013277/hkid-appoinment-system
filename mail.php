<?php

mailTo("217013277@stu.vtc.edu.hk", "Test email subject", "Test Content", "sent success", "sent fail", "finish");

function mailTo($email, $subject, $content, $success, $fail, $nav){
	#
	# Based on PHPMailer v6.0.5
	# ref.: https://github.com/PHPMailer/PHPMailer
	#

	require "PHPMailer/src/PHPMailer.php";
	require "PHPMailer/src/Exception.php";
	require "PHPMailer/src/SMTP.php";
	
	$mail = new PHPMailer\PHPMailer\PHPMailer();
	
	try {
		//Server settings
		# $mail->SMTPDebug = 2; // Enable verbose debug output
		$mail->isSMTP(); // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com'; // Specify SMTP server
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->Username = 'test.hkid.appointment@gmail.com'; // SMTP username
		$mail->Password = 'fbjxyhktvjsubrig'; // SMTP password
		$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587; // TCP port to connect to

		$mail->SMTPOptions = array(
		'ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
		)
		);

		//Recipients
		$mail->setFrom('test.hkid.appointment@gmail.com', 'Test Sender');
		$mail->addAddress($email, 'Test receipt'); // Add a recipient
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
		
		echo $nav;
	} catch (Exception $e) {
		echo $fail;
		echo 'Mailer Error: ', $mail->ErrorInfo;
	}
}