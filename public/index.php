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

// Hjelpefunksjon for å hente innsendt verdi
function old_value(string $key, array $data): string {
    return htmlspecialchars($data[$key] ?? '', ENT_QUOTES, 'UTF-8');
}

// --- DEFINER YOUTUBE VIDEO ID HER ---
$youtubeVideoId = "AwwZMoYNK2o"; // <--- DENNE LINJEN MANGLER I DIN KODE

// Sidetittel og beskrivelse
$pageTitle = 'Google Workspace for Bedrifter | Akarit';
$pageDescription = 'Styrk bedriften med Google Workspace. Akarit tilbyr ekspertise.';

// Data for Gemini AI-seksjonen
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


// Inkluder header
require __DIR__ . '/../templates/header.php';
?>

<!-- ================================== -->
<!-- SIDEINNHOLD STARTER HER -->
<!-- ================================== -->

<!-- Hero Seksjon -->
<section class="hero" id="hjem">
    <!-- Video Background Container -->
    <div class="video-background-container">
        <iframe
            src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtubeVideoId, ENT_QUOTES, 'UTF-8'); ?>?autoplay=1&mute=1&loop=1&controls=0&showinfo=0&modestbranding=1&rel=0&playlist=<?php echo htmlspecialchars($youtubeVideoId, ENT_QUOTES, 'UTF-8'); ?>&playsinline=1&enablejsapi=1"
            frameborder="0"
            allow="autoplay; encrypted-media"
            title="Akarit Bakgrunnsvideo">
        </iframe>
    </div>
    <!-- Hero Content -->
    <div class="container"> <!-- Container er sentrert av CSS -->
         <h1>Fremtiden er Samarbeid med <span>Google Workspace</span></h1>
         <p>Akarit hjelper din bedrift med å utnytte kraften i Google Workspace for økt produktivitet, sømløst samarbeid og bunnsolid sikkerhet. La oss ta oss av det tekniske, så du kan fokusere på vekst.</p>
         <div> <?php // Ekstra div for knapper kan hjelpe med mobil-styling ?>
            <a href="#kontakt" class="cta-button">Start Samtalen</a>
            <a href="#produkter" class="cta-button secondary">Se Verktøyene</a>
         </div>
    </div>
</section>

<!-- Fordeler Seksjon -->
<section id="fordeler">
    <div class="container">
        <h2>Hvorfor Velge Google Workspace?</h2>
        <p>Oppdag fordelene med en moderne, skybasert plattform for kommunikasjon og samarbeid.</p>
        <div class="features-grid">
            <!-- Kort 1 -->
            <div class="feature-item">
                <img src="https://ssl.gstatic.com/images/icons/material/system/2x/group_work_black_48dp.png" alt="Samarbeidsikon">
                <h3>Sømløst Samarbeid</h3>
                <p>Jobb sammen i sanntid på dokumenter, regneark og presentasjoner. Del enkelt filer og kommuniser effektivt med integrert chat og videomøter.</p>
            </div>
            <!-- Kort 2 -->
            <div class="feature-item">
                <img src="https://ssl.gstatic.com/images/icons/material/system/2x/trending_up_black_48dp.png" alt="Produktivitetsikon">
                <h3>Økt Produktivitet</h3>
                <p>Få profesjonell e-post (@dittfirma.no), smart kalender, rikelig med skylagring og kraftige verktøy som effektiviserer arbeidsdagen.</p>
            </div>
            <!-- Kort 3 -->
            <div class="feature-item">
                <img src="https://ssl.gstatic.com/images/icons/material/system/2x/security_black_48dp.png" alt="Sikkerhetsikon">
                <h3>Sikkerhet i Fokus</h3>
                <p>Dra nytte av Googles ledende sikkerhetsteknologi med avansert trusselbeskyttelse, datakontroll og garantert oppetid.</p>
            </div>
        </div>
    </div>
</section>

