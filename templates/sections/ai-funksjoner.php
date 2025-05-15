<!-- Google Workspace AI (Gemini) Seksjon -->
<section id="ai-funksjoner">
    <div class="container">
        <h2>Google Workspace Superladet med Gemini AI</h2>
        <p>Oppdag hvordan kunstig intelligens (AI) fra Gemini forvandler Google Workspace-verktøyene du bruker hver dag, og gjør dem smartere og mer effektive.</p>
        <div class="ai-tools-grid">
            <?php foreach ($workspaceToolsData as $tool): ?>
            <div class="ai-tool-card">
                <div class="ai-tool-header">
                    <?php if (!empty($tool['imageUrl'])): ?>
                        <img src="<?php echo htmlspecialchars($tool['imageUrl']); ?>" alt="<?php echo htmlspecialchars($tool['name']); ?> ikon" class="ai-tool-icon-img">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($tool['name']); ?></h3>
                </div>
                <div class="ai-tool-features">
                    <?php foreach ($tool['features'] as $feature): ?>
                    <div class="ai-feature-item">
                        <h4><?php echo htmlspecialchars($feature['title']); ?></h4>
                        <p><?php echo htmlspecialchars($feature['description']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
