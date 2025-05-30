<?php // templates/seminar_single.php
// $contentData (seminar data) er tilgjengelig her fra index.php
// $formResult, $formMessage, $formSuccess, $submittedData også tilgjengelig

// Funksjon for å formatere dato og tid på norsk for detaljsiden
if (!function_exists('format_seminar_datetime_norwegian')) {
    function format_seminar_datetime_norwegian($dateString) {
        $timestamp = strtotime($dateString);
        if ($timestamp === false) {
            return htmlspecialchars($dateString); // Returner original streng hvis ugyldig
        }
        // For 'Tirsdag 17. juni 2025, kl. 08:30' (endret til fullt månedsnavn)
        $formatter = new IntlDateFormatter(
            'nb_NO',
            IntlDateFormatter::FULL, // For dagnavn og full dato
            IntlDateFormatter::NONE, // Ingen tid her, vi legger til manuelt for "kl."
            'Europe/Oslo',
            IntlDateFormatter::GREGORIAN,
            'EEEE d. MMMM yyyy' // EEEE for fullt dagnavn, MMMM for fullt månedsnavn
        );
        $formattedDate = $formatter->format($timestamp);
        $formattedTime = date('H:i', $timestamp);
        // Legg til ucfirst() her
        return ucfirst($formattedDate) . ', kl. ' . $formattedTime;
    }
}
?>
<article class="seminar-single-page">
    <?php if ($contentData && isset($contentData['title'])): ?>
        
        <div class="seminar-header-content-wrapper">
            <h1 class="seminar-main-title"><?php echo htmlspecialchars($contentData['title']); ?></h1>
            <p class="seminar-main-meta">
                <strong>Dato:</strong> <?php echo format_seminar_datetime_norwegian($contentData['date']); ?><br>
                <strong>Sted:</strong> <?php echo htmlspecialchars($contentData['location'] ?? 'N/A'); ?>
                <?php if (isset($contentData['contact_person_email'])): ?>
                    <br><strong>Kontakt:</strong> <a href="mailto:<?php echo htmlspecialchars($contentData['contact_person_email']); ?>"><?php echo htmlspecialchars($contentData['contact_person_email']); ?></a>
                <?php endif; ?>
            </p>

            <?php if (!empty($contentData['image']) && file_exists(__DIR__ . '/../public' . $contentData['image'])): ?>
                <figure class="featured-image-container">
                    <img src="<?php echo htmlspecialchars($contentData['image']); ?>" alt="<?php echo htmlspecialchars($contentData['title']); ?>" class="seminar-featured-image">
                </figure>
            <?php endif; ?>

            <div class="seminar-content">
                <?php echo $contentData['content']; // HTML-innhold fra Markdown ?>
            </div>
        </div>

        <?php
        $isRegistrationOpen = (isset($contentData['registration_open']) && strtolower($contentData['registration_open']) === 'true');
        $isFull = (isset($contentData['status']) && strtolower($contentData['status']) === 'full');
        
        $seminarTimestamp = strtotime($contentData['date'] ?? 'now +1 day'); // Default til fremtiden hvis dato mangler
        $isPast = ($seminarTimestamp < time()) || (isset($contentData['status']) && strtolower($contentData['status']) === 'past');

        $showForm = $isRegistrationOpen && !$isFull && !$isPast;
        ?>

        <?php if ($showForm): ?>
            <section id="seminar-registrering" class="seminar-registration-section">
                <div class="container">
                    <h2>Meld deg på seminaret</h2>
                    <?php require __DIR__ . '/forms/seminar_registration_form.php'; ?>
                </div>
            </section>
        <?php elseif($isPast): ?>
             <div class="container seminar-status-container">
                <p class="seminar-status-message info">Dette seminaret har allerede funnet sted.</p>
            </div>
        <?php elseif($isFull): ?>
            <div class="container seminar-status-container">
                <p class="seminar-status-message info">Dette seminaret er dessverre fulltegnet.</p>
            </div>
        <?php elseif(!$isRegistrationOpen && !$isPast): ?>
             <div class="container seminar-status-container">
                <p class="seminar-status-message info">Påmeldingen til dette seminaret er foreløpig ikke åpen eller er stengt. Prøv igjen senere eller ta kontakt.</p>
            </div>
        <?php endif; ?>


        <div class="seminar-navigation-wrapper">
            <div class="seminar-navigation">
                <a href="/seminarer/" class="cta-button secondary">← Tilbake til alle seminarer</a>
            </div>
        </div>

    <?php else: ?>
        <div class="container" style="text-align: center; padding: 40px 0;">
            <h2>Seminar ikke funnet</h2>
            <p>Beklager, vi kunne ikke finne det spesifiserte seminaret.</p>
            <p><a href="/seminarer/" class="cta-button">Se alle seminarer</a></p>
        </div>
    <?php endif; ?>
</article>
