<?php
// src/forms/ContactFormHandler.php

class ContactFormHandler
{
    /**
     * Process contact form submission
     */
    public static function process($data)
    {
        $formResult = [
            'message' => '',
            'success' => false,
            'data' => []
        ];
        
        // Validate form data
        $validation = FormValidator::validateContactForm($data);
        $errors = $validation['errors'];
        $validated_data = $validation['validated_data'];
        
        // Add form source location
        $validated_data['form_source_location'] = htmlspecialchars(trim($data['form_source_location'] ?? 'Ukjent kilde'), ENT_QUOTES, 'UTF-8');
        
        // Store form data for potential re-display
        $formResult['data'] = [
            'firstname' => $validated_data['firstname'],
            'lastname' => $validated_data['lastname'],
            'email' => $validated_data['email'],
            'company' => $validated_data['company'],
            'phone' => $validated_data['phone'],
            'privacy' => $validated_data['privacy_agreed'] ? 'on' : '',
            'package_interest' => $validated_data['package_interest'],
            'message' => $validated_data['message']
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
        $db_success = DatabaseService::saveContactForm($validated_data);
        
        // Send email to admin
        $admin_email_success = MailService::sendContactFormToAdmin($validated_data);
        
        if ($admin_email_success) {
            $formResult['message'] = 'Takk! Meldingen din er sendt. Vi har også sendt deg en bekreftelse på e-post.';
            $formResult['success'] = true;
            
            // Send receipt to user
            MailService::sendContactFormReceipt($validated_data);
            
            // Clear form data on success
            $formResult['data'] = [];
        } else {
            $formResult['message'] = "Beklager, meldingen kunne ikke sendes. Kontakt oss direkte.";
            $formResult['success'] = false;
        }
        
        return $formResult;
    }
} 