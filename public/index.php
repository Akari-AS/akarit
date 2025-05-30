<?php // index.php

// --------- AUTOLOADER & GRUNNLEGGENDE OPPSETT ---------
require __DIR__ . '/../vendor/autoload.php'; // For Parsedown

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
            return array_merge($frontMatter, ['content' => $htmlContent, 'slug' => $slug]);
        } else {
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
$formSourceOverride = null; 

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
    if ($articleData && isset($articleData['title'])) { 
        $pageTitle = htmlspecialchars($articleData['title']) . ' | Artikler | Akari';
        $pageDescription = htmlspecialchars($articleData['meta_description'] ?? $articleData['excerpt'] ?? $defaultMetaDescription);
        $formSourceOverride = "Artikkel: " . htmlspecialchars($articleData['title']); 
    } else {
        http_response_code(404);
        $pageTitle = "Artikkel ikke funnet | Akari";
        $pageDescription = $defaultMetaDescription;
        $articleData = null; 
        $formSourceOverride = "Artikkel: Ukjent (404)"; 
    }
} elseif ($pageType === 'article_listing') {
    $pageTitle = 'Artikler om Google Workspace | Akari';
    $pageDescription = 'Les våre siste artikler og innsikt om Google Workspace, AI, produktivitet og samarbeid.';
} else { 
    $basePageTitle = "Google Workspace Leverandør";
    if ($currentLocationName !== "Generell" && isset($currentLocationData)) {
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

// --------- ANDRE GLOBALE VARIABLER / DATA ---------
$youtubeVideoId = "AwwZMoYNK2o";
$workspaceToolsData = require __DIR__ . '/../config/workspace_tools_data.php';


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
} else { // Landing page
    require __DIR__ . '/../templates/sections/hjem.php';
    require __DIR__ . '/../templates/sections/fordeler.php';
    require __DIR__ . '/../templates/sections/produkter.php';
    require __DIR__ . '/../templates/sections/ai-funksjoner.php';
    require __DIR__ . '/../templates/sections/prispakker.php';
    require __DIR__ . '/../templates/sections/nrk-google-workspace.php'; // Inkluderer nå teaseren
    require __DIR__ . '/../templates/sections/hvorfor-oss.php';
    require __DIR__ . '/../templates/sections/kontakt.php';
}

require __DIR__ . '/../templates/footer.php';
?>
