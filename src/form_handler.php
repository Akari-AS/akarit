<?php // src/form_handler.php

// Importer PHPMailer klasser inn i det globale navnerommet
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

// I toppen av form_handler.php, etter require autoload.php:
$log_file = __DIR__ . '/form_handler_debug.log';
file_put_contents($log_file, "--- Nytt Skjemaforsøk: " . date('Y-m-d H:i:s') . " ---\n", FILE_APPEND);
file_put_contents($log_file, "MAIL_FROM_ADDRESS (getenv): " . (getenv('MAIL_FROM_ADDRESS') ?: 'IKKE SATT I ENV') . "\n", FILE_APPEND);
file_put_contents($log_file, "MAIL_FROM_NAME (getenv): " . (getenv('MAIL_FROM_NAME') ?: 'IKKE SATT I ENV') . "\n", FILE_APPEND);
file_put_contents($log_file, "MAILGUN_SMTP_USERNAME (getenv): " . (getenv('MAILGUN_SMTP_USERNAME') ?: 'IKKE SATT I ENV') . "\n\n", FILE_APPEND);

$formResult = [
    'message' => '',
    'success' => false,
    'data' => [], 
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Hent og rens brukerinput
    $firstname = htmlspecialchars(trim($_POST['firstname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars(trim($_POST['lastname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $fullname = trim($firstname . ' ' . $lastname); 

    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $company = htmlspecialchars(trim($_POST['company'] ?? ''), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $package_interest = htmlspecialchars(trim($_POST['package_interest'] ?? ''), ENT_QUOTES, 'UTF-8');
    $message_content = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
    $privacy_policy_agreed = isset($_POST['privacy']);

    $formResult['data'] = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'company' => $company,
        'phone' => $phone,
        'package_interest' => $package_interest,
        'message' => $message_content,
        'privacy' => $privacy_policy_agreed,
    ];

    $errors = [];
    if (empty($firstname)) {
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

    // Hent Mailgun-innstillinger fra Miljøvariabler eller bruk fallbacks
    $smtpHost = getenv('MAILGUN_SMTP_HOST') ?: 'smtp.eu.mailgun.org';
    $smtpPort = getenv('MAILGUN_SMTP_PORT') ?: 587;
    $smtpUsername = getenv('MAILGUN_SMTP_USERNAME'); // Bør være din Mailgun SMTP-bruker, f.eks. postmaster@mg.akari.no
    $smtpPassword = getenv('MAILGUN_SMTP_PASSWORD'); // Ditt Mailgun SMTP-passord
    
    // VIKTIG: Endre disse standardverdiene til de nye ønskede verdiene
    // Disse vil bli brukt HVIS miljøvariablene ikke er satt i Forge.
    // Det ANBEFALES STERKT å sette disse i Forge Environment.
    $mailFromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'googleworkspace@akari.no'; // NY standard FROM adresse
    $mailFromName = getenv('MAIL_FROM_NAME') ?: 'Akari Google Workspace'; // Nytt standard FROM navn
    $mailRecipientAddress = getenv('MAIL_RECIPIENT_ADDRESS'); // Mottaker forblir den samme

    if (empty($smtpUsername) || empty($smtpPassword) || empty($mailRecipientAddress)) {
         $errors[] = "Serverkonfigurasjonsfeil (manglende e-postinnstillinger).";
         error_log("Mailgun SMTP credentials or recipient address missing in environment variables for googleworkspace.akari.no");
    }

    if (empty($errors)) {
        $mail = new PHPMailer(true);

        try {
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUsername; // Brukes for å logge inn på Mailgun
            $mail->Password   = $smtpPassword; // Brukes for å logge inn på Mailgun
            $mail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $smtpPort;
            $mail->CharSet    = PHPMailer::CHARSET_UTF8;

            // Avsender (dette er hva mottakeren ser)
            $mail->setFrom($mailFromAddress, $mailFromName); 
            
            $mail->addAddress($mailRecipientAddress); // Din mottakeradresse
            $mail->addReplyTo($email, $fullname); // Svar går til innsenderen

            $mail->isHTML(false); 
            $mail->Subject = 'Henvendelse via googleworkspace.akari.no - ' . $fullname; // Mer spesifikt emne

            $emailBody = "Ny henvendelse fra googleworkspace.akari.no:\n\n"; // Mer spesifikk
            $emailBody .= "Navn: " . $fullname . "\n";
            $emailBody .= "Firma: " . (!empty($company) ? $company : "Ikke oppgitt") . "\n";
            $emailBody .= "E-post (fra skjema): " . $email . "\n";
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
                 error_log("PHPMailer send() returned false without Exception for googleworkspace.akari.no");
            }

        } catch (Exception $e) {
            $formResult['message'] = "Beklager, meldingen kunne ikke sendes. Kontakt oss direkte.";
            error_log("PHPMailer Exception for googleworkspace.akari.no: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
        }

    } else {
        $formResult['message'] = implode(" ", $errors);
        $formResult['success'] = false;
    }
}
return $formResult;
