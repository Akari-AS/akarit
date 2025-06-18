<?php
// src/forms/FormHandler.php

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

// Include all form handler classes
require __DIR__ . '/CalendarHelper.php';
require __DIR__ . '/FormValidator.php';
require __DIR__ . '/DatabaseService.php';
require __DIR__ . '/MailService.php';
require __DIR__ . '/ContactFormHandler.php';
require __DIR__ . '/SeminarFormHandler.php';

class FormHandler
{
    /**
     * Main form processing method
     */
    public static function processForm()
    {
        $formResult = [
            'message' => '',
            'success' => false,
            'data' => []
        ];

        if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
            $form_type = $_POST['form_type'] ?? 'contact';
            
            switch ($form_type) {
                case 'contact':
                    $formResult = ContactFormHandler::process($_POST);
                    break;
                    
                case 'seminar_registration':
                    $formResult = SeminarFormHandler::process($_POST);
                    break;
                    
                default:
                    $formResult['message'] = "Ugyldig skjematype.";
                    $formResult['success'] = false;
                    break;
            }
        }
        
        return $formResult;
    }
}

// Process the form and return result
return FormHandler::processForm(); 