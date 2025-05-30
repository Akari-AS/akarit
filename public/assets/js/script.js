document.addEventListener('DOMContentLoaded', function () {
    // Mobilmeny
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenuPanel = document.getElementById('mobile-menu');

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
    const allNavLinks = Array.from(desktopNavLinks).concat(Array.from(mobileNavLinks));

    const sections = [];
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
            // Vi sjekker om lenken faktisk er en del av menyen vi vil deaktivere,
            // for å unngå å fjerne 'active' fra f.eks. knapper andre steder på siden
            // som kan ha samme href ved en feiltakelse.
            if (link.closest('nav.desktop-nav') || link.closest('.mobile-menu-panel')) {
                link.classList.remove('active');
            }
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
            setActiveStates('/artikler/');
        } else if (currentPath === '/' || currentPath === '/index.php' || currentPath === '' || currentPath.endsWith('/')) { 
            // Endret til currentPath.endsWith('/') for å fange opp lokasjonssider som /oslo/ også,
            // men dette kan bli for generelt. La oss fokusere på forsiden for ankerlenker.
            // Tilbake til en strengere sjekk for forsiden for anker-logikk:
            if (currentPath === '/' || currentPath === '/index.php' || currentPath === '') {
                const scrollPaddingTop = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--scroll-padding-top'), 10) || 70;
                let activeSectionId = '';
                let foundSectionViaScroll = false;

                if (currentHash && currentHash.length > 1) {
                    activeSectionId = currentHash.substring(1);
                } else {
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
                            // Prøv å finne den siste ankerlenken i menyen for "kontakt"
                            let kontaktId = 'kontakt'; // Standard
                            navLinks.forEach(navLink => { // Gå gjennom navLinks for å finne den siste ankerlenken
                                const href = navLink.getAttribute('href');
                                if (href && href.startsWith('/#')) {
                                    kontaktId = href.substring(2); // Oppdaterer med IDen til siste ankerlenke
                                }
                            });
                            activeSectionId = kontaktId;
                        }
                    }
                }
                
                if (activeSectionId) {
                    setActiveStates(`/#${activeSectionId}`);
                } else {
                    if (window.scrollY < 100 && !currentHash) { 
                         setActiveStates('/#fordeler');
                    }
                }
            }
        }
        // Ingen spesiell logikk for /lokasjoner/ siden den ikke er i menyen
    }

    allNavLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            // Denne funksjonen er nå primært for å la updateActiveLink() gjøre jobben
            // ved navigering eller hash-endring.
        });
    });

    window.addEventListener('scroll', updateActiveLink);
    window.addEventListener('hashchange', updateActiveLink);
    window.addEventListener('load', () => { 
         setTimeout(updateActiveLink, 150); 
    });

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
        if(messageTextarea.value) { 
            charCounter.textContent = `${messageTextarea.value.length} av ${maxLength} maks tegn`;
        }
    }
});
