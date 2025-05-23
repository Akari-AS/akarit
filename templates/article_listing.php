<?php // templates/article_listing.php
// $allArticles (metadata) er tilgjengelig her fra index.php
?>
<section id="artikkel-liste" class="article-listing-page">
    <div class="container">
        <h2>Artikler om Google Workspace</h2>
        <p>Her finner du innsikt, tips og nyheter relatert til Google Workspace og hvordan du kan jobbe smartere.</p>

        <?php if (!empty($allArticles)): ?>
            <div class="articles-grid">
                <?php foreach ($allArticles as $articleMeta): ?>
                    <div class="article-card">
                        <?php if (!empty($articleMeta['image']) && file_exists(__DIR__ . '/../public' . $articleMeta['image'])): ?>
                            <a href="/artikler/<?php echo htmlspecialchars($articleMeta['slug']); ?>/">
                                <img src="<?php echo htmlspecialchars($articleMeta['image']); ?>" alt="<?php echo htmlspecialchars($articleMeta['title']); ?>" class="article-card-image" loading="lazy">
                            </a>
                        <?php endif; ?>
                        <div class="article-card-content">
                            <h3><a href="/artikler/<?php echo htmlspecialchars($articleMeta['slug']); ?>/"><?php echo htmlspecialchars($articleMeta['title']); ?></a></h3>
                            <p class="article-card-meta">
                                Av <?php echo htmlspecialchars($articleMeta['author']); ?> | <?php echo date("d. M Y", strtotime($articleMeta['date'])); ?>
                            </p>
                            <p class="article-card-excerpt"><?php echo htmlspecialchars($articleMeta['excerpt']); ?></p>
                            <a href="/artikler/<?php echo htmlspecialchars($articleMeta['slug']); ?>/" class="cta-button pk-btn">Les mer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Ingen artikler funnet.</p>
        <?php endif; ?>
    </div>
</section>
