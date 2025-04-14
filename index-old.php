<?php
// --- PHP Skjemahåndtering (Uendret - krever serveroppsett for å fungere) ---
$formMessage = '';
$formSuccess = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipientEmail = "din-salgs-epost@akarit.no"; // <<<--- VIKTIG: Bytt til din faktiske e-postadresse her!
    $emailSubject = "Google Workspace Henvendelse fra Akarit.no";
    $name = filter_var(trim($_POST['name'] ?? ''), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $company = filter_var(trim($_POST['company'] ?? ''), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($_POST['message'] ?? ''), FILTER_SANITIZE_STRING);

    if (empty($name) || empty($email) || empty($message)) {
        $formMessage = "Vennligst fyll ut alle obligatoriske felt (Navn, E-post, Melding).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formMessage = "Vennligst oppgi en gyldig e-postadresse.";
    } else {
        $emailBody = "Ny Google Workspace henvendelse:\n\n";
        $emailBody .= "Navn: " . $name . "\n";
        $emailBody .= "Firma: " . (!empty($company) ? $company : "Ikke oppgitt") . "\n";
        $emailBody .= "E-post: " . $email . "\n";
        $emailBody .= "Melding:\n" . $message . "\n";
        $headers = "From: " . $name . " <" . $email . ">\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        if (mail($recipientEmail, $emailSubject, $emailBody, $headers)) {
            $formMessage = "Takk! Meldingen din er sendt. Vi tar kontakt med deg snart.";
            $formSuccess = true;
            $_POST = array();
        } else {
            $formMessage = "Beklager, det oppstod en feil under sending av meldingen. Prøv igjen senere eller kontakt oss direkte via e-post.";
        }
    }
}

// --- Korrekt YouTube Video ID ---
$youtubeVideoId = "AwwZMoYNK2o";

