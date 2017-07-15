<?php


// I have created a gmail account to use

// Details:
// brighthorizonsreport@gmail.com
// notpassword

// Just change the password when deployed
// Or use another address and/ or server by chaning the config below

function respond($success, $data, $error = null) {
    header('Content-Type: application/json');

    $payload = array(
        'success' => $success,
        'data' => $data,
        'error' => $error
    );

    echo json_encode($payload);

    exit();
}

require 'mailer/PHPMailerAutoload.php';

$to = isset($_REQUEST['to']) ? $_REQUEST['to'] : false; // Array of addresses
$cc = isset($_REQUEST['cc']) ? $_REQUEST['cc'] : false; // Array of addresses
$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : false;
$body = isset($_REQUEST['body']) ? $_REQUEST['body'] : false; // HTML content

if ($to === false || $subject === false || $body === false) {
    respond(false, null, 'Missing Parameters');
}

if (!is_array($to)) {
    respond(false, null, 'Parameter \'to\' must be of type array');
}

if (!is_array($cc)) {
    if (gettype($to) !== 'array') {
        respond(false, null, 'Parameter \'cc\' must be of type array');
    }
}

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;
$mail->IsHTML(true);
$mail->Username = "brighthorizonsreport@gmail.com";
$mail->Password = "notpassword";
$mail->SetFrom("brighthorizonsreport@gmail.com");

$mail->Subject = $subject;
$mail->Body = $body;

if ($cc !== false) {
    foreach ($cc as $key => $address) {
        $mail->AddCC($address);
    }
}

foreach ($to as $key => $address) {
    $mail->AddAddress($address);
}

 if(!$mail->Send()) {
    respond(false, null, $mail->ErrorInfo);
 } else {
    $data = array(
        'to' => $to,
        'subject' => $subject
    );

    respond(true, $data);
 }

?>