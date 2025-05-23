<?php // templates/article_single.php ?>
<article class="article-single-page container">
    <?php if ($articleData && isset($articleData['title'])): ?>
        <header class="article-header">
            <h1><?php echo htmlspecialchars($articleData['title']); ?></h1>
            <?php if (isset($articleData['author']) && isset($articleData['date'])): ?>
            <p class="article-meta">
                Av <?php echo htmlspecialchars($articleData['author']); ?> | Publisert: <?php echo date("d. M Y", strtotime($articleData['date'])); ?>
            </p>
            <?php endif; ?>
        </header> 

        <?php // Featured image kommer ETTER header-elementet med tittel/meta ?>
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
