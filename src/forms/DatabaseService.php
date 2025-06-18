<?php
// src/forms/DatabaseService.php

class DatabaseService
{
    private static $pdo = null;
    
    /**
     * Get database connection
     */
    private static function getConnection()
    {
        if (self::$pdo === null) {
            $dbHost = getenv('DB_HOST') ?: '127.0.0.1';
            $dbPort = getenv('DB_PORT') ?: '3306';
            $dbName = getenv('DB_DATABASE');
            $dbUser = getenv('DB_USERNAME');
            $dbPass = getenv('DB_PASSWORD');
            
            try {
                $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];
                self::$pdo = new PDO($dsn, $dbUser, $dbPass, $options);
            } catch (\PDOException $e) {
                error_log("Database connection error for googleworkspace.akari.no: " . $e->getMessage());
                throw $e;
            }
        }
        
        return self::$pdo;
    }
    
    /**
     * Save contact form submission
     */
    public static function saveContactForm($data)
    {
        try {
            $pdo = self::getConnection();
            $sql = "INSERT INTO contact_form_submissions (firstname, lastname, company, email, phone, package_interest, message, privacy_agreed, form_source_location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['firstname'],
                $data['lastname'],
                $data['company'],
                $data['email'],
                $data['phone'],
                $data['package_interest'],
                $data['message'],
                $data['privacy_agreed'] ? 1 : 0,
                $data['form_source_location']
            ]);
            return true;
        } catch (\PDOException $e) {
            error_log("Database Error (contact_form_submissions) for googleworkspace.akari.no: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Save seminar registration
     */
    public static function saveSeminarRegistration($data)
    {
        try {
            $pdo = self::getConnection();
            $sql = "INSERT INTO seminar_registrations (seminar_slug, seminar_title, firstname, lastname, company, email, phone, num_attendees, dietary_restrictions, privacy_agreed, form_source_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['seminar_slug'],
                $data['seminar_title'],
                $data['firstname'],
                $data['lastname'],
                $data['company'],
                $data['email'],
                $data['phone'],
                $data['num_attendees'],
                $data['dietary_restrictions'],
                $data['privacy_agreed'] ? 1 : 0,
                $data['seminar_title']
            ]);
            return true;
        } catch (\PDOException $e) {
            error_log("Database Error (seminar_registrations) for googleworkspace.akari.no: " . $e->getMessage());
            return false;
        }
    }
} 