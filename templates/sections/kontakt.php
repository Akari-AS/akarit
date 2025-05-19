<!-- Kontakt Seksjon -->
<section id="kontakt">
     <div class="container">
        <div class="contact-form-wrapper">
            <div class="contact-info-column">
                <h3>Kontakt <span>Oss</span></h3>
                <p>Vi er klare til å hjelpe deg med å utnytte kraften i Google Workspace. Ta kontakt for en uforpliktende prat!</p>
                <div class="contact-person">
                    <img src="/assets/img/kenneth_bjerke.jpg" alt="Kenneth B. Bjerke" class="contact-person-img">
                    <div>
                        <h4>Kenneth B. Bjerke</h4>
                        <p class="title">Leder System & IT</p>
                        <p>Tlf: <a href="tel:+4796621811">966 21 811</a></p>
                        <p>E-post: <a href="mailto:kenneth@akari.no">kenneth@akari.no</a></p>
                    </div>
                </div>
            </div>

            <div class="contact-form-column">
                <h2 class="form-title"><em>Skriv</em> til oss:</h2>
                <?php if (!empty($formMessage)): ?>
                    <div class="form-message <?php echo $formSuccess ? 'success' : 'error'; ?>">
                        <?php echo htmlspecialchars($formMessage); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#kontakt" method="post" id="contactForm" class="modern-contact-form">
                    <div class="form-row">
                        <div class="form-group form-group-half">
                            <label for="firstname">Navn <span class="required">*</span></label> 
                            <input type="text" id="firstname" name="firstname" placeholder="Fornavn" required value="<?php echo old_value('firstname', $submittedData); ?>">
                        </div>
                        <div class="form-group form-group-half">
                            <label for="lastname">Etternavn</label> 
                            <input type="text" id="lastname" name="lastname" placeholder="Etternavn" value="<?php echo old_value('lastname', $submittedData); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company">Bedrift</label>
                        <input type="text" id="company" name="company" placeholder="Din bedrift (valgfritt)" value="<?php echo old_value('company', $submittedData); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">E-post <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="Din e-postadresse" required value="<?php echo old_value('email', $submittedData); ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefon <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" placeholder="Ditt telefonnummer" required value="<?php echo old_value('phone', $submittedData); ?>">
                    </div>

                    <div class="form-group">
                        <label for="package_interest">Interessert i pakke</label>
                        <select id="package_interest" name="package_interest" class="form-input">
                            <option value="">Velg pakke (valgfritt)</option>
                            <option value="Startpakken" <?php echo (old_value('package_interest', $submittedData) === 'Startpakken' ? 'selected' : ''); ?>>Startpakken</option>
                            <option value="Standardpakken" <?php echo (old_value('package_interest', $submittedData) === 'Standardpakken' ? 'selected' : ''); ?>>Standardpakken</option>
                            <option value="Premiumpakken" <?php echo (old_value('package_interest', $submittedData) === 'Premiumpakken' ? 'selected' : ''); ?>>Premiumpakken</option>
                            <option value="Usikker" <?php echo (old_value('package_interest', $submittedData) === 'Usikker' ? 'selected' : ''); ?>>Usikker/Annet</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Hva kan vi hjelpe deg med? <span class="required">*</span></label>
                        <textarea id="message" name="message" rows="5" required placeholder="Skriv din melding her..."><?php echo old_value('message', $submittedData); ?></textarea>
                        <small class="char-counter">0 av 600 maks tegn</small>
                    </div>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="privacy" name="privacy" required <?php echo isset($submittedData['privacy']) ? 'checked' : ''; ?>>
                        <label for="privacy">Jeg har lest og forstått <a href="https://akari.no/personvern/" target="_blank">personvernerklæringen</a><span class="required">*</span></label> <!-- ENDRET HER -->
                    </div>
                    
                    <div style="text-align: center;">
                        <button type="submit" class="cta-button modern-submit">Send inn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
