<?php // index.php

// --------- AUTOLOADER & GRUNNLEGGENDE OPPSETT ---------
require __DIR__ . '/../vendor/autoload.php'; 

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

// --------- FUNKSJONER (Artikler & Seminarer) ---------
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

function get_content_data($slug, $contentType = 'article') {
    $folder = ($contentType === 'seminar') ? 'seminars' : 'articles';
    $filePath = __DIR__ . '/../content/' . $folder . '/' . $slug . '.md';

    // --- START AGGRESSIV DEBUG ---
    echo "<div style='border:2px solid red; padding:10px; margin:10px; background-color: #fee; color: black; z-index: 9999; position:relative;'>";
    echo "<strong>DEBUG: get_content_data KALT</strong><br>";
    echo "Slug: <code style='background:#f0f0f0; padding:2px;'>" . htmlspecialchars($slug) . "</code><br>";
    echo "ContentType: <code style='background:#f0f0f0; padding:2px;'>" . htmlspecialchars($contentType) . "</code><br>";
    echo "Forventet Mappe: <code style='background:#f0f0f0; padding:2px;'>" . htmlspecialchars($folder) . "</code><br>";
    echo "Kalkulert Filsti: <code style='background:#f0f0f0; padding:2px;'>" . htmlspecialchars($filePath) . "</code><br>";
    // --- END AGGRESSIV DEBUG ---

    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $parsedown = new Parsedown();
        
        if (preg_match('/^---\s*$(.*?)^---\s*$(.*)/ms', $content, $matches)) {
            $frontMatter = parse_front_matter($matches[1]);
            $markdown_content_body = trim($matches[2]); 
            
            // --- DETALJERT DEBUG FOR SPESIFIKK ARTIKKEL ---
            if ($contentType === 'article') { // Gjelder alle artikler nå for å se input
                echo "DEBUG: Rå markdown_content_body (før Parsedown) - Lengde: " . strlen($markdown_content_body) . "<br>";
                echo "<textarea style='width:95%; height: 150px; border:1px solid #ccc;'>" . htmlspecialchars($markdown_content_body) . "</textarea><br>";
            }
            // --- SLUTT DETALJERT DEBUG ---
            
            $htmlContent = $parsedown->text($markdown_content_body);

             // --- DEBUG HTML CONTENT ---
            if ($contentType === 'article') {
                echo "DEBUG: HTML Content (etter Parsedown) - Lengde: " . strlen($htmlContent) . "<br>";
                echo "<textarea style='width:95%; height: 150px; border:1px solid #ccc;'>" . htmlspecialchars($htmlContent) . "</textarea><br>";
            }
            // --- END DEBUG ---
            
            echo "</div>"; // Lukk debug-div
            return array_merge($frontMatter, ['content' => $htmlContent, 'slug' => $slug]);
        } else {
             error_log("Kunne ikke parse front-matter for {$contentType}: " . $slug . ". Regex feilet. Filinnhold starter med: " . substr(htmlspecialchars($content), 0, 200)); // htmlspecialchars her også
             $htmlContentOnly = $parsedown->text($content);
             echo "DEBUG: Kunne ikke parse front-matter. Hele filen blir behandlet som markdown.<br>";
             echo "</div>"; // Lukk debug-div
             return ['title' => ucfirst($contentType) . ' uten formatert tittel', 'content' => $htmlContentOnly, 'slug' => $slug, 'error_parsing_frontmatter' => true];
        }
    }
    error_log(ucfirst($contentType) . "fil ikke funnet: " . $filePath);
    echo "DEBUG: Filen ble ikke funnet på sti: " . htmlspecialchars($filePath) . "<br>"; 
    echo "</div>"; // Lukk debug-div
    return null;
}

