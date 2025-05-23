<?php // index.php

// --------- AUTOLOADER & GRUNNLEGGENDE OPPSETT ---------
require __DIR__ . '/../vendor/autoload.php'; // For Parsedown
use Parsedown; // For Markdown-parser

// --------- LOKASJONSDATA ---------
$allLocationsData = [];
$locationFilesPath = __DIR__ . '/../config/locations/';
if (is_dir($locationFilesPath)) {
    $locationFiles = glob($locationFilesPath . '*.php');
    if ($locationFiles !== false) {
        foreach ($locationFiles as $file) {
            $locationsInFile = require $file;
            if (is_array($locationsInFile)) {
                $allLocationsData = array_merge($allLocationsData, $locationsInFile);
            }
        }
    } else {
        error_log("Feil ved lesing av lokasjonsmappe: " . $locationFilesPath);
    }
}

// --------- ARTIKKELFUNKSJONER ---------
function parse_front_matter($rawFrontMatter) {
    $frontMatter = [];
    $lines = explode("\n", trim($rawFrontMatter));
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, ':') === false) {
            continue; 
        }
        list($key, $value) = explode(':', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if ((substr($value, 0, 1) == '"' && substr($value, -1) == '"') ||
            (substr($value, 0, 1) == "'" && substr($value, -1) == "'")) {
            $value = substr($value, 1, -1);
        }
        $frontMatter[$key] = $value;
    }
    return $frontMatter;
}

function get_article_data($slug) {
    $filePath = __DIR__ . '/../content/articles/' . $slug . '.md';
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $parsedown = new Parsedown();
        
        if (preg_match('/^---\s*$(.*?)^---\s*$(.*)/ms', $content, $matches)) {
            $frontMatter = parse_front_matter($matches[1]);
            $htmlContent = $parsedown->text(trim($matches[2]));
            return array_merge($frontMatter, ['content' => $htmlContent, 'slug' => $slug]); // Legg til slug her også
        } else {
            // Fallback hvis ingen front-matter, eller for å feilsøke
             error_log("Kunne ikke parse front-matter for artikkel: " . $slug);
             return ['title' => 'Artikkel uten tittel', 'content' => $parsedown->text($content), 'slug' => $slug];
        }
    }
    error_log("Artikkelfil ikke funnet: " . $filePath);
    return null;
}

function get_all_articles_metadata() {
    $articles = [];
    $articleFilesPath = __DIR__ . '/../content/articles/';
    $markdownFiles = glob($articleFilesPath . '*.md');
    if ($markdownFiles === false) return [];

    foreach ($markdownFiles as $file) {
        $content = file_get_contents($file);
        if (preg_match('/^---\s*$(.*?)^---\s*$/ms', $content, $matches)) {
            $frontMatter = parse_front_matter($matches[1]);
            // Legg til filnavn-basert slug hvis 'slug' mangler i front-matter, for robusthet
            if (!isset($frontMatter['slug'])) {
                $frontMatter['slug'] = pathinfo($file, PATHINFO_FILENAME);
            }
            $articles[] = $frontMatter;
        }
    }
    usort($articles, function($a, $b) {
        $dateA = isset($a['date']) ? strtotime($a['date']) : 0;
        $dateB = isset($b['date']) ? strtotime($b['date']) : 0;
        return $dateB <=> $dateA;
    });
    return $articles;
}


// --------- ROUTING LOGIKK ---------
$requestedPath = '';
if (isset($_GET['path'])) {
    $requestedPath = trim($_GET['path'], '/');
} elseif (isset($_SERVER['REQUEST_URI'])) {
    $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestedPath = trim($uriPath, '/');
}

$pathSegments = explode('/', $requestedPath);
$pageType = 'landingpage'; 
$currentLocationSlug = strtolower($pathSegments[0] ?? ''); 
$articleSlug = null;
$articleData = null; 

if ($currentLocationSlug === 'artikler') {
    if (isset($pathSegments[1]) && !empty($pathSegments[1])) {
        $pageType = 'article_single';
        $articleSlug = $pathSegments[1];
        $currentLocationSlug = ''; 
    } else {
        $pageType = 'article_listing';
        $currentLocationSlug = ''; 
    }
}

