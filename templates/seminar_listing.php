<?php // templates/seminar_listing.php
// $allSeminars (metadata) er tilgjengelig her fra index.php

// Funksjon for å formatere dato og tid på norsk for listevisning
if (!function_exists('format_seminar_list_datetime_norwegian')) {
    function format_seminar_list_datetime_norwegian($dateString) {
        $timestamp = strtotime($dateString);
        if ($timestamp === false) {
            return htmlspecialchars($dateString);
        }
        // For 'Tirsdag 17. juni, 08:30' (endret til fullt månedsnavn og fjernet år for kommende)
        $formatter = new IntlDateFormatter(
            'nb_NO',
            IntlDateFormatter::FULL, 
            IntlDateFormatter::NONE, 
            'Europe/Oslo',
            IntlDateFormatter::GREGORIAN,
            'EEEE d. MMMM' // Viser ikke år for kommende seminarer for et renere utseende
        );
        $formattedDate = $formatter->format($timestamp);
        $formattedTime = date('H:i', $timestamp);
        return $formattedDate . ', ' . $formattedTime; // La til komma etter dato
    }
}
// Funksjon for å formatere dato (uten tid) for tidligere seminarer
if (!function_exists('format_seminar_list_date_norwegian')) {
    function format_seminar_list_date_norwegian($dateString) {
        $timestamp = strtotime($dateString);
        if ($timestamp === false) {
            return htmlspecialchars($dateString);
        }
        // For '17. juni 2025'
        $formatter = new IntlDateFormatter(
            'nb_NO',
            IntlDateFormatter::LONG, // For å få med fullt månedsnavn og år
            IntlDateFormatter::NONE,
            'Europe/Oslo',
            IntlDateFormatter::GREGORIAN
            // Standard LONG format vil typisk være '17. juni 2025'
        );
        return $formatter->format($timestamp);
    }
}
?>
<section id="seminar-liste" class="seminar-listing-page">
    <div class="container">
        <h2>Kommende Seminarer</h2>
        <p>Bli med på våre frokostseminarer for faglig påfyll, nettverksbygging og en god start på dagen. Her finner du en oversikt over planlagte arrangementer.</p>

        <?php 
        $upcomingSeminars = array_filter($allSeminars, function ($seminar) {
            $seminarTimestamp = strtotime($seminar['date'] ?? 'now -1 day');
            return ($seminarTimestamp >= time()) && 
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
                                <span class="meta-date"><strong>Dato:</strong> <?php echo format_seminar_list_datetime_norwegian($seminarMeta['date']); ?></span><br>
                                <span class="meta-location"><strong>Sted:</strong> <?php echo htmlspecialchars($seminarMeta['location'] ?? 'Nærmere info kommer'); ?></span>
                            </p>
                            <p class="seminar-card-excerpt"><?php echo htmlspecialchars($seminarMeta['excerpt'] ?? 'Les mer om dette spennende seminaret.'); ?></p>
                            
                            <?php
                            $seminarTimestampForButton = strtotime($seminarMeta['date'] ?? 'now -1 day');
                            $isPastForButton = ($seminarTimestampForButton < time()) || (isset($seminarMeta['status']) && strtolower($seminarMeta['status']) === 'past');
                            
                            $isRegistrationOpen = (isset($seminarMeta['registration_open']) && strtolower($seminarMeta['registration_open']) === 'true');
                            $isFull = (isset($seminarMeta['status']) && strtolower($seminarMeta['status']) === 'full');

                            if ($isPastForButton) {
                                echo '<p class="seminar-status-info"><em>Dette seminaret har allerede vært.</em></p>';
                            } elseif ($isFull) {
                                echo '<p class="seminar-status-info"><em>Seminaret er fulltegnet.</em></p>';
                            } elseif ($isRegistrationOpen) {
                                echo '<a href="/seminarer/' . htmlspecialchars($seminarMeta['slug']) . '/#seminar-registrering" class="cta-button pk-btn">Meld deg på</a>';
                            } else {
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
        $pastSeminars = array_filter($allSeminars, function ($seminar) {
            $seminarTimestamp = strtotime($seminar['date'] ?? 'now +1 day'); 
            return ($seminarTimestamp < time()) || (isset($seminar['status']) && strtolower($seminar['status']) === 'past');
        });
        
        if(!empty($pastSeminars)) {
            usort($pastSeminars, function($a, $b) {
                return ($b['timestamp'] ?? 0) <=> ($a['timestamp'] ?? 0);
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
                            </a> - <?php echo format_seminar_list_date_norwegian($seminarMeta['date']); ?>
                            (<?php echo htmlspecialchars($seminarMeta['location'] ?? 'Ukjent sted'); ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>
