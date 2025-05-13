
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samling av Fargepaletter 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Egendefinert stil for font og generelt utseende */
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth; /* For myk scrolling til seksjoner */
        }
        .palette-section {
            padding-top: 2rem; /* Gir litt pusterom over hver seksjonstittel */
            padding-bottom: 3rem; /* Mellomrom under hver seksjon */
            border-bottom: 1px solid #e5e7eb; /* Svak skillelinje mellom seksjoner */
        }
        .palette-section:last-child {
            border-bottom: none; /* Fjerner linjen for siste seksjon */
        }
        .color-box {
            /* Stil for fargeboksene */
            min-height: 180px; /* Litt mindre høyde for å passe flere på skjermen */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1rem; /* Justert fontstørrelse */
            line-height: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            margin-bottom: 1.5rem;
        }
        .color-box-dark-text {
             color: #1f2937; /* Mørk grå tekst for lyse farger */
        }
        .color-box-light-text {
             color: #f9fafb; /* Lys grå/hvit tekst for mørkere farger */
        }
        /* Sikrer at Inter lastes hvis tilgjengelig */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');

        /* Navigasjonsbar stiler */
        .sticky-nav {
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: rgba(255, 255, 255, 0.95); /* Lett gjennomsiktig hvit */
            backdrop-filter: blur(8px); /* For "frosted glass" effekt */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .nav-link {
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
            color: #374151; /* Grå tekst */
        }
        .nav-link:hover {
            background-color: #e5e7eb; /* Lys grå bakgrunn ved hover */
            color: #1f2937; /* Mørkere tekst ved hover */
        }
    </style>
</head>
<body class="bg-gray-50">

    <header class="text-center py-8 sm:py-12 bg-white shadow-md">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-800">
            Fargepaletter for 2025
        </h1>
        <p class="text-gray-600 mt-3 text-lg">En visuell guide til årets fargetrender</p>
    </header>

    <nav class="sticky-nav">
        <div class="container mx-auto max-w-6xl px-4">
            <ul class="flex flex-wrap justify-center sm:justify-around py-3">
                <li><a href="#palette6" class="nav-link font-medium text-sm sm:text-base">Eiriks Palett</a></li>
                <li><a href="#palette1" class="nav-link font-medium text-sm sm:text-base">Blåtoner</a></li>
                <li><a href="#palette2" class="nav-link font-medium text-sm sm:text-base">Pasteller</a></li>
                <li><a href="#palette3" class="nav-link font-medium text-sm sm:text-base">Jordfarger</a></li>
                <li><a href="#palette4" class="nav-link font-medium text-sm sm:text-base">Grønn Palett</a></li>
                <li><a href="#palette5" class="nav-link font-medium text-sm sm:text-base">Optimistiske Aksenter</a></li>
            </ul>
        </div>
    </nav>

    <main class="container mx-auto max-w-5xl p-4 sm:p-8">

        <section id="palette6" class="palette-section">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                1. Eiriks Utvalgte Palett
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-light-text" style="background-color: #2D978D;">
                    <h3 class="text-xl font-semibold">Primærfarge</h3>
                    <p>Frisk Sjøgrønn</p>
                    <p class="font-mono text-md mt-1">#2D978D</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #97BEAB;">
                    <h3 class="text-xl font-semibold">Sekundærfarge</h3>
                    <p>Demped Salvie</p>
                    <p class="font-mono text-md mt-1">#97BEAB</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #EBE7DE;">
                    <h3 class="text-xl font-semibold">Støttefarge 1</h3>
                    <p>Lys Lin</p>
                    <p class="font-mono text-md mt-1">#EBE7DE</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #F2F2F2;">
                    <h3 class="text-xl font-semibold">Støttefarge 2</h3>
                    <p>Klar Gråhvit</p>
                    <p class="font-mono text-md mt-1">#F2F2F2</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #FF6F61;">
                    <h3 class="text-xl font-semibold">Kontrastfarge</h3>
                    <p>Varm Korall</p>
                    <p class="font-mono text-md mt-1">#FF6F61</p>
                </div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #2D978D; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #1f2937; background-color: #FF6F61; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

        <section id="palette1" class="palette-section">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                2. Myke og Luftige Blåtoner
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-dark-text" style="background-color: #A0D2DB;">
                    <h3 class="text-xl font-semibold">Primærfarge</h3>
                    <p>Dus Himmelblå</p>
                    <p class="font-mono text-md mt-1">#A0D2DB</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #C7E8E3;">
                    <h3 class="text-xl font-semibold">Sekundærfarge</h3>
                    <p>Myk Akvamarin</p>
                    <p class="font-mono text-md mt-1">#C7E8E3</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #F4F8F7;">
                    <h3 class="text-xl font-semibold">Støttefarge 1</h3>
                    <p>Perlehvit</p>
                    <p class="font-mono text-md mt-1">#F4F8F7</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #E8ECEF;">
                    <h3 class="text-xl font-semibold">Støttefarge 2</h3>
                    <p>Svært Lys Grå</p>
                    <p class="font-mono text-md mt-1">#E8ECEF</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #FFB38A;">
                    <h3 class="text-xl font-semibold">Kontrastfarge</h3>
                    <p>Dempet Oransje-Korall</p>
                    <p class="font-mono text-md mt-1">#FFB38A</p>
                </div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #333333; background-color: #A0D2DB; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk grå tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #1f2937; background-color: #FFB38A; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

        <section id="palette2" class="palette-section">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                3. Dempede Pasteller
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-dark-text" style="background-color: #D4C4E0;">
                    <h3 class="text-xl font-semibold">Primærfarge</h3>
                    <p>Dus Lavendel</p>
                    <p class="font-mono text-md mt-1">#D4C4E0</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #E8B4B8;">
                    <h3 class="text-xl font-semibold">Sekundærfarge</h3>
                    <p>Støvet Rose</p>
                    <p class="font-mono text-md mt-1">#E8B4B8</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #FDEBD0;">
                    <h3 class="text-xl font-semibold">Støttefarge 1</h3>
                    <p>Kremet Fersken</p>
                    <p class="font-mono text-md mt-1">#FDEBD0</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #D1E7DD;">
                    <h3 class="text-xl font-semibold">Støttefarge 2</h3>
                    <p>Lys Mint</p>
                    <p class="font-mono text-md mt-1">#D1E7DD</p>
                </div>
                <div class="color-box color-box-light-text" style="background-color: #8A5063;">
                    <h3 class="text-xl font-semibold">Kontrastfarge</h3>
                    <p>Dyp Bær</p>
                    <p class="font-mono text-md mt-1">#8A5063</p>
                </div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #333333; background-color: #D4C4E0; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk grå tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #8A5063; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

        <section id="palette3" class="palette-section">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                4. Jordfarger i Sentrum
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-light-text" style="background-color: #B87333;">
                    <h3 class="text-xl font-semibold">Primærfarge</h3>
                    <p>Varm Terrakotta</p>
                    <p class="font-mono text-md mt-1">#B87333</p>
                </div>
                <div class="color-box color-box-light-text" style="background-color: #4A5D23;">
                    <h3 class="text-xl font-semibold">Sekundærfarge</h3>
                    <p>Dyp Skoggrønn</p>
                    <p class="font-mono text-md mt-1">#4A5D23</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #EDE0C8;">
                    <h3 class="text-xl font-semibold">Støttefarge 1</h3>
                    <p>Lys Sand</p>
                    <p class="font-mono text-md mt-1">#EDE0C8</p>
                </div>
                <div class="color-box color-box-light-text" style="background-color: #AFA090;">
                    <h3 class="text-xl font-semibold">Støttefarge 2</h3>
                    <p>Dempet Leire</p>
                    <p class="font-mono text-md mt-1">#AFA090</p>
                </div>
                <div class="color-box color-box-light-text" style="background-color: #CC5500;">
                    <h3 class="text-xl font-semibold">Kontrastfarge</h3>
                    <p>Brent Oransje</p>
                    <p class="font-mono text-md mt-1">#CC5500</p>
                </div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #B87333; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #CC5500; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

        <section id="palette4" class="palette-section">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                5. Naturens Grønne Palett
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-light-text" style="background-color: #3A5F0B;">
                    <h3 class="text-xl font-semibold">Primærfarge</h3>
                    <p>Dyp Skogsgrønn</p>
                    <p class="font-mono text-md mt-1">#3A5F0B</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #9DC183;">
                    <h3 class="text-xl font-semibold">Sekundærfarge</h3>
                    <p>Salviegrønn</p>
                    <p class="font-mono text-md mt-1">#9DC183</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #C1D39F;">
                    <h3 class="text-xl font-semibold">Støttefarge 1</h3>
                    <p>Lys Mosegrønn</p>
                    <p class="font-mono text-md mt-1">#C1D39F</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #F0F3E5;">
                    <h3 class="text-xl font-semibold">Støttefarge 2</h3>
                    <p>Kremet Bjørk</p>
                    <p class="font-mono text-md mt-1">#F0F3E5</p>
                </div>
                <div class="color-box color-box-light-text" style="background-color: #B7410E;">
                    <h3 class="text-xl font-semibold">Kontrastfarge</h3>
                    <p>Rustrød</p>
                    <p class="font-mono text-md mt-1">#B7410E</p>
                </div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #3A5F0B; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #B7410E; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

        <section id="palette5" class="palette-section">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                6. Varme og Optimistiske Aksenter
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-dark-text" style="background-color: #FFD700;">
                    <h3 class="text-xl font-semibold">Primærfarge</h3>
                    <p>Solgul</p>
                    <p class="font-mono text-md mt-1">#FFD700</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #FF6347;">
                    <h3 class="text-xl font-semibold">Sekundærfarge</h3>
                    <p>Livlig Korall</p>
                    <p class="font-mono text-md mt-1">#FF6347</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #FFF8DC;">
                    <h3 class="text-xl font-semibold">Støttefarge 1</h3>
                    <p>Kremhvit</p>
                    <p class="font-mono text-md mt-1">#FFF8DC</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #FFE4B5;">
                    <h3 class="text-xl font-semibold">Støttefarge 2</h3>
                    <p>Lys Fersken</p>
                    <p class="font-mono text-md mt-1">#FFE4B5</p>
                </div>
                <div class="color-box color-box-dark-text" style="background-color: #87CEFA;">
                    <h3 class="text-xl font-semibold">Kontrastfarge</h3>
                    <p>Klar Himmelblå</p>
                    <p class="font-mono text-md mt-1">#87CEFA</p>
                </div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #1f2937; background-color: #FFD700; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk grå tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #1f2937; background-color: #87CEFA; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk grå tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

    </main>

    <footer class="text-center py-8 mt-8 bg-gray-800 text-gray-300">
        <p>&copy; <span id="currentYear"></span> Din Nettside. Fargepaletter generert <span id="currentDate"></span>.</p>
    </footer>

    <script>
        // Liten script for å sette inn nåværende år og dato i footeren
        document.getElementById('currentYear').textContent = new Date().getFullYear();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('nb-NO', options);

        // Myk scrolling for nav-lenker
        document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    // Justerer for høyden på sticky nav + litt ekstra margin
                    const offsetTop = targetElement.offsetTop - 80; // 80px er en ca. verdi, juster etter behov for nav høyde
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

</body>
</html>
