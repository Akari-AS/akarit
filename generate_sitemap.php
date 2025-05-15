<?php // generate_sitemap.php

$baseUrl = "https://googleworkspace.akari.no/";
$today = date('Y-m-d');

$urls = [
    ['loc' => '', 'changefreq' => 'weekly', 'priority' => '1.0'],
    ['loc' => '#fordeler', 'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => '#produkter', 'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => '#ai-funksjoner', 'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => '#prispakker', 'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '#nrk-google-workspace', 'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '#hvorfor-oss', 'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '#kontakt', 'changefreq' => 'yearly', 'priority' => '0.6'],
];

$xmlOutput = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xmlOutput .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

foreach ($urls as $urlData) {
    $xmlOutput .= '  <url>' . PHP_EOL;
    $xmlOutput .= '    <loc>' . htmlspecialchars($baseUrl . $urlData['loc']) . '</loc>' . PHP_EOL;
    $xmlOutput .= '    <lastmod>' . $today . '</lastmod>' . PHP_EOL;
    $xmlOutput .= '    <changefreq>' . $urlData['changefreq'] . '</changefreq>' . PHP_EOL;
    $xmlOutput .= '    <priority>' . $urlData['priority'] . '</priority>' . PHP_EOL;
    $xmlOutput .= '  </url>' . PHP_EOL;
}

$xmlOutput .= '</urlset>' . PHP_EOL;

// Lagre til public-mappen
$sitemapPath = __DIR__ . '/public/sitemap.xml';
if (file_put_contents($sitemapPath, $xmlOutput)) {
    echo "Sitemap generert og lagret til: " . $sitemapPath . PHP_EOL;
} else {
    echo "Feil: Kunne ikke lagre sitemap til: " . $sitemapPath . PHP_EOL;
}
?>
