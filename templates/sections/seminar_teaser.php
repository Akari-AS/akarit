<?php // templates/sections/seminar_teaser.php

if (!function_exists('format_teaser_datetime_norwegian')) {
    function format_teaser_datetime_norwegian($dateString) {
        $timestamp = strtotime($dateString);
        if ($timestamp === false) {
            return htmlspecialchars($dateString);
        }
        // For 'Tirsdag 17. juni, kl. 08:30'
        $formatter = new IntlDateFormatter(
            'nb_NO',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'Europe/Oslo',
            IntlDateFormatter::GREGORIAN,
            'EEEE d. MMMM' 
        );
        $formattedDate = $formatter->format($timestamp);
        $formattedTime = date('H:i', $timestamp);
        // Legg til ucfirst() her
        return ucfirst($formattedDate) . ', kl. ' . $formattedTime;
    }
}

$allSeminarsForTeaser = get_all_content_metadata('seminar');
$upcomingTeaserSeminars = array_filter($allSeminarsForTeaser, function ($seminar) {
    $seminarTimestamp = strtotime($seminar['date'] ?? 'now -1 day'); 
    
    $isInFuture = ($seminarTimestamp >= time());
    $statusOK = (!isset($seminar['status']) || !in_array(strtolower($seminar['status']), ['past', 'cancelled', 'full']));
    $registrationOpen = (isset($seminar['registration_open']) && strtolower($seminar['registration_open']) === 'true');

    return $isInFuture && $statusOK && $registrationOpen;
});

$teaserSeminar = null;
if (!empty($upcomingTeaserSeminars)) {
    // Sortert etter dato stigende (tidligste først) i get_all_content_metadata
    $teaserSeminar = reset($upcomingTeaserSeminars); 
}
?>

<?php if ($teaserSeminar): ?>
<section id="seminarer-teaser" class="seminar-teaser-section">
    <div class="container">
        <h2>Kommende Frokostseminar: <span><?php echo htmlspecialchars($teaserSeminar['title']); ?></span></h2>
        <p>
            <strong>Dato:</strong> <?php echo format_teaser_datetime_norwegian($teaserSeminar['date']); ?><br>
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
