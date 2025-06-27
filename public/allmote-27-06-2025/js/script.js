document.addEventListener('DOMContentLoaded', () => {

    const imageViewport = document.getElementById('image-viewport');

    // Funksjon som håndterer "neste steg"
    const nextStep = () => {
        // Finn det første 'fragmentet' som fortsatt er skjult
        const hiddenFragment = document.querySelector('.fragment:not(.visible)');

        // Hvis vi fant et skjult fragment, vis det
        if (hiddenFragment) {
            hiddenFragment.classList.add('visible');

            // Sjekk om dette fragmentet skal bytte bildet
            const newImage = hiddenFragment.getAttribute('data-image');
            if (newImage && imageViewport) {
                // Bytt bildet
                imageViewport.style.backgroundImage = `url('${newImage}')`;
                // OG FJERN zoom-effekten
                imageViewport.classList.remove('zoom-effect');
            }
        } else {
            // Hvis ingen flere fragmenter er skjult, gå til neste slide
            if (config.nextSlideUrl) {
                window.location.href = config.nextSlideUrl;
            }
        }
    };

    // Funksjon som går til forrige slide
    const prevStep = () => {
        if (config.prevSlideUrl) {
            window.location.href = config.prevSlideUrl;
        }
    };

    // Lytt etter tastetrykk
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' || e.key === ' ' || e.key === 'PageDown') {
            // Høyrepil, mellomrom eller PageDown: Gå til neste steg
            e.preventDefault();
            nextStep();
        } else if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
            // Venstrepil eller PageUp: Gå til forrige slide
            e.preventDefault();
            prevStep();
        }
    });

});