?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Workspace for Bedrifter | Akarit</title>
    <meta name="description" content="Styrk bedriften med Google Workspace. Akarit (av Akari) tilbyr ekspertise innen oppsett, migrering og support for økt samarbeid og produktivitet."> <!-- Meta beskrivelse oppdatert -->

    <!-- Google Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- CSS Styling (Akari Inspired - Endelig versjon?) --- */
        :root {
            --primary-color: #00a99d;
            --dark-color: #064f55;
            --light-green-bg: #f4fef8;
            --light-green-bg-darker: #eef8f2;
            --text-color: #333;
            --text-color-light: #ffffff;
            --heading-font: 'Poppins', sans-serif;
            --body-font: 'Poppins', sans-serif;
            --border-radius: 15px;
        }

        html, body { margin: 0; padding: 0; width: 100%; overflow-x: hidden; }
        * { box-sizing: border-box; }

        body {
            font-family: var(--body-font); line-height: 1.7; color: var(--text-color);
            background-color: var(--light-green-bg); font-weight: 400;
        }

        .container { max-width: 1140px; margin: 0 auto; padding: 0 20px; }

        /* --- Header --- */
        header { background-color: var(--dark-color); padding: 15px 0; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        header .container { display: flex; justify-content: space-between; align-items: center; }
        /* Oppdatert Logo Styling */
        .logo { font-size: 1.9em; font-weight: 700; color: #fff; text-decoration: none; letter-spacing: 0.5px; display: flex; align-items: baseline; }
        .logo .sub-logo {
            font-size: 0.45em; /* Enda litt mindre */
            opacity: 0.8;
            font-weight: 400;
            margin-left: 7px; /* Litt mer avstand */
          white-space: nowrap; /* Hindrer linjeskift */
          color: #01938b;
        }

        nav ul { list-style: none; display: flex; margin: 0; padding: 0; }
        nav ul li { margin-left: 25px; }
        nav ul li a { text-decoration: none; color: #e0e0e0; font-weight: 600; transition: color 0.3s ease, border-bottom-color 0.3s ease; padding-bottom: 5px; border-bottom: 2px solid transparent; }
        nav ul li a:hover, nav ul li a.active { color: #fff; border-bottom-color: var(--primary-color); }

        /* --- Hero Section med VIDEO Banner --- */
        .hero { position: relative; padding: 140px 0 120px 0; text-align: center; overflow: hidden; color: var(--text-color-light); width: 100%; }
        .video-background-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden; pointer-events: none; }
        .video-background-container iframe { position: absolute; top: 50%; left: 50%; min-width: 101%; min-height: 101%; width: 177.77vh; height: 56.25vw; transform: translate(-50%, -50%); }
        /* Mørk overlay - Enda mer transparent */
        .hero::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(6, 79, 85, 0.50); /* Alpha redusert til 0.50 */
            z-index: 0;
        }
        .hero .container { position: relative; z-index: 1; }
        .hero h1 { font-family: var(--heading-font); font-size: 3.2em; font-weight: 700; color: var(--text-color-light); margin-bottom: 25px; text-shadow: 1px 1px 5px rgba(0,0,0,0.5); /* Økt skygge litt for lesbarhet */ }
        .hero h1 span { color: var(--primary-color); }
        .hero p { font-size: 1.25em; color: var(--text-color-light); max-width: 780px; margin: 0 auto 40px auto; line-height: 1.8; }

        /* --- Knapper (CTA - uendret) --- */
        .cta-button { display: inline-block; background-color: var(--primary-color); color: #fff; padding: 15px 35px; text-decoration: none; border-radius: var(--border-radius); font-weight: 600; transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease; font-size: 1.1em; border: none; box-shadow: 0 4px 10px rgba(0, 169, 157, 0.3); }
        .cta-button:hover { background-color: #008a7d; transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0, 169, 157, 0.4); }
        .cta-button.secondary { background-color: rgba(255, 255, 255, 0.15); color: var(--text-color-light); border: 2px solid var(--text-color-light); box-shadow: none; }
        .cta-button.secondary:hover { background-color: rgba(255, 255, 255, 0.3); color: #fff; border-color: #fff; transform: translateY(-3px); }
        section:not(.hero) .cta-button.secondary { background-color: transparent; color: var(--primary-color); border: 2px solid var(--primary-color); box-shadow: none; }
        section:not(.hero) .cta-button.secondary:hover { background-color: var(--primary-color); color: #fff; border-color: var(--primary-color); }

        /* --- Seksjoner --- */
        section { padding: 80px 0; width: 100%; }
        section:nth-of-type(even):not(.hero) { background-color: var(--light-green-bg-darker); }
        #kontakt { background-color: var(--light-green-bg); }
        section h2 { font-family: var(--heading-font); text-align: center; font-size: 2.4em; font-weight: 700; color: var(--dark-color); margin-bottom: 25px; padding-bottom: 15px; position: relative; }
        section h2::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 80px; height: 3px; background-color: var(--primary-color); border-radius: 2px; }
        section#kontakt h2::after { display: none; }
        section h2 + p { text-align: center; max-width: 800px; margin: 0 auto 50px auto; font-size: 1.1em; color: #555; }

        /* --- Kort (Funksjoner & Produkter) --- */
        .features-grid, .products-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 35px; margin-top: 40px; }
        .feature-item, .product-card { background-color: #fff; padding: 40px; border-radius: var(--border-radius); box-shadow: 0 5px 20px rgba(0,0,0,0.07); text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease; border: none; display: flex; flex-direction: column; align-items: center; }
        .feature-item:hover, .product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .feature-item i, .product-card img { color: var(--primary-color); margin-bottom: 25px; height: 48px; width: auto; }
        .feature-item i { font-size: 3em; }
        .feature-item h3, .product-card h3 { font-family: var(--heading-font); font-size: 1.5em; font-weight: 600; color: var(--primary-color); margin-bottom: 15px; }
        .feature-item p, .product-card p { font-size: 1em; color: var(--text-color); flex-grow: 1; }
        #fordeler .feature-item img { filter: invert(48%) sepia(98%) saturate(1431%) hue-rotate(135deg) brightness(95%) contrast(101%); opacity: 0.8; }

        /* --- "Hvorfor Oss" Seksjon --- */
        #hvorfor-oss { background-color: var(--light-green-bg-darker); }
        #hvorfor-oss ul { list-style: none; max-width: 850px; margin: 40px auto 0 auto; padding-left: 0; }
        #hvorfor-oss li { margin-bottom: 20px; font-size: 1.1em; position: relative; padding-left: 35px; line-height: 1.6; }
        #hvorfor-oss li::before { content: '✔'; color: var(--primary-color); position: absolute; left: 0; top: 3px; font-size: 1.4em; font-weight: bold; }

        /* --- Kontakt Skjema --- */
        .contact-form { max-width: 700px; margin: 50px auto 0 auto; background-color: #fff; padding: 50px 60px; border-radius: var(--border-radius); box-shadow: 0 5px 20px rgba(0,0,0,0.08); border: none; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark-color); }
        .form-group input[type="text"], .form-group input[type="email"], .form-group textarea { width: 100%; padding: 15px; border: 1px solid #dcdcdc; border-radius: var(--border-radius); font-size: 1em; font-family: var(--body-font); background-color: #fff; transition: border-color 0.3s ease, box-shadow 0.3s ease; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(0, 169, 157, 0.2); }
        .form-group textarea { resize: vertical; min-height: 140px; }
        .form-message { padding: 15px 20px; margin-bottom: 25px; border-radius: var(--border-radius); text-align: center; font-weight: 600; font-size: 1.05em; }
        .form-message.success { background-color: #e0f2f1; color: #00695c; border: 1px solid #b2dfdb; }
        .form-message.error { background-color: #fdecea; color: #c62828; border: 1px solid #f9bdbb; }

        /* --- Footer --- */
        footer { background-color: var(--dark-color); color: #c0c0c0; text-align: center; padding: 40px 0; margin-top: 80px; width: 100%; }
        footer p { margin: 0; font-size: 0.95em; }
        footer a { color: var(--primary-color); text-decoration: none; }
        footer a:hover { text-decoration: underline; }

        /* --- Responsive Design (uendret) --- */
        @media (max-width: 992px) { .container { max-width: 960px; } .hero h1 { font-size: 2.8em; } .hero p { font-size: 1.2em; } }
        @media (max-width: 768px) { .container { max-width: 720px; } header .container { flex-direction: column; text-align: center; } .logo { margin-bottom: 15px; } nav ul { margin-top: 10px; justify-content: center; flex-wrap: wrap; } nav ul li { margin: 5px 10px; } .hero { padding: 120px 0 100px 0; } .hero h1 { font-size: 2.4em; } .hero p { font-size: 1.1em; max-width: 90%; } section h2 { font-size: 2em; } .features-grid, .products-grid { grid-template-columns: 1fr; gap: 25px; } .contact-form { padding: 40px 30px; } }
        @media (max-width: 576px) { .hero { padding: 100px 20px 80px 20px; } .hero h1 { font-size: 2em; } .hero p { font-size: 1em; } section h2 { font-size: 1.8em; } .cta-button { padding: 14px 30px; font-size: 1em; } nav ul li a { font-size: 0.9em; } .logo { font-size: 1.7em; } .contact-form { padding: 30px 20px; margin-left: 15px; margin-right: 15px; } .feature-item, .product-card { padding: 30px; margin-left: 15px; margin-right: 15px;} }

    </style>
</head>
<body>

    <header>
         <div class="container">
            <!-- Logo med "av Akari" -->
            <a href="#" class="logo">Akarit <span class="sub-logo">av Akari</span></a>
            <nav>
                <ul>
                    <li><a href="#fordeler">Fordeler</a></li>
                    <li><a href="#produkter">Produkter</a></li>
                    <li><a href="#hvorfor-oss">Hvorfor Akarit?</a></li>
                    <li><a href="#kontakt">Kontakt</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero" id="hjem">
        <!-- Video Background Container (uendret struktur) -->
        <div class="video-background-container">
            <iframe
                src="https://www.youtube.com/embed/<?php echo $youtubeVideoId; ?>?autoplay=1&mute=1&loop=1&controls=0&showinfo=0&modestbranding=1&rel=0&playlist=<?php echo $youtubeVideoId; ?>&playsinline=1&enablejsapi=1"
                frameborder="0"
                allow="autoplay; encrypted-media"
                title="Akarit Bakgrunnsvideo">
            </iframe>
        </div>

        <!-- Hero Content Container (uendret) -->
        <div class="container">
             <h1>Fremtiden er Samarbeid med <span>Google Workspace</span></h1>
             <p>Akarit hjelper din bedrift med å utnytte kraften i Google Workspace for økt produktivitet, sømløst samarbeid og bunnsolid sikkerhet. La oss ta oss av det tekniske, så du kan fokusere på vekst.</p>
             <a href="#kontakt" class="cta-button">Start Samtalen</a>
             <a href="#produkter" class="cta-button secondary" style="margin-left: 15px;">Se Verktøyene</a>
        </div>
    </section>

    <section id="fordeler">
        <!-- Seksjonsinnhold (uendret) -->
          <div class="container">
            <h2>Hvorfor Velge Google Workspace?</h2>
             <p>Oppdag fordelene med en moderne, skybasert plattform for kommunikasjon og samarbeid.</p>
            <div class="features-grid">
                <div class="feature-item">
                    <img src="https://ssl.gstatic.com/images/icons/material/system/2x/group_work_black_48dp.png" alt="Samarbeidsikon" >
                    <h3>Sømløst Samarbeid</h3>
                    <p>Jobb sammen i sanntid på dokumenter, regneark og presentasjoner. Del enkelt filer og kommuniser effektivt med integrert chat og videomøter.</p>
                </div>
                <div class="feature-item">
                     <img src="https://ssl.gstatic.com/images/icons/material/system/2x/trending_up_black_48dp.png" alt="Produktivitetsikon" >
                    <h3>Økt Produktivitet</h3>
                    <p>Få profesjonell e-post (@dittfirma.no), smart kalender, rikelig med skylagring og kraftige verktøy som effektiviserer arbeidsdagen.</p>
                </div>
                <div class="feature-item">
                     <img src="https://ssl.gstatic.com/images/icons/material/system/2x/security_black_48dp.png" alt="Sikkerhetsikon" >
                    <h3>Sikkerhet i Fokus</h3>
                    <p>Dra nytte av Googles ledende sikkerhetsteknologi med avansert trusselbeskyttelse, datakontroll og garantert oppetid.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="produkter" class="products-section">
         <!-- Seksjonsinnhold (uendret) -->
          <div class="container">
            <h2>Verktøyene Som Driver Vekst</h2>
             <p>En komplett pakke med applikasjoner designet for moderne bedrifter.</p>
            <div class="products-grid">
                 <div class="product-card">
                     <img src="https://ssl.gstatic.com/images/branding/product/1x/gmail_48dp.png" alt="Gmail Ikon">
                    <h3>Gmail</h3>
                    <p>Profesjonell, sikker og reklamefri e-post med ditt eget domene (@dittfirma.no). Kraftig søk og integrasjon med resten av Workspace.</p>
                </div>
                <div class="product-card">
                     <img src="https://ssl.gstatic.com/images/branding/product/1x/drive_48dp.png" alt="Google Drive Ikon">
                    <h3>Drive</h3>
                    <p>Trygg skylagring for alle bedriftens filer. Enkel deling, tilgang fra alle enheter og samarbeidsmuligheter med Delte Disker.</p>
                </div>
                <div class="product-card">
                    <img src="https://ssl.gstatic.com/images/branding/product/1x/calendar_48dp.png" alt="Google Kalender Ikon">
                    <h3>Kalender</h3>
                    <p>Smarte, delte kalendere som gjør møtebooking enkelt. Se kollegers tilgjengelighet og planlegg effektivt.</p>
                </div>
                <div class="product-card">
                     <img src="https://ssl.gstatic.com/images/branding/product/1x/meet_48dp.png" alt="Google Meet Ikon">
                    <h3>Meet</h3>
                    <p>Pålitelige og sikre videomøter for team, kunder og partnere. Enkel å bruke, med skjermdeling og opptaksmuligheter.</p>
                </div>
                 <div class="product-card">
                     <img src="https://ssl.gstatic.com/images/branding/product/1x/chat_48dp.png" alt="Google Chat Ikon">
                    <h3>Chat</h3>
                    <p>Direktemeldinger og gruppechat for rask kommunikasjon og samarbeid. Reduser intern e-post og få svar raskere.</p>
                </div>
                <div class="product-card">
                     <div style="margin-bottom: 20px;">
                        <img src="https://ssl.gstatic.com/images/branding/product/1x/docs_48dp.png" alt="Google Docs Ikon" style="height: 48px; margin: 0 5px;">
                        <img src="https://ssl.gstatic.com/images/branding/product/1x/sheets_48dp.png" alt="Google Sheets Ikon" style="height: 48px; margin: 0 5px;">
                        <img src="https://ssl.gstatic.com/images/branding/product/1x/slides_48dp.png" alt="Google Slides Ikon" style="height: 48px; margin: 0 5px;">
                     </div>
                    <h3>Docs, Sheets & Slides</h3>
                    <p>Lag og rediger dokumenter, regneark og presentasjoner sammen i sanntid. Alltid nyeste versjon, tilgjengelig overalt.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="hvorfor-oss">
         <div class="container">
            <h2>Hvorfor Velge Akarit som Din Partner?</h2>
            <p>Vi er mer enn bare en leverandør – vi er din dedikerte Google Workspace-ekspert.</p>
            <ul>
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

    <section id="kontakt">
        <!-- Seksjonsinnhold (uendret) -->
         <div class="container">
            <h2>Klar for en Smartere Arbeidsdag?</h2>
            <p>Ta det første steget mot en mer effektiv og samarbeidsorientert bedrift. Fyll ut skjemaet, så tar vi kontakt for en uforpliktende prat om hvordan Akarit og Google Workspace kan hjelpe deg.</p>

            <div class="contact-form">
                 <?php if (!empty($formMessage)): ?>
                    <div class="form-message <?php echo $formSuccess ? 'success' : 'error'; ?>">
                        <?php echo htmlspecialchars($formMessage); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#kontakt" method="post" id="contactForm">
                     <div class="form-group">
                        <label for="name">Ditt Navn <span style="color: red;">*</span></label>
                        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Din E-post <span style="color: red;">*</span></label>
                        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="company">Firmanavn</label>
                        <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($_POST['company'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="message">Hva ønsker du hjelp med? <span style="color: red;">*</span></label>
                        <textarea id="message" name="message" rows="6" required placeholder="Fortell oss kort om dine behov eller spørsmål..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    </div>
                    <div style="text-align: center;">
                        <button type="submit" class="cta-button">Send Forespørsel</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <!-- Footer HTML - Oppdatert firmanavn -->
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Akarit (av Akari AS). Alle Rettigheter Reservert. | Sertifisert Google Workspace Partner</p> <!-- Justert footer-tekst -->
        </div>
    </footer>

    <script>
    // --- JavaScript (Uendret) ---
    document.querySelectorAll('header nav a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const headerOffset = document.querySelector('header').offsetHeight;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset - 20;
                window.scrollTo({ top: offsetPosition, behavior: "smooth" });
                document.querySelectorAll('header nav a').forEach(link => link.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });
    let currentActive = null;
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('header nav a');
    window.addEventListener('scroll', () => {
        let currentSectionId = '';
        const headerHeight = document.querySelector('header').offsetHeight;
        sections.forEach(section => {
            const sectionTop = section.offsetTop - headerHeight - 50;
            if (window.pageYOffset >= sectionTop) {
                currentSectionId = section.getAttribute('id');
            }
        });
        if (window.pageYOffset < sections[0].offsetTop - headerHeight - 50) {
             currentSectionId = 'hjem';
        }
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').slice(1) === currentSectionId) {
                link.classList.add('active');
            }
        });
         if (currentSectionId === 'hjem' && navLinks.length > 0) {
             if (!navLinks[0].classList.contains('active')) {
                navLinks.forEach(link => link.classList.remove('active'));
                 const homeLink = document.querySelector('header nav a[href="#hjem"]');
                 if (homeLink) homeLink.classList.add('active');
                 else if(navLinks.length > 0) navLinks[0].classList.add('active');
             }
        }
    });
    console.log("Akarit Google Workspace side lastet (v8 - Logo/Overlay endelig justering).");
    </script>

</body>
</html>
