<?php // src/form_handler.php

// Importer PHPMailer klasser
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

    // Hent og rens felles brukerinput
    $firstname = htmlspecialchars(trim($_POST['firstname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars(trim($_POST['lastname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $fullname = trim($firstname . ' ' . $lastname); 
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $company = htmlspecialchars(trim($_POST['company'] ?? ''), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $privacy_policy_agreed = isset($_POST['privacy']) && $_POST['privacy'] === 'on';
    
    // Skjema-spesifikk input
    $form_type = $_POST['form_type'] ?? 'contact'; // Default to contact
    $form_source_location = htmlspecialchars(trim($_POST['form_source_location'] ?? 'Ukjent kilde'), ENT_QUOTES, 'UTF-8');

    // Felles data for retur ved feil
    $formResult['data'] = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'company' => $company,
        'phone' => $phone,
        'privacy' => $privacy_policy_agreed ? 'on' : '', // For checkbox state
    ];

    $errors = [];
    // Felles validering
    if (empty($firstname)) { $errors[] = "Fornavn er påkrevd."; }
    if (empty($email)) { $errors[] = "E-post er påkrevd."; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Vennligst oppgi en gyldig e-postadresse."; $formResult['data']['email'] = $_POST['email'] ?? ''; }
    if (empty($phone)) { $errors[] = "Telefonnummer er påkrevd."; }
    if (!$privacy_policy_agreed) { $errors[] = "Du må godta personvernerklæringen."; }

    // Hent e-postinnstillinger FRA MILJØVARIABLER
    $smtpHost = getenv('MAILGUN_SMTP_HOST') ?: 'smtp.eu.mailgun.org'; // Fallback hvis ikke satt
    $smtpPort = getenv('MAILGUN_SMTP_PORT') ?: 587;
    $smtpUsername = getenv('MAILGUN_SMTP_USERNAME'); // Forventes satt
    $smtpPassword = getenv('MAILGUN_SMTP_PASSWORD'); // Forventes satt
    $mailFromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'googleworkspace@akari.no';
    $mailFromName = getenv('MAIL_FROM_NAME') ?: 'Akari Google Workspace';
    $mailRecipientAddress = getenv('MAIL_RECIPIENT_ADDRESS'); // Forventes satt

    // Hent databaseinnstillinger FRA MILJØVARIABLER
    $dbHost = getenv('DB_HOST') ?: '127.0.0.1';
    $dbPort = getenv('DB_PORT') ?: '3306';
    $dbName = getenv('DB_DATABASE'); // Forventes satt
    $dbUser = getenv('DB_USERNAME'); // Forventes satt
    $dbPass = getenv('DB_PASSWORD'); // Forventes satt

    if (empty($smtpUsername) || empty($smtpPassword) || empty($mailRecipientAddress)) {
         $errors[] = "Serverkonfigurasjonsfeil (manglende e-postinnstillinger). Vennligst konfigurer miljøvariabler.";
         error_log("Mailgun SMTP credentials or recipient address missing from environment variables for googleworkspace.akari.no");
    }
    if (empty($dbName) || empty($dbUser) || empty($dbPass)) {
        $errors[] = "Serverkonfigurasjonsfeil (manglende databaseinnstillinger). Vennligst konfigurer miljøvariabler.";
        error_log("Database credentials missing from environment variables for googleworkspace.akari.no");
    }

    // Skjemaspesifikk logikk
    if ($form_type === 'contact') {
        $package_interest = htmlspecialchars(trim($_POST['package_interest'] ?? ''), ENT_QUOTES, 'UTF-8');
        $message_content = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
        $formResult['data']['package_interest'] = $package_interest;
        $formResult['data']['message'] = $message_content;

        if (empty($message_content)) { $errors[] = "Melding er påkrevd."; }

        if (empty($errors)) {
            $pdo = null;
            try {
                $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
                $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];
                $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
                $sql = "INSERT INTO contact_form_submissions (firstname, lastname, company, email, phone, package_interest, message, privacy_agreed, form_source_location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$firstname, $lastname, $company, $email, $phone, $package_interest, $message_content, $privacy_policy_agreed ? 1 : 0, $form_source_location]);
            } catch (\PDOException $e) {
                error_log("Database Error (contact_form_submissions) for googleworkspace.akari.no: " . $e->getMessage());
                // Ikke vis databasefeil direkte, men logg. E-post vil fortsatt forsøkes sendt.
            }

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP(); $mail->Host = $smtpHost; $mail->SMTPAuth = true; $mail->Username = $smtpUsername; $mail->Password = $smtpPassword;
                $mail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = $smtpPort; $mail->CharSet = PHPMailer::CHARSET_UTF8;
                $mail->setFrom($mailFromAddress, $mailFromName); $mail->addAddress($mailRecipientAddress); $mail->addReplyTo($email, $fullname); 
                $mail->isHTML(false); 
                $mail->Subject = 'Henvendelse via googleworkspace.akari.no (' . $form_source_location . ') - ' . $fullname;
                $emailBodyToAdmin = "Ny henvendelse fra googleworkspace.akari.no:\n\nKilde: " . $form_source_location . "\nNavn: " . $fullname . "\nFirma: " . (!empty($company) ? $company : "Ikke oppgitt") . "\nE-post: " . $email . "\nTelefon: " . $phone . "\nInteressert i pakke: " . (!empty($package_interest) ? $package_interest : "Ikke spesifisert") . "\n\nMelding:\n" . $message_content . "\n\nPersonvern godtatt: " . ($privacy_policy_agreed ? "Ja" : "Nei") . "\n";
                $mail->Body = $emailBodyToAdmin;

                if ($mail->send()) {
                    $formResult['message'] = 'Takk! Meldingen din er sendt. Vi har også sendt deg en bekreftelse på e-post.';
                    $formResult['success'] = true;

                    $receiptMail = new PHPMailer(true);
                    $receiptMail->isSMTP(); $receiptMail->Host = $smtpHost; $receiptMail->SMTPAuth = true; $receiptMail->Username = $smtpUsername; $receiptMail->Password = $smtpPassword;
                    $receiptMail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                    $receiptMail->Port = $smtpPort; $receiptMail->CharSet = PHPMailer::CHARSET_UTF8;
                    $receiptMail->setFrom($mailFromAddress, $mailFromName); $receiptMail->addAddress($email, $fullname);           
                    $receiptMail->isHTML(true); $receiptMail->Subject = 'Takk for din henvendelse til Akari!';
                    $greetingName = !empty($firstname) ? $firstname : 'du';
                    $sourceInfo = ($form_source_location !== 'Generell' && $form_source_location !== 'Ukjent kilde' && strpos($form_source_location, 'Artikkel:') === false) ? " for " . htmlspecialchars($form_source_location) : "";
                    $receiptBody = "<p>Hei " . htmlspecialchars($greetingName) . ",</p><p>Takk for din henvendelse til Akari" . $sourceInfo . ". Vi har mottatt meldingen din og vil komme tilbake til deg så snart som mulig.</p><p>Dette er en automatisk bekreftelse, du trenger ikke svare på denne e-posten.</p>";
                    if (!empty($message_content)) { $receiptBody .= "<p><strong>Din melding var:</strong></p><blockquote style='border-left: 2px solid #ccc; padding-left: 10px; margin-left: 0; font-style: italic;'>" . nl2br(htmlspecialchars($message_content)) . "</blockquote>"; }
                    $receiptBody .= "<p>Vennlig hilsen,<br>Team Akari</p>";
                    $receiptMail->Body = $receiptBody; $receiptMail->AltBody = strip_tags(str_replace(["<br>","<br/>","<br />"], "\n", str_replace(["</p><p>","</p>"], "\n\n", $receiptBody)));
                    try { $receiptMail->send(); } catch (Exception $e_receipt) { error_log("PHPMailer Exception (Receipt Contact) for " . $email . ": " . $receiptMail->ErrorInfo . " | Exception: " . $e_receipt->getMessage());}
                    $formResult['data'] = []; 
                } else {
                    $formResult['message'] = "Meldingen kunne ikke sendes. (Ukjent feil)"; error_log("PHPMailer send() (Admin Contact) returned false without Exception for googleworkspace.akari.no");
                }
            } catch (Exception $e) {
                $formResult['message'] = "Beklager, meldingen kunne ikke sendes. Kontakt oss direkte."; error_log("PHPMailer Exception (Admin Contact) for googleworkspace.akari.no: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
            }
        } else {
            $formResult['message'] = implode(" ", $errors); $formResult['success'] = false;
        }

    } elseif ($form_type === 'seminar_registration') {
        $seminar_slug = htmlspecialchars(trim($_POST['seminar_slug'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_title = htmlspecialchars(trim($_POST['seminar_title'] ?? 'Ukjent seminar'), ENT_QUOTES, 'UTF-8');
        $num_attendees_raw = trim($_POST['num_attendees'] ?? '1');
        $num_attendees = filter_var($num_attendees_raw, FILTER_VALIDATE_INT);
        if ($num_attendees === false || $num_attendees < 1) {
            $num_attendees = 1; // Default til 1 hvis ugyldig
        }
        $dietary_restrictions = htmlspecialchars(trim($_POST['dietary_restrictions'] ?? ''), ENT_QUOTES, 'UTF-8');

        $formResult['data']['seminar_slug'] = $seminar_slug;
        $formResult['data']['seminar_title'] = $seminar_title;
        $formResult['data']['num_attendees'] = $num_attendees;
        $formResult['data']['dietary_restrictions'] = $dietary_restrictions;

        if (empty($seminar_slug) || empty($seminar_title)) { $errors[] = "Seminar informasjon mangler."; }
        
        if (empty($errors)) {
            $pdo = null;
            try {
                $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
                $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];
                $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
                $sql = "INSERT INTO seminar_registrations (seminar_slug, seminar_title, firstname, lastname, company, email, phone, num_attendees, dietary_restrictions, privacy_agreed, form_source_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$seminar_slug, $seminar_title, $firstname, $lastname, $company, $email, $phone, $num_attendees, $dietary_restrictions, $privacy_policy_agreed ? 1 : 0, $seminar_title]); // Bruker seminar_title som form_source_info her
            } catch (\PDOException $e) {
                error_log("Database Error (seminar_registrations) for googleworkspace.akari.no: " . $e->getMessage());
                $errors[] = "En intern feil oppstod under lagring av påmeldingen. Vennligst prøv igjen senere eller kontakt oss.";
            }

            if (empty($errors)) { // Fortsett kun hvis databaseoperasjonen var vellykket (eller ikke ga feil som stoppet)
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP(); $mail->Host = $smtpHost; $mail->SMTPAuth = true; $mail->Username = $smtpUsername; $mail->Password = $smtpPassword;
                    $mail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = $smtpPort; $mail->CharSet = PHPMailer::CHARSET_UTF8;
                    $mail->setFrom($mailFromAddress, $mailFromName); $mail->addAddress($mailRecipientAddress); $mail->addReplyTo($email, $fullname); 
                    $mail->isHTML(false); 
                    $mail->Subject = 'Ny Påmelding: ' . $seminar_title . ' - ' . $fullname;
                    $emailBodyToAdmin = "Ny påmelding til seminaret \"" . $seminar_title . "\":\n\n";
                    $emailBodyToAdmin .= "Seminar ID (Slug): " . $seminar_slug . "\n";
                    $emailBodyToAdmin .= "Navn: " . $fullname . "\n";
                    $emailBodyToAdmin .= "Firma: " . (!empty($company) ? $company : "Ikke oppgitt") . "\n";
                    $emailBodyToAdmin .= "E-post: " . $email . "\n";
                    $emailBodyToAdmin .= "Telefon: " . $phone . "\n";
                    $emailBodyToAdmin .= "Antall deltakere: " . $num_attendees . "\n";
                    $emailBodyToAdmin .= "Mathensyn: " . (!empty($dietary_restrictions) ? $dietary_restrictions : "Ingen") . "\n\n";
                    $emailBodyToAdmin .= "Personvern godtatt: " . ($privacy_policy_agreed ? "Ja" : "Nei") . "\n";
                    $mail->Body = $emailBodyToAdmin;

                    if ($mail->send()) {
                        $formResult['message'] = 'Takk for din påmelding til seminaret! Vi har sendt deg en bekreftelse på e-post.';
                        $formResult['success'] = true;

                        $receiptMail = new PHPMailer(true);
                        $receiptMail->isSMTP(); $receiptMail->Host = $smtpHost; $receiptMail->SMTPAuth = true; $receiptMail->Username = $smtpUsername; $receiptMail->Password = $smtpPassword;
                        $receiptMail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                        $receiptMail->Port = $smtpPort; $receiptMail->CharSet = PHPMailer::CHARSET_UTF8;
                        $receiptMail->setFrom($mailFromAddress, $mailFromName); $receiptMail->addAddress($email, $fullname);           
                        $receiptMail->isHTML(true); $receiptMail->Subject = 'Bekreftelse på påmelding: ' . $seminar_title;
                        $greetingName = !empty($firstname) ? $firstname : 'du';
                        $receiptBody = "<p>Hei " . htmlspecialchars($greetingName) . ",</p>";
                        $receiptBody .= "<p>Takk for din påmelding til seminaret: <strong>" . htmlspecialchars($seminar_title) . "</strong>.</p>";
                        $receiptBody .= "<p>Vi har registrert din påmelding";
                        if ($num_attendees > 1) {
                            $receiptBody .= " for " . htmlspecialchars($num_attendees) . " personer";
                        }
                        $receiptBody .= ".</p>";
                        if (!empty($dietary_restrictions)) {
                             $receiptBody .= "<p>Mathensyn registrert: " . nl2br(htmlspecialchars($dietary_restrictions)) . "</p>";
                        }
                        $receiptBody .= "<p>Mer informasjon om seminaret vil bli sendt ut nærmere datoen. Vi gleder oss til å se deg!</p>";
                        $receiptBody .= "<p>Dette er en automatisk bekreftelse, du trenger ikke svare på denne e-posten.</p>";
                        $receiptBody .= "<p>Vennlig hilsen,<br>Team Akari</p>";
                        $receiptMail->Body = $receiptBody; $receiptMail->AltBody = strip_tags(str_replace(["<br>","<br/>","<br />"], "\n", str_replace(["</p><p>","</p>"], "\n\n", $receiptBody)));
                        try { $receiptMail->send(); } catch (Exception $e_receipt) { error_log("PHPMailer Exception (Receipt Seminar) for " . $email . ": " . $receiptMail->ErrorInfo . " | Exception: " . $e_receipt->getMessage());}
                        $formResult['data'] = [];
                    } else {
                        $formResult['message'] = "Påmeldingen kunne ikke sendes. (Ukjent feil)"; error_log("PHPMailer send() (Admin Seminar) returned false without Exception for googleworkspace.akari.no");
                    }
                } catch (Exception $e) {
                    $formResult['message'] = "Beklager, påmeldingen kunne ikke sendes. Kontakt oss direkte."; error_log("PHPMailer Exception (Admin Seminar) for googleworkspace.akari.no: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
                }
            }
        }
        
        if(!empty($errors)) { // Hvis det var feil fra validering eller database
            $formResult['message'] = implode(" ", $errors); $formResult['success'] = false;
        }

    } else {
        // Ukjent form_type
        $errors[] = "Ugyldig skjematype.";
        $formResult['message'] = implode(" ", $errors); $formResult['success'] = false;
    }
}
return $formResult;
?>
