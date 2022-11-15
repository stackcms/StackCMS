<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

// passing true in constructor enables exceptions in PHPMailer
$mail = new PHPMailer(true);

try
{
	// Server settings
	$mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = 587;

	// Set the TCG's GMAIL account here
	$mail->Username = $tcgemail;
	$mail->Password = 'YOUR_GMAIL_PASSWORD'; // Change to your actual TCG's gmail password

	// Sender and recipient settings
	$mail->setFrom($tcgemail, $tcgname);
	$mail->addAddress($email, $name);
	$mail->addReplyTo($tcgemail, $tcgname);

	// Setting the email content
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $message;
	$mail->AltBody = $message;

	$mail->send();
}

catch (Exception $e)
{
	echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
}
?>
