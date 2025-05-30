<?php // templates/seminar_listing.php
// $allSeminars (metadata) er tilgjengelig her fra index.php
?>
<section id="seminar-liste" class="seminar-listing-page">
    <div class="container">
        <h2>Kommende Seminarer</h2>
        <p>Bli med på våre frokostseminarer for faglig påfyll, nettverksbygging og en god start på dagen. Her finner du en oversikt over planlagte arrangementer.</p>

        <?php 
        $upcomingSeminars = array_filter($allSeminars, function ($seminar) {
            // Vis seminarer som er i fremtiden eller i dag, og som ikke er merket som "past" eller "cancelled"
            $seminarDate = strtotime($seminar['date'] ?? 'now'); // Full datetime for seminar
            $todayStartOfDay = strtotime(date('Y-m-d 00:00:00')); // Starten på dagen i dag
            
            return ($seminarDate >= $todayStartOfDay) && 
                   (!isset($seminar['status']) || !in_array(strtolower($seminar['status']), ['past', 'cancelled']));
        });

        if (!empty($upcomingSeminars)): ?>
            <div class="seminars-grid">
                <?php foreach ($upcomingSeminars as $seminarMeta): ?>
                    <div class="seminar-card">
                        <?php if (!empty($seminarMeta['image']) && file_exists(__DIR__ . '/../public' . $seminarMeta['image'])): ?>
                            <a href="/seminarer/<?php echo htmlspecialchars($seminarMeta['slug']); ?>/">
                                <img src="<?php echo htmlspecialchars($seminarMeta['image']); ?>" alt="<?php echo htmlspecialchars($seminarMeta['title']); ?>" class="seminar-card-image" loading="lazy">
                            </a>
                        <?php endif; ?>
                        <div class="seminar-card-content">
                            <h3><a href="/seminarer/<?php echo htmlspecialchars($seminarMeta['slug']); ?>/"><?php echo htmlspecialchars($seminarMeta['title']); ?></a></h3>
                            <p class="seminar-card-meta">
                                <span class="meta-date"><strong>Dato:</strong> <?php echo date("d. M Y, H:i", strtotime($seminarMeta['date'])); ?></span><br>
                                <span class="meta-location"><strong>Sted:</strong> <?php echo htmlspecialchars($seminarMeta['location'] ?? 'Nærmere info kommer'); ?></span>
                            </p>
                            <p class="seminar-card-excerpt"><?php echo htmlspecialchars($seminarMeta['excerpt'] ?? 'Les mer om dette spennende seminaret.'); ?></p>
                            
                            <?php
                            // Logikk for knapp/status
                            $seminarDateOnly = date('Y-m-d', strtotime($seminarMeta['date'] ?? 'now +1 day')); // Seminars dato
                            $todayDateOnly = date('Y-m-d'); // Dagens dato
                            
                            // Seminaret er "past" hvis datoen er før i dag, eller status er eksplisitt 'past'
                            $isPastForButton = ($seminarDateOnly < $todayDateOnly) || (isset($seminarMeta['status']) && strtolower($seminarMeta['status']) === 'past');
                            
                            $isRegistrationOpen = (isset($seminarMeta['registration_open']) && strtolower($seminarMeta['registration_open']) === 'true');
                            $isFull = (isset($seminarMeta['status']) && strtolower($seminarMeta['status']) === 'full');

                            if ($isPastForButton) {
                                echo '<p class="seminar-status-info"><em>Dette seminaret har allerede vært.</em></p>';
                            } elseif ($isFull) {
                                echo '<p class="seminar-status-info"><em>Seminaret er fulltegnet.</em></p>';
                            } elseif ($isRegistrationOpen) {
                                echo '<a href="/seminarer/' . htmlspecialchars($seminarMeta['slug']) . '/#seminar-registrering" class="cta-button pk-btn">Meld deg på</a>';
                            } else { // Registrering er ikke åpen (og det er ikke "past" eller "full")
                                echo '<p class="seminar-status-info"><em>Påmelding er ikke åpen.</em></p>';
                            }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align:center; margin-top:30px;">Ingen kommende seminarer er planlagt for øyeblikket. Følg med her for oppdateringer!</p>
        <?php endif; ?>

         <?php
        // Viser tidligere seminarer
        $pastSeminars = array_filter($allSeminars, function ($seminar) {
            $seminarDateOnly = date('Y-m-d', strtotime($seminar['date'] ?? 'now +1 day'));
            $todayDateOnly = date('Y-m-d');
            return ($seminarDateOnly < $todayDateOnly) || (isset($seminar['status']) && strtolower($seminar['status']) === 'past');
        });
        
        // Sorter tidligere seminarer synkende etter dato (nyeste først)
        if(!empty($pastSeminars)) {
            usort($pastSeminars, function($a, $b) {
                $dateA = strtotime($a['date'] ?? 0);
                $dateB = strtotime($b['date'] ?? 0);
                return $dateB <=> $dateA;
            });
        }


        if (!empty($pastSeminars)):
        ?>
            <div class="past-seminars-section">
                <h2>Tidligere Seminarer</h2>
                <ul class="past-seminars-list">
                    <?php foreach ($pastSeminars as $seminarMeta): ?>
                        <li>
                            <a href="/seminarer/<?php echo htmlspecialchars($seminarMeta['slug']); ?>/">
                                <?php echo htmlspecialchars($seminarMeta['title']); ?>
                            </a> - <?php echo date("d. M Y", strtotime($seminarMeta['date'])); ?>
                            (<?php echo htmlspecialchars($seminarMeta['location'] ?? 'Ukjent sted'); ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>
