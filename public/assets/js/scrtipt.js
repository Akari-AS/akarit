document.addEventListener('DOMContentLoaded', function() {
    console.log("Akarit JS Initializing..."); // Startmelding

    const header = document.querySelector('header');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuCloseButton = document.getElementById('mobile-menu-close-button');
    const body = document.body;

    // Funksjon for å åpne meny
    function openMobileMenu() {
        if (mobileMenu && mobileMenuButton) {
            console.log("Attempting to open mobile menu...");
            mobileMenu.classList.add('open');
            mobileMenuButton.setAttribute('aria-expanded', 'true');
            body.classList.add('mobile-menu-is-open');
            console.log("Mobile menu should be open.");
        } else {
            console.error("Cannot open menu: Button or Panel not found.");
        }
    }

    // Funksjon for å lukke meny
    function closeMobileMenu() {
        if (mobileMenu && mobileMenu.classList.contains('open')) {
            console.log("Attempting to close mobile menu...");
            mobileMenu.classList.remove('open');
            mobileMenuButton?.setAttribute('aria-expanded', 'false');
            body.classList.remove('mobile-menu-is-open');
            console.log("Mobile menu closed.");
        }
    }

    // --- Legg til lyttere ETTER at funksjonene er definert ---

    // Lytter for hamburger-knapp
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', (e) => {
            e.stopPropagation(); // Forhindre bobling
            console.log("Hamburger button clicked!");
            // Veksle menyen basert på nåværende tilstand
            if (mobileMenu && mobileMenu.classList.contains('open')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
    } else {
        console.error("Mobile menu button not found on page load.");
    }

    // Lytter for lukkeknapp inne i menyen
    if (mobileMenuCloseButton) {
        mobileMenuCloseButton.addEventListener('click', (e) => {
            e.stopPropagation();
            console.log("Close button inside menu clicked!");
            closeMobileMenu();
        });
    } else {
        console.warn("Mobile menu close button not found (optional)."); // Mindre kritisk
    }

    // --- Jevn Rulling for Navigasjonslenker (inkl. mobilmeny) ---
    const scrollLinks = document.querySelectorAll('header nav.desktop-nav a[href^="#"], #mobile-menu a[href^="#"], footer a[href^="#"], a.cta-button[href^="#"]');

    scrollLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId.startsWith('#') && targetId.length > 1) {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    console.log("Link clicked, closing menu and allowing scroll to:", targetId);
                    // Viktig: Lukk menyen FØR nettleseren prøver å scrolle
                    closeMobileMenu();

                    // La nettleseren håndtere scroll via CSS scroll-padding-top
                    // Vi trenger ikke e.preventDefault() eller manuell scroll
                } else {
                    console.warn("Scroll target not found:", targetId);
                    // Vurder om vi skal lukke menyen selv om målet ikke finnes?
                    closeMobileMenu();
                }
            }
        });
    });


    // --- Aktiv Link ved Scrolling (Beholdt som før, kan finjusteres) ---
    const sections = document.querySelectorAll('section[id]');
    const desktopNavLinks = document.querySelectorAll('header nav.desktop-nav a');
    const mobileNavLinks = document.querySelectorAll('#mobile-menu a');
    let scrollPaddingTop = 90;

    function updateScrollPadding() {
        try {
            const rootStyle = getComputedStyle(document.documentElement);
            const paddingValue = rootStyle.getPropertyValue('--scroll-padding-top').trim().replace('px', '');
            if (paddingValue && !isNaN(parseInt(paddingValue, 10))) {
                 scrollPaddingTop = parseInt(paddingValue, 10);
            } else {
                 scrollPaddingTop = 90; // Fallback
            }
        } catch (err) {
             console.warn("Could not read --scroll-padding-top, using fallback.", err);
             scrollPaddingTop = 90;
        }
    }

    function setActiveLink() {
        updateScrollPadding();
        let currentSectionId = '';
        const scrollBuffer = 50;
        const scrollPosition = window.pageYOffset + scrollPaddingTop + 1; // +1 for å unngå likhetsfeil

        sections.forEach(section => {
            if (scrollPosition >= section.offsetTop) {
                currentSectionId = section.getAttribute('id');
            }
        });
        if (window.pageYOffset < sections[0].offsetTop - scrollPaddingTop) { // Justert betingelse
             currentSectionId = '';
        }
        if ((window.innerHeight + Math.ceil(window.pageYOffset)) >= document.body.offsetHeight - 2) { // Sjekk nær bunnen
             if (sections.length > 0) { currentSectionId = sections[sections.length - 1].getAttribute('id'); }
        }

        const allNavLinks = [...desktopNavLinks, ...mobileNavLinks];
        allNavLinks.forEach(link => {
            const linkHref = link.getAttribute('href')?.slice(1);
            if (currentSectionId && linkHref === currentSectionId) {
                 if (!link.classList.contains('active')) { // Sett kun hvis ikke allerede aktiv
                    link.classList.add('active');
                 }
            } else {
                 if (link.classList.contains('active')) { // Fjern kun hvis aktiv
                    link.classList.remove('active');
                 }
            }
        });
    }
    setActiveLink(); // Kjør en gang ved lasting
    window.addEventListener('scroll', setActiveLink, { passive: true }); // Bruk passive listener for ytelse
    window.addEventListener('resize', () => { updateScrollPadding(); setActiveLink(); closeMobileMenu(); });

    // Lukk menyen hvis man klikker utenfor selve meny-panelet
    document.addEventListener('click', function(event) {
        // Sjekk om menyen finnes og er åpen FØR vi sjekker klikk
        if (mobileMenu && mobileMenu.classList.contains('open') && mobileMenuButton) {
             const isClickInsidePanel = mobileMenu.contains(event.target);
             const isClickOnButton = mobileMenuButton.contains(event.target);

             // Lukk kun hvis klikket er UTENFOR panelet OG UTENFOR knappen
             if (!isClickInsidePanel && !isClickOnButton) {
                 console.log("Klikk utenfor meny og knapp - lukker menyen.");
                 closeMobileMenu();
             }
        }
    });

    console.log("Akarit JS Initialization Complete.");
});
