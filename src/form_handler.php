<?php // src/form_handler.php

// Importer PHPMailer klasser
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

// Hjelpefunksjon for å generere ICS-innhold
if (!function_exists('generate_ics_content')) {
    function generate_ics_content($seminar_data, $attendee_data) {
        $uid = ($seminar_data['slug'] ?? 'seminar') . '-' . time() . '@googleworkspace.akari.no';
        $dtstamp = gmdate('Ymd\THis\Z');
        
        $seminar_datetime_str_raw = $seminar_data['datetime_raw'] ?? '';
        if (empty($seminar_datetime_str_raw)) {
            error_log("ICS Generation: Manglende 'datetime_raw' for seminar slug: " . ($seminar_data['slug'] ?? 'UKJENT'));
            return null;
        }

        try {
            $start_datetime_oslo = new DateTime($seminar_datetime_str_raw, new DateTimeZone('Europe/Oslo'));
            $start_timestamp = $start_datetime_oslo->getTimestamp();
        } catch (Exception $e) {
            error_log("ICS Generation: Kunne ikke parse dato: " . $seminar_datetime_str_raw . " for slug " . ($seminar_data['slug'] ?? 'UKJENT') . " - Feil: " . $e->getMessage());
            return null;
        }

        $dtstart_utc = gmdate('Ymd\THis\Z', $start_timestamp);

        $duration_hours = floatval($seminar_data['duration_hours_raw'] ?? 1.5);
        $duration_seconds = intval($duration_hours * 3600);
        $dtend_timestamp_utc = $start_timestamp + $duration_seconds;
        $dtend_utc = gmdate('Ymd\THis\Z', $dtend_timestamp_utc);

        $summary = str_replace(["\r\n", "\n", "\r", ",", ";"], ["\\n", "\\n", "\\n", "\\,", "\\;"], $seminar_data['title'] ?? 'Seminar');
        $description = str_replace(["\r\n", "\n", "\r", ",", ";"], ["\\n", "\\n", "\\n", "\\,", "\\;"], $seminar_data['excerpt_raw'] ?? 'Påmelding til Akari seminar.');
        $location = str_replace(["\r\n", "\n", "\r", ",", ";"], ["\\n", "\\n", "\\n", "\\,", "\\;"], $seminar_data['location_raw'] ?? 'Akari');
        $attendee_name_esc = str_replace(["\r\n", "\n", "\r", ",", ";"], ["\\n", "\\n", "\\n", "\\,", "\\;"], $attendee_data['fullname'] ?? 'Deltaker');
        $organizer_email = !empty($seminar_data['contact_email_raw']) ? $seminar_data['contact_email_raw'] : (getenv('MAIL_FROM_ADDRESS') ?: 'googleworkspace@akari.no');
        $organizer_name = getenv('MAIL_FROM_NAME') ?: 'Akari AS';

        $ics_content = "BEGIN:VCALENDAR\r\n";
        $ics_content .= "VERSION:2.0\r\n";
        $ics_content .= "PRODID:-//Akari AS//NONSGML Akari Seminar//EN\r\n";
        $ics_content .= "CALSCALE:GREGORIAN\r\n";
        $ics_content .= "METHOD:REQUEST\r\n"; 
        $ics_content .= "BEGIN:VEVENT\r\n";
        $ics_content .= "DTSTAMP:" . $dtstamp . "\r\n";
        $ics_content .= "DTSTART:" . $dtstart_utc . "\r\n";
        $ics_content .= "DTEND:" . $dtend_utc . "\r\n";
        $ics_content .= "SUMMARY:" . $summary . "\r\n";
        $ics_content .= "DESCRIPTION:" . $description . "\r\n";
        $ics_content .= "LOCATION:" . $location . "\r\n";
        $ics_content .= "UID:" . $uid . "\r\n";
        $ics_content .= "ORGANIZER;CN=\"" . $organizer_name . "\":MAILTO:" . $organizer_email . "\r\n";
        $ics_content .= "ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=\"" . $attendee_name_esc . "\":MAILTO:" . ($attendee_data['email'] ?? '') . "\r\n";
        $ics_content .= "SEQUENCE:0\r\n";
        $ics_content .= "STATUS:CONFIRMED\r\n";
        $ics_content .= "TRANSP:OPAQUE\r\n";
        
        $reminder_datetime_oslo = clone $start_datetime_oslo;
        $reminder_datetime_oslo->modify('-1 day');
        $reminder_datetime_oslo->setTime(9, 0, 0);
        $reminder_dt_utc = $reminder_datetime_oslo->setTimezone(new DateTimeZone('UTC'))->format('Ymd\THis\Z');

        $ics_content .= "BEGIN:VALARM\r\n";
        $ics_content .= "ACTION:DISPLAY\r\n";
        $ics_content .= "DESCRIPTION:Påminnelse: " . $summary . "\r\n";
        $ics_content .= "TRIGGER;VALUE=DATE-TIME:" . $reminder_dt_utc . "\r\n";
        $ics_content .= "END:VALARM\r\n";
        $ics_content .= "END:VEVENT\r\n";
        $ics_content .= "END:VCALENDAR\r\n";
        return $ics_content;
    }
}