// --------- LOKASJONSSPESIFIKK DATA (hvis det er en landingsside for lokasjon) ---------
$currentLocationData = null;
$currentLocationName = "Generell";
if ($pageType === 'landingpage' && !empty($currentLocationSlug) && isset($allLocationsData[$currentLocationSlug])) {
    $currentLocationData = $allLocationsData[$currentLocationSlug];
    $currentLocationName = $currentLocationData['name'];
} elseif ($pageType === 'landingpage' && empty($currentLocationSlug)) {
    $currentLocationName = "Generell";
}

// --------- SIDETITTEL OG METABESKRIVELSE ---------
$defaultHeroText = "Som din dedikerte Google Workspace leverandør, hjelper Akari din bedrift med økt produktivitet, sømløst samarbeid og bunnsolid sikkerhet. La oss ta oss av det tekniske, så du kan fokusere på vekst.";
$defaultMetaDescription = "Akari er din erfarne Google Workspace leverandør. Vi tilbyr skreddersydde skyløsninger, implementering, support og AI-drevne verktøy for bedrifter i Norge.";
$locationSpecificHeroText = ($pageType === 'landingpage' && isset($currentLocationData['heroText'])) ? $currentLocationData['heroText'] : $defaultHeroText;

if ($pageType === 'article_single' && $articleSlug) {
    $articleData = get_article_data($articleSlug); 
    if ($articleData && isset($articleData['title'])) { // Sjekk om title er satt
        $pageTitle = htmlspecialchars($articleData['title']) . ' | Artikler | Akari';
        $pageDescription = htmlspecialchars($articleData['meta_description'] ?? $articleData['excerpt'] ?? $defaultMetaDescription);
    } else {
        http_response_code(404);
        $pageTitle = "Artikkel ikke funnet | Akari";
        $pageDescription = $defaultMetaDescription;
        $articleData = null; // Sørg for at $articleData er null hvis artikkelen ikke finnes
    }
} elseif ($pageType === 'article_listing') {
    $pageTitle = 'Artikler om Google Workspace | Akari';
    $pageDescription = 'Les våre siste artikler og innsikt om Google Workspace, AI, produktivitet og samarbeid.';
} else { 
    $basePageTitle = "Google Workspace Leverandør";
    if ($currentLocationName !== "Generell") {
        $pageTitle = $basePageTitle . ' i ' . htmlspecialchars($currentLocationName) . ' | Akari';
        $pageDescription = $currentLocationData['metaDescription'] ?? $defaultMetaDescription;
    } else {
        $pageTitle = $basePageTitle . ' for Bedrifter | Akari';
        $pageDescription = $defaultMetaDescription;
    }
}

// --------- SKJEMAHÅNDTERING (hvis POST) ---------
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

