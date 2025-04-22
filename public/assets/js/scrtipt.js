document.addEventListener('DOMContentLoaded', function() {

    const header = document.querySelector('header');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    // --- Mobilmeny Toggle ---
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('open'); // Bruker klasse for synlighet
            mobileMenuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            // Bytt ikon på knappen
            mobileMenuButton.classList.toggle('menu-open', isOpen);

             // Legg til/fjern klasse på body for å hindre scrolling når meny er åpen (valgfritt)
             // document.body.classList.toggle('overflow-hidden', isOpen);
        });
    }

    function closeMobileMenu() {
        if (mobileMenu && mobileMenu.classList.contains('open')) {
            mobileMenu.classList.remove('open');
            mobileMenuButton?.setAttribute('aria-expanded', 'false');
            mobileMenuButton?.classList.remove('menu-open');
            // document.body.classList.remove('overflow-hidden');
        }
    }

    // --- Jevn Rulling for Navigasjonslenker ---
    // Inkluderer nå lenker fra BÅDE desktop og mobilmeny
    const scrollLinks = document.querySelectorAll('header nav.desktop-nav a[href^="#"], #mobile-menu a[href^="#"], footer a[href^="#"], a.cta-button[href^="#"]');

    scrollLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId.startsWith('#') && targetId.length > 1) {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();

                    // Beregn header høyde mer dynamisk ved klikk
                    let headerOffset = 70; // Default/fallback
                    if(header && window.getComputedStyle(header).position === 'fixed') {
                        headerOffset = header.offsetHeight;
                    }

                    const elementPosition = targetElement.getBoundingClientRect().top;
                    // Justert offset for å gi litt mer luft (f.eks. 20px)
                    const offsetPosition = window.pageYOffset + elementPosition - headerOffset - 20;

                    window.scrollTo({ top: offsetPosition }); // La CSS håndtere smooth

                    // Lukk mobilmenyen etter klikk på en lenke der
                    closeMobileMenu();

                    // Forsøk å sette aktiv klasse umiddelbart (uendret logikk)
                    try {
                        document.querySelectorAll('header nav.desktop-nav a, #mobile-menu a').forEach(link => link.classList.remove('active'));
                        document.querySelector(`header nav.desktop-nav a[href="${targetId}"]`)?.classList.add('active');
                        document.querySelector(`#mobile-menu a[href="${targetId}"]`)?.classList.add('active');
                    } catch (error) { /* Ignorer feil */ }
                }
            }
        });
    });

    // --- Aktiv Link ved Scrolling ---
    const sections = document.querySelectorAll('section[id]');
    const desktopNavLinks = document.querySelectorAll('header nav.desktop-nav a');
    const mobileNavLinks = document.querySelectorAll('#mobile-menu a');
    let scrollCheckHeaderOffset = 75; // Estimat for scroll-sjekk

    function updateScrollCheckOffset() {
         // Bruk faktisk høyde hvis header er sticky/fixed og synlig
         if (header && (window.getComputedStyle(header).position === 'sticky' || window.getComputedStyle(header).position === 'fixed')) {
              scrollCheckHeaderOffset = header.offsetHeight + 10; // Legg til litt buffer
         } else {
              scrollCheckHeaderOffset = 75; // Fallback hvis header ikke er sticky/fixed
         }
    }

    function setActiveLink() {
        updateScrollCheckOffset(); // Oppdater offset før sjekk
        let currentSectionId = '';
        const scrollPosition = window.pageYOffset;

        sections.forEach(section => {
            const sectionTop = section.offsetTop - scrollCheckHeaderOffset;
            const sectionHeight = section.offsetHeight;
             // Bedre logikk for å finne aktiv seksjon (inkluderer bunnen av siden)
             // Sjekk om toppen av seksjonen er over ELLER lik nåværende scroll + en liten buffer
             // OG at bunnen av seksjonen er under nåværende scroll
            if (scrollPosition >= sectionTop && scrollPosition < (section.offsetTop + sectionHeight - scrollCheckHeaderOffset)) {
                 currentSectionId = section.getAttribute('id');
            }
        });

        // Håndter toppen av siden
        if (scrollPosition < sections[0].offsetTop - scrollCheckHeaderOffset * 1.5) {
             currentSectionId = '';
        }
        // Håndter bunnen av siden
        if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight - 50) {
            currentSectionId = sections[sections.length - 1].getAttribute('id');
        }

        // Oppdater både desktop og mobil-lenker
        [...desktopNavLinks, ...mobileNavLinks].forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href')?.slice(1) === currentSectionId) {
                link.classList.add('active');
            }
        });
    }
    setActiveLink(); // Kjør en gang ved lasting
    window.addEventListener('scroll', setActiveLink);
    window.addEventListener('resize', setActiveLink); // Oppdater ved resize

    // Lukk menyen hvis man klikker utenfor (uendret)
    document.addEventListener('click', function(event) {
        if (mobileMenu && mobileMenuButton) {
             const isClickInsideMenu = mobileMenu.contains(event.target);
             const isClickOnButton = mobileMenuButton.contains(event.target);
             if (!isClickInsideMenu && !isClickOnButton && mobileMenu.classList.contains('open')) {
                 closeMobileMenu();
             }
        }
    });

    console.log("Akarit Google Workspace side lastet (v10 - Mobilmeny implementert).");
});
