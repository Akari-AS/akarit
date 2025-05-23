<?php // templates/article_single.php
// $articleData er tilgjengelig her fra index.php
?>
<article class="article-single-page container">
    <header class="article-header">
        <h1><?php echo htmlspecialchars($articleData['title']); ?></h1>
        <p class="article-meta">
            Av <?php echo htmlspecialchars($articleData['author']); ?> | Publisert: <?php echo date("d. M Y", strtotime($articleData['date'])); ?>
        </p>
        <?php if (!empty($articleData['image']) && file_exists(__DIR__ . '/../public' . $articleData['image'])): ?>
            <img src="<?php echo htmlspecialchars($articleData['image']); ?>" alt="<?php echo htmlspecialchars($articleData['title']); ?>" class="article-featured-image">
        <?php endif; ?>
    </header>

    <div class="article-content">
        <?php echo $articleData['content']; // HTML-innhold fra Markdown ?>
    </div>

    <div class="article-navigation">
        <a href="/artikler/" class="cta-button secondary">← Tilbake til alle artikler</a>
    </div>
</article>
