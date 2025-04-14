<?php // src/form_handler.php

ini_set('display_errors', 1); // Vis feil i nettleseren
ini_set('display_startup_errors', 1); // Vis også feil som skjer før skriptet kjører
error_reporting(E_ALL); // Vis alle typer feil
// -- Resten av koden starter her (use PHPMailer etc.) --

// Importer PHPMailer klasser inn i det globale navnerommet
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * VIKTIG: Sørg for at Composer autoloaderen inkluderes korrekt.
 * Stien her antar at vendor/ ligger i rotmappen, ETT NIVÅ OPP fra mappen der denne filen (form_handler.php) ligger.
 * Juster stien ('/../') om nødvendig, basert på din faktiske mappestruktur på serveren.
 * Eksempel: Hvis src/ og vendor/ ligger i samme mappe, fjern '/..' -> require __DIR__ . '/vendor/autoload.php';
 */
require __DIR__ . '/../vendor/autoload.php';

// Standardverdier for retur
$formResult = [
    'message' => '',
    'success' => false,
    'data' => [], // For å sende tilbake data ved feil (pre-fill form)
];

// Prosesser kun hvis det er en POST-forespørsel
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Hent og rens brukerinput
      $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
      $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL); // Fortsatt OK
      $company = htmlspecialchars(trim($_POST['company'] ?? ''), ENT_QUOTES, 'UTF-8');
      $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

    // Lagre innsendt data for evt. pre-fill ved valideringsfeil
    $formResult['data'] = [
        'name' => $name,
        'email' => $email, // Behold opprinnelig e-post hvis den er ugyldig
        'company' => $company,
        'message' => $message,
    ];

    // === Input Validering ===
    $errors = [];
    if (empty($name)) {
        $errors[] = "Navn er påkrevd.";
    }
    if (empty($email)) {
        $errors[] = "E-post er påkrevd.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Vennligst oppgi en gyldig e-postadresse.";
        // Behold feilaktig e-post for korrigering
        $formResult['data']['email'] = $_POST['email'] ?? '';
    }
    if (empty($message)) {
        $errors[] = "Melding er påkrevd.";
    }

    // === Hent Mailgun-innstillinger fra Miljøvariabler (Forge Environment) ===
    // Bruk standardverdier (fallbacks) i tilfelle miljøvariabelen ikke er satt
    // (selv om den BØR være satt i Forge).
    $smtpHost = getenv('MAILGUN_SMTP_HOST') ?: 'smtp.eu.mailgun.org'; // Bruk din region!
    $smtpPort = getenv('MAILGUN_SMTP_PORT') ?: 587;
    $smtpUsername = getenv('MAILGUN_SMTP_USERNAME'); // Ingen god standardverdi for disse
    $smtpPassword = getenv('MAILGUN_SMTP_PASSWORD');
    $mailFromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'noreply@akarit.no'; // Standard avsender
    $mailFromName = getenv('MAIL_FROM_NAME') ?: 'Akarit Nettside';
    $mailRecipientAddress = getenv('MAIL_RECIPIENT_ADDRESS'); // E-post som mottar henvendelsen

    // === Hent Mailgun-innstillinger ... (linjene før) ===

    // --- DEBUGGING START ---
    echo "<pre style='background: #eee; padding: 10px; border: 1px solid #ccc; color: #000; text-align: left; position: relative; z-index: 9999;'>"; // Lagt til styling for synlighet
    echo "DEBUG - Environment Variables Check:\n";
    echo "PHP Version: " . phpversion() . "\n"; // Nyttig info
    echo "MAILGUN_SMTP_HOST: '" . htmlspecialchars(getenv('MAILGUN_SMTP_HOST') ?: 'IKKE SATT/TOM') . "'\n";
    echo "MAILGUN_SMTP_PORT: '" . htmlspecialchars(getenv('MAILGUN_SMTP_PORT') ?: 'IKKE SATT/TOM') . "'\n";
    echo "MAILGUN_SMTP_USERNAME: '" . htmlspecialchars(getenv('MAILGUN_SMTP_USERNAME') ?: 'IKKE SATT/TOM') . "'\n";
    echo "MAILGUN_SMTP_PASSWORD: " . (getenv('MAILGUN_SMTP_PASSWORD') ? "'****** (Satt)'" : "'IKKE SATT/TOM'") . "\n"; // Ikke vis passordet!
    echo "MAIL_FROM_ADDRESS: '" . htmlspecialchars(getenv('MAIL_FROM_ADDRESS') ?: 'IKKE SATT/TOM') . "'\n";
    echo "MAIL_FROM_NAME: '" . htmlspecialchars(getenv('MAIL_FROM_NAME') ?: 'IKKE SATT/TOM') . "'\n";
    echo "MAIL_RECIPIENT_ADDRESS: '" . htmlspecialchars(getenv('MAIL_RECIPIENT_ADDRESS') ?: 'IKKE SATT/TOM') . "'\n";
    echo "</pre>";
    // --- DEBUGGING SLUTT ---

    // Sjekk om kritiske miljøvariabler er satt (denne linjen kommer etterpå)
    if (empty($smtpUsername) || empty($smtpPassword) || empty($mailRecipientAddress)) {
         // ... feilmeldingen din genereres her ...
    }


    // Sjekk om kritiske miljøvariabler er satt
    if (empty($smtpUsername) || empty($smtpPassword) || empty($mailRecipientAddress)) {
         $errors[] = "Serverkonfigurasjonsfeil (manglende e-postinnstillinger).";
         // Logg dette for deg selv
         error_log("Mailgun SMTP credentials or recipient address missing in environment variables for akarit.no");
    }


    // === Send E-post (hvis ingen valideringsfeil) ===
    if (empty($errors)) {
        $mail = new PHPMailer(true); // Aktiverer unntak

        try {
            // Serverinnstillinger
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Aktiver for feilsøking under utvikling
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUsername;
            $mail->Password   = $smtpPassword;
            $mail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $smtpPort;
            $mail->CharSet    = PHPMailer::CHARSET_UTF8; // Bruk PHPMailer konstant

            // Avsender og Mottakere
            $mail->setFrom($mailFromAddress, $mailFromName);    // Avsender (fra ditt domene)
            $mail->addAddress($mailRecipientAddress);           // Mottaker (din interne e-post)
            $mail->addReplyTo($email, $name);                   // Sett brukerens e-post som Reply-To

            // Innhold
            $mail->isHTML(false); // Send som ren tekst
            $mail->Subject = 'Google Workspace Henvendelse fra Akarit.no';

            // Bygg e-post body
            $emailBody = "Ny Google Workspace henvendelse:\n\n";
            $emailBody .= "Navn: " . $name . "\n";
            $emailBody .= "Firma: " . (!empty($company) ? $company : "Ikke oppgitt") . "\n";
            $emailBody .= "E-post: " . $email . "\n\n";
            $emailBody .= "Melding:\n" . $message . "\n";
            $mail->Body = $emailBody;

            // Send e-posten
            if ($mail->send()) {
                 $formResult['message'] = 'Takk! Meldingen din er sendt. Vi tar kontakt med deg snart.';
                 $formResult['success'] = true;
                 $formResult['data'] = []; // Tøm innsendt data ved suksess
            } else {
                 // $mail->send() returnerer false, men unntak bør fanges opp
                 $formResult['message'] = "Meldingen kunne ikke sendes. (Ukjent feil)";
                 error_log("PHPMailer send() returned false without Exception for akarit.no");
            }

        } catch (Exception $e) {
            $formResult['message'] = "Beklager, meldingen kunne ikke sendes. Kontakt oss direkte.";
            // Logg den faktiske feilen for deg selv (viktig for feilsøking!)
            error_log("PHPMailer Exception for akarit.no: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
        }

    } else {
        // Det var valideringsfeil, sett feilmelding
        $formResult['message'] = implode(" ", $errors); // Kombiner alle feilmeldinger
        $formResult['success'] = false;
        // $formResult['data'] inneholder allerede brukerens input
    }

} // End if ($_SERVER["REQUEST_METHOD"] == "POST")


// Returner resultatet slik at index.php kan bruke det
return $formResult;

?>
