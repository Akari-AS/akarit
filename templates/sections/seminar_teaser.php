<?php // templates/sections/seminar_teaser.php
// Hent kun kommende seminarer for teaseren
$allSeminarsForTeaser = get_all_content_metadata('seminar');
$upcomingTeaserSeminars = array_filter($allSeminarsForTeaser, function ($seminar) {
    $seminarDate = strtotime($seminar['date'] ?? 'now'); // Full datetime for seminar
    $now = time(); // Nåværende tidspunkt

    // Sjekk om seminaret er i fremtiden
    $isInFuture = ($seminarDate >= $now);
    
    // Sjekk status (ikke "past", "cancelled", "full")
    $statusOK = (!isset($seminar['status']) || !in_array(strtolower($seminar['status']), ['past', 'cancelled', 'full']));
    
    // Sjekk om påmelding er åpen
    $registrationOpen = (isset($seminar['registration_open']) && strtolower($seminar['registration_open']) === 'true');

    return $isInFuture && $statusOK && $registrationOpen;
});

$teaserSeminar = null;
if (!empty($upcomingTeaserSeminars)) {
    // Sorter etter dato stigende (tidligste først) for å få det neste seminaret
    usort($upcomingTeaserSeminars, function($a, $b) {
        return ($a['timestamp'] ?? 0) <=> ($b['timestamp'] ?? 0);
    });
    $teaserSeminar = reset($upcomingTeaserSeminars); // Ta det første (neste) kommende seminaret
}
?>

<?php if ($teaserSeminar): ?>
<section id="seminarer-teaser" class="seminar-teaser-section"> <?php // Endret ID her for å matche sitemap ?>
    <div class="container">
        <h2>Kommende Frokostseminar: <span><?php echo htmlspecialchars($teaserSeminar['title']); ?></span></h2>
        <p>
            <strong>Dato:</strong> <?php echo date("d. M Y, kl. H:i", strtotime($teaserSeminar['date'])); ?><br>
            <strong>Sted:</strong> <?php echo htmlspecialchars($teaserSeminar['location'] ?? 'Nærmere info kommer'); ?>
        </p>
        <div class="seminar-teaser-content">
            <p><?php echo htmlspecialchars($teaserSeminar['excerpt'] ?? 'Et spennende seminar du ikke vil gå glipp av!'); ?></p>
            <a href="/seminarer/<?php echo htmlspecialchars($teaserSeminar['slug']); ?>/" class="cta-button">Les mer og meld deg på</a>
            <a href="/seminarer/" class="cta-button secondary" style="margin-left: 15px;">Se alle seminarer</a>
        </div>
    </div>
</section>
<?php endif; ?>
