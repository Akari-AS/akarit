document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenuPanel = document.getElementById('mobile-menu');
    const body = document.body;

    if (mobileMenuButton && mobileMenuPanel) {
        mobileMenuButton.addEventListener('click', function() {
            const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true' || false;
            mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
            mobileMenuButton.classList.toggle('menu-open'); // For ikonbytte
            mobileMenuPanel.classList.toggle('open');
            body.classList.toggle('mobile-menu-is-open'); // For å hindre scrolling på body

            // Skjul/vis ikoner
            // Dette håndteres nå primært av CSS basert på .menu-open klassen på knappen
        });

        // Lukk mobilmenyen når man klikker på en lenke i den
        const mobileMenuLinks = mobileMenuPanel.querySelectorAll('a');
        mobileMenuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                if (mobileMenuPanel.classList.contains('open')) {
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                    mobileMenuButton.classList.remove('menu-open');
                    mobileMenuPanel.classList.remove('open');
                    body.classList.remove('mobile-menu-is-open');
                }
            });
        });
    }

    // Aktiv lenke i navigasjonen basert på scrolling
    const sections = document.querySelectorAll('section[id]');
    const navLinksDesktop = document.querySelectorAll('nav.desktop-nav ul li a');
    const navLinksMobile = document.querySelectorAll('.mobile-menu-panel ul li a');

    function changeLinkState(navLinks) {
        let index = sections.length;

        while(--index && window.scrollY + 100 < sections[index].offsetTop) {} // +100 for litt offset
        
        navLinks.forEach((link) => link.classList.remove('active'));
        // Sjekk om det finnes en lenke som matcher før vi prøver å legge til klassen
        if (navLinks[index]) {
            navLinks[index].classList.add('active');
        }
    }

    // Initial sjekk ved lasting av siden
    if (sections.length > 0) {
        changeLinkState(navLinksDesktop);
        changeLinkState(navLinksMobile); // Sørg for at mobil også sjekkes ved lasting

        window.addEventListener('scroll', function() {
            changeLinkState(navLinksDesktop);
            changeLinkState(navLinksMobile);
        });
    }


    // Tegn-teller for kontaktskjemaets meldingsfelt
    const messageTextarea = document.getElementById('message');
    const charCounter = document.querySelector('.char-counter'); // Endret til querySelector for klasse
    const maxLength = 600; // Synkroniser med HTML hvis du endrer maks

    if (messageTextarea && charCounter) {
        // Funksjon for å oppdatere telleren
        function updateCharCounter() {
            const currentLength = messageTextarea.value.length;
            charCounter.textContent = currentLength + ' av ' + maxLength + ' maks tegn';

            if (currentLength > maxLength) {
                charCounter.style.color = 'red'; // Visuelt hint om overskridelse
                // Du kan også vurdere å deaktivere submit-knappen her
            } else {
                charCounter.style.color = '#777'; // Tilbake til normal farge
            }
        }

        messageTextarea.addEventListener('input', updateCharCounter);
        
        // Kjør en gang ved lasting for å vise initialt antall hvis det er forhåndsutfylt tekst
        updateCharCounter(); 
    }

});
