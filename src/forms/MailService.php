<?php
// src/forms/MailService.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private static $smtpHost;
    private static $smtpPort;
    private static $smtpUsername;
    private static $smtpPassword;
    private static $mailFromAddress;
    private static $mailFromName;
    private static $mailRecipientAddress;
    
    /**
     * Initialize mail configuration
     */
    private static function init()
    {
        if (self::$smtpHost === null) {
            self::$smtpHost = getenv('MAILGUN_SMTP_HOST') ?: 'smtp.eu.mailgun.org';
            self::$smtpPort = getenv('MAILGUN_SMTP_PORT') ?: 587;
            self::$smtpUsername = getenv('MAILGUN_SMTP_USERNAME');
            self::$smtpPassword = getenv('MAILGUN_SMTP_PASSWORD');
            self::$mailFromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'googleworkspace@akari.no';
            self::$mailFromName = getenv('MAIL_FROM_NAME') ?: 'Akari Google Workspace';
            self::$mailRecipientAddress = getenv('MAIL_RECIPIENT_ADDRESS');
        }
    }
    
    /**
     * Configure PHPMailer instance
     */
    private static function configureMailer($mail)
    {
        self::init();
        
        $mail->isSMTP();
        $mail->Host = self::$smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = self::$smtpUsername;
        $mail->Password = self::$smtpPassword;
        $mail->SMTPSecure = (self::$smtpPort == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = self::$smtpPort;
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->setFrom(self::$mailFromAddress, self::$mailFromName);
    }
    
    /**
     * Send contact form notification to admin
     */
    public static function sendContactFormToAdmin($data)
    {
        try {
            $mail = new PHPMailer(true);
            self::configureMailer($mail);
            
            $mail->addAddress(self::$mailRecipientAddress);
            $mail->addReplyTo($data['email'], $data['fullname']);
            $mail->isHTML(false);
            $mail->Subject = 'Henvendelse via googleworkspace.akari.no (' . $data['form_source_location'] . ') - ' . $data['fullname'];
            
            $emailBody = "Ny henvendelse fra googleworkspace.akari.no:\n\n";
            $emailBody .= "Kilde: " . $data['form_source_location'] . "\n";
            $emailBody .= "Navn: " . $data['fullname'] . "\n";
            $emailBody .= "Firma: " . (!empty($data['company']) ? $data['company'] : "Ikke oppgitt") . "\n";
            $emailBody .= "E-post: " . $data['email'] . "\n";
            $emailBody .= "Telefon: " . $data['phone'] . "\n";
            $emailBody .= "Interessert i pakke: " . (!empty($data['package_interest']) ? $data['package_interest'] : "Ikke spesifisert") . "\n\n";
            $emailBody .= "Melding:\n" . $data['message'] . "\n\n";
            $emailBody .= "Personvern godtatt: " . ($data['privacy_agreed'] ? "Ja" : "Nei") . "\n";
            
            $mail->Body = $emailBody;
            
            return $mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer Exception (Admin Contact) for googleworkspace.akari.no: " . ($mail->ErrorInfo ?? $e->getMessage()));
            return false;
        }
    }
    
    /**
     * Send contact form receipt to user
     */
    public static function sendContactFormReceipt($data)
    {
        try {
            $mail = new PHPMailer(true);
            self::configureMailer($mail);
            
            $mail->addAddress($data['email'], $data['fullname']);
            $mail->isHTML(true);
            $mail->Subject = 'Takk for din henvendelse til Akari!';
            
            $greetingName = !empty($data['firstname']) ? $data['firstname'] : 'du';
            $sourceInfo = ($data['form_source_location'] !== 'Generell' && $data['form_source_location'] !== 'Ukjent kilde' && strpos($data['form_source_location'], 'Artikkel:') === false) ? " for " . htmlspecialchars($data['form_source_location']) : "";
            
            $receiptBody = "<p>Hei " . htmlspecialchars($greetingName) . ",</p>";
            $receiptBody .= "<p>Takk for din henvendelse til Akari" . $sourceInfo . ". Vi har mottatt meldingen din og vil komme tilbake til deg så snart som mulig.</p>";
            $receiptBody .= "<p>Dette er en automatisk bekreftelse, du trenger ikke svare på denne e-posten.</p>";
            
            if (!empty($data['message'])) {
                $receiptBody .= "<p><strong>Din melding var:</strong></p>";
                $receiptBody .= "<blockquote style='border-left: 2px solid #ccc; padding-left: 10px; margin-left: 0; font-style: italic;'>" . nl2br(htmlspecialchars($data['message'])) . "</blockquote>";
            }
            
            $receiptBody .= "<p>Vennlig hilsen,<br>Team Akari</p>";
            
            $mail->Body = $receiptBody;
            $mail->AltBody = strip_tags(str_replace(["<br>","<br/>","<br />"], "\n", str_replace(["</p><p>","</p>"], "\n\n", $receiptBody)));
            
            return $mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer Exception (Receipt Contact) for " . $data['email'] . ": " . ($mail->ErrorInfo ?? $e->getMessage()));
            return false;
        }
    }
    
    /**
     * Send seminar registration notification to admin
     */
    public static function sendSeminarRegistrationToAdmin($data)
    {
        try {
            $mail = new PHPMailer(true);
            self::configureMailer($mail);
            
            $mail->addAddress(self::$mailRecipientAddress);
            $mail->addReplyTo($data['email'], $data['fullname']);
            $mail->isHTML(false);
            $mail->Subject = 'Ny Påmelding: ' . $data['seminar_title'] . ' - ' . $data['fullname'];
            
            $emailBody = "Ny påmelding til seminaret \"" . $data['seminar_title'] . "\":\n\n";
            $emailBody .= "Seminar ID (Slug): " . $data['seminar_slug'] . "\n";
            $emailBody .= "Navn: " . $data['fullname'] . "\n";
            $emailBody .= "Firma: " . (!empty($data['company']) ? $data['company'] : "Ikke oppgitt") . "\n";
            $emailBody .= "E-post: " . $data['email'] . "\n";
            $emailBody .= "Telefon: " . $data['phone'] . "\n";
            $emailBody .= "Antall deltakere: " . $data['num_attendees'] . "\n";
            $emailBody .= "Kommentar: " . (!empty($data['dietary_restrictions']) ? $data['dietary_restrictions'] : "Ingen") . "\n\n";
            $emailBody .= "Personvern godtatt: " . ($data['privacy_agreed'] ? "Ja" : "Nei") . "\n";
            
            $mail->Body = $emailBody;
            
            return $mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer Exception (Admin Seminar) for googleworkspace.akari.no: " . ($mail->ErrorInfo ?? $e->getMessage()));
            return false;
        }
    }
    
    /**
     * Send seminar registration receipt to user
     */
    public static function sendSeminarRegistrationReceipt($data, $ics_content = null)
    {
        try {
            $mail = new PHPMailer(true);
            self::configureMailer($mail);
            
            $mail->addAddress($data['email'], $data['fullname']);
            $mail->isHTML(true);
            $mail->Subject = 'Bekreftelse på påmelding: ' . $data['seminar_title'];
            
            $greetingName = !empty($data['firstname']) ? $data['firstname'] : 'du';
            
            $receiptBody = "<p>Hei " . htmlspecialchars($greetingName) . ",</p>";
            $receiptBody .= "<p>Takk for din påmelding til seminaret: <strong>" . htmlspecialchars($data['seminar_title']) . "</strong>.</p>";
            $receiptBody .= "<p>Vi har registrert din påmelding";
            if ($data['num_attendees'] > 1) {
                $receiptBody .= " for " . htmlspecialchars($data['num_attendees']) . " personer";
            }
            $receiptBody .= ".</p>";
            
            if (!empty($data['dietary_restrictions'])) {
                $receiptBody .= "<p>Kommentar registrert: " . nl2br(htmlspecialchars($data['dietary_restrictions'])) . "</p>";
            }
            
            $receiptBody .= "<p>En kalenderinvitasjon (.ics-fil) er lagt ved denne e-posten. ";
            
            // Generate Google Calendar link
            $seminar_data = [
                'slug' => $data['seminar_slug'],
                'title' => $data['seminar_title'],
                'datetime_raw' => $data['seminar_datetime_raw'],
                'location_raw' => $data['seminar_location_raw'],
                'excerpt_raw' => $data['seminar_excerpt_raw'],
                'contact_email_raw' => $data['seminar_contact_email_raw'],
                'duration_hours_raw' => $data['seminar_duration_hours_raw']
            ];
            
            $google_calendar_link = CalendarHelper::generateGoogleCalendarLink($seminar_data);
            if ($google_calendar_link !== '#') {
                $receiptBody .= "Du kan også <a href=\"" . htmlspecialchars($google_calendar_link) . "\" target=\"_blank\" rel=\"noopener noreferrer\">legge arrangementet direkte til i din Google Kalender her</a>.";
            }
            $receiptBody .= "</p>";
            $receiptBody .= "<p>Mer informasjon om seminaret vil bli sendt ut nærmere datoen. Vi gleder oss til å se deg!</p>";
            $receiptBody .= "<p>Dette er en automatisk bekreftelse, du trenger ikke svare på denne e-posten.</p>";
            $receiptBody .= "<p>Vennlig hilsen,<br>Team Akari</p>";
            
            $mail->Body = $receiptBody;
            $mail->AltBody = strip_tags(str_replace(["<br>","<br/>","<br />"], "\n", str_replace(["</p><p>","</p>"], "\n\n", $receiptBody)));
            
            // Add ICS attachment if provided
            if ($ics_content) {
                $ics_filename = preg_replace('/[^a-z0-9_\-\.]/i', '_', $data['seminar_slug']) . '.ics';
                $mail->addStringAttachment($ics_content, $ics_filename, 'base64', 'text/calendar; charset=utf-8; method=REQUEST');
            }
            
            return $mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer Exception (Receipt Seminar) for " . $data['email'] . ": " . ($mail->ErrorInfo ?? $e->getMessage()));
            return false;
        }
    }
} 