<!-- Hero Seksjon -->
<section class="hero" id="hjem">
  <div class="video-background-container">
<iframe
    src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtubeVideoId, ENT_QUOTES, 'UTF-8'); ?>?autoplay=1&mute=1&loop=1&controls=0&showinfo=0&modestbranding=1&rel=0&playlist=<?php echo htmlspecialchars($youtubeVideoId, ENT_QUOTES, 'UTF-8'); ?>&playsinline=1&enablejsapi=1"
    frameborder="0"
    allow="autoplay; encrypted-media"
    title="Akari Bakgrunnsvideo">
</iframe>
  </div>
  <div class="container">
    <h1>Fremtiden er samarbeid med <span>Google Workspace</span>
      <?php if (isset($currentLocationName) && $currentLocationName !== "Generell"): ?>
      i <?php echo htmlspecialchars($currentLocationName); ?>
      <?php endif; ?>
</h1>
    <p>
      <?php echo htmlspecialchars($locationSpecificHeroText); // Denne settes nå i index.php med "leverandør" ?>
    </p>
    <div>
      <a href="#kontakt" class="cta-button">Start samtalen</a>
      <a href="#produkter" class="cta-button secondary">Se verktøyene</a>
    </div>
  </div>
</section>
