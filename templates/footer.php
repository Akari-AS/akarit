</main> <?php // Main tag lukkes her ?>

<footer class="site-footer">
    <div class="container footer-container">
        <div class="footer-column footer-column-nav">
            <h4>Om Akari</h4>
            <ul>
                <li><a href="https://akari.no/vilkar-og-betingelser" target="_blank">Vilkår og betingelser</a></li>
                <li><a href="https://akari.no/personvern" target="_blank">Personvern og cookies</a></li>
            </ul>
            <h4>Sosiale Medier</h4>
            <ul>
                <li><a href="https://www.facebook.com/akarias" target="_blank">Facebook</a></li>
                <li><a href="https://www.instagram.com/akari_as/" target="_blank">Instagram</a></li>
                <li><a href="https://www.linkedin.com/company/akari-as/" target="_blank">LinkedIn</a></li>
                <li><a href="https://www.youtube.com/channel/UCoy3JgIe3p50Pq3f52zftFA" target="_blank">YouTube</a></li>
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
            <!-- Bruker <picture> for WebP-støtte og lazy loading for logoen i footeren -->
            <picture>
                <source srcset="/assets/img/Akari_jubileum.webp" type="image/webp">
                <source srcset="/assets/img/Akari_jubileum.svg" type="image/svg+xml">
                <img src="/assets/img/Akari_jubileum.svg" alt="Akari Logo" class="footer-logo" loading="lazy">
            </picture>
            <div class="footer-locations">
                Kongsberg • Hønefoss • Notodden • Numedal
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p>© <?php echo date("Y"); ?> Akari AS</p>
        </div>
    </div>
</footer>

<!-- Link til VÅR JavaScript fil -->
<script src="/assets/js/script.js" defer></script> <!-- defer er lagt til her -->

</body> <?php // Body tag lukkes her ?>
</html>
