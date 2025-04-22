document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM Loaded. Initializing scripts..."); // Bekreft at scriptet starter

    const header = document.querySelector('header');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuCloseButton = document.getElementById('mobile-menu-close-button');

    // --- Mobilmeny Toggle ---
    if (mobileMenuButton && mobileMenu) {
        console.log("Mobilmeny-knapp og panel funnet."); // Bekreft at elementene finnes

        mobileMenuButton.addEventListener('click', (e) => {
            e.stopPropagation(); // Forhindre at klikket propagerer
            console.log("Hamburger-knapp klikket!"); // Bekreft klikk
            openMobileMenu();
        });

        // Legg til lytter for lukkeknapp inne i menyen
        if (mobileMenuCloseButton) {
            mobileMenuCloseButton.addEventListener('click', (e) => {
                e.stopPropagation();
                console.log("Lukkeknapp i meny klikket!");
                closeMobileMenu();
            });
        } else {
             console.error("Element med ID 'mobile-menu-close-button' ble ikke funnet.");
        }

    } else {
        if (!mobileMenuButton) console.error("Element med ID 'mobile-menu-button' ble ikke funnet.");
        if (!mobileMenu) console.error("Element med ID 'mobile-menu' ble ikke funnet.");
    }

    function openMobileMenu() {
        if (mobileMenu && !mobileMenu.classList.contains('open')) {
            mobileMenu.classList.add('open');
            mobileMenuButton?.setAttribute('aria-expanded', 'true');
            document.body.classList.add('mobile-menu-is-open'); // Hindre body scroll
            console.log("Mobilmeny åpnet.");
        }
    }

    function closeMobileMenu() {
        if (mobileMenu && mobileMenu.classList.contains('open')) {
            mobileMenu.classList.remove('open');
            mobileMenuButton?.setAttribute('aria-expanded', 'false');
             document.body.classList.remove('mobile-menu-is-open'); // Tillat body scroll igjen
             console.log("Mobilmeny lukket.");
        }
    }

    // --- Jevn Rulling for Navigasjonslenker ---
    const scrollLinks = document.querySelectorAll('header nav.desktop-nav a[href^="#"], #mobile-menu a[href^="#"], footer a[href^="#"], a.cta-button[href^="#"]');

    scrollLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId.startsWith('#') && targetId.length > 1) {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                     console.log("Scrolling til:", targetId);
                    // Lukk mobilmenyen FØR scrolling for å unngå hopp
                    closeMobileMenu();

                    // La CSS håndtere selve scrollingen via scroll-padding-top
                    // Vi trenger ikke preventDefault hvis vi lar href="#" gjøre jobben sammen med CSS.
                    // e.preventDefault();

                    // Sett aktiv klasse manuelt umiddelbart for bedre respons (valgfritt)
                     try {
                         document.querySelectorAll('header nav.desktop-nav a, #mobile-menu a').forEach(link => link.classList.remove('active'));
                         document.querySelector(`header nav.desktop-nav a[href="${targetId}"]`)?.classList.add('active');
                         document.querySelector(`#mobile-menu a[href="${targetId}"]`)?.classList.add('active');
                     } catch (error) { /* Ignorer feil */ }

                } else {
                    console.warn("Scroll target not found:", targetId);
                }
            }
        });
    });


    // --- Aktiv Link ved Scrolling (Logikk beholdt, men kan finjusteres) ---
    const sections = document.querySelectorAll('section[id]');
    const desktopNavLinks = document.querySelectorAll('header nav.desktop-nav a');
    const mobileNavLinks = document.querySelectorAll('#mobile-menu a');
    let scrollPaddingTop = 90;

    function updateScrollPadding() {
        const rootStyle = getComputedStyle(document.documentElement);
        const paddingValue = rootStyle.getPropertyValue('--scroll-padding-top').trim().replace('px', '');
        if (paddingValue && !isNaN(parseInt(paddingValue, 10))) {
             scrollPaddingTop = parseInt(paddingValue, 10);
        } else {
             scrollPaddingTop = 90; // Fallback
        }
    }

    function setActiveLink() {
        // Kanskje ikke oppdater padding HVER scroll for ytelse? Kun ved resize?
        // updateScrollPadding();
        let currentSectionId = '';
        const scrollBuffer = 50;
        const scrollPosition = window.pageYOffset + scrollPaddingTop + scrollBuffer;

        sections.forEach(section => {
            if (scrollPosition >= section.offsetTop) {
                currentSectionId = section.getAttribute('id');
            }
        });
        if (window.pageYOffset < sections[0].offsetTop - scrollPaddingTop * 1.5) { currentSectionId = ''; }
        if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight - 50) {
             if (sections.length > 0) { currentSectionId = sections[sections.length - 1].getAttribute('id'); }
        }

        const allNavLinks = [...desktopNavLinks, ...mobileNavLinks];
        allNavLinks.forEach(link => {
            link.classList.remove('active');
            if (currentSectionId && link.getAttribute('href')?.slice(1) === currentSectionId) {
                link.classList.add('active');
            }
        });
    }
    // Kjør ved lasting, scroll og resize
    updateScrollPadding(); // Kjør én gang for å få riktig padding
    setActiveLink();
    window.addEventListener('scroll', setActiveLink);
    window.addEventListener('resize', () => { updateScrollPadding(); setActiveLink(); closeMobileMenu(); });

    // Lukk menyen hvis man klikker utenfor selve meny-panelet
    document.addEventListener('click', function(event) {
        if (mobileMenu && mobileMenuButton) {
             // Sjekk om klikket var på selve panelet eller knappen
             const isClickInsidePanel = mobileMenu.contains(event.target);
             const isClickOnButton = mobileMenuButton.contains(event.target);

             // Lukk hvis menyen er åpen OG klikket var UTENFOR panelet OG UTENFOR knappen
             if (mobileMenu.classList.contains('open') && !isClickInsidePanel && !isClickOnButton) {
                 console.log("Klikk utenfor meny og knapp - lukker menyen.");
                 closeMobileMenu();
             }
        }
    });

    console.log("Akarit Google Workspace side lastet (v13 - Fullskjerm Mobilmeny).");
});
