document.addEventListener('DOMContentLoaded', function() {
    console.log("Akarit JS Initializing (v14)...");

    const header = document.querySelector('header');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuCloseButton = document.getElementById('mobile-menu-close-button');
    const body = document.body;

    // Sjekk om elementene finnes FØR vi legger til lyttere
    if (!mobileMenuButton) {
        console.error("FATAL: Mobilmeny-KNAPP (#mobile-menu-button) ble ikke funnet!");
        return; // Stopp videre JS hvis knappen mangler
    }
    if (!mobileMenu) {
        console.error("FATAL: Mobilmeny-PANEL (#mobile-menu) ble ikke funnet!");
        return; // Stopp videre JS hvis panelet mangler
    }
     if (!mobileMenuCloseButton) {
        console.warn("Advarsel: Lukkeknapp i meny (#mobile-menu-close-button) ble ikke funnet.");
    }


    // Funksjon for å åpne meny
    function openMobileMenu() {
        console.log("openMobileMenu function called.");
        mobileMenu.classList.add('open');
        mobileMenuButton.setAttribute('aria-expanded', 'true');
        body.classList.add('mobile-menu-is-open');
        console.log("Menu should now have 'open' class and body 'mobile-menu-is-open'.");
    }

    // Funksjon for å lukke meny
    function closeMobileMenu() {
        console.log("closeMobileMenu function called.");
        mobileMenu.classList.remove('open');
        mobileMenuButton.setAttribute('aria-expanded', 'false');
        body.classList.remove('mobile-menu-is-open');
         console.log("Menu should now NOT have 'open' class and body NOT 'mobile-menu-is-open'.");
    }

    // --- Legg til Hoved-lyttere ---

    // Lytter for hamburger-knapp
    mobileMenuButton.addEventListener('click', (e) => {
        e.stopPropagation();
        console.log("Hamburger button click event fired!");
        // Enkel toggle: Hvis menyen har 'open', lukk den, ellers åpne den.
        if (mobileMenu.classList.contains('open')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    });

    // Lytter for lukkeknapp inne i menyen (hvis den finnes)
    if (mobileMenuCloseButton) {
        mobileMenuCloseButton.addEventListener('click', (e) => {
            e.stopPropagation();
            console.log("Close button inside menu click event fired!");
            closeMobileMenu();
        });
    }

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
                    // La CSS håndtere scrolling (pga. scroll-padding-top og scroll-behavior)
                } else {
                    console.warn("Scroll target not found:", targetId);
                    closeMobileMenu(); // Lukk meny uansett
                }
            }
        });
    });


    // --- Aktiv Link ved Scrolling (Beholdt som før, men kan forenkles senere) ---
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
            link.classList.toggle('active', isActive); // Enklere toggle av klasse
        });
    }
    setActiveLink(); // Kjør ved lasting
    window.addEventListener('scroll', setActiveLink, { passive: true });
    window.addEventListener('resize', () => { updateScrollPadding(); setActiveLink(); closeMobileMenu(); });

    // Lukk menyen hvis man klikker utenfor selve meny-panelet
    // Endret til å lytte på body, men sjekke target
     body.addEventListener('click', function(event) {
         if (mobileMenu && mobileMenu.classList.contains('open') && mobileMenuButton) {
              // Sjekk om klikket var på menyen ELLER på knappen som åpnet den
              const isClickInsidePanel = mobileMenu.contains(event.target);
              const isClickOnButton = mobileMenuButton.contains(event.target);

              if (!isClickInsidePanel && !isClickOnButton) {
                  console.log("Klikk utenfor meny og knapp (på body) - lukker menyen.");
                  closeMobileMenu();
              }
         }
     });


    console.log("Akarit JS Initialization Complete (v14).");
});
