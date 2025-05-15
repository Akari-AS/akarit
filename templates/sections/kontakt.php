<!-- Kontakt Seksjon -->
<section id="kontakt">
     <div class="container">
        <h2>Klar for en smartere arbeidsdag?</h2> <!-- Forble lik -->
        <p>Ta det første steget mot en mer effektiv og samarbeidsorientert bedrift. Fyll ut skjemaet, så tar vi kontakt for en uforpliktende prat.</p>

        <div class="contact-form">
            <?php if (!empty($formMessage)): ?>
                <div class="form-message <?php echo $formSuccess ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($formMessage); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#kontakt" method="post" id="contactForm">
                 <div class="form-group">
                    <label for="name">Ditt navn <span style="color: red;">*</span></label> <!-- Endret -->
                    <input type="text" id="name" name="name" required value="<?php echo old_value('name', $submittedData); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Din e-post <span style="color: red;">*</span></label> <!-- Endret -->
                    <input type="email" id="email" name="email" required value="<?php echo old_value('email', $submittedData); ?>">
                </div>
                <div class="form-group">
                    <label for="company">Firmanavn</label> <!-- Forble lik -->
                    <input type="text" id="company" name="company" value="<?php echo old_value('company', $submittedData); ?>">
                </div>
                <div class="form-group">
                    <label for="message">Hva ønsker du hjelp med? <span style="color: red;">*</span></label> <!-- Forble lik -->
                    <textarea id="message" name="message" rows="6" required placeholder="Fortell oss kort om dine behov eller spørsmål..."><?php echo old_value('message', $submittedData); ?></textarea>
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="cta-button">Send forespørsel</button> <!-- Endret -->
                </div>
            </form>
        </div>
    </div>
</section>
