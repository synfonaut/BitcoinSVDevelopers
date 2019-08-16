<?php

require_once(__DIR__ . "/../../lib/sendgrid/sendgrid-php.php");
require_once(__DIR__ . "/../config.php");

$name = $_POST["name"];
$userEmail = $_POST["email"];
$userMessage = trim($_POST["message"]);
$formattedMessage = nl2br($userMessage);

$isOfficeHours = $_POST["officehours"] == 1;
$isConsulting = $_POST["consulting"] == 1;

if (!isset($people[$name])) {
    die("invalid person");
}

$person = $people[$name];

if (empty($person["email"])) {
    die("invalid email for person");
}

if ($isOfficeHours) {
    $title = "Office Hours";
} else if ($isConsulting) {
    $title = "Consulting";
} else {
    die("invalid form choice, need to choose office hours or consulting");
}

$plainText = <<<TEXT
There was a new {$title} request from https://bitcoinsvdevelopers.com

Email: {$userEmail}
Message: {$userMessage}
TEXT;

$htmlText = <<<TEXT
<p>There was a new {$title} request from https://bitcoinsvdevelopers.com</p>

<p><strong>Email:</strong> {$userEmail}</p>
<p><strong>Message:</strong> {$formattedMessage}</p>
TEXT;

$email = new \SendGrid\Mail\Mail();
$email->setFrom("officehours@bitcoinsvdevelopers.com", "Office Hours");
$email->setSubject("New " . $title . " Request");
$email->setReplyTo($userEmail);
$email->addTo($person["email"], $person["name"]);
$email->addContent("text/plain", $plainText);
$email->addContent("text/html", $htmlText);
$sendgrid = new \SendGrid($SENDGRID_API_TOKEN);
try {
    $response = $sendgrid->send($email);
    if ($response->statusCode() == 200 || $response->statusCode() == 202) {
        header("Location: /mailer/success/");
        exit(0);
    } else {
        echo("Error while sending request. Please try again or contact synfonaut@protonmail.com");
    }
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
