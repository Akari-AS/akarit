document.addEventListener('DOMContentLoaded', () => {

    // --- Oppsett for bildebytte-funksjonalitet ---
    const imageViewport = document.getElementById('image-viewport');
    let originalImageSrc = null;

    if (imageViewport) {
        // Hent den initielle bakgrunnsbildestilen
        originalImageSrc = imageViewport.style.backgroundImage;
    }

    // Funksjon for å bytte bilde
    function switchImage(newImageUrl) {
        if (!imageViewport) return;

        // Bytt bakgrunnsbildet
        imageViewport.style.backgroundImage = `url('${newImageUrl}')`;

        // Legg til eller fjern zoom-effekten basert på bildets navn
        if (newImageUrl.includes('google.png')) {
            imageViewport.classList.add('zoom-effect');
        } else {
            imageViewport.classList.remove('zoom-effect');
        }
    }

    // --- Hovednavigasjonsfunksjon ---
    function navigate(direction) {
        if (direction === 'forward') {
            const hiddenFragments = document.querySelectorAll('.fragment:not(.visible)');
            
            if (hiddenFragments.length > 0) {
                const fragmentToShow = hiddenFragments[0];
                fragmentToShow.classList.add('visible');
                
                if (fragmentToShow.dataset.image) {
                    switchImage(fragmentToShow.dataset.image);
                }

            } else {
                if (config.nextSlideUrl) {
                    window.location.href = config.nextSlideUrl;
                }
            }
        } 
        else if (direction === 'backward') {
            const visibleFragments = document.querySelectorAll('.fragment.visible');
            
            if (visibleFragments.length > 0) {
                const fragmentToHide = visibleFragments[visibleFragments.length - 1];
                fragmentToHide.classList.remove('visible');

                const newVisibleFragments = document.querySelectorAll('.fragment.visible');
                if (newVisibleFragments.length > 0) {
                    const lastVisibleFragment = newVisibleFragments[newVisibleFragments.length - 1];
                    if (lastVisibleFragment.dataset.image) {
                        switchImage(lastVisibleFragment.dataset.image);
                    }
                } else {
                    if(originalImageSrc) {
                        imageViewport.style.backgroundImage = originalImageSrc;
                        imageViewport.classList.remove('zoom-effect');
                    }
                }

            } else {
                if (config.prevSlideUrl) {
                    window.location.href = config.prevSlideUrl;
                }
            }
        }
    }

    // Lytt etter tastetrykk
    document.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowRight' || event.key === 'PageDown' || event.key === ' ') {
            event.preventDefault();
            navigate('forward');
        }

        if (event.key === 'ArrowLeft' || event.key === 'PageUp') {
            event.preventDefault();
            navigate('backward');
        }
    });

    // Legg til klikk-event på Neste/Forrige-knappene
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');

    if (nextBtn) {
        nextBtn.addEventListener('click', (event) => {
            event.preventDefault();
            navigate('forward');
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', (event) => {
            event.preventDefault();
            navigate('backward');
        });
    }
});
