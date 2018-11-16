<?php
function Send_Mail($to,$subject,$body)
{
require 'class.phpmailer.php';
$from = "info@greentoday.in";
$mail = new PHPMailer();
$mail->IsSMTP(true); // SMTP
$mail->SMTPAuth   = true;  // SMTP authentication
$mail->Mailer = "smtp";
$mail->Host       = "tls://smtp.gmail.com"; // Amazon SES server, note "tls://" protocol
$mail->Port       = 465;                    // set the SMTP port
$mail->Username   = "username";  // SES SMTP  username
$mail->Password   = "password";  // SES SMTP password
$mail->SetFrom($from, 'Green Today');
$mail->AddReplyTo($from,'Green Today');
$mail->Subject = $subject;
$mail->MsgHTML($body);
$address = $to;
$mail->AddAddress($address, $to);

if(!$mail->Send())
echo "Mail not sent";
else
echo "success";
}

$to = "infotech.rameshm@thechennaisilks.com";
$subject = "Test Mail Subject";
$body = "Hi <br> This is Test Mail<br/>M RAMESH"; // HTML  tags
Send_Mail($to,$subject,$body);


?>