<!-- Produkter Seksjon -->
<section id="produkter" class="products-section">
    <div class="container">
        <h2>Verktøyene Som Driver Vekst</h2>
        <p>En komplett pakke med applikasjoner designet for moderne bedrifter.</p>
        <div class="products-grid">
            <!-- Produktkort -->
            <div class="product-card">
                 <img src="https://ssl.gstatic.com/images/branding/product/1x/gmail_48dp.png" alt="Gmail Ikon">
                <h3>Gmail</h3>
                <p>Profesjonell, sikker og reklamefri e-post med ditt eget domene (@dittfirma.no). Kraftig søk og integrasjon.</p>
            </div>
             <div class="product-card">
                 <img src="https://ssl.gstatic.com/images/branding/product/1x/drive_48dp.png" alt="Drive Ikon">
                <h3>Drive</h3>
                <p>Trygg skylagring for alle filer. Enkel deling, tilgang overalt og samarbeid med Delte Disker.</p>
            </div>
             <div class="product-card">
                 <img src="https://ssl.gstatic.com/images/branding/product/1x/calendar_48dp.png" alt="Calendar Ikon">
                <h3>Kalender</h3>
                <p>Smarte, delte kalendere som gjør møtebooking og planlegging enkelt.</p>
            </div>
             <div class="product-card">
                 <img src="https://ssl.gstatic.com/images/branding/product/1x/meet_48dp.png" alt="Meet Ikon">
                <h3>Meet</h3>
                <p>Pålitelige og sikre videomøter for team, kunder og partnere. Enkelt og effektivt.</p>
            </div>
            <div class="product-card">
                 <img src="https://ssl.gstatic.com/images/branding/product/1x/chat_48dp.png" alt="Chat Ikon">
                <h3>Chat</h3>
                <p>Direktemeldinger og gruppechat for rask kommunikasjon og samarbeid.</p>
            </div>
            <div class="product-card">
                 <div style="margin-bottom: 20px;">
                    <img src="https://ssl.gstatic.com/images/branding/product/1x/docs_48dp.png" alt="Docs Ikon" style="height: 48px; margin: 0 5px; display: inline-block;">
                    <img src="https://ssl.gstatic.com/images/branding/product/1x/sheets_48dp.png" alt="Sheets Ikon" style="height: 48px; margin: 0 5px; display: inline-block;">
                    <img src="https://ssl.gstatic.com/images/branding/product/1x/slides_48dp.png" alt="Slides Ikon" style="height: 48px; margin: 0 5px; display: inline-block;">
                 </div>
                <h3>Docs, Sheets & Slides</h3>
                <p>Lag og rediger dokumenter, regneark og presentasjoner sammen i sanntid.</p>
            </div>
        </div>
    </div>
</section>

