document.addEventListener('DOMContentLoaded', function() {

    // --- Jevn Rulling for Navigasjonslenker ---
    const scrollLinks = document.querySelectorAll('header nav a[href^="#"], footer a[href^="#"], a.cta-button[href^="#"], #mobile-menu a[href^="#"]'); // Inkluder mobilmeny-lenker

    scrollLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId.startsWith('#') && targetId.length > 1) {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();

                    const headerOffset = document.querySelector('header')?.offsetHeight || 70;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = window.pageYOffset + elementPosition - headerOffset - 25;

                    window.scrollTo({ top: offsetPosition });

                    // Lukk mobilmeny hvis den er åpen
                    closeMobileMenu();

                    // Oppdater aktiv link umiddelbart (valgfritt men smooth)
                    document.querySelectorAll('header nav a, #mobile-menu a').forEach(link => link.classList.remove('active'));
                     // Finn korresponderende desktop/mobil link for å sette aktiv klasse
                     try {
                          document.querySelector(`header nav a[href="${targetId}"]`)?.classList.add('active');
                          document.querySelector(`#mobile-menu a[href="${targetId}"]`)?.classList.add('active');
                     } catch (error) {
                         console.warn("Kunne ikke sette aktiv klasse umiddelbart:", error);
                     }
                }
            }
        });
    });

    // --- Aktiv Link ved Scrolling ---
    const sections = document.querySelectorAll('section[id]');
    const desktopNavLinks = document.querySelectorAll('header nav a');
    const mobileNavLinks = document.querySelectorAll('#mobile-menu a'); // Velg mobil-lenker
    const headerHeightEstimate = 90;

    function setActiveLink() {
        let currentSectionId = '';
        const scrollPosition = window.pageYOffset;

        sections.forEach(section => {
            const sectionTop = section.offsetTop - headerHeightEstimate;
            const sectionHeight = section.offsetHeight;
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                 currentSectionId = section.getAttribute('id');
            }
        });
        if (scrollPosition < sections[0].offsetTop - headerHeightEstimate * 1.5) { currentSectionId = ''; }
        if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight - 50) { currentSectionId = sections[sections.length - 1].getAttribute('id'); }

        // Oppdater både desktop og mobil-lenker
        [...desktopNavLinks, ...mobileNavLinks].forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href')?.slice(1) === currentSectionId) {
                link.classList.add('active');
            }
        });
    }
    setActiveLink();
    window.addEventListener('scroll', setActiveLink);

    // --- Mobilmeny Toggle ---
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', () => {
             // Bytt mellom display: block og display: none
             const isHidden = mobileMenu.style.display === 'none' || mobileMenu.style.display === '';
             mobileMenu.style.display = isHidden ? 'block' : 'none';
             // Oppdater aria-expanded for tilgjengelighet
             menuButton.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        });
    }

    function closeMobileMenu() {
        if (mobileMenu && mobileMenu.style.display !== 'none') {
            mobileMenu.style.display = 'none';
            menuButton?.setAttribute('aria-expanded', 'false');
        }
    }

    // Lukk menyen hvis man klikker utenfor
    document.addEventListener('click', function(event) {
        if (mobileMenu && menuButton) {
             const isClickInsideMenu = mobileMenu.contains(event.target);
             const isClickOnButton = menuButton.contains(event.target);
             if (!isClickInsideMenu && !isClickOnButton && mobileMenu.style.display !== 'none') {
                 closeMobileMenu();
             }
        }
    });

    console.log("Akarit Google Workspace side lastet (Strukturert u/ Tailwind).");
});
