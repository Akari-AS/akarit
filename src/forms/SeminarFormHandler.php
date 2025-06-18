<?php
// src/forms/SeminarFormHandler.php

class SeminarFormHandler
{
    /**
     * Process seminar registration form submission
     */
    public static function process($data)
    {
        $formResult = [
            'message' => '',
            'success' => false,
            'data' => []
        ];
        
        // Validate form data
        $validation = FormValidator::validateSeminarForm($data);
        $errors = $validation['errors'];
        $validated_data = $validation['validated_data'];
        
        // Store form data for potential re-display
        $formResult['data'] = [
            'firstname' => $validated_data['firstname'],
            'lastname' => $validated_data['lastname'],
            'email' => $validated_data['email'],
            'company' => $validated_data['company'],
            'phone' => $validated_data['phone'],
            'privacy' => $validated_data['privacy_agreed'] ? 'on' : '',
            'seminar_slug' => $validated_data['seminar_slug'],
            'seminar_title' => $validated_data['seminar_title'],
            'num_attendees' => $validated_data['num_attendees'],
            'dietary_restrictions' => $validated_data['dietary_restrictions'],
            'seminar_datetime_raw' => $validated_data['seminar_datetime_raw'],
            'seminar_location_raw' => $validated_data['seminar_location_raw'],
            'seminar_excerpt_raw' => $validated_data['seminar_excerpt_raw'],
            'seminar_contact_email_raw' => $validated_data['seminar_contact_email_raw'],
            'seminar_duration_hours_raw' => $validated_data['seminar_duration_hours_raw']
        ];
        
        // Check for validation errors
        if (!empty($errors)) {
            $formResult['message'] = implode(" ", $errors);
            $formResult['success'] = false;
            return $formResult;
        }
        
        // Validate server configuration
        $config_errors = FormValidator::validateServerConfig();
        if (!empty($config_errors)) {
            $formResult['message'] = implode(" ", $config_errors);
            $formResult['success'] = false;
            return $formResult;
        }
        
        // Save to database
        $db_success = DatabaseService::saveSeminarRegistration($validated_data);
        if (!$db_success) {
            $errors[] = "En intern feil oppstod under lagring av påmeldingen. Vennligst prøv igjen senere eller kontakt oss.";
            $formResult['message'] = implode(" ", $errors);
            $formResult['success'] = false;
            return $formResult;
        }
        
        // Send email to admin
        $admin_email_success = MailService::sendSeminarRegistrationToAdmin($validated_data);
        
        if ($admin_email_success) {
            $formResult['message'] = 'Takk for din påmelding til seminaret! Vi har sendt deg en bekreftelse på e-post med en kalenderinvitasjon.';
            $formResult['success'] = true;
            
            // Generate ICS content for calendar invitation
            $seminar_ics_data = [
                'slug' => $validated_data['seminar_slug'],
                'title' => $validated_data['seminar_title'],
                'datetime_raw' => $validated_data['seminar_datetime_raw'],
                'location_raw' => $validated_data['seminar_location_raw'],
                'excerpt_raw' => $validated_data['seminar_excerpt_raw'],
                'contact_email_raw' => $validated_data['seminar_contact_email_raw'],
                'duration_hours_raw' => $validated_data['seminar_duration_hours_raw']
            ];
            
            $attendee_ics_data = [
                'fullname' => $validated_data['fullname'],
                'email' => $validated_data['email']
            ];
            
            $ics_content = CalendarHelper::generateIcsContent($seminar_ics_data, $attendee_ics_data);
            if (!$ics_content) {
                error_log("Kunne ikke generere ICS for seminar: " . $validated_data['seminar_slug']);
            }
            
            // Send receipt to user with ICS attachment
            MailService::sendSeminarRegistrationReceipt($validated_data, $ics_content);
            
            // Clear form data on success
            $formResult['data'] = [];
        } else {
            $formResult['message'] = "Beklager, påmeldingen kunne ikke sendes (generell epostfeil). Kontakt oss direkte.";
            $formResult['success'] = false;
        }
        
        return $formResult;
    }
} 