<?php // templates/article_single.php ?>
<article class="article-single-page container">
    <?php if ($articleData && isset($articleData['title'])): ?>
        
        <h1 class="article-main-title"><?php echo htmlspecialchars($articleData['title']); ?></h1>
        <?php if (isset($articleData['author']) && isset($articleData['date'])): ?>
        <p class="article-main-meta">
            Av <?php echo htmlspecialchars($articleData['author']); ?> | Publisert: <?php echo date("d. M Y", strtotime($articleData['date'])); ?>
        </p>
        <?php endif; ?>

        <?php if (!empty($articleData['image']) && file_exists(__DIR__ . '/../public' . $articleData['image'])): ?>
            <figure class="featured-image-container">
                <img src="<?php echo htmlspecialchars($articleData['image']); ?>" alt="<?php echo htmlspecialchars($articleData['title']); ?>" class="article-featured-image">
            </figure>
        <?php endif; ?>

        <div class="article-content">
            <?php echo $articleData['content']; // HTML-innhold fra Markdown ?>
        </div>

        <?php
        // --------- INKLUDER KONTAKTSKJEMA-SEKSJONEN ---------
        // Vi må sørge for at variabler som kontakt.php forventer er tilgjengelige.
        // $currentLocationName er allerede definert i index.php, så den bør være tilgjengelig her.
        // $formMessage, $formSuccess, $submittedData er også definert i index.php
        // og vil reflektere et eventuelt skjemainnsending fra *denne* siden hvis skjemaet postes hit.

        // Det kan være lurt å sette en spesifikk "kilde" for skjemaet hvis du vil spore
        // henvendelser fra artikkelsider separat, men vi bruker $currentLocationName for nå.
        // Hvis du vil ha noe mer generisk som "Artikkel: [Tittel]", må du justere hidden input i kontakt.php
        // eller sende med en ny variabel. Foreløpig gjenbruker vi eksisterende logikk.

        // Siden $currentLocationName settes til "Generell" for artikkelsider i index.php,
        // vil skjemaets 'form_source_location' bli "Generell".
        // Hvis du vil at det skal stå f.eks. "Artikkel: [Artikkeltittel]",
        // må vi sende med en annen verdi til kontakt.php eller modifisere kontakt.php.

        // La oss for nå bare inkludere den.
        // Vi må sørge for at seksjonstittel i kontakt.php ikke kræsjer visuelt.
        // Kanskje vi vil ha en litt annen intro til kontaktskjemaet her.
        ?>

        <?php require __DIR__ . '/sections/kontakt.php'; // Inkluderer hele kontaktseksjonen ?>


        <div class="article-navigation">
            <a href="/artikler/" class="cta-button secondary">← Tilbake til alle artikler</a>
        </div>

    <?php else: ?>
        <div style="text-align: center; padding: 40px 0;">
            <h2>Artikkel ikke funnet</h2>
            <p>Beklager, vi kunne ikke finne den spesifiserte artikkelen.</p>
            <p><a href="/artikler/" class="cta-button">Se alle artikler</a></p>
        </div>
    <?php endif; ?>
</article>
