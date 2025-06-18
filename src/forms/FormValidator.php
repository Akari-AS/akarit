<?php
// src/forms/FormValidator.php

class FormValidator
{
    /**
     * Validate common form fields
     */
    public static function validateCommonFields($data)
    {
        $errors = [];
        
        $firstname = htmlspecialchars(trim($data['firstname'] ?? ''), ENT_QUOTES, 'UTF-8');
        $lastname = htmlspecialchars(trim($data['lastname'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars(trim($data['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $privacy_policy_agreed = isset($data['privacy']) && $data['privacy'] === 'on';
        
        if (empty($firstname)) {
            $errors[] = "Fornavn er påkrevd.";
        }
        if (empty($email)) {
            $errors[] = "E-post er påkrevd.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Vennligst oppgi en gyldig e-postadresse.";
        }
        if (empty($phone)) {
            $errors[] = "Telefonnummer er påkrevd.";
        }
        if (!$privacy_policy_agreed) {
            $errors[] = "Du må godta personvernerklæringen.";
        }
        
        return [
            'errors' => $errors,
            'validated_data' => [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'fullname' => trim($firstname . ' ' . $lastname),
                'email' => $email,
                'phone' => $phone,
                'privacy_agreed' => $privacy_policy_agreed
            ]
        ];
    }
    
    /**
     * Validate contact form specific fields
     */
    public static function validateContactForm($data)
    {
        $common = self::validateCommonFields($data);
        $errors = $common['errors'];
        $validated_data = $common['validated_data'];
        
        $company = htmlspecialchars(trim($data['company'] ?? ''), ENT_QUOTES, 'UTF-8');
        $package_interest = htmlspecialchars(trim($data['package_interest'] ?? ''), ENT_QUOTES, 'UTF-8');
        $message_content = htmlspecialchars(trim($data['message'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        if (empty($message_content)) {
            $errors[] = "Melding er påkrevd.";
        }
        
        $validated_data['company'] = $company;
        $validated_data['package_interest'] = $package_interest;
        $validated_data['message'] = $message_content;
        
        return [
            'errors' => $errors,
            'validated_data' => $validated_data
        ];
    }
    
    /**
     * Validate seminar registration form specific fields
     */
    public static function validateSeminarForm($data)
    {
        $common = self::validateCommonFields($data);
        $errors = $common['errors'];
        $validated_data = $common['validated_data'];
        
        $company = htmlspecialchars(trim($data['company'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_slug = htmlspecialchars(trim($data['seminar_slug'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_title = htmlspecialchars(trim($data['seminar_title'] ?? 'Ukjent seminar'), ENT_QUOTES, 'UTF-8');
        $seminar_datetime_raw = trim($data['seminar_datetime_raw'] ?? '');
        $seminar_location_raw = htmlspecialchars(trim($data['seminar_location_raw'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_excerpt_raw = htmlspecialchars(trim($data['seminar_excerpt_raw'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_contact_email_raw = htmlspecialchars(trim($data['seminar_contact_email_raw'] ?? ''), ENT_QUOTES, 'UTF-8');
        $seminar_duration_hours_raw = htmlspecialchars(trim($data['seminar_duration_hours_raw'] ?? '1.5'), ENT_QUOTES, 'UTF-8');
        
        $num_attendees_raw = trim($data['num_attendees'] ?? '1');
        $num_attendees = filter_var($num_attendees_raw, FILTER_VALIDATE_INT);
        if ($num_attendees === false || $num_attendees < 1) {
            $num_attendees = 1;
        }
        
        $dietary_restrictions = htmlspecialchars(trim($data['dietary_restrictions'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        if (empty($seminar_slug) || empty($seminar_title) || empty($seminar_datetime_raw)) {
            $errors[] = "Seminar informasjon mangler eller er ugyldig.";
        }
        
        $validated_data['company'] = $company;
        $validated_data['seminar_slug'] = $seminar_slug;
        $validated_data['seminar_title'] = $seminar_title;
        $validated_data['seminar_datetime_raw'] = $seminar_datetime_raw;
        $validated_data['seminar_location_raw'] = $seminar_location_raw;
        $validated_data['seminar_excerpt_raw'] = $seminar_excerpt_raw;
        $validated_data['seminar_contact_email_raw'] = $seminar_contact_email_raw;
        $validated_data['seminar_duration_hours_raw'] = $seminar_duration_hours_raw;
        $validated_data['num_attendees'] = $num_attendees;
        $validated_data['dietary_restrictions'] = $dietary_restrictions;
        
        return [
            'errors' => $errors,
            'validated_data' => $validated_data
        ];
    }
    
    /**
     * Validate server configuration
     */
    public static function validateServerConfig()
    {
        $errors = [];
        
        $smtpUsername = getenv('MAILGUN_SMTP_USERNAME');
        $smtpPassword = getenv('MAILGUN_SMTP_PASSWORD');
        $mailRecipientAddress = getenv('MAIL_RECIPIENT_ADDRESS');
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
        
        return $errors;
    }
} 