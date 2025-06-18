<?php
// src/forms/CalendarHelper.php

class CalendarHelper
{
    /**
     * Generate ICS content for calendar invitation
     */
    public static function generateIcsContent($seminar_data, $attendee_data)
    {
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

        $summary = self::escapeIcsText($seminar_data['title'] ?? 'Seminar');
        $description = self::escapeIcsText($seminar_data['excerpt_raw'] ?? 'Påmelding til Akari seminar.');
        $location = self::escapeIcsText($seminar_data['location_raw'] ?? 'Akari');
        $attendee_name_esc = self::escapeIcsText($attendee_data['fullname'] ?? 'Deltaker');
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

    /**
     * Generate Google Calendar link
     */
    public static function generateGoogleCalendarLink($seminar_data)
    {
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
            'text' => $seminar_data['title'] ?? 'Seminar',
            'dates' => $dtstart_google . '/' . $dtend_google,
            'details' => $seminar_data['excerpt_raw'] ?? 'Påmelding til Akari seminar.',
            'location' => $seminar_data['location_raw'] ?? 'Akari',
            'ctz' => 'Europe/Oslo'
        ];
        
        return $base_url . '&' . http_build_query($params);
    }

    /**
     * Escape text for ICS format
     */
    private static function escapeIcsText($text)
    {
        return str_replace(["\r\n", "\n", "\r", ",", ";"], ["\\n", "\\n", "\\n", "\\,", "\\;"], $text);
    }
} 