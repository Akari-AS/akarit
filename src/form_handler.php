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
    $fullname = trim($firstname . ' ' . $lastname); 

    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $company = htmlspecialchars(trim($_POST['company'] ?? ''), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $package_interest = htmlspecialchars(trim($_POST['package_interest'] ?? ''), ENT_QUOTES, 'UTF-8');
    $message_content = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
    $privacy_policy_agreed = isset($_POST['privacy']);
    $form_source_location = htmlspecialchars(trim($_POST['form_source_location'] ?? 'Ukjent kilde'), ENT_QUOTES, 'UTF-8'); // HENT VERDIEN HER

    $formResult['data'] = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'company' => $company,
        'phone' => $phone,
        'package_interest' => $package_interest,
        'message' => $message_content,
        'privacy' => $privacy_policy_agreed,
        // Vi trenger ikke lagre form_source_location i $formResult['data'] for prefill, da det er skjult.
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

    $smtpHost = getenv('MAILGUN_SMTP_HOST') ?: 'smtp.eu.mailgun.org';
    $smtpPort = getenv('MAILGUN_SMTP_PORT') ?: 587;
    $smtpUsername = getenv('MAILGUN_SMTP_USERNAME') ?: 'googleworkspace@mg.akari.no';
    $smtpPassword = getenv('MAILGUN_SMTP_PASSWORD');
    $mailFromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'googleworkspace@akari.no';
    $mailFromName = getenv('MAIL_FROM_NAME') ?: 'Akari Google Workspace';
    $mailRecipientAddress = getenv('MAIL_RECIPIENT_ADDRESS');

    if (empty($smtpUsername) || empty($smtpPassword) || empty($mailRecipientAddress)) {
         $errors[] = "Serverkonfigurasjonsfeil (manglende e-postinnstillinger).";
         error_log("Mailgun SMTP credentials or recipient address missing for googleworkspace.akari.no");
    }

    if (empty($errors)) {
        $mail = new PHPMailer(true);

        try {
            // ----- SEND E-POST TIL AKARI (INTERN) -----
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
            $mail->addReplyTo($email, $fullname); 

            $mail->isHTML(false); 
            $mail->Subject = 'Henvendelse via googleworkspace.akari.no (' . $form_source_location . ') - ' . $fullname; // LAGT TIL KILDE I EMNE

            $emailBodyToAdmin = "Ny henvendelse fra googleworkspace.akari.no:\n\n";
            $emailBodyToAdmin .= "Kilde (Landingsside): " . $form_source_location . "\n"; // LAGT TIL KILDE I BODY
            $emailBodyToAdmin .= "Navn: " . $fullname . "\n";
            $emailBodyToAdmin .= "Firma: " . (!empty($company) ? $company : "Ikke oppgitt") . "\n";
            $emailBodyToAdmin .= "E-post (fra skjema): " . $email . "\n";
            $emailBodyToAdmin .= "Telefon: " . $phone . "\n";
            $emailBodyToAdmin .= "Interessert i pakke: " . (!empty($package_interest) ? $package_interest : "Ikke spesifisert") . "\n\n";
            $emailBodyToAdmin .= "Melding:\n" . $message_content . "\n\n";
            $emailBodyToAdmin .= "Personvernerklæring godtatt: Ja\n";
            $mail->Body = $emailBodyToAdmin;

            if ($mail->send()) {
                 $formResult['message'] = 'Takk! Meldingen din er sendt. Vi har også sendt deg en bekreftelse på e-post.';
                 $formResult['success'] = true;

                // ----- SEND KVITTERINGSEPOST TIL INNSENDER -----
                $receiptMail = new PHPMailer(true);
                $receiptMail->isSMTP();
                $receiptMail->Host       = $smtpHost;
                $receiptMail->SMTPAuth   = true;
                $receiptMail->Username   = $smtpUsername;
                $receiptMail->Password   = $smtpPassword;
                $receiptMail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                $receiptMail->Port       = $smtpPort;
                $receiptMail->CharSet    = PHPMailer::CHARSET_UTF8;

                $receiptMail->setFrom($mailFromAddress, $mailFromName); 
                $receiptMail->addAddress($email, $fullname);           

                $receiptMail->isHTML(true); 
                $receiptMail->Subject = 'Takk for din henvendelse til Akari!';
                
                $greetingName = !empty($firstname) ? $firstname : 'du';
                $sourceInfo = ($form_source_location !== 'Generell' && $form_source_location !== 'Ukjent kilde') ? " for " . htmlspecialchars($form_source_location) : "";


                $receiptBody = "<p>Hei " . htmlspecialchars($greetingName) . ",</p>";
                $receiptBody .= "<p>Takk for din henvendelse til Akari" . $sourceInfo . ". Vi har mottatt meldingen din og vil komme tilbake til deg så snart som mulig.</p>"; // Lagt til lokasjonsinfo hvis relevant
                $receiptBody .= "<p>Dette er en automatisk bekreftelse, du trenger ikke svare på denne e-posten.</p>";
                if (!empty($message_content)) { // Vis meldingen kun hvis den ikke er tom
                    $receiptBody .= "<p><strong>Din melding var:</strong></p>";
                    $receiptBody .= "<blockquote style='border-left: 2px solid #ccc; padding-left: 10px; margin-left: 0; font-style: italic;'>";
                    $receiptBody .= nl2br(htmlspecialchars($message_content)); 
                    $receiptBody .= "</blockquote>";
                }
                $receiptBody .= "<p>Vennlig hilsen,<br>Team Akari</p>";
                
                $receiptMail->Body = $receiptBody;
                $receiptMail->AltBody = strip_tags(str_replace(["<br>","<br/>","<br />"], "\n", str_replace(["</p><p>","</p>"], "\n\n", $receiptBody)));

                try {
                    $receiptMail->send();
                    $formResult['data'] = []; 
                } catch (Exception $e_receipt) {
                    error_log("PHPMailer Exception (Receipt) for " . $email . ": " . $receiptMail->ErrorInfo . " | Exception: " . $e_receipt->getMessage());
                    $formResult['data'] = []; 
                }

            } else {
                 $formResult['message'] = "Meldingen kunne ikke sendes. (Ukjent feil)";
                 error_log("PHPMailer send() (Admin) returned false without Exception for googleworkspace.akari.no");
            }

        } catch (Exception $e) {
            $formResult['message'] = "Beklager, meldingen kunne ikke sendes. Kontakt oss direkte.";
            error_log("PHPMailer Exception (Admin) for googleworkspace.akari.no: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
        }

    } else {
        $formResult['message'] = implode(" ", $errors);
        $formResult['success'] = false;
    }
}
return $formResult;

?>
