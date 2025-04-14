<?php
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
