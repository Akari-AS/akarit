</main> <?php // Main tag lukkes her ?>

<footer class="site-footer">
    <div class="container footer-container">
        <div class="footer-column footer-column-nav">
            <h4>Om Akari</h4>
            <ul>
                <li><a href="https://akari.no/om-akari/" target="_blank">Om Akari</a></li>
                <li><a href="https://akari.no/betingelser/" target="_blank">Vilkår og betingelser</a></li>
                <li><a href="https://akari.no/personvern/" target="_blank">Personvern og cookies</a></li>
            </ul>
            <h4>Sosiale Medier</h4>
            <ul>
                <li><a href="https://www.facebook.com/akarireklame" target="_blank">Facebook</a></li>
                <li><a href="https://www.instagram.com/akari_reklame/" target="_blank">Instagram</a></li>
                <li><a href="https://www.linkedin.com/company/11776262/" target="_blank">LinkedIn</a></li>
                <li><a href="https://www.youtube.com/@akarireklame" target="_blank">YouTube</a></li>
            </ul>
        </div>

        <div class="footer-column footer-column-contact">
            <h4>Kontakt oss</h4>
            <p>
                e: <a href="mailto:kreative@akari.no">kreative@akari.no</a><br>
                t: <a href="tel:+4732766600">32 76 66 00</a>
            </p>
            <h4>Åpningstider:</h4>
            <p>Hverdager 08.00 – 16.00</p>
            
            <h4>Websupport</h4>
            <p>
                e: <a href="mailto:support@akari.no">support@akari.no</a><br>
                t: <a href="tel:+4732766600">32 76 66 00</a>
            </p>
        </div>

        <div class="footer-column footer-column-brand">
            <img src="/assets/img/Akari_jubileum.svg" alt="Akari Logo" class="footer-logo" loading="lazy">
            <div class="footer-locations">
                Kongsberg • Hønefoss • Notodden • Numedal • Larvik
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p>© <?php echo date("Y"); ?> Akari AS | Sertifisert Google Workspace Leverandør</p> <!-- Endret Partner til Leverandør -->
        </div>
    </div>
</footer>

<!-- Link til VÅR JavaScript fil -->
<script src="/assets/js/script.js" defer></script>
<script>
function loadAndPlayYouTubeVideo() {
    const iframe = document.getElementById('hero-background-video');
    // Sjekk om iframe eksisterer, har data-src, og src ikke allerede er satt
    if (iframe && iframe.dataset.src && !iframe.src) {
        iframe.src = iframe.dataset.src;
    }
}

function checkCookieYesConsentAndLoadVideo() {
    // -------------------------------------------------------------------------
    // VIKTIG: Tilpass denne nøkkelen!
    // Bytt ut 'marketing' med den faktiske nøkkelen (key) for din
    // samtykkekategori for YouTube-videoer i CookieYes-oppsettet.
    // Du finner denne nøkkelen slik:
    // 1. Logg inn på ditt CookieYes-dashboard.
    // 2. Gå til "Cookie Manager" -> "Categories" (eller tilsvarende på norsk).
    // 3. Finn kategorien videoen tilhører (f.eks. kan "Display Name" være "Markedsføring").
    // 4. Noter deg verdien i kolonnen "Key" for denne kategorien (f.eks. "marketing", "advertisement", "analytics").
    const consentCategoryKey = 'marketing'; // <-- TILPASS DENNE NØKKELEN
    // -------------------------------------------------------------------------

    if (window.CookieYes &&
        window.CookieYes.consent &&
        window.CookieYes.consent.categories &&
        window.CookieYes.consent.categories[consentCategoryKey] &&
        window.CookieYes.consent.categories[consentCategoryKey].accepted === true) {
        loadAndPlayYouTubeVideo();
    }
}

// Funksjon som kjører når CookieYes er klar eller samtykke oppdateres
function handleCookieYesLogic() {
    checkCookieYesConsentAndLoadVideo();
}

// Lytt etter CookieYes samtykkeoppdateringer
// 'cookieyes_consent_update' er en vanlig hendelse fra CookieYes v3
window.addEventListener('cookieyes_consent_update', handleCookieYesLogic);

// Lytt etter at CookieYes banner-skriptet er lastet
// Dette er ofte et godt tidspunkt for å gjøre den første sjekken for samtykke
window.addEventListener('cookieyes_banner_script_loaded', function() {
    if (window.CookieYes) { // Dobbeltsjekk at CookieYes-objektet er definert
        handleCookieYesLogic();
    }
});

// En ekstra sjekk når DOM er fullstendig lastet,
// i tilfelle CookieYes-objektet var tilgjengelig tidligere enn 'cookieyes_banner_script_loaded'-hendelsen.
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.CookieYes !== 'undefined' && typeof window.CookieYes.consent !== 'undefined') {
         handleCookieYesLogic();
    }
});
</script>

</body> <?php // Body tag lukkes her ?>
</html>
