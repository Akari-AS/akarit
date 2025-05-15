<!-- Google Workspace AI (Gemini) Seksjon -->
<section id="ai-funksjoner">
    <div class="container">
        <h2>Google Workspace superladet med Gemini AI</h2> <!-- Endret -->
        <p>Oppdag hvordan kunstig intelligens (AI) fra Gemini forvandler Google Workspace-verktøyene du bruker hver dag, og gjør dem smartere og mer effektive. Google inkluderer nå Gemini i sine bedriftspakker, slik at din virksomhet kan dra nytte av neste generasjons AI-drevet produktivitet og samarbeid. Disse løsningene kan du naturligvis kjøpe gjennom Akari, din lokale Google Workspace-forhandler.</p>
        <div class="ai-tools-grid">
            <?php foreach ($workspaceToolsData as $tool): ?>
            <div class="ai-tool-card">
                <div class="ai-tool-header">
                    <?php if (!empty($tool['imageUrl'])): ?>
                        <img src="<?php echo htmlspecialchars($tool['imageUrl']); ?>" alt="<?php echo htmlspecialchars($tool['name']); ?> ikon" class="ai-tool-icon-img">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($tool['name']); ?></h3> <!-- Egennavn -->
                </div>
                <div class="ai-tool-features">
                    <?php foreach ($tool['features'] as $feature): ?>
                    <div class="ai-feature-item">
                        <h4><?php echo htmlspecialchars($feature['title']); ?></h4> <!-- Beholder som de er, da de er titler fra en liste -->
                        <p><?php echo htmlspecialchars($feature['description']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
