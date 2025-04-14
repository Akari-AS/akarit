<?php
// Denne filen håndterer KUN logikken og returnerer status/melding
// Den bør IKKE produsere HTML output selv.

// Standardverdier
$formMessage = '';
$formSuccess = false;
$submittedData = []; // For å sende tilbake data ved feil

// Sjekk om metoden er POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // <<<--- VIKTIG: Sett din e-postadresse for mottak her! ---<<<
    $recipientEmail = "din-salgs-epost@akarit.no";
    $emailSubject = "Google Workspace Henvendelse fra Akarit.no";

    // Hent og rens data (Behold data for pre-filling ved feil)
    $name = filter_var(trim($_POST['name'] ?? ''), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $company = filter_var(trim($_POST['company'] ?? ''), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($_POST['message'] ?? ''), FILTER_SANITIZE_STRING);

    $submittedData = [
        'name' => $name,
        'email' => $email,
        'company' => $company,
        'message' => $message,
    ];

    // Validering
    if (empty($name) || empty($email) || empty($message)) {
        $formMessage = "Vennligst fyll ut alle obligatoriske felt (Navn, E-post, Melding).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formMessage = "Vennligst oppgi en gyldig e-postadresse.";
        // Bevar feilaktig e-post for korrigering
        $submittedData['email'] = $_POST['email'] ?? '';
    } else {
        // --- Forbered E-post ---
        $emailBody = "Ny Google Workspace henvendelse:\n\n";
        $emailBody .= "Navn: " . $name . "\n";
        $emailBody .= "Firma: " . (!empty($company) ? $company : "Ikke oppgitt") . "\n";
        $emailBody .= "E-post: " . $email . "\n";
        $emailBody .= "Melding:\n" . $message . "\n";

        $headers = "From: " . $name . " <" . $email . ">\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // --- Prøv å sende E-post ---
        // Merk: mail()-funksjonens suksess avhenger sterkt av serverkonfigurasjon.
        if (mail($recipientEmail, $emailSubject, $emailBody, $headers)) {
            $formMessage = "Takk! Meldingen din er sendt. Vi tar kontakt med deg snart.";
            $formSuccess = true;
            // Tøm innsendt data ved suksess
            $submittedData = [];
        } else {
            $formMessage = "Beklager, det oppstod en feil under sending av meldingen. Prøv igjen senere eller kontakt oss direkte via e-post.";
        }
    }
}

// Funksjon for å hente innsendt data (eller tomt)
function getSubmittedValue(string $key, array $data): string {
    return htmlspecialchars($data[$key] ?? '', ENT_QUOTES, 'UTF-8');
}

// Returner resultatet slik at index.php kan bruke det
return [
    'message' => $formMessage,
    'success' => $formSuccess,
    'data' => $submittedData,
];

?>