// Hjelpefunksjon for Google Calendar Link - JUSTERT
if (!function_exists('generate_google_calendar_link')) {
    function generate_google_calendar_link($seminar_data) {
        $seminar_datetime_str_raw_gcal = $seminar_data['datetime_raw'] ?? '';
         if (empty($seminar_datetime_str_raw_gcal)) {
            error_log("Google Calendar Link: Manglende 'datetime_raw' for seminar slug: " . ($seminar_data['slug'] ?? 'UKJENT'));
            return '#';
        }
        try {
            $start_datetime_oslo_gcal = new DateTime($seminar_datetime_str_raw_gcal, new DateTimeZone('Europe/Oslo'));
        } catch (Exception $e) {
            error_log("Google Calendar Link: Kunne ikke parse dato: " . $seminar_datetime_str_raw_gcal . " for slug " . ($seminar_data['slug'] ?? 'UKJENT') . " - Feil: " . $e->getMessage());
            return '#';
        }

        $dtstart_google = $start_datetime_oslo_gcal->format('Ymd\THis');
        
        $duration_hours_gcal = floatval($seminar_data['duration_hours_raw'] ?? 1.5);
        $duration_seconds_gcal = intval($duration_hours_gcal * 3600);
        
        $end_datetime_oslo_gcal = clone $start_datetime_oslo_gcal;
        $end_datetime_oslo_gcal->modify('+' . $duration_seconds_gcal . ' seconds');
        $dtend_google = $end_datetime_oslo_gcal->format('Ymd\THis');

        $base_url = 'https://www.google.com/calendar/render?action=TEMPLATE';
        
        $params = [
            'text' => $seminar_data['title'] ?? 'Seminar', // Direkte verdi
            'dates' => $dtstart_google . '/' . $dtend_google,
            'details' => $seminar_data['excerpt_raw'] ?? 'Påmelding til Akari seminar.', // Direkte verdi
            'location' => $seminar_data['location_raw'] ?? 'Akari', // Direkte verdi
            'ctz' => 'Europe/Oslo'
        ];
        
        return $base_url . '&' . http_build_query($params);
    }
}


