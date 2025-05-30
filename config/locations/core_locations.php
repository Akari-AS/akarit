<?php
// Nøkkellokasjoner for Akari
return [
    'kongsberg' => [
        'name' => 'Kongsberg',
        'isHeadOffice' => true, 
        'heroText' => "Akari i Kongsberg hjelper din bedrift med Google Workspace for økt produktivitet, sømløst samarbeid og bunnsolid sikkerhet i Kongsberg-regionen. Som teknologibyens foretrukne IT-partner, forstår vi behovene til både innovative industribedrifter og lokale tjenesteytere i Kongsberg.",
        'metaDescription' => "Styrk bedriften i Kongsberg med Google Workspace. Akari, ditt lokale hovedkontor, tilbyr ekspertise og moderne skyløsninger.",
    ],
    'honefoss' => [
        'name' => 'Hønefoss',
        'isHeadOffice' => false,
        'heroText' => "Akari i Hønefoss leverer Google Workspace-løsninger som transformerer hvordan din bedrift jobber. Vi bistår virksomheter i hele Ringeriksregionen med å effektivisere hverdagen og styrke det digitale samarbeidet fra vårt kontor i Hønefoss.",
        'metaDescription' => "Google Workspace-ekspert i Hønefoss. Akari hjelper din bedrift med skyløsninger, AI-verktøy og lokal support i Ringerike.",
    ],
    'notodden' => [
        'name' => 'Notodden',
        'isHeadOffice' => false,
        'heroText' => "Optimaliser arbeidsdagen med Google Workspace fra Akari i Notodden. Vi tilbyr skreddersydde løsninger for bedrifter i Telemark, og fra Notodden hjelper vi deg å utnytte skyens fulle potensial.",
        'metaDescription' => "Din Google Workspace-partner i Notodden. Akari tilbyr support, implementering og AI-løsninger for bedrifter i Telemark.",
    ],
    'numedal' => [ 
        'name' => 'Numedal',
        'isHeadOffice' => false,
        'heroText' => "Fra Rollag til Rødberg – Akari bringer kraften i Google Workspace til bedrifter i hele Numedal. Vi er din lokale ekspert på skybasert samarbeid og effektive digitale verktøy for virksomheter i Numedal.",
        'metaDescription' => "Akari: Google Workspace-løsninger for bedrifter i Numedal. Øk effektiviteten med skybaserte verktøy og lokal support.",
    ],
    'larvik' => [
        'name' => 'Larvik',
        'isHeadOffice' => false,
        'heroText' => "Akari er klar til å hjelpe din Vestfold-bedrift med Google Workspace for fremtidens arbeidsplass, direkte fra Larvik. Opplev hvordan vi kan modernisere din drift med vår lokale tilstedeværelse i Larvik.",
        'metaDescription' => "Google Workspace i Larvik. Akari tilbyr implementering, support og AI-løsninger for bedrifter i Vestfold.",
    ],
    // Oslo er også en kjernelokasjon for mange, hvis den ikke er i denne filen, bør den kanskje være det?
    // Hvis Oslo er i en annen fil (f.eks. oslo_region.php) og du vil behandle den som en "core",
    // kan du vurdere å flytte den hit eller legge til 'isCore' => true e.l. og justere logikken.
    // For nå antar jeg at Oslo håndteres i sin egen fil og at listen over er de du definerer som "core" her.
];
