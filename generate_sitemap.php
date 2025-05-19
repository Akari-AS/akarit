<?php

$baseUrl = "https://googleworkspace.akari.no/";
$today = date('Y-m-d');
$locationsData = require __DIR__ . '/config/locations.php'; // Hent lokasjonsdata

$urls = [];

// Hovedside (generell - googleworkspace.akari.no/)
$urls[] = ['loc' => '', 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => $today];

// Lokasjonssider (f.eks. googleworkspace.akari.no/kongsberg/)
foreach (array_keys($locationsData) as $slug) {
    $urls[] = ['loc' => $slug . '/', 'changefreq' => 'monthly', 'priority' => '0.9', 'lastmod' => $today];
}

// Ankerlenker. Disse vil peke til ankerne på hovedsiden/generell side.
// Hvis du vil ha ankerlenker for *hver* lokasjonsside, må du utvide logikken her.
$anchorSections = [
    ['path' => '#fordeler', 'priority' => '0.8'],
    ['path' => '#produkter', 'priority' => '0.8'],
    ['path' => '#ai-funksjoner', 'priority' => '0.8'],
    ['path' => '#prispakker', 'priority' => '0.7'],
    ['path' => '#nrk-google-workspace', 'priority' => '0.7'],
    ['path' => '#hvorfor-oss', 'priority' => '0.7'],
    ['path' => '#kontakt', 'priority' => '0.6'],
];

foreach ($anchorSections as $section) {
    // Legger til ankerlenker kun for hovedsiden (baseUrl uten spesifikk lokasjonsslug)
    $urls[] = ['loc' => $section['path'], 'changefreq' => 'monthly', 'priority' => $section['priority'], 'lastmod' => $today];

    // Valgfritt: Hvis du vil at ankerlenker skal listes for hver lokasjonsside også:
    // foreach (array_keys($locationsData) as $slug) {
    //     $urls[] = ['loc' => $slug . '/' . $section['path'], 'changefreq' => 'monthly', 'priority' => $section['priority'] - 0.1, 'lastmod' => $today];
    // }
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
