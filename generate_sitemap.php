<?php

$baseUrl = "https://googleworkspace.akari.no/";
$today = date('Y-m-d');

// Slå sammen lokasjonsdata
$allLocationsData = [];
$locationFilesPath = __DIR__ . '/config/locations/';
if (is_dir($locationFilesPath)) {
    $locationFiles = glob($locationFilesPath . '*.php');
    if ($locationFiles !== false) {
        foreach ($locationFiles as $file) {
            $locationsInFile = require $file;
            if (is_array($locationsInFile)) {
                $allLocationsData = array_merge($allLocationsData, $locationsInFile);
            }
        }
    }
}

$urls = [];

// Hovedside (generell)
$urls[] = ['loc' => '', 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => $today];

// Lokasjonssider
foreach (array_keys($allLocationsData) as $slug) {
    $urls[] = ['loc' => $slug . '/', 'changefreq' => 'monthly', 'priority' => '0.9', 'lastmod' => $today];
}

// Ankerseksjoner på forsiden
$anchorSections = [
    ['path' => '#fordeler', 'priority' => '0.8'],
    ['path' => '#produkter', 'priority' => '0.8'],
    ['path' => '#ai-funksjoner', 'priority' => '0.8'],
    ['path' => '#prispakker', 'priority' => '0.7'],
    ['path' => '#seminarer-teaser', 'priority' => '0.7'], // ID for seminar teaser seksjonen
    ['path' => '#nrk-google-workspace-teaser', 'priority' => '0.7'], // ID for NRK teaser seksjonen
    ['path' => '#hvorfor-oss', 'priority' => '0.7'],
    ['path' => '#kontakt', 'priority' => '0.6'],
];
foreach ($anchorSections as $section) {
     $urls[] = ['loc' => $section['path'], 'changefreq' => 'monthly', 'priority' => $section['priority'], 'lastmod' => $today];
}

// Artikkel-listeside
$urls[] = ['loc' => 'artikler/', 'changefreq' => 'weekly', 'priority' => '0.9', 'lastmod' => $today];

// Seminar-listeside
$urls[] = ['loc' => 'seminarer/', 'changefreq' => 'weekly', 'priority' => '0.9', 'lastmod' => $today];


// Lokasjonslisteside
$urls[] = ['loc' => 'lokasjoner/', 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => $today];

// Hjelpefunksjon for å parse frontmatter (forenklet versjon for sitemap)
function parse_front_matter_for_sitemap($rawFrontMatter) {
    $frontMatter = [];
    $lines = explode("\n", trim($rawFrontMatter));
    foreach ($lines as $line) {
        if(strpos($line, ':') !== false) { 
            list($key, $value) = explode(':', $line, 2);
            $frontMatter[trim($key)] = trim(trim($value), "'\"");
        }
    }
    return $frontMatter;
}


// Hent artikler for sitemap
$articleContentPath = __DIR__ . '/content/articles/';
$markdownArticleFiles = glob($articleContentPath . '*.md');
if ($markdownArticleFiles !== false) {
    foreach ($markdownArticleFiles as $articleFile) {
        $articleContent = file_get_contents($articleFile);
        if (preg_match('/^---\s*$(.*?)^---\s*$/ms', $articleContent, $matches)) {
            $articleFrontMatter = parse_front_matter_for_sitemap($matches[1]);
            if (isset($articleFrontMatter['slug'])) {
                $articleLastMod = isset($articleFrontMatter['date']) ? date('Y-m-d', strtotime($articleFrontMatter['date'])) : $today;
                $urls[] = [
                    'loc' => 'artikler/' . $articleFrontMatter['slug'] . '/',
                    'changefreq' => 'monthly',
                    'priority' => '0.8',
                    'lastmod' => $articleLastMod
                ];
            }
        }
    }
}

// Hent seminarer for sitemap
$seminarContentPath = __DIR__ . '/content/seminars/';
$markdownSeminarFiles = glob($seminarContentPath . '*.md');
if ($markdownSeminarFiles !== false) {
    foreach ($markdownSeminarFiles as $seminarFile) {
        $seminarContent = file_get_contents($seminarFile);
        if (preg_match('/^---\s*$(.*?)^---\s*$/ms', $seminarContent, $matches)) {
            $seminarFrontMatter = parse_front_matter_for_sitemap($matches[1]);
            if (isset($seminarFrontMatter['slug'])) {
                $seminarLastMod = isset($seminarFrontMatter['date']) ? date('Y-m-d', strtotime($seminarFrontMatter['date'])) : $today;
                $urls[] = [
                    'loc' => 'seminarer/' . $seminarFrontMatter['slug'] . '/',
                    'changefreq' => 'monthly', // Seminarer kan endre seg sjeldnere enn artikler
                    'priority' => '0.8',
                    'lastmod' => $seminarLastMod
                ];
            }
        }
    }
}


$xmlOutput = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xmlOutput .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

foreach ($urls as $urlData) {
    $xmlOutput .= '  <url>' . PHP_EOL;
    $xmlOutput .= '    <loc>' . htmlspecialchars($baseUrl . $urlData['loc']) . '</loc>' . PHP_EOL;
    $xmlOutput .= '    <lastmod>' . $urlData['lastmod'] . '</lastmod>' . PHP_EOL;
    $xmlOutput .= '    <changefreq>' . $urlData['changefreq'] . '</changefreq>' . PHP_EOL;
    $xmlOutput .= '    <priority>' . $urlData['priority'] . '</priority>' . PHP_EOL;
    $xmlOutput .= '  </url>' . PHP_EOL;
}

$xmlOutput .= '</urlset>' . PHP_EOL;

$sitemapPath = __DIR__ . '/public/sitemap.xml';
if (file_put_contents($sitemapPath, $xmlOutput)) {
    echo "Sitemap generert og lagret til: " . $sitemapPath . PHP_EOL;
} else {
    echo "Feil: Kunne ikke lagre sitemap til: " . $sitemapPath . PHP_EOL;
}
?>
