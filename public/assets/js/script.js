document.addEventListener('DOMContentLoaded', function () {
    // Mobilmeny
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenuPanel = document.getElementById('mobile-menu');
    // const mainContent = document.querySelector('main'); // Ikke brukt aktivt nå

    if (mobileMenuButton && mobileMenuPanel) {
        mobileMenuButton.addEventListener('click', function () {
            const isOpen = this.classList.toggle('menu-open');
            this.setAttribute('aria-expanded', isOpen);
            mobileMenuPanel.classList.toggle('open');
            document.body.classList.toggle('mobile-menu-is-open');
        });

        mobileMenuPanel.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (mobileMenuPanel.classList.contains('open')) {
                    mobileMenuButton.click();
                }
            });
        });
    }

    // Aktiv menylenke-logikk
    const desktopNavLinks = document.querySelectorAll('nav.desktop-nav a');
    const mobileNavLinks = document.querySelectorAll('.mobile-menu-panel a');
    const allNavLinks = Array.from(desktopNavLinks).concat(Array.from(mobileNavLinks)); // Samle alle nav-lenker

    const sections = [];
    // Samle kun ankerseksjoner for scroll-logikk
    allNavLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.startsWith('/#') && href.length > 2) {
            try {
                const sectionId = href.substring(2);
                const section = document.getElementById(sectionId);
                if (section) {
                    sections.push(section);
                }
            } catch (e) {
                // console.warn(`Could not find section for href: ${href}`, e);
            }
        }
    });

    function clearAllActiveStates() {
        allNavLinks.forEach(link => {
            link.classList.remove('active');
        });
    }
    
    function setActiveStates(targetHref) {
        clearAllActiveStates();
        allNavLinks.forEach(link => {
            if (link.getAttribute('href') === targetHref) {
                link.classList.add('active');
            }
        });
    }

    function updateActiveLink() {
        const currentPath = window.location.pathname;
        const currentHash = window.location.hash;

        clearAllActiveStates();

        if (currentPath.startsWith('/artikler')) {
            // Hvis vi er på en artikkelside eller artikkellistesiden
            allNavLinks.forEach(link => {
                if (link.getAttribute('href') === '/artikler/') {
                    link.classList.add('active');
                }
            });
        } else if (currentPath === '/' || currentPath === '/index.php' || currentPath === '') {
            // Vi er på forsiden, bruk scroll/hash-logikk
            const scrollPaddingTop = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--scroll-padding-top'), 10) || 70;
            let activeSectionId = '';
            let foundSectionViaScroll = false;

            if (currentHash && currentHash.length > 1) {
                // Hvis det er en hash, prioriter den
                activeSectionId = currentHash.substring(1);
            } else {
                // Ellers, bruk scroll-posisjon
                for (let i = 0; i < sections.length; i++) {
                    const section = sections[i];
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;
                    const scrollPosition = window.scrollY;

                    if (scrollPosition >= sectionTop - scrollPaddingTop - 50 && 
                        scrollPosition < sectionTop + sectionHeight - scrollPaddingTop - 50) {
                        activeSectionId = section.id;
                        foundSectionViaScroll = true;
                        break;
                    }
                }
                
                if (!foundSectionViaScroll && sections.length > 0) {
                    if (window.scrollY < sections[0].offsetTop - scrollPaddingTop - 50) {
                        activeSectionId = 'fordeler'; 
                    } else if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 50) {
                        activeSectionId = 'kontakt'; 
                    }
                }
            }
            
            if (activeSectionId) {
                setActiveStates(`/#${activeSectionId}`);
            } else {
                // Fallback hvis ingen seksjon er aktiv (f.eks. midt mellom seksjoner)
                // eller hvis vi er helt på toppen uten hash og ingen seksjon er enda i "aktiv sone"
                // Ofte ønsker man da at "Hjem" eller første relevante menypunkt er aktivt.
                // For nå, hvis ingen spesifikk, la "Google Workspace" (#fordeler) være aktiv hvis vi er på toppen.
                if (window.scrollY < 100 && !currentHash) { // Anta 100px som "toppen"
                     setActiveStates('/#fordeler');
                }
            }
        }
        // Hvis ingen av betingelsene over slår til (f.eks. en annen ukjent side),
        // vil ingen lenker være aktive, noe som er greit.
    }

    // Lytt etter klikk på ALLE navigasjonslenker for å oppdatere umiddelbart (om mulig)
    allNavLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            const href = this.getAttribute('href');
            // For ankerlenker på samme side, eller lenker til /artikler/
            if (href.startsWith('/#') || href === '/artikler/') {
                 // La standard navigasjon skje (enten scroll eller sidebytte)
                 // setActiveStates(href); // Sett umiddelbart for raskere feedback
                 // La updateActiveLink() håndtere det mer nøyaktig etter navigasjon/scroll
            }
             // For andre lenker (f.eks. eksterne), la standard oppførsel skje uten å endre active state her
        });
    });

    window.addEventListener('scroll', updateActiveLink);
    window.addEventListener('hashchange', updateActiveLink);
    window.addEventListener('load', () => { // Endret fra DOMContentLoaded til load for å være sikker på at offsetTop er korrekt
         setTimeout(updateActiveLink, 150); // Gi litt ekstra tid
    });


    // Karakterteller for meldingsfelt (uendret)
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
        if(messageTextarea.value) { // Oppdater ved lasting hvis det er eksisterende tekst
            charCounter.textContent = `${messageTextarea.value.length} av ${maxLength} maks tegn`;
        }
    }
});
