document.addEventListener('DOMContentLoaded', function() {

    const header = document.querySelector('header');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    // --- Mobilmeny Toggle ---
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', (e) => {
            e.stopPropagation(); // Forhindre at klikket propagerer til dokumentet
            const isOpen = mobileMenu.classList.toggle('open');
            mobileMenuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            mobileMenuButton.classList.toggle('menu-open', isOpen);
        });
    }

    function closeMobileMenu() {
        if (mobileMenu && mobileMenu.classList.contains('open')) {
            mobileMenu.classList.remove('open');
            mobileMenuButton?.setAttribute('aria-expanded', 'false');
            mobileMenuButton?.classList.remove('menu-open');
        }
    }

    // --- Jevn Rulling for Navigasjonslenker ---
    // Bruker nå nettleserens innebygde smooth scroll via CSS `scroll-behavior` og `scroll-padding-top`
    // Vi trenger kun å lukke menyen og håndtere aktiv klasse her.
    const scrollLinks = document.querySelectorAll('header nav.desktop-nav a[href^="#"], #mobile-menu a[href^="#"], footer a[href^="#"], a.cta-button[href^="#"]');

    scrollLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            // Sjekk om det er en intern lenke
            if (targetId && targetId.startsWith('#') && targetId.length > 1) {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    // Vi lar preventDefault være kommentert ut foreløpig
                    // for å la nettleseren håndtere scrollingen naturlig med scroll-padding-top.
                    // Hvis det oppstår problemer, kan vi aktivere den og JS-scrolling igjen.
                    // e.preventDefault();

                    // Lukk mobilmenyen uansett
                    closeMobileMenu();

                    // Sett aktiv klasse (kan gjøres umiddelbart eller via scroll-event)
                    // La scroll-event håndtere det for konsistens.
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
        const paddingValue = rootStyle.getPropertyValue('--scroll-padding-top').trim();
        if (paddingValue) {
             scrollPaddingTop = parseInt(paddingValue, 10) || 90;
        }
    }

    function setActiveLink() {
        updateScrollPadding(); // Oppdater padding før sjekk
        let currentSectionId = '';
        // Legg til litt ekstra buffer for å aktivere litt før seksjonen når toppen
        const scrollBuffer = 50;
        const scrollPosition = window.pageYOffset + scrollPaddingTop + scrollBuffer;

        sections.forEach(section => {
            if (scrollPosition >= section.offsetTop) {
                currentSectionId = section.getAttribute('id');
            }
        });

        // Håndter toppen av siden
        if (window.pageYOffset < sections[0].offsetTop - scrollPaddingTop * 1.5) {
             currentSectionId = '';
        }
        // Håndter bunnen av siden (ingen endring nødvendig her)
        if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight - 50) {
             // Behold siste aktive eller sett siste manuelt hvis currentSectionId er tom
             if (!currentSectionId && sections.length > 0) {
                  currentSectionId = sections[sections.length - 1].getAttribute('id');
             }
        }


        // Oppdater både desktop og mobil-lenker
        const allNavLinks = [...desktopNavLinks, ...mobileNavLinks];
        allNavLinks.forEach(link => {
            link.classList.remove('active');
            // Sjekk om linkens href matcher OG at currentSectionId ikke er tom
            if (currentSectionId && link.getAttribute('href')?.slice(1) === currentSectionId) {
                link.classList.add('active');
            }
        });
    }

    // Initialiser og legg til lyttere
    setActiveLink();
    window.addEventListener('scroll', setActiveLink);
    window.addEventListener('resize', () => {
        setActiveLink();
        closeMobileMenu(); // Lukk meny ved resize for enkelhets skyld
    });

    // Lukk menyen hvis man klikker utenfor
    document.addEventListener('click', function(event) {
        if (mobileMenu && mobileMenuButton) {
             const isClickInsideMenu = mobileMenu.contains(event.target);
             const isClickOnButton = mobileMenuButton.contains(event.target);
             // Lukk kun hvis klikket er UTENFOR BÅDE menyen OG knappen
             if (!isClickInsideMenu && !isClickOnButton && mobileMenu.classList.contains('open')) {
                 closeMobileMenu();
             }
        }
    });

    console.log("Akarit Google Workspace side lastet (v11 - Mobilmeny/Scroll Fiks).");
});
