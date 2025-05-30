<?php // src/form_handler.php

// Importer PHPMailer klasser
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

// Hjelpefunksjon for å generere ICS-innhold
if (!function_exists('generate_ics_content')) {
    function generate_ics_content($seminar_data, $attendee_data) {
        // ... (eksisterende ICS genereringslogikk som før) ...
        // (Jeg korter ned denne delen for lesbarhet, men den skal være den samme som i forrige svar)
        $uid = ($seminar_data['slug'] ?? 'seminar') . '-' . time() . '@googleworkspace.akari.no';
        $dtstamp = gmdate('Ymd\THis\Z');
        $start_timestamp = strtotime($seminar_data['datetime_raw'] ?? '');
        if ($start_timestamp === false) return null;
        $dtstart = gmdate('Ymd\THis\Z', $start_timestamp);
        $duration_hours = floatval($seminar_data['duration_hours_raw'] ?? 1.5);
        $duration_seconds = intval($duration_hours * 3600);
        $dtend_timestamp = $start_timestamp + $duration_seconds;
        $dtend = gmdate('Ymd\THis\Z', $dtend_timestamp);
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
        $ics_content .= "DTSTART:" . $dtstart . "\r\n";
        $ics_content .= "DTEND:" . $dtend . "\r\n";
        $ics_content .= "SUMMARY:" . $summary . "\r\n";
        $ics_content .= "DESCRIPTION:" . $description . "\r\n";
        $ics_content .= "LOCATION:" . $location . "\r\n";
        $ics_content .= "UID:" . $uid . "\r\n";
        $ics_content .= "ORGANIZER;CN=\"" . $organizer_name . "\":MAILTO:" . $organizer_email . "\r\n";
        $ics_content .= "ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=\"" . $attendee_name_esc . "\":MAILTO:" . ($attendee_data['email'] ?? '') . "\r\n";
        $ics_content .= "SEQUENCE:0\r\n";
        $ics_content .= "STATUS:CONFIRMED\r\n";
        $ics_content .= "TRANSP:OPAQUE\r\n";
        $reminder_timestamp_local = $start_timestamp - (24 * 3600); 
        $year = date('Y', $reminder_timestamp_local);
        $month = date('m', $reminder_timestamp_local);
        $day = date('d', $reminder_timestamp_local);
        $reminder_dt_local = strtotime("$year-$month-$day 09:00:00");
        $reminder_dt_utc = gmdate('Ymd\THis\Z', $reminder_dt_local);
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

// NY Hjelpefunksjon for Google Calendar Link
if (!function_exists('generate_google_calendar_link')) {
    function generate_google_calendar_link($seminar_data) {
        $start_timestamp = strtotime($seminar_data['datetime_raw'] ?? '');
        if ($start_timestamp === false) {
            return '#'; // Returner en ufarlig lenke hvis dato er ugyldig
        }

        // Google Kalender bruker et format som er litt annerledes for start/slutt (uten Z og med T)
        // Format: YYYYMMDDTHHMMSS/YYYYMMDDTHHMMSS (for UTC)
        // Eller: YYYYMMDDTHHMMSSZ/YYYYMMDDTHHMMSSZ (for UTC, men Z er ofte implisitt hvis ikke timezone er satt)
        // Vi bruker lokal tid her for Google Calendar lenken, da den håndterer tidssoner basert på brukerens kalender.
        $dtstart_google = date('Ymd\THis', $start_timestamp);
        
        $duration_hours = floatval($seminar_data['duration_hours_raw'] ?? 1.5);
        $duration_seconds = intval($duration_hours * 3600);
        $dtend_google = date('Ymd\THis', $start_timestamp + $duration_seconds);

        $base_url = 'https://www.google.com/calendar/render?action=TEMPLATE';
        $params = [
            'text' => urlencode($seminar_data['title'] ?? 'Seminar'),
            'dates' => $dtstart_google . '/' . $dtend_google,
            'details' => urlencode($seminar_data['excerpt_raw'] ?? 'Påmelding til Akari seminar.'),
            'location' => urlencode($seminar_data['location_raw'] ?? 'Akari'),
            // 'crm' => 'BUSY', // Kan sette status til "Opptatt"
            // 'trp' => 'false', // Viser ikke gjesteliste for den som legger til
        ];
        
        // Legg til tidssone hvis vi vil være eksplisitte (kan være nyttig)
        // $params['ctz'] = 'Europe/Oslo'; 

        return $base_url . '&' . http_build_query($params);
    }
}


$formResult = [
    'message' => '',
    'success' => false,
    'data' => [], 
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ... (All annen POST data henting og validering forblir den samme som før) ...
    // Kopierer inn den relevante delen for seminarpåmelding for å vise endringen

    $firstname = htmlspecialchars(trim($_POST['firstname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars(trim($_POST['lastname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $fullname = trim($firstname . ' ' . $lastname); 
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $company = htmlspecialchars(trim($_POST['company'] ?? ''), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $privacy_policy_agreed = isset($_POST['privacy']) && $_POST['privacy'] === 'on';
    $form_type = $_POST['form_type'] ?? 'contact';

    // ... (feilhåndtering og database/smtp config som før) ...
    $errors = []; // Sørg for at errors er initialisert
    // Felles validering (gjentatt for kontekst)
    if (empty($firstname)) { $errors[] = "Fornavn er påkrevd."; }
    if (empty($email)) { $errors[] = "E-post er påkrevd."; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Vennligst oppgi en gyldig e-postadresse."; $formResult['data']['email'] = $_POST['email'] ?? ''; }
    if (empty($phone)) { $errors[] = "Telefonnummer er påkrevd."; }
    if (!$privacy_policy_agreed) { $errors[] = "Du må godta personvernerklæringen."; }

    // Hent e-postinnstillinger (gjentatt for kontekst)
    $smtpHost = getenv('MAILGUN_SMTP_HOST') ?: 'smtp.eu.mailgun.org';
    $smtpPort = getenv('MAILGUN_SMTP_PORT') ?: 587;
    $smtpUsername = getenv('MAILGUN_SMTP_USERNAME');
    $smtpPassword = getenv('MAILGUN_SMTP_PASSWORD');
    $mailFromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'googleworkspace@akari.no';
    $mailFromName = getenv('MAIL_FROM_NAME') ?: 'Akari Google Workspace';
    $mailRecipientAddress = getenv('MAIL_RECIPIENT_ADDRESS');

    // Hent databaseinnstillinger (gjentatt for kontekst)
    $dbHost = getenv('DB_HOST') ?: '127.0.0.1';
    $dbPort = getenv('DB_PORT') ?: '3306';
    $dbName = getenv('DB_DATABASE');
    $dbUser = getenv('DB_USERNAME');
    $dbPass = getenv('DB_PASSWORD');

    if (empty($smtpUsername) || empty($smtpPassword) || empty($mailRecipientAddress)) {
         $errors[] = "Serverkonfigurasjonsfeil (manglende e-postinnstillinger).";
    }
    if (empty($dbName) || empty($dbUser) || empty($dbPass)) {
        $errors[] = "Serverkonfigurasjonsfeil (manglende databaseinnstillinger).";
    }


    if ($form_type === 'seminar_registration') {
        // ... (henting av seminarspesifikk POST-data som før) ...
        $seminar_slug = htmlspecialchars(trim($_POST['seminar_slug'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_title = htmlspecialchars(trim($_POST['seminar_title'] ?? 'Ukjent seminar'), ENT_QUOTES, 'UTF-8');
        $seminar_datetime_raw = trim($_POST['seminar_datetime_raw'] ?? '');
        $seminar_location_raw = htmlspecialchars(trim($_POST['seminar_location_raw'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_excerpt_raw = htmlspecialchars(trim($_POST['seminar_excerpt_raw'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_contact_email_raw = htmlspecialchars(trim($_POST['seminar_contact_email_raw'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_duration_hours_raw = htmlspecialchars(trim($_POST['seminar_duration_hours_raw'] ?? '1.5'), ENT_QUOTES, 'UTF-8');
        $num_attendees_raw = trim($_POST['num_attendees'] ?? '1');
        $num_attendees = filter_var($num_attendees_raw, FILTER_VALIDATE_INT);
        if ($num_attendees === false || $num_attendees < 1) $num_attendees = 1;
        $dietary_restrictions = htmlspecialchars(trim($_POST['dietary_restrictions'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        // Legg til i $formResult['data'] som før...
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
            // ... (databaseinnsetting som før) ...
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
                // ... (admin e-post som før) ...
                $adminMail = new PHPMailer(true);
                // (Konfigurer adminMail her)
                $adminMail->isSMTP(); $adminMail->Host = $smtpHost; $adminMail->SMTPAuth = true; $adminMail->Username = $smtpUsername; $adminMail->Password = $smtpPassword;
                $adminMail->SMTPSecure = ($smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                $adminMail->Port = $smtpPort; $adminMail->CharSet = PHPMailer::CHARSET_UTF8;
                $adminMail->setFrom($mailFromAddress, $mailFromName); $adminMail->addAddress($mailRecipientAddress); $adminMail->addReplyTo($email, $fullname); 
                $adminMail->isHTML(false); 
                $adminMail->Subject = 'Ny Påmelding: ' . $seminar_title . ' - ' . $fullname;
                $emailBodyToAdmin = "Ny påmelding til seminaret \"" . $seminar_title . "\":\n\n";
                // ... (resten av admin e-post body)
                $adminMail->Body = $emailBodyToAdmin;


                if ($adminMail->send()) {
                    $formResult['message'] = 'Takk for din påmelding til seminaret! Vi har sendt deg en bekreftelse på e-post med en kalenderinvitasjon.';
                    $formResult['success'] = true;

                    $receiptMail = new PHPMailer(true);
                    // ... (konfigurasjon for kvitteringsepost som før) ...
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

                    // Generer Google Kalender-lenke
                    $google_calendar_link = generate_google_calendar_link($seminar_ics_data);

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
                    $receiptBody .= "<p>En kalenderinvitasjon er lagt ved denne e-posten. Du kan også <a href=\"" . htmlspecialchars($google_calendar_link) . "\" target=\"_blank\">legge arrangementet direkte til i din Google Kalender her</a>.</p>";
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
                        error_log("Kunne ikke generere ICS for seminar: " . $seminar_slug);
                    }

                    try { 
                        $receiptMail->send(); 
                    } catch (Exception $e_receipt) { 
                        error_log("PHPMailer Exception (Receipt Seminar) for " . $email . ": " . $receiptMail->ErrorInfo . " | Exception: " . $e_receipt->getMessage());
                    }
                    $formResult['data'] = [];
                } else {
                    // Feil ved sending av admin e-post
                     $formResult['message'] = "Påmeldingen kunne ikke sendes (feil ved adminvarsel)."; error_log("PHPMailer send() (Admin Seminar) returned false without Exception for googleworkspace.akari.no");
                }
            }  catch (Exception $e) {
                // Generell PHPMailer feil for admin e-post
                $formResult['message'] = "Beklager, påmeldingen kunne ikke sendes (generell epostfeil). Kontakt oss direkte."; error_log("PHPMailer Exception (Admin Seminar) for googleworkspace.akari.no: " . ($adminMail->ErrorInfo ?? $e->getMessage()) );
            }
        } // end if empty($errors) for database
        
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
