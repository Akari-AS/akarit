<?php // templates/forms/seminar_registration_form.php
// $contentData (seminardetaljer), $formMessage, $formSuccess, $submittedData er tilgjengelig
?>

<?php if (!empty($formMessage)): ?>
    <div class="form-message <?php echo $formSuccess ? 'success' : 'error'; ?>">
        <?php echo htmlspecialchars($formMessage); ?>
    </div>
<?php endif; ?>

<?php if (!$formSuccess): // Vis skjemaet kun hvis det ikke var en vellykket innsending ?>
<form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>#seminar-registrering" method="post" id="seminarRegistrationForm" class="modern-contact-form seminar-form">
    
    <input type="hidden" name="form_type" value="seminar_registration">
    <input type="hidden" name="seminar_slug" value="<?php echo htmlspecialchars($contentData['slug'] ?? ''); ?>">
    <input type="hidden" name="seminar_title" value="<?php echo htmlspecialchars($contentData['title'] ?? ''); ?>">
    
    <?php // Nye skjulte felter for .ics-generering ?>
    <input type="hidden" name="seminar_datetime_raw" value="<?php echo htmlspecialchars($contentData['date'] ?? ''); ?>">
    <input type="hidden" name="seminar_location_raw" value="<?php echo htmlspecialchars($contentData['location'] ?? ''); ?>">
    <input type="hidden" name="seminar_excerpt_raw" value="<?php echo htmlspecialchars($contentData['excerpt'] ?? ''); ?>">
    <input type="hidden" name="seminar_contact_email_raw" value="<?php echo htmlspecialchars($contentData['contact_person_email'] ?? ''); ?>">
    <input type="hidden" name="seminar_duration_hours_raw" value="<?php echo htmlspecialchars($contentData['duration_hours'] ?? '1.5'); // Default 1.5 timer ?>">


    <input type="hidden" name="form_source_location" value="Seminar: <?php echo htmlspecialchars($contentData['title'] ?? 'Ukjent'); ?>">


    <div class="form-row">
        <div class="form-group form-group-half">
            <label for="firstname_seminar">Fornavn <span class="required">*</span></label> 
            <input type="text" id="firstname_seminar" name="firstname" placeholder="Ditt fornavn" required value="<?php echo old_value('firstname', $submittedData); ?>">
        </div>
        <div class="form-group form-group-half">
            <label for="lastname_seminar">Etternavn</label> 
            <input type="text" id="lastname_seminar" name="lastname" placeholder="Ditt etternavn" value="<?php echo old_value('lastname', $submittedData); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="company_seminar">Bedrift/Organisasjon</label>
        <input type="text" id="company_seminar" name="company" placeholder="Din bedrift (valgfritt)" value="<?php echo old_value('company', $submittedData); ?>">
    </div>
    <div class="form-row">
        <div class="form-group form-group-half">
            <label for="email_seminar">E-post <span class="required">*</span></label>
            <input type="email" id="email_seminar" name="email" placeholder="Din e-postadresse" required value="<?php echo old_value('email', $submittedData); ?>">
        </div>
        <div class="form-group form-group-half">
            <label for="phone_seminar">Telefon <span class="required">*</span></label>
            <input type="tel" id="phone_seminar" name="phone" placeholder="Ditt telefonnummer" required value="<?php echo old_value('phone', $submittedData); ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="num_attendees_seminar">Antall deltakere <span class="required">*</span></label>
        <input type="number" id="num_attendees_seminar" name="num_attendees" min="1" value="<?php echo old_value('num_attendees', $submittedData, '1'); ?>" required style="max-width: 100px;">
    </div>

    <div class="form-group">
        <label for="dietary_restrictions_seminar">Kommentar eller spørsmål</label>
        <textarea id="dietary_restrictions_seminar" name="dietary_restrictions" rows="3" placeholder=""><?php echo old_value('dietary_restrictions', $submittedData); ?></textarea>
    </div>

    <div class="form-group checkbox-group">
        <input type="checkbox" id="privacy_seminar" name="privacy" required <?php echo (isset($submittedData['privacy']) && $submittedData['privacy'] == 'on') ? 'checked' : ''; ?>>
        <label for="privacy_seminar">Jeg har lest og forstått <a href="https://akari.no/personvern/" target="_blank">personvernerklæringen</a> og samtykker til at Akari lagrer mine data for dette arrangementet <span class="required">*</span></label>
    </div>
    
    <div style="text-align: center;">
        <button type="submit" class="cta-button modern-submit">Meld på</button>
    </div>
</form>
<?php endif; ?>
