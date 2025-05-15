<?php
// Fremprovosert endring
// Inkluder form håndtering KUN hvis det er en POST request
$formResult = ['message' => '', 'success' => false, 'data' => []];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Juster sti etter behov basert på serveroppsett
    $formResult = require __DIR__ . '/../src/form_handler.php';
}
$formMessage = $formResult['message'];
$formSuccess = $formResult['success'];
$submittedData = $formResult['data'];

// Hjelpefunksjon for å hente innsendt verdi (trengs av kontakt.php)
function old_value(string $key, array $data): string {
    return htmlspecialchars($data[$key] ?? '', ENT_QUOTES, 'UTF-8');
}

// --- DEFINER YOUTUBE VIDEO ID HER (trengs av hjem.php) ---
$youtubeVideoId = "AwwZMoYNK2o";

// Sidetittel og beskrivelse (trengs av header.php)
$pageTitle = 'Google Workspace for bedrifter | Akari'; // Endret
$pageDescription = 'Styrk bedriften med Google Workspace. Akari tilbyr ekspertise.'; // Forble lik, da den var i sentence case

// Data for Gemini AI-seksjonen (trengs av ai-funksjoner.php)
$workspaceToolsData = [
    [
        'id' => 'gmail',
        'name' => 'Gmail',
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/gmail_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Hjelp meg å skrive', // Forble lik
                'description' => 'Skriv og forbedre e-postene dine uten problemer, fra å finpusse tonen med et enkelt klikk til å generere nye utkast fra bunnen av.'
            ],
            [
                'title' => 'Oppsummer e-poster', // Forble lik
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
        'name' => 'Google Docs',
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/docs_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Gemini i Docs-sidepanelet', // Egennavn
                'description' => 'Oppsummer hovedpunktene i et langt dokument, lag en disposisjon for en salgspresentasjon, eller brainstorm ideer for en ny markedsføringskampanje. Poler enkelt dokumentene dine med skrive-, grammatikk- og formateringsforslag fra Gemini.'
            ],
            [
                'title' => 'Generer bilder', // Forble lik
                'description' => 'Lag unike innebygde bilder og fullformats forsidebilder direkte i dokumentet ditt for en rekke behov, som en reklamebrosjyre, en kampanjeoversikt, eller en restaurantmeny.'
            ],
            [
                'title' => 'Hjelp meg å skrive (med ledetekst)', // Forble lik
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
                'title' => 'Ta notater for meg i Meet', // Egennavn
                'description' => 'Ta møtenotater automatisk, organiser dem i Google Docs, og del dem med teamet ditt. De som kommer sent, kan oppdatere seg under møtet med "Sammendrag så langt".'
            ],
            [
                'title' => 'Adaptiv lyd', // Forble lik
                'description' => 'Adaptiv lyd lar team delta i møter fra flere bærbare datamaskiner i nærheten uten forstyrrende ekko eller tilbakekobling, noe som er nyttig når konferanserom eller møteutstyr er mangelvare.'
            ],
            [
                'title' => 'Oversatte tekstinger', // Forble lik
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
        'name' => 'Google Sheets',
        'imageUrl' => 'https://www.gstatic.com/images/branding/product/1x/sheets_2020q4_48dp.png',
        'features' => [
            [
                'title' => 'Gemini i Sheets-sidepanelet', // Egennavn
                'description' => 'Lag raskt tabeller (f.eks. en utgiftssporing), generer innsikt og visualiseringer basert på regnearkdata, og automatiser oppgaver ved hjelp av naturlig språk.'
            ],
            [
                'title' => 'Hjelp meg å organisere', // Forble lik
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
                'title' => 'Hjelp meg å lage et bilde', // Forble lik
                'description' => 'Med en enkel ledetekst kan du enkelt lage originale bilder for presentasjonene dine, som konsepter for digitale markedsføringskampanjer eller illustrasjoner for å forbedre forslaget til årsplanlegging.'
            ],
            [
                'title' => 'Gemini i Slides-sidepanelet', // Egennavn
                'description' => 'Generer raskt nye lysbilder (f.eks. en møteagenda), lag tilpassede bilder for presentasjonene dine, omskriv innhold for klarhet eller tone, og få hjelp til å oppsummere presentasjonen.'
            ]
        ]
    ],
];

// Inkluder header
require __DIR__ . '/../templates/header.php';

// Inkluder seksjoner
require __DIR__ . '/../templates/sections/hjem.php';
require __DIR__ . '/../templates/sections/fordeler.php';
require __DIR__ . '/../templates/sections/produkter.php';
require __DIR__ . '/../templates/sections/ai-funksjoner.php';
require __DIR__ . '/../templates/sections/prispakker.php';
require __DIR__ . '/../templates/sections/nrk-google-workspace.php';
require __DIR__ . '/../templates/sections/hvorfor-oss.php';
require __DIR__ . '/../templates/sections/kontakt.php';

// Inkluder footer
require __DIR__ . '/../templates/footer.php';
?>