$workspaceToolsData = [
    [
        'id' => 'gmail',
        'name' => 'Gmail',
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/gmail_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Hjelp meg å skrive',
                'description' => 'Skriv og forbedre e-postene dine uten problemer, fra å finpusse tonen med et enkelt klikk til å generere nye utkast fra bunnen av.'
            ],
            [
                'title' => 'Oppsummer e-poster',
                'description' => 'Få et sammendrag direkte fra en e-postmelding eller e-posttråd, som fremhever nøkkelpunkter og sparer deg tid.'
            ],
            [
                'title' => 'Gemini i Gmail-sidepanelet',
                'description' => 'Lag utkast til e-postsvar, still spørsmål til e-postene dine (f.eks. "Oppdater meg på e-poster om Prosjekt Alfa"), og oppsummer lange e-poster og tråder.'
            ]
        ]
    ],
    [
        'id' => 'docs',
        'name' => 'Google Docs',
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/docs_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Gemini i Docs-sidepanelet',
                'description' => 'Oppsummer hovedpunktene i et langt dokument, lag en disposisjon for en salgspresentasjon, eller brainstorm ideer for en ny markedsføringskampanje. Poler enkelt dokumentene dine med skrive-, grammatikk- og formateringsforslag fra Gemini.'
            ],
            [
                'title' => 'Generer bilder',
                'description' => 'Lag unike innebygde bilder og fullformats forsidebilder direkte i dokumentet ditt for en rekke behov, som en reklamebrosjyre, en kampanjeoversikt, eller en restaurantmeny.'
            ],
            [
                'title' => 'Hjelp meg å skrive (med ledetekst)',
                'description' => 'Bare legg til en ledetekst, som "Lag en prosjektoversikt, inkludert forsknings-, design-, test- og produksjonsfaser", og et utkast vil umiddelbart bli generert.'
            ],
        ]
    ],
    [
        'id' => 'meet',
        'name' => 'Google Meet',
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/meet_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Ta notater for meg i Meet',
                'description' => 'Ta møtenotater automatisk, organiser dem i Google Docs, og del dem med teamet ditt. De som kommer sent, kan oppdatere seg under møtet med "Sammendrag så langt".'
            ],
            [
                'title' => 'Adaptiv lyd',
                'description' => 'Adaptiv lyd lar team delta i møter fra flere bærbare datamaskiner i nærheten uten forstyrrende ekko eller tilbakekobling, noe som er nyttig når konferanserom eller møteutstyr er mangelvare.'
            ],
            [
                'title' => 'Oversatte tekstinger',
                'description' => 'Oversatte tekstinger gir sanntidsoversettelser av talerens språk, noe som bidrar til å gjøre møter mer inkluderende og effektive for globale team.'
            ]
        ]
    ],
    [
        'id' => 'drive',
        'name' => 'Google Drive',
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/drive_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Gemini i Drive-sidepanelet',
                'description' => 'Oppsummer flere dokumenter, generer innsikt om et spesifikt emne, få hjelp til å finne filer raskere, og mer, direkte fra Drive-grensesnittet.'
            ],
            [
                'title' => 'Jobb med PDF-er i Drive',
                'description' => 'Gemini kan oppsummere lange PDF-filer, generere innsikt fra innholdet, eller bruke PDF-en som grunnlag for å lage noe nytt, som en studieplan eller et e-postutkast.'
            ],
        ]
    ],
    [
        'id' => 'sheets',
        'name' => 'Google Sheets',
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/sheets_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Gemini i Sheets-sidepanelet',
                'description' => 'Lag raskt tabeller (f.eks. en utgiftssporing), generer innsikt og visualiseringer basert på regnearkdata, og automatiser oppgaver ved hjelp av naturlig språk.'
            ],
            [
                'title' => 'Hjelp meg å organisere',
                'description' => 'Få hjelp til å lage maler og strukturere dataene dine. For eksempel, be om en "prosjektplanlegger med kolonner for oppgave, ansvarlig, tidsfrist og status".'
            ]
        ]
    ],
    [
        'id' => 'slides',
        'name' => 'Google Slides',
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/slides_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Hjelp meg å lage et bilde',
                'description' => 'Med en enkel ledetekst kan du enkelt lage originale bilder for presentasjonene dine, som konsepter for digitale markedsføringskampanjer eller illustrasjoner for å forbedre forslaget til årsplanlegging.'
            ],
            [
                'title' => 'Gemini i Slides-sidepanelet',
                'description' => 'Generer raskt nye lysbilder (f.eks. en møteagenda), lag tilpassede bilder for presentasjonene dine, omskriv innhold for klarhet eller tone, og få hjelp til å oppsummere presentasjonen.'
            ]
        ]
    ],
];

// --------- INKLUDER MALER BASERT PÅ PAGETYPE ---------
require __DIR__ . '/../templates/header.php'; 

if ($pageType === 'article_single') {
    if ($articleData) { 
        require __DIR__ . '/../templates/article_single.php';
    } else {
        echo "<main><div class='container' style='padding: 50px 20px; text-align: center;'><h2>404 - Artikkel ikke funnet</h2><p>Beklager, vi fant ikke artikkelen du lette etter.</p><p><a href='/artikler/' class='cta-button'>Se alle artikler</a> <a href='/' class='cta-button secondary'>Til forsiden</a></p></div></main>";
    }
} elseif ($pageType === 'article_listing') {
    $allArticles = get_all_articles_metadata(); 
    require __DIR__ . '/../templates/article_listing.php';
} else { 
    require __DIR__ . '/../templates/sections/hjem.php';
    require __DIR__ . '/../templates/sections/fordeler.php';
    require __DIR__ . '/../templates/sections/produkter.php';
    require __DIR__ . '/../templates/sections/ai-funksjoner.php';
    require __DIR__ . '/../templates/sections/prispakker.php';
    require __DIR__ . '/../templates/sections/nrk-google-workspace.php';
    require __DIR__ . '/../templates/sections/hvorfor-oss.php';
    require __DIR__ . '/../templates/sections/kontakt.php';
}

require __DIR__ . '/../templates/footer.php';
?>