$formResult = [
    'message' => '',
    'success' => false,
    'data' => [], 
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstname = htmlspecialchars(trim($_POST['firstname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars(trim($_POST['lastname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $fullname = trim($firstname . ' ' . $lastname); 
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $company = htmlspecialchars(trim($_POST['company'] ?? ''), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $privacy_policy_agreed = isset($_POST['privacy']) && $_POST['privacy'] === 'on';
    $form_type = $_POST['form_type'] ?? 'contact';
    $form_source_location = htmlspecialchars(trim($_POST['form_source_location'] ?? 'Ukjent kilde'), ENT_QUOTES, 'UTF-8');

    $formResult['data'] = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'company' => $company,
        'phone' => $phone,
        'privacy' => $privacy_policy_agreed ? 'on' : '',
    ];

    $errors = [];
    if (empty($firstname)) { $errors[] = "Fornavn er påkrevd."; }
    if (empty($email)) { $errors[] = "E-post er påkrevd."; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Vennligst oppgi en gyldig e-postadresse."; $formResult['data']['email'] = $_POST['email'] ?? ''; }
    if (empty($phone)) { $errors[] = "Telefonnummer er påkrevd."; }
    if (!$privacy_policy_agreed) { $errors[] = "Du må godta personvernerklæringen."; }

    $smtpHost = getenv('MAILGUN_SMTP_HOST') ?: 'smtp.eu.mailgun.org';
    $smtpPort = getenv('MAILGUN_SMTP_PORT') ?: 587;
    $smtpUsername = getenv('MAILGUN_SMTP_USERNAME');
    $smtpPassword = getenv('MAILGUN_SMTP_PASSWORD');
    $mailFromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'googleworkspace@akari.no';
    $mailFromName = getenv('MAIL_FROM_NAME') ?: 'Akari Google Workspace';
    $mailRecipientAddress = getenv('MAIL_RECIPIENT_ADDRESS');

    $dbHost = getenv('DB_HOST') ?: '127.0.0.1';
    $dbPort = getenv('DB_PORT') ?: '3306';
    $dbName = getenv('DB_DATABASE');
    $dbUser = getenv('DB_USERNAME');
    $dbPass = getenv('DB_PASSWORD');

    if (empty($smtpUsername) || empty($smtpPassword) || empty($mailRecipientAddress)) {
         $errors[] = "Serverkonfigurasjonsfeil (manglende e-postinnstillinger).";
         error_log("Mailgun SMTP credentials or recipient address missing for googleworkspace.akari.no");
    }
    if (empty($dbName) || empty($dbUser) || empty($dbPass)) {
        $errors[] = "Serverkonfigurasjonsfeil (manglende databaseinnstillinger).";
        error_log("Database credentials missing for googleworkspace.akari.no");
    }

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
                $formResult['message'] = "Beklager, meldingen kunne ikke sendes. Kontakt oss direkte."; error_log("PHPMailer Exception (Admin Contact) for googleworkspace.akari.no: " . ($mail->ErrorInfo ?? $e->getMessage()));
            }
        } else {
            $formResult['message'] = implode(" ", $errors); $formResult['success'] = false;
        }

    } elseif ($form_type === 'seminar_registration') {
        $seminar_slug = htmlspecialchars(trim($_POST['seminar_slug'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_title = htmlspecialchars(trim($_POST['seminar_title'] ?? 'Ukjent seminar'), ENT_QUOTES, 'UTF-8');
        $seminar_datetime_raw = trim($_POST['seminar_datetime_raw'] ?? '');
        $seminar_location_raw = htmlspecialchars(trim($_POST['seminar_location_raw'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_excerpt_raw = htmlspecialchars(trim($_POST['seminar_excerpt_raw'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_contact_email_raw = htmlspecialchars(trim($_POST['seminar_contact_email_raw'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_duration_hours_raw = htmlspecialchars(trim($_POST['seminar_duration_hours_raw'] ?? '1.5'), ENT_QUOTES, 'UTF-8');

        $num_attendees_raw = trim($_POST['num_attendees'] ?? '1');
        $num_attendees = filter_var($num_attendees_raw, FILTER_VALIDATE_INT);
        if ($num_attendees === false || $num_attendees < 1) {
            $num_attendees = 1;
        }
        $dietary_restrictions = htmlspecialchars(trim($_POST['dietary_restrictions'] ?? ''), ENT_QUOTES, 'UTF-8'); // Dette feltet er nå "Kommentar"

        $formResult['data']['seminar_slug'] = $seminar_slug;
        $formResult['data']['seminar_title'] = $seminar_title;
        $formResult['data']['num_attendees'] = $num_attendees;
        $formResult['data']['dietary_restrictions'] = $dietary_restrictions;
        $formResult['data']['seminar_datetime_raw'] = $seminar_datetime_raw;
        $formResult['data']['seminar_location_raw'] = $seminar_location_raw;
        $formResult['data']['seminar_excerpt_raw'] = $seminar_excerpt_raw;
        $formResult['data']['seminar_contact_email_raw'] = $seminar_contact_email_raw;
        $formResult['data']['seminar_duration_hours_raw'] = $seminar_duration_hours_raw;

        if (empty($seminar_slug) || empty($seminar_title) || empty($seminar_datetime_raw)) { $errors[] = "Seminar informasjon mangler eller er ugyldig."; }
        
        if (empty($errors)) {
            $pdo = null;
            try {
                $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
                $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];
                $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
                $sql = "INSERT INTO seminar_registrations (seminar_slug, seminar_title, firstname, lastname, company, email, phone, num_attendees, dietary_restrictions, privacy_agreed, form_source_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$seminar_slug, $seminar_title, $firstname, $lastname, $company, $email, $phone, $num_attendees, $dietary_restrictions, $privacy_policy_agreed ? 1 : 0, $seminar_title]);
            } catch (\PDOException $e) {
                error_log("Database Error (seminar_registrations) for googleworkspace.akari.no: " . $e->getMessage());
                $errors[] = "En intern feil oppstod under lagring av påmeldingen. Vennligst prøv igjen senere eller kontakt oss.";
            }

            if (empty($errors)) {
                $adminMail = new PHPMailer(true);
                try {
                    $adminMail->isSMTP(); $adminMail->Host = $smtpHost; $adminMail->SMTPAuth = true; $adminMail->Username = $smtpUsername; $adminMail->Password = $smtpPassword;
                    $adminMail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                    $adminMail->Port = $smtpPort; $adminMail->CharSet = PHPMailer::CHARSET_UTF8;
                    $adminMail->setFrom($mailFromAddress, $mailFromName); $adminMail->addAddress($mailRecipientAddress); $adminMail->addReplyTo($email, $fullname); 
                    $adminMail->isHTML(false); 
                    $adminMail->Subject = 'Ny Påmelding: ' . $seminar_title . ' - ' . $fullname;
                    $emailBodyToAdmin = "Ny påmelding til seminaret \"" . $seminar_title . "\":\n\n";
                    $emailBodyToAdmin .= "Seminar ID (Slug): " . $seminar_slug . "\n";
                    $emailBodyToAdmin .= "Navn: " . $fullname . "\n";
                    $emailBodyToAdmin .= "Firma: " . (!empty($company) ? $company : "Ikke oppgitt") . "\n";
                    $emailBodyToAdmin .= "E-post: " . $email . "\n";
                    $emailBodyToAdmin .= "Telefon: " . $phone . "\n";
                    $emailBodyToAdmin .= "Antall deltakere: " . $num_attendees . "\n";
                    $emailBodyToAdmin .= "Kommentar: " . (!empty($dietary_restrictions) ? $dietary_restrictions : "Ingen") . "\n\n"; // Endret fra Mathensyn
                    $emailBodyToAdmin .= "Personvern godtatt: " . ($privacy_policy_agreed ? "Ja" : "Nei") . "\n";
                    $adminMail->Body = $emailBodyToAdmin;

                    if ($adminMail->send()) {
                        $formResult['message'] = 'Takk for din påmelding til seminaret! Vi har sendt deg en bekreftelse på e-post med en kalenderinvitasjon.';
                        $formResult['success'] = true;

                        $receiptMail = new PHPMailer(true);
                        $receiptMail->isSMTP(); $receiptMail->Host = $smtpHost; $receiptMail->SMTPAuth = true; $receiptMail->Username = $smtpUsername; $receiptMail->Password = $smtpPassword;
                        $receiptMail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                        $receiptMail->Port = $smtpPort; $receiptMail->CharSet = PHPMailer::CHARSET_UTF8;
                        $receiptMail->setFrom($mailFromAddress, $mailFromName); 
                        $receiptMail->addAddress($email, $fullname);           
                        $receiptMail->isHTML(true); 
                        $receiptMail->Subject = 'Bekreftelse på påmelding: ' . $seminar_title;
                        
                        $seminar_ics_data = [
                            'slug' => $seminar_slug,
                            'title' => $seminar_title,
                            'datetime_raw' => $seminar_datetime_raw,
                            'location_raw' => $seminar_location_raw,
                            'excerpt_raw' => $seminar_excerpt_raw,
                            'contact_email_raw' => $seminar_contact_email_raw,
                            'duration_hours_raw' => $seminar_duration_hours_raw
                        ];
                        $attendee_ics_data = [
                            'fullname' => $fullname,
                            'email' => $email
                        ];
                        
                        $google_calendar_link = generate_google_calendar_link($seminar_ics_data);

                        $greetingName = !empty($firstname) ? $firstname : 'du';
                        $receiptBody = "<p>Hei " . htmlspecialchars($greetingName) . ",</p>";
                        $receiptBody .= "<p>Takk for din påmelding til seminaret: <strong>" . htmlspecialchars($seminar_title) . "</strong>.</p>";
                        $receiptBody .= "<p>Vi har registrert din påmelding";
                        if ($num_attendees > 1) {
                            $receiptBody .= " for " . htmlspecialchars($num_attendees) . " personer";
                        }
                        $receiptBody .= ".</p>";
                        if (!empty($dietary_restrictions)) { // Dette er nå "Kommentar" feltet
                             $receiptBody .= "<p>Kommentar registrert: " . nl2br(htmlspecialchars($dietary_restrictions)) . "</p>"; // Endret fra Mathensyn
                        }
                        $receiptBody .= "<p>En kalenderinvitasjon (.ics-fil) er lagt ved denne e-posten. ";
                        if ($google_calendar_link !== '#') {
                             $receiptBody .= "Du kan også <a href=\"" . htmlspecialchars($google_calendar_link) . "\" target=\"_blank\" rel=\"noopener noreferrer\">legge arrangementet direkte til i din Google Kalender her</a>.";
                        }
                        $receiptBody .= "</p>";
                        $receiptBody .= "<p>Mer informasjon om seminaret vil bli sendt ut nærmere datoen. Vi gleder oss til å se deg!</p>";
                        $receiptBody .= "<p>Dette er en automatisk bekreftelse, du trenger ikke svare på denne e-posten.</p>";
                        $receiptBody .= "<p>Vennlig hilsen,<br>Team Akari</p>";
                        $receiptMail->Body = $receiptBody; 
                        $receiptMail->AltBody = strip_tags(str_replace(["<br>","<br/>","<br />"], "\n", str_replace(["</p><p>","</p>"], "\n\n", $receiptBody)));
                        
                        $ics_content = generate_ics_content($seminar_ics_data, $attendee_ics_data);
                        if ($ics_content) {
                            $ics_filename = preg_replace('/[^a-z0-9_\-\.]/i', '_', $seminar_slug) . '.ics';
                            $receiptMail->addStringAttachment($ics_content, $ics_filename, 'base64', 'text/calendar; charset=utf-8; method=REQUEST');
                        } else {
                            error_log("Kunne ikke generere ICS for seminar (etter admin e-post): " . $seminar_slug);
                        }

                        try { 
                            $receiptMail->send(); 
                        } catch (Exception $e_receipt) { 
                            error_log("PHPMailer Exception (Receipt Seminar) for " . $email . ": " . $receiptMail->ErrorInfo . " | Exception: " . $e_receipt->getMessage());
                        }
                        $formResult['data'] = [];
                    } else {
                         $formResult['message'] = "Påmeldingen kunne ikke sendes (feil ved adminvarsel)."; error_log("PHPMailer send() (Admin Seminar) returned false without Exception for googleworkspace.akari.no");
                    }
                }  catch (Exception $e) {
                    $formResult['message'] = "Beklager, påmeldingen kunne ikke sendes (generell epostfeil). Kontakt oss direkte."; error_log("PHPMailer Exception (Admin Seminar) for googleworkspace.akari.no: " . ($adminMail->ErrorInfo ?? $e->getMessage()) );
                }
            } 
        }
        
        if(!empty($errors)) {
            $formResult['message'] = implode(" ", $errors); $formResult['success'] = false;
        }

    } else { // Ukjent form_type
        $errors[] = "Ugyldig skjematype.";
        $formResult['message'] = implode(" ", $errors); $formResult['success'] = false;
    }
}
return $formResult;
?>