function get_all_content_metadata($contentType = 'article') {
    $items = [];
    $folder = ($contentType === 'seminar') ? 'seminars' : 'articles';
    $contentFilesPath = __DIR__ . '/../content/' . $folder . '/';
    $markdownFiles = glob($contentFilesPath . '*.md');
    if ($markdownFiles === false) return [];

    foreach ($markdownFiles as $file) {
        $content = file_get_contents($file);
        if (preg_match('/^---\s*$(.*?)^---\s*$/ms', $content, $matches)) {
            $frontMatter = parse_front_matter($matches[1]);
            if (!isset($frontMatter['slug'])) {
                $frontMatter['slug'] = pathinfo($file, PATHINFO_FILENAME);
            }
            if ($contentType === 'seminar' && isset($frontMatter['date'])) {
                $frontMatter['timestamp'] = strtotime($frontMatter['date']);
            }
            $items[] = $frontMatter;
        }
    }
    usort($items, function($a, $b) use ($contentType) {
        if ($contentType === 'seminar') {
            $dateA = $a['timestamp'] ?? 0;
            $dateB = $b['timestamp'] ?? 0;
            return $dateA <=> $dateB;
        } else { 
            $dateA = isset($a['date']) ? strtotime($a['date']) : 0;
            $dateB = isset($b['date']) ? strtotime($b['date']) : 0;
            return $dateB <=> $dateA;
        }
    });
    return $items;
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
$contentSlug = null; 
$contentData = null;  
$formSourceOverride = null; 

if ($currentLocationSlug === 'artikler') {
    if (isset($pathSegments[1]) && !empty($pathSegments[1])) {
        $pageType = 'article_single';
        $contentSlug = $pathSegments[1];
    } else {
        $pageType = 'article_listing';
    }
    $currentLocationSlug = ''; 
} elseif ($currentLocationSlug === 'seminarer') {
    if (isset($pathSegments[1]) && !empty($pathSegments[1])) {
        $pageType = 'seminar_single';
        $contentSlug = $pathSegments[1];
    } else {
        $pageType = 'seminar_listing';
    }
    $currentLocationSlug = '';
} elseif ($currentLocationSlug === 'lokasjoner') {
    $pageType = 'location_listing';
    $currentLocationSlug = ''; 
}


// --------- LOKASJONSSPESIFIKK DATA & FORBEREDELSE FOR LISTING ---------
$currentLocationData = null;
$currentLocationName = "Generell"; 
$coreLocations = []; 
$regionalLocations = []; 

if ($pageType === 'landingpage' && !empty($currentLocationSlug) && isset($allLocationsData[$currentLocationSlug])) {
    $currentLocationData = $allLocationsData[$currentLocationSlug];
    $currentLocationName = $currentLocationData['name'];
} elseif ($pageType === 'landingpage' && empty($currentLocationSlug)) {
    $currentLocationName = "Generell";
}

if ($pageType === 'location_listing') {
    $coreLocationsDataFromFile = require __DIR__ . '/../config/locations/core_locations.php';
    foreach ($coreLocationsDataFromFile as $slug => $data) {
        $coreLocations[$slug] = $data;
    }
    $locationFiles = glob(__DIR__ . '/../config/locations/*.php');
    if ($locationFiles !== false) {
        foreach ($locationFiles as $file) {
            $fileName = basename($file, '.php');
            if ($fileName === 'core_locations') continue; 
            $regionName = ucfirst(str_replace(['_region', '_'], ['', ' '], $fileName)); 
            $locationsInFile = require $file;
            if (is_array($locationsInFile)) {
                $tempRegionLocations = [];
                foreach($locationsInFile as $slug => $data) {
                    if (!array_key_exists($slug, $coreLocations)) {
                         $tempRegionLocations[$data['name']] = array_merge($data, ['slug' => $slug]);
                    }
                }
                if (!empty($tempRegionLocations)) {
                    ksort($tempRegionLocations); 
                    $regionalLocations[$regionName] = $tempRegionLocations;
                }
            }
        }
        ksort($regionalLocations); 
    }
}

// --------- SIDETITTEL OG METABESKRIVELSE ---------
$defaultHeroText = "Som din dedikerte Google Workspace leverandør, hjelper Akari din bedrift med økt produktivitet, sømløst samarbeid og bunnsolid sikkerhet. La oss ta oss av det tekniske, så du kan fokusere på vekst.";
$defaultMetaDescription = "Akari er din erfarne Google Workspace leverandør. Vi tilbyr skreddersydde skyløsninger, implementering, support og AI-drevne verktøy for bedrifter i Norge.";
$locationSpecificHeroText = ($pageType === 'landingpage' && isset($currentLocationData['heroText'])) ? $currentLocationData['heroText'] : $defaultHeroText;

if ($pageType === 'article_single' && $contentSlug) {
    $contentData = get_content_data($contentSlug, 'article'); 
    if ($contentData && isset($contentData['title']) && !empty(trim($contentData['content'] ?? ''))) { // Sjekk også at content ikke er tomt
        $pageTitle = htmlspecialchars($contentData['title']) . ' | Artikler | Akari';
        $pageDescription = htmlspecialchars($contentData['meta_description'] ?? $contentData['excerpt'] ?? $defaultMetaDescription);
        $formSourceOverride = "Artikkel: " . htmlspecialchars($contentData['title']); 
    } else {
        http_response_code(404);
        $pageTitle = "Artikkel ikke funnet | Akari";
        $pageDescription = $defaultMetaDescription;
        $contentData = null; 
        $formSourceOverride = "Artikkel: Ukjent (404)"; 
    }
} elseif ($pageType === 'article_listing') {
    $pageTitle = 'Artikler om Google Workspace | Akari';
    $pageDescription = 'Les våre siste artikler og innsikt om Google Workspace, AI, produktivitet og samarbeid.';
} elseif ($pageType === 'seminar_single' && $contentSlug) {
    $contentData = get_content_data($contentSlug, 'seminar');
    if ($contentData && isset($contentData['title']) && !empty(trim($contentData['content'] ?? ''))) {
        $pageTitle = htmlspecialchars($contentData['title']) . ' | Seminar | Akari';
        $pageDescription = htmlspecialchars($contentData['meta_description'] ?? $contentData['excerpt'] ?? 'Delta på vårt seminar: ' . ($contentData['title'] ?? '') . '. Lær mer og meld deg på!');
    } else {
        http_response_code(404);
        $pageTitle = "Seminar ikke funnet | Akari";
        $pageDescription = $defaultMetaDescription;
        $contentData = null;
    }
} elseif ($pageType === 'seminar_listing') {
    $pageTitle = 'Kommende Seminarer | Akari';
    $pageDescription = 'Se oversikt over våre kommende frokostseminarer og arrangementer. Meld deg på for faglig påfyll!';
} elseif ($pageType === 'location_listing') { 
    $pageTitle = 'Våre lokasjoner | Akari Google Workspace';
    $pageDescription = 'Oversikt over Akaris kontorer og dekningsområder for Google Workspace-tjenester i Norge.';
    $currentLocationName = "Lokasjoner"; 
} else { // Landing page
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

function old_value(string $key, array $data, $default = ''): string {
    return htmlspecialchars($data[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}

// --------- ANDRE GLOBALE VARIABLER / DATA ---------
$youtubeVideoId = "AwwZMoYNK2o";
$workspaceToolsData = require __DIR__ . '/../config/workspace_tools_data.php';


// --------- INKLUDER MALER BASERT PÅ PAGETYPE ---------
require __DIR__ . '/../templates/header.php'; 

if ($pageType === 'article_single') {
    if ($contentData && isset($contentData['content']) && !empty(trim($contentData['content']))) { 
        require __DIR__ . '/../templates/article_single.php';
    } else {
        http_response_code(404);
        echo "<main><div class='container' style='padding: 50px 20px; text-align: center;'><h2>404 - Artikkel ikke funnet</h2><p>Beklager, vi fant ikke artikkelen du lette etter. Teknisk info: Innhold for artikkelen '<strong>" . htmlspecialchars($contentSlug ?? 'Ukjent') . "</strong>' kunne ikke lastes korrekt.</p><p><a href='/artikler/' class='cta-button'>Se alle artikler</a> <a href='/' class='cta-button secondary'>Til forsiden</a></p></div></main>";
    }
} elseif ($pageType === 'article_listing') {
    $allArticles = get_all_content_metadata('article'); 
    require __DIR__ . '/../templates/article_listing.php';
} elseif ($pageType === 'seminar_single') {
    if ($contentData && isset($contentData['content']) && !empty(trim($contentData['content']))) {
        require __DIR__ . '/../templates/seminar_single.php';
    } else {
         http_response_code(404);
         echo "<main><div class='container' style='padding: 50px 20px; text-align: center;'><h2>404 - Seminar ikke funnet</h2><p>Beklager, vi fant ikke seminaret du lette etter. Teknisk info: Innhold for seminaret '<strong>" . htmlspecialchars($contentSlug ?? 'Ukjent') . "</strong>' kunne ikke lastes korrekt.</p><p><a href='/seminarer/' class='cta-button'>Se alle seminarer</a> <a href='/' class='cta-button secondary'>Til forsiden</a></p></div></main>";
    }
} elseif ($pageType === 'seminar_listing') {
    $allSeminars = get_all_content_metadata('seminar');
    require __DIR__ . '/../templates/seminar_listing.php';
} elseif ($pageType === 'location_listing') { 
    require __DIR__ . '/../templates/location_listing.php';
} else { // Landing page
    require __DIR__ . '/../templates/sections/hjem.php';
    require __DIR__ . '/../templates/sections/fordeler.php';
    require __DIR__ . '/../templates/sections/produkter.php';
    require __DIR__ . '/../templates/sections/ai-funksjoner.php';
    require __DIR__ . '/../templates/sections/prispakker.php';
    require __DIR__ . '/../templates/sections/seminar_teaser.php';
    require __DIR__ . '/../templates/sections/nrk-google-workspace.php';
    require __DIR__ . '/../templates/sections/hvorfor-oss.php';
    require __DIR__ . '/../templates/sections/kontakt.php';
}

require __DIR__ . '/../templates/footer.php';
?>
