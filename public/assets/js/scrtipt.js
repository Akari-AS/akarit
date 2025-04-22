document.addEventListener('DOMContentLoaded', function() {

    const header = document.querySelector('header');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    // --- Mobilmeny Toggle ---
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', (e) => {
            e.stopPropagation(); // Forhindre at klikket propagerer til dokumentet
            const isOpen = mobileMenu.classList.toggle('open'); // Veksler .open klassen for synlighet
            mobileMenuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
             // Ingen behov for .menu-open klasse på knappen nå
        });
    } else {
        if (!mobileMenuButton) console.error("Element med ID 'mobile-menu-button' ble ikke funnet.");
        if (!mobileMenu) console.error("Element med ID 'mobile-menu' ble ikke funnet.");
    }

    function closeMobileMenu() {
        if (mobileMenu && mobileMenu.classList.contains('open')) {
            mobileMenu.classList.remove('open');
            mobileMenuButton?.setAttribute('aria-expanded', 'false');
        }
    }

    // --- Jevn Rulling for Navigasjonslenker ---
    // Bruker nå nettleserens innebygde smooth scroll via CSS `scroll-behavior` og `scroll-padding-top`
    const scrollLinks = document.querySelectorAll('header nav.desktop-nav a[href^="#"], #mobile-menu a[href^="#"], footer a[href^="#"], a.cta-button[href^="#"]');

    scrollLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            // Sjekk om det er en intern lenke (#...) og ikke bare #
            if (targetId && targetId.startsWith('#') && targetId.length > 1) {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    // La nettleseren håndtere scroll via CSS scroll-padding-top.
                    // Vi trenger KUN å lukke mobilmenyen.
                    closeMobileMenu();

                    // La scroll-event listeneren håndtere aktiv klasse for konsistens.
                } else {
                    console.warn("Scroll target not found:", targetId);
                }
            }
        });
    });


    // --- Aktiv Link ved Scrolling ---
    const sections = document.querySelectorAll('section[id]');
    const desktopNavLinks = document.querySelectorAll('header nav.desktop-nav a');
    const mobileNavLinks = document.querySelectorAll('#mobile-menu a');
    let scrollPaddingTop = 90; // Startverdi, matcher CSS :root

    function updateScrollPadding() {
        const rootStyle = getComputedStyle(document.documentElement);
        // Hent verdien, fjern 'px' og konverter til tall
        const paddingValue = rootStyle.getPropertyValue('--scroll-padding-top').trim().replace('px', '');
        if (paddingValue && !isNaN(parseInt(paddingValue, 10))) {
             scrollPaddingTop = parseInt(paddingValue, 10);
        } else {
             scrollPaddingTop = 90; // Fallback
        }
    }

    function setActiveLink() {
        updateScrollPadding(); // Oppdater padding før sjekk
        let currentSectionId = '';
        // Legg til litt ekstra buffer for å aktivere litt FØR seksjonen når toppen
        const scrollBuffer = 50;
        // Legg til 1px for å unngå "flimmer" ved nøyaktig grense
        const scrollPosition = window.pageYOffset + scrollPaddingTop + 1;

        sections.forEach(section => {
            // Aktiver hvis toppen av seksjonen er over den justerte scroll-posisjonen
            if (scrollPosition >= section.offsetTop) {
                currentSectionId = section.getAttribute('id');
            }
        });

        // Håndter toppen av siden
        if (window.pageYOffset < sections[0].offsetTop - scrollPaddingTop) {
             currentSectionId = '';
        }
        // Håndter bunnen av siden
        if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight - 50) {
             if (sections.length > 0) { // Sikrer at det finnes seksjoner
                currentSectionId = sections[sections.length - 1].getAttribute('id');
             }
        }


        // Oppdater både desktop og mobil-lenker
        const allNavLinks = [...desktopNavLinks, ...mobileNavLinks];
        allNavLinks.forEach(link => {
            link.classList.remove('active');
            if (currentSectionId && link.getAttribute('href')?.slice(1) === currentSectionId) {
                link.classList.add('active');
            }
        });
    }

    // Initialiser og legg til lyttere
    setActiveLink();
    window.addEventListener('scroll', setActiveLink);
    window.addEventListener('resize', () => { setActiveLink(); closeMobileMenu(); });

    // Lukk menyen hvis man klikker utenfor body/main
    document.addEventListener('click', function(event) {
        if (mobileMenu && mobileMenuButton) {
             const isClickInsideHeader = header?.contains(event.target); // Sjekk om klikket er i headeren
             // Lukk hvis klikket er UTENFOR header OG menyen er åpen
             if (!isClickInsideHeader && mobileMenu.classList.contains('open')) {
                 closeMobileMenu();
             }
        }
    });

    console.log("Akarit Google Workspace side lastet (v12 - Mobilmeny Fiks).");
});
