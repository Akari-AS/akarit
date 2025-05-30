document.addEventListener('DOMContentLoaded', function () {
    // Mobilmeny
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenuPanel = document.getElementById('mobile-menu');
    const mainContent = document.querySelector('main'); // Anta at hovedinnholdet er i <main>

    if (mobileMenuButton && mobileMenuPanel) {
        mobileMenuButton.addEventListener('click', function () {
            const isOpen = this.classList.toggle('menu-open');
            this.setAttribute('aria-expanded', isOpen);
            mobileMenuPanel.classList.toggle('open');
            document.body.classList.toggle('mobile-menu-is-open');
        });

        // Lukk mobilmeny når en lenke klikkes
        mobileMenuPanel.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (mobileMenuPanel.classList.contains('open')) {
                    mobileMenuButton.click(); // Simulerer et klikk for å lukke og bytte ikon
                }
            });
        });
    }

    // Aktiv menylenke-logikk (Desktop og Mobil)
    // Velger lenker som starter med /# for å kun sikte mot ankerlenker på forsiden
    const navLinks = document.querySelectorAll('nav.desktop-nav a[href^="/#"], .mobile-menu-panel a[href^="/#"]');
    const sections = [];

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        // Sikrer at vi bare jobber med gyldige ankerlenker som starter med /#
        if (href && href.startsWith('/#') && href.length > 2) {
            try {
                const sectionId = href.substring(2); // Fjerner '/#'
                const section = document.getElementById(sectionId);
                if (section) {
                    sections.push(section);
                }
            } catch (e) {
                console.warn(`Could not find section for href: ${href}`, e);
            }
        }
    });

    function clearAllActiveStates() {
        navLinks.forEach(link => {
            link.classList.remove('active');
        });
    }
    
    function setActiveStates(targetHref) {
        clearAllActiveStates();
        navLinks.forEach(link => {
            if (link.getAttribute('href') === targetHref) {
                link.classList.add('active');
            }
        });
    }


    function updateActiveLinkOnScroll() {
        const currentPath = window.location.pathname;
        // Kjør kun scroll-logikk hvis vi er på forsiden
        if (currentPath !== '/' && currentPath !== '/index.php' && currentPath !== '') {
            clearAllActiveStates(); // Ingen skal være aktive på andre sider
            return;
        }

        const scrollPaddingTop = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--scroll-padding-top'), 10) || 70;
        let currentSectionId = '';
        let foundSection = false;

        // Finn den første seksjonen som er innenfor "aktiv sone" fra toppen
        for (let i = 0; i < sections.length; i++) {
            const section = sections[i];
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const scrollPosition = window.scrollY;

            // Er toppen av seksjonen over "aktiv sone" og bunnen av seksjonen under "aktiv sone"?
            if (scrollPosition >= sectionTop - scrollPaddingTop - 50 && // Litt buffer over
                scrollPosition < sectionTop + sectionHeight - scrollPaddingTop - 50) { // Litt buffer under
                currentSectionId = section.id;
                foundSection = true;
                break;
            }
        }
        
        // Fallback: Hvis ingen seksjon er perfekt i sonen (f.eks. helt på toppen før første seksjon)
        // eller helt på bunnen etter siste seksjon.
        if (!foundSection && sections.length > 0) {
            if (window.scrollY < sections[0].offsetTop - scrollPaddingTop - 50) {
                 // Hvis vi er over den første seksjonen (f.eks. i hero), marker "Google Workspace" (#fordeler)
                currentSectionId = 'fordeler'; // Anta at "Google Workspace" peker til #fordeler
            } else if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 50) {
                // Hvis vi er nær bunnen av siden, og siste seksjon er Kontakt
                currentSectionId = 'kontakt'; // Anta at siste menyvalg er Kontakt
            }
        }


        if (currentSectionId) {
            setActiveStates(`/#${currentSectionId}`);
        } else {
            clearAllActiveStates();
        }
    }

    // Håndter klikk på ankerlenker
    navLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            const href = this.getAttribute('href');
            const currentPath = window.location.pathname;

            if (href.startsWith('/#')) {
                if (currentPath === '/' || currentPath === '/index.php' || currentPath === '') {
                    // Vi er allerede på forsiden, la smooth scroll skje og oppdater active state
                    // event.preventDefault(); // Fjern hvis du vil ha default browser scroll
                    const targetId = href.substring(2);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        // setActiveStates(href); // Sett umiddelbart for respons
                        // La onScroll håndtere det for nøyaktighet, eller uncomment for umiddelbar.
                        // window.scrollTo(...) kan brukes her for custom smooth scroll
                    }
                } else {
                    // Vi er på en underside, naviger til forsiden + anker
                    // Standard nettleser-navigasjon vil skje.
                    // `hashchange` eller `onScroll` på neste side vil håndtere active state.
                    clearAllActiveStates(); // Fjern aktiv fra nåværende side
                }
            }
            // For lenker som ikke er /#, la standard oppførsel skje (f.eks. /artikler/)
        });
    });

    // Oppdater ved scroll og ved lasting av siden
    window.addEventListener('scroll', updateActiveLinkOnScroll);
    
    // Kjør en gang ved lasting for å sette korrekt initiell tilstand
    // Gi DOM litt tid til å rendre og kalkulere offsetTop korrekt, spesielt hvis det er bilder
    setTimeout(updateActiveLinkOnScroll, 100);

    // Oppdater også når hash endres, for eksempel ved bruk av tilbake/fremover-knapper
    // eller hvis en lenke på siden endrer hashen.
    window.addEventListener('hashchange', function() {
        const currentPath = window.location.pathname;
        if (currentPath === '/' || currentPath === '/index.php' || currentPath === '') {
             updateActiveLinkOnScroll(); // Bruk scroll-logikken som tar hensyn til posisjon
        } else {
            clearAllActiveStates();
        }
    });


    // Karakterteller for meldingsfelt i kontaktskjema
    const messageTextarea = document.getElementById('message');
    const charCounter = document.querySelector('.char-counter');
    if (messageTextarea && charCounter) {
        const maxLength = 600; 
        messageTextarea.addEventListener('input', function () {
            const currentLength = this.value.length;
            charCounter.textContent = `${currentLength} av ${maxLength} maks tegn`;
            if (currentLength > maxLength) {
                charCounter.style.color = 'red';
            } else {
                charCounter.style.color = '#78909C'; 
            }
        });
        charCounter.textContent = `${messageTextarea.value.length} av ${maxLength} maks tegn`;
    }
});
