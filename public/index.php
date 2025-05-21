<?php
// ... (eksisterende PHP-kode øverst forblir uendret) ...

// --------- SETT LOKASJONSSPESIFIKKE VARIABLER (eller fallbacks) ---------
$defaultHeroText = "Som din dedikerte Google Workspace leverandør, hjelper Akari din bedrift med økt produktivitet, sømløst samarbeid og bunnsolid sikkerhet. La oss ta oss av det tekniske, så du kan fokusere på vekst.";
$defaultMetaDescription = "Akari er din erfarne Google Workspace leverandør. Vi tilbyr skreddersydde skyløsninger, implementering, support og AI-drevne verktøy for bedrifter i Norge.";

$locationSpecificHeroText = $currentLocationData['heroText'] ?? $defaultHeroText;
$locationSpecificMetaDescription = $currentLocationData['metaDescription'] ?? $defaultMetaDescription;

// ... (resten av $formResult, old_value, $youtubeVideoId forblir uendret) ...

// Oppdatert sidetittel-logikk (denne var allerede i sentence case for stedsnavn)
$basePageTitle = "Google Workspace leverandør"; // Endret til sentence case
if ($currentLocationName !== "Generell") {
    $pageTitle = $basePageTitle . ' i ' . htmlspecialchars($currentLocationName) . ' | Akari';
} else {
    $pageTitle = $basePageTitle . ' for bedrifter | Akari'; // Endret
}
$pageDescription = $locationSpecificMetaDescription; // Denne hentes fra config, pass på sentence case der


$workspaceToolsData = [
    [
        'id' => 'gmail',
        'name' => 'Gmail', // Egennavn
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
                'title' => 'Gemini i Gmail-sidepanelet', // Egennavn
                'description' => 'Lag utkast til e-postsvar, still spørsmål til e-postene dine (f.eks. "Oppdater meg på e-poster om Prosjekt Alfa"), og oppsummer lange e-poster og tråder.'
            ]
        ]
    ],
    [
        'id' => 'docs',
        'name' => 'Google Docs', // Egennavn
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/docs_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Gemini i Docs-sidepanelet', // Egennavn
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
        'name' => 'Google Meet', // Egennavn
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/meet_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Ta notater for meg i Meet', // Egennavn
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
        'name' => 'Google Drive', // Egennavn
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/drive_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Gemini i Drive-sidepanelet', // Egennavn
                'description' => 'Oppsummer flere dokumenter, generer innsikt om et spesifikt emne, få hjelp til å finne filer raskere, og mer, direkte fra Drive-grensesnittet.'
            ],
            [
                'title' => 'Jobb med PDF-er i Drive', // Egennavn
                'description' => 'Gemini kan oppsummere lange PDF-filer, generere innsikt fra innholdet, eller bruke PDF-en som grunnlag for å lage noe nytt, som en studieplan eller et e-postutkast.'
            ],
        ]
    ],
    [
        'id' => 'sheets',
        'name' => 'Google Sheets', // Egennavn
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/sheets_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Gemini i Sheets-sidepanelet', // Egennavn
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
        'name' => 'Google Slides', // Egennavn
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/slides_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Hjelp meg å lage et bilde',
                'description' => 'Med en enkel ledetekst kan du enkelt lage originale bilder for presentasjonene dine, som konsepter for digitale markedsføringskampanjer eller illustrasjoner for å forbedre forslaget til årsplanlegging.'
            ],
            [
                'title' => 'Gemini i Slides-sidepanelet', // Egennavn
                'description' => 'Generer raskt nye lysbilder (f.eks. en møteagenda), lag tilpassede bilder for presentasjonene dine, omskriv innhold for klarhet eller tone, og få hjelp til å oppsummere presentasjonen.'
            ]
        ]
    ],
];

// --------- INKLUDER MALER ---------
require __DIR__ . '/../templates/header.php'; 

require __DIR__ . '/../templates/sections/hjem.php';
require __DIR__ . '/../templates/sections/fordeler.php';
require __DIR__ . '/../templates/sections/produkter.php';
require __DIR__ . '/../templates/sections/ai-funksjoner.php'; // Denne filen bruker $workspaceToolsData
require __DIR__ . '/../templates/sections/prispakker.php';
require __DIR__ . '/../templates/sections/nrk-google-workspace.php';
require __DIR__ . '/../templates/sections/hvorfor-oss.php';
require __DIR__ . '/../templates/sections/kontakt.php';

require __DIR__ . '/../templates/footer.php';
?>
