<!-- Hero Seksjon -->
<section class="hero" id="hjem">
    <!-- Video Background Container -->
    <div class="video-background-container">
        <iframe
            src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtubeVideoId, ENT_QUOTES, 'UTF-8'); ?>?autoplay=1&mute=1&loop=1&controls=0&showinfo=0&modestbranding=1&rel=0&playlist=<?php echo htmlspecialchars($youtubeVideoId, ENT_QUOTES, 'UTF-8'); ?>&playsinline=1&enablejsapi=1"
            frameborder="0"
            allow="autoplay; encrypted-media"
            title="Akari Bakgrunnsvideo"> <!-- Endret fra Akarit -->
        </iframe>
    </div>
    <!-- Hero Content -->
    <div class="container"> <!-- Container er sentrert av CSS -->
         <h1>Fremtiden er Samarbeid med <span>Google Workspace</span></h1>
         <p>Akari hjelper din bedrift med å utnytte kraften i Google Workspace for økt produktivitet, sømløst samarbeid og bunnsolid sikkerhet. La oss ta oss av det tekniske, så du kan fokusere på vekst.</p> <!-- Endret fra Akarit -->
         <div> <?php // Ekstra div for knapper kan hjelpe med mobil-styling ?>
            <a href="#kontakt" class="cta-button">Start Samtalen</a>
            <a href="#produkter" class="cta-button secondary">Se Verktøyene</a>
         </div>
    </div>
</section>
