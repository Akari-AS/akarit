<?php
// templates/location_listing.php
// Variablene $coreLocations og $regionalLocations er tilgjengelige her fra index.php
?>
<section id="lokasjonsliste" class="location-listing-page-simple">
    <div class="container">
        <h2>Våre lokasjoner og dekningsområder</h2>
        <p>Oversikt over Akaris kontorer og områdene vi leverer Google Workspace-tjenester til i Norge.</p>

        <?php if (!empty($coreLocations)): ?>
            <div class="location-group-simple">
                <h3>Våre nøkkelkontorer</h3>
                <ul class="location-links-list">
                    <?php
                    // Sorter kjerne-lokasjoner på navn for visning
                    uasort($coreLocations, function($a, $b) { 
                        return strcmp($a['name'], $b['name']);
                    });
                    ?>
                    <?php foreach ($coreLocations as $slug => $location): ?>
                        <li>
                            <a href="/<?php echo htmlspecialchars($slug); ?>/">
                                <?php echo htmlspecialchars($location['name']); ?>
                                <?php if (isset($location['isHeadOffice']) && $location['isHeadOffice']): ?>
                                    <span class="head-office-indicator">(Hovedkontor)</span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($regionalLocations)): ?>
            <div class="location-group-simple">
                <h3>Andre dekningsområder</h3>
                <?php foreach ($regionalLocations as $regionName => $locationsInRegion): ?>
                    <div class="region-block-simple">
                        <h4><?php echo htmlspecialchars($regionName); ?></h4>
                        <ul class="location-links-list">
                            <?php foreach ($locationsInRegion as $locationName => $locationData): ?>
                                <li>
                                    <a href="/<?php echo htmlspecialchars($locationData['slug']); ?>/">
                                        <?php echo htmlspecialchars($locationData['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($coreLocations) && empty($regionalLocations)): ?>
            <p style="text-align:center;">Informasjon om våre lokasjoner kommer snart.</p>
        <?php endif; ?>

        <div class="location-listing-contact-cta">
             <p style="text-align:center; margin-top: 50px; margin-bottom:20px; font-size: 1.2em;">Finner du ikke din lokasjon, eller har du spørsmål?</p>
             <div style="text-align:center;">
                <a href="/#kontakt" class="cta-button">Ta kontakt med oss</a>
             </div>
        </div>

    </div>
</section>