<!-- Google Workspace AI (Gemini) Seksjon -->
<section id="ai-funksjoner">
    <div class="container">
        <h2>Google Workspace Superladet med Gemini AI</h2>
        <p>Oppdag hvordan kunstig intelligens (AI) fra Gemini forvandler Google Workspace-verktøyene du bruker hver dag, og gjør dem smartere og mer effektive.</p>
        <div class="ai-tools-grid">
            <?php foreach ($workspaceToolsData as $tool): ?>
            <div class="ai-tool-card">
                <div class="ai-tool-header">
                    <?php if (!empty($tool['imageUrl'])): ?>
                        <img src="<?php echo htmlspecialchars($tool['imageUrl']); ?>" alt="<?php echo htmlspecialchars($tool['name']); ?> ikon" class="ai-tool-icon-img">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($tool['name']); ?></h3>
                </div>
                <div class="ai-tool-features">
                    <?php foreach ($tool['features'] as $feature): ?>
                    <div class="ai-feature-item">
                        <h4><?php echo htmlspecialchars($feature['title']); ?></h4>
                        <p><?php echo htmlspecialchars($feature['description']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Prispakker Seksjon -->
<section id="prispakker">
    <div class="pk-pricing-container container">
        <div class="pk-pricing-intro">
            <h2>Lei av kompliserte IT-fakturaer?</h2>
            <p>Hos oss får du Google Workspace til en fast, oversiktlig pris per bruker per måned. Inkludert i prisen er enkel drift og teknisk support. Enkelt og greit – det er tanken!</p>
        </div>

        <div class="pk-pricing-table">
            <div class="pk-pricing-plan">
                <div class="pk-plan-header">
                    <h3>Startpakken</h3>
                    <div class="pk-plan-price">
                        <span class="pk-price-amount">200kr</span>
                        <span class="pk-price-term">/mnd per bruker</span>
                    </div>
                </div>
                <div class="pk-plan-storage">30GB lagring</div>
                <ul class="pk-plan-features">
                    <li>Profesjonell e-post (@dittfirma.no)</li>
                    <li>Google Docs, Sheets, Slides</li>
                    <li>Google Meet (videomøter)</li>
                    <li>Google Calendar</li>
                    <li>Enkel drift & teknisk support</li>
                    <li>Forutsigbar, fast faktura</li>
                </ul>
                <div class="pk-plan-description">
                    <p><strong>Passer for:</strong> Nystartede, mindre bedrifter (1-10 ansatte) og de med grunnleggende behov for e-post og dokumenthåndtering.</p>
                </div>
                <div class="pk-plan-footer">
                    <a href="#kontakt" class="cta-button pk-btn">Velg Pakke</a>
                </div>
            </div>

            <div class="pk-pricing-plan pk-plan-recommended">
                <div class="pk-plan-badge">Mest Populær</div>
                <div class="pk-plan-header">
                    <h3>Standardpakken</h3>
                    <div class="pk-plan-price">
                        <span class="pk-price-amount">350kr</span>
                        <span class="pk-price-term">/mnd per bruker</span>
                    </div>
                </div>
                <div class="pk-plan-storage">2TB lagring</div>
                <ul class="pk-plan-features">
                    <li>Alt i Startpakken</li>
                    <li><strong>Rikelig med lagringsplass (2TB)</strong></li>
                    <li>Møteopptak i Google Meet*</li>
                    <li>Avanserte søkefunksjoner</li>
                    <li>Perfekt for voksende team</li>
                    <li>Enkel drift & teknisk support</li>
                </ul>
                <div class="pk-plan-description">
                    <p><strong>Passer for:</strong> Voksende bedrifter, team som jobber med større filer (design, video, store dokumenter) og de som trenger mer samarbeidsplass.</p>
                </div>
                <div class="pk-plan-footer">
                    <a href="#kontakt" class="cta-button pk-btn pk-btn-recommended">Velg Pakke</a>
                </div>
            </div>

            <div class="pk-pricing-plan">
                <div class="pk-plan-header">
                    <h3>Premiumpakken</h3>
                    <div class="pk-plan-price">
                        <span class="pk-price-amount">550kr</span>
                        <span class="pk-price-term">/mnd per bruker</span>
                    </div>
                </div>
                <div class="pk-plan-storage">5TB lagring</div>
                <ul class="pk-plan-features">
                    <li>Alt i Standardpakken</li>
                    <li><strong>Massiv lagringskapasitet (5TB)</strong></li>
                    <li>Google Vault for arkivering*</li>
                    <li>Avanserte sikkerhetskontroller*</li>
                    <li>For de mest krevende brukerne</li>
                    <li>Enkel drift & teknisk support</li>
                </ul>
                <div class="pk-plan-description">
                    <p><strong>Passer for:</strong> Bedrifter med store datamengder (videoproduksjon, mediehus), strenge arkiveringskrav og behov for maksimal kapasitet.</p>
                </div>
                <div class="pk-plan-footer">
                    <a href="#kontakt" class="cta-button pk-btn">Velg Pakke</a>
                </div>
            </div>
        </div>

        <div class="pk-pricing-outro">
            <p><em>*Enkelte avanserte funksjoner som møteopptak og Google Vault avhenger av den underliggende Google Workspace-lisensen. Vi hjelper deg å velge riktig!</em></p>
            <p><strong>Alltid inkludert:</strong> Enkel drift og teknisk support. <br><strong>Din fordel:</strong> Én pris, én faktura – ingen overraskelser!</p>
        </div>
    </div>
</section>


<!-- Hvorfor Oss Seksjon -->
<section id="hvorfor-oss">
     <div class="container">
        <h2>Hvorfor Velge Akarit som Din Partner?</h2>
        <p>Vi er mer enn bare en leverandør – vi er din dedikerte Google Workspace-ekspert.</p>
        <ul> <?php // Bruker standard ul/li fra original CSS ?>
            <li><strong>Sømløs Overgang:</strong> Vi håndterer migrering og oppsett trygt og effektivt, minimere nedetid og forstyrrelser.</li>
            <li><strong>Skreddersydd Løsning:</strong> Vi tilpasser Workspace til din bedrifts unike behov, arbeidsflyt og sikkerhetskrav.</li>
            <li><strong>Økt Brukeradopsjon:</strong> Vi tilbyr opplæring og støtte for å sikre at teamet ditt får fullt utbytte av verktøyene.</li>
            <li><strong>Lokal Support:</strong> Få rask og personlig hjelp på norsk fra våre sertifiserte spesialister når du trenger det.</li>
            <li><strong>Strategisk Rådgivning:</strong> Vi hjelper deg å utnytte Workspace fullt ut for å nå dine forretningsmål.</li>
        </ul>
         <div style="text-align: center; margin-top: 50px;">
             <a href="#kontakt" class="cta-button">Bli Kontaktet av en Spesialist</a>
         </div>
    </div>
</section>

<!-- Kontakt Seksjon -->
<section id="kontakt">
     <div class="container">
        <h2>Klar for en Smartere Arbeidsdag?</h2>
        <p>Ta det første steget mot en mer effektiv og samarbeidsorientert bedrift. Fyll ut skjemaet, så tar vi kontakt for en uforpliktende prat.</p>

        <div class="contact-form">
            <?php if (!empty($formMessage)): ?>
                <div class="form-message <?php echo $formSuccess ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($formMessage); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#kontakt" method="post" id="contactForm">
                 <div class="form-group">
                    <label for="name">Ditt Navn <span style="color: red;">*</span></label>
                    <input type="text" id="name" name="name" required value="<?php echo old_value('name', $submittedData); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Din E-post <span style="color: red;">*</span></label>
                    <input type="email" id="email" name="email" required value="<?php echo old_value('email', $submittedData); ?>">
                </div>
                <div class="form-group">
                    <label for="company">Firmanavn</label>
                    <input type="text" id="company" name="company" value="<?php echo old_value('company', $submittedData); ?>">
                </div>
                <div class="form-group">
                    <label for="message">Hva ønsker du hjelp med? <span style="color: red;">*</span></label>
                    <textarea id="message" name="message" rows="6" required placeholder="Fortell oss kort om dine behov eller spørsmål..."><?php echo old_value('message', $submittedData); ?></textarea>
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="cta-button">Send Forespørsel</button>
                </div>
            </form>
        </div>
    </div>
</section>


<!-- ================================== -->
<!-- SIDEINNHOLD SLUTTER HER -->
<!-- ================================== -->

<?php
// Inkluder footer
require __DIR__ . '/../templates/footer.php';
?>
