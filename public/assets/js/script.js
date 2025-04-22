document.addEventListener('DOMContentLoaded', function() {
    console.log("Akarit JS Initializing (v15 - Best Practice Toggle)...");

    const header = document.querySelector('header');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    // Fjernet referanse til close button inne i menyen
    // const mobileMenuCloseButton = document.getElementById('mobile-menu-close-button');
    const body = document.body;

    if (!mobileMenuButton) console.error("FATAL: Mobilmeny-KNAPP (#mobile-menu-button) ble ikke funnet!");
    if (!mobileMenu) console.error("FATAL: Mobilmeny-PANEL (#mobile-menu) ble ikke funnet!");


    // Funksjon for å veksle menyen
    function toggleMobileMenu() {
        if (!mobileMenu || !mobileMenuButton) return; // Sjekk om elementene finnes

        const isOpen = mobileMenu.classList.toggle('open'); // Veksler .open på panelet
        mobileMenuButton.classList.toggle('menu-open', isOpen); // Veksler .menu-open på knappen (for ikonbytte)
        mobileMenuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        body.classList.toggle('mobile-menu-is-open', isOpen); // Veksler body-klassen
        console.log(`Mobilmeny ${isOpen ? 'åpnet' : 'lukket'}.`);
    }

    // Funksjon for å lukke meny (hvis den er åpen)
    function closeMobileMenu() {
        if (mobileMenu && mobileMenu.classList.contains('open')) {
            console.log("closeMobileMenu function called.");
            mobileMenu.classList.remove('open');
            mobileMenuButton?.classList.remove('menu-open'); // Fjern klasse fra knapp også
            mobileMenuButton?.setAttribute('aria-expanded', 'false');
            body.classList.remove('mobile-menu-is-open');
             console.log("Mobile menu explicitly closed.");
        }
    }

    // --- Legg til Hoved-lyttere ---

    // Lytter for hamburger/kryss-knapp
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', (e) => {
            e.stopPropagation();
            console.log("Toggle button clicked!");
            toggleMobileMenu(); // Kall veksle-funksjonen
        });
    }

    // Fjernet lytter for intern lukkeknapp

    // --- Jevn Rulling for Navigasjonslenker ---
    const scrollLinks = document.querySelectorAll('header nav.desktop-nav a[href^="#"], #mobile-menu a[href^="#"], footer a[href^="#"], a.cta-button[href^="#"]');

    scrollLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId.startsWith('#') && targetId.length > 1) {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                     console.log("Link clicked, closing menu and allowing scroll to:", targetId);
                    // Lukk menyen FØR scrolling
                    closeMobileMenu();
                    // La CSS håndtere scrollingen
                } else {
                    console.warn("Scroll target not found:", targetId);
                    closeMobileMenu(); // Lukk meny uansett
                }
            }
        });
    });


    // --- Aktiv Link ved Scrolling (Uendret) ---
    const sections = document.querySelectorAll('section[id]');
    const desktopNavLinks = document.querySelectorAll('header nav.desktop-nav a');
    const mobileNavLinks = document.querySelectorAll('#mobile-menu a');
    let scrollPaddingTop = 90;

    function updateScrollPadding() {
        try {
            const rootStyle = getComputedStyle(document.documentElement);
            const paddingValue = rootStyle.getPropertyValue('--scroll-padding-top').trim().replace('px', '');
            scrollPaddingTop = !isNaN(parseInt(paddingValue, 10)) ? parseInt(paddingValue, 10) : 90;
        } catch (err) { scrollPaddingTop = 90; }
    }

    function setActiveLink() {
        updateScrollPadding();
        let currentSectionId = '';
        const scrollBuffer = 50;
        const scrollPosition = window.pageYOffset + scrollPaddingTop + 1;

        sections.forEach(section => {
            if (scrollPosition >= section.offsetTop) { currentSectionId = section.getAttribute('id'); }
        });
        if (window.pageYOffset < sections[0].offsetTop - scrollPaddingTop) { currentSectionId = ''; }
        if ((window.innerHeight + Math.ceil(window.pageYOffset)) >= document.body.offsetHeight - 2) {
             if (sections.length > 0) { currentSectionId = sections[sections.length - 1].getAttribute('id'); }
        }

        const allNavLinks = [...desktopNavLinks, ...mobileNavLinks];
        allNavLinks.forEach(link => {
            const linkHref = link.getAttribute('href')?.slice(1);
            const isActive = currentSectionId && linkHref === currentSectionId;
            link.classList.toggle('active', isActive);
        });
    }
    setActiveLink();
    window.addEventListener('scroll', setActiveLink, { passive: true });
    window.addEventListener('resize', () => { updateScrollPadding(); setActiveLink(); closeMobileMenu(); });

    // Lukk menyen hvis man klikker utenfor selve meny-panelet
     document.addEventListener('click', function(event) {
         if (mobileMenu && mobileMenu.classList.contains('open') && mobileMenuButton) {
              const isClickInsidePanel = mobileMenu.contains(event.target);
              const isClickOnButton = mobileMenuButton.contains(event.target);

              if (!isClickInsidePanel && !isClickOnButton) {
                  console.log("Klikk utenfor meny og knapp (på body) - lukker menyen.");
                  closeMobileMenu();
              }
         }
     });


    console.log("Akarit JS Initialization Complete (v15 - Best Practice Toggle).");
});
