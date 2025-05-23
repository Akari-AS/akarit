<?php // templates/article_single.php ?>
<article class="article-single-page container">
    <?php if ($articleData && isset($articleData['title'])): ?>
        
        <?php // Tittel og meta direkte i containeren, ikke i egen header-blokk ?>
        <h1 class="article-main-title"><?php echo htmlspecialchars($articleData['title']); ?></h1>
        <?php if (isset($articleData['author']) && isset($articleData['date'])): ?>
        <p class="article-main-meta">
            Av <?php echo htmlspecialchars($articleData['author']); ?> | Publisert: <?php echo date("d. M Y", strtotime($articleData['date'])); ?>
        </p>
        <?php endif; ?>

        <?php // Featured image kommer ETTER tittel og meta ?>
        <?php if (!empty($articleData['image']) && file_exists(__DIR__ . '/../public' . $articleData['image'])): ?>
            <figure class="featured-image-container">
                <img src="<?php echo htmlspecialchars($articleData['image']); ?>" alt="<?php echo htmlspecialchars($articleData['title']); ?>" class="article-featured-image">
            </figure>
        <?php endif; ?>

        <div class="article-content">
            <?php echo $articleData['content']; // HTML-innhold fra Markdown ?>
        </div>

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
