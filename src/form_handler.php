<?php // src/form_handler.php

// Importer PHPMailer klasser inn i det globale navnerommet
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

$formResult = [
    'message' => '',
    'success' => false,
    'data' => [], 
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Hent og rens brukerinput
    $firstname = htmlspecialchars(trim($_POST['firstname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars(trim($_POST['lastname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $fullname = trim($firstname . ' ' . $lastname); // Kombiner fornavn og etternavn

    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $company = htmlspecialchars(trim($_POST['company'] ?? ''), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $package_interest = htmlspecialchars(trim($_POST['package_interest'] ?? ''), ENT_QUOTES, 'UTF-8');
    $message_content = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8'); // Endret variabelnavn for klarhet
    $privacy_policy_agreed = isset($_POST['privacy']);

    $formResult['data'] = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'company' => $company,
        'phone' => $phone,
        'package_interest' => $package_interest,
        'message' => $message_content, // Bruker 'message' her for old_value()
        'privacy' => $privacy_policy_agreed,
    ];

    $errors = [];
    if (empty($firstname)) { // Endret fra 'name' til 'firstname'
        $errors[] = "Fornavn er påkrevd.";
    }
    if (empty($email)) {
        $errors[] = "E-post er påkrevd.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Vennligst oppgi en gyldig e-postadresse.";
        $formResult['data']['email'] = $_POST['email'] ?? '';
    }
    if (empty($phone)) {
        $errors[] = "Telefonnummer er påkrevd.";
    }
    if (empty($message_content)) {
        $errors[] = "Melding er påkrevd.";
    }
    if (!$privacy_policy_agreed) {
        $errors[] = "Du må godta personvernerklæringen.";
    }

    $smtpHost = getenv('MAILGUN_SMTP_HOST') ?: 'smtp.eu.mailgun.org';
    $smtpPort = getenv('MAILGUN_SMTP_PORT') ?: 587;
    $smtpUsername = getenv('MAILGUN_SMTP_USERNAME');
    $smtpPassword = getenv('MAILGUN_SMTP_PASSWORD');
    $mailFromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'noreply@akari.no';
    $mailFromName = getenv('MAIL_FROM_NAME') ?: 'Akari Nettside';
    $mailRecipientAddress = getenv('MAIL_RECIPIENT_ADDRESS');

    if (empty($smtpUsername) || empty($smtpPassword) || empty($mailRecipientAddress)) {
         $errors[] = "Serverkonfigurasjonsfeil (manglende e-postinnstillinger).";
         error_log("Mailgun SMTP credentials or recipient address missing in environment variables for akari.no");
    }

    if (empty($errors)) {
        $mail = new PHPMailer(true);

        try {
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUsername;
            $mail->Password   = $smtpPassword;
            $mail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $smtpPort;
            $mail->CharSet    = PHPMailer::CHARSET_UTF8;

            $mail->setFrom($mailFromAddress, $mailFromName);
            $mail->addAddress($mailRecipientAddress);
            $mail->addReplyTo($email, $fullname); // Bruker $fullname her

            $mail->isHTML(false); 
            $mail->Subject = 'Google Workspace Henvendelse fra Akari.no - ' . $fullname; // Lagt til navn i emne

            $emailBody = "Ny Google Workspace henvendelse:\n\n";
            $emailBody .= "Navn: " . $fullname . "\n"; // Bruker $fullname
            $emailBody .= "Firma: " . (!empty($company) ? $company : "Ikke oppgitt") . "\n";
            $emailBody .= "E-post: " . $email . "\n";
            $emailBody .= "Telefon: " . $phone . "\n";
            $emailBody .= "Interessert i pakke: " . (!empty($package_interest) ? $package_interest : "Ikke spesifisert") . "\n\n";
            $emailBody .= "Melding:\n" . $message_content . "\n\n";
            $emailBody .= "Personvernerklæring godtatt: Ja\n";
            $mail->Body = $emailBody;

            if ($mail->send()) {
                 $formResult['message'] = 'Takk! Meldingen din er sendt. Vi tar kontakt med deg snart.';
                 $formResult['success'] = true;
                 $formResult['data'] = []; 
            } else {
                 $formResult['message'] = "Meldingen kunne ikke sendes. (Ukjent feil)";
                 error_log("PHPMailer send() returned false without Exception for akari.no");
            }

        } catch (Exception $e) {
            $formResult['message'] = "Beklager, meldingen kunne ikke sendes. Kontakt oss direkte.";
            error_log("PHPMailer Exception for akari.no: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
        }

    } else {
        $formResult['message'] = implode(" ", $errors);
        $formResult['success'] = false;
    }
}
return $formResult;

?>
