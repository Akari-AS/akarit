<?php // templates/article_single.php ?>
<article class="article-single-page"> <?php // Fjernet .container her ?>
    <?php if ($articleData && isset($articleData['title'])): ?>
        
        <div class="article-header-content-wrapper"> <?php // NY WRAPPER for innhold som IKKE skal ha fullbredde ?>
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
        </div> <?php // SLUTT PÅ article-header-content-wrapper ?>

        <?php // Kontaktseksjonen kommer UTENFOR wrapperen, slik at den kan ta full bredde ?>
        <?php require __DIR__ . '/sections/kontakt.php'; ?>

        <?php // Navigasjon kan også være i sin egen wrapper for sentrering ?>
        <div class="article-navigation-wrapper">
            <div class="article-navigation">
                <a href="/artikler/" class="cta-button secondary">← Tilbake til alle artikler</a>
            </div>
        </div>

    <?php else: ?>
        <div class="container" style="text-align: center; padding: 40px 0;"> <?php // La til .container for 404-siden ?>
            <h2>Artikkel ikke funnet</h2>
            <p>Beklager, vi kunne ikke finne den spesifiserte artikkelen.</p>
            <p><a href="/artikler/" class="cta-button">Se alle artikler</a></p>
        </div>
    <?php endif; ?>
</article>
