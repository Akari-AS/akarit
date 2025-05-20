<?php
// --------- HENT LOKASJONSDATA OG BESTEM AKTIV LOKASJON ---------
$allLocationsData = [];
$locationFilesPath = __DIR__ . '/../config/locations/'; 

if (is_dir($locationFilesPath)) {
    $locationFiles = glob($locationFilesPath . '*.php');
    if ($locationFiles === false) {
        error_log("Feil ved lesing av lokasjonsmappe: " . $locationFilesPath);
    } else {
        foreach ($locationFiles as $file) {
            $locationsInFile = require $file;
            if (is_array($locationsInFile)) {
                $allLocationsData = array_merge($allLocationsData, $locationsInFile);
            }
        }
    }
}

$requestedPath = '';
if (isset($_GET['path'])) {
    $requestedPath = trim($_GET['path'], '/');
} elseif (isset($_SERVER['REQUEST_URI'])) {
    $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestedPath = trim($uriPath, '/');
}

$pathSegments = explode('/', $requestedPath);
$locationSlug = strtolower($pathSegments[0] ?? '');

$currentLocationData = null;
$currentLocationName = "Generell"; 

if (!empty($locationSlug) && isset($allLocationsData[$locationSlug])) {
    $currentLocationData = $allLocationsData[$locationSlug];
    $currentLocationName = $currentLocationData['name'];
}

// --------- SETT LOKASJONSSPESIFIKKE VARIABLER (eller fallbacks) ---------
$defaultHeroText = "Som din dedikerte Google Workspace leverandør, hjelper Akari din bedrift med økt produktivitet, sømløst samarbeid og bunnsolid sikkerhet. La oss ta oss av det tekniske, så du kan fokusere på vekst.";
$defaultMetaDescription = "Akari er din erfarne Google Workspace leverandør. Vi tilbyr skreddersydde skyløsninger, implementering, support og AI-drevne verktøy for bedrifter i Norge.";

$locationSpecificHeroText = $currentLocationData['heroText'] ?? $defaultHeroText;
$locationSpecificMetaDescription = $currentLocationData['metaDescription'] ?? $defaultMetaDescription;

// --------- FELLES OPPSETT ---------
$formResult = ['message' => '', 'success' => false, 'data' => []];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formResult = require __DIR__ . '/../src/form_handler.php';
}
$formMessage = $formResult['message'];
$formSuccess = $formResult['success'];
$submittedData = $formResult['data'];

function old_value(string $key, array $data): string {
    return htmlspecialchars($data[$key] ?? '', ENT_QUOTES, 'UTF-8');
}

$youtubeVideoId = "AwwZMoYNK2o";

// Oppdatert sidetittel-logikk
$basePageTitle = "Google Workspace Leverandør";
if ($currentLocationName !== "Generell") {
    $pageTitle = $basePageTitle . ' i ' . htmlspecialchars($currentLocationName) . ' | Akari';
} else {
    $pageTitle = $basePageTitle . ' for Bedrifter | Akari';
}
$pageDescription = $locationSpecificMetaDescription;


$workspaceToolsData = [ // Denne forblir som før, jeg forkorter den her for lesbarhet
    ['id' => 'gmail', /* ... resten av data ... */],
    // ... (alle dine workspaceToolsData-elementer)
];

// --------- INKLUDER MALER ---------
require __DIR__ . '/../templates/header.php'; 

require __DIR__ . '/../templates/sections/hjem.php';
require __DIR__ . '/../templates/sections/fordeler.php';
require __DIR__ . '/../templates/sections/produkter.php';
require __DIR__ . '/../templates/sections/ai-funksjoner.php';
require __DIR__ . '/../templates/sections/prispakker.php';
require __DIR__ . '/../templates/sections/nrk-google-workspace.php';
require __DIR__ . '/../templates/sections/hvorfor-oss.php';
require __DIR__ . '/../templates/sections/kontakt.php';

require __DIR__ . '/../templates/footer.php';
?>
