
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamisk Samling av Fargepaletter 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS Variabler for temastyring */
        :root {
            --page-bg: #F9FAFB; /* bg-gray-50 */
            --text-color-primary: #1F2937; /* text-gray-800 */
            --text-color-secondary: #4B5563; /* text-gray-600 */
            --header-bg: #FFFFFF; /* bg-white */
            --header-shadow: rgba(0,0,0,0.1);
            --nav-bg: rgba(255, 255, 255, 0.95);
            --nav-text-color: #374151;
            --nav-text-hover-color: #1f2937;
            --nav-bg-hover-color: #e5e7eb;
            --nav-shadow: rgba(0,0,0,0.1);
            --section-title-color: #374151; /* text-gray-700 */
            --section-border-color: #e5e7eb;
            --card-bg: #FFFFFF; /* bg-white for eksempelbokser */
            --footer-bg: #1f2937; /* bg-gray-800 */
            --footer-text-color: #D1D5DB; /* text-gray-300 */

            /* Farger for fargebokser - disse vil IKKE endres med temaet, da de representerer selve paletten */
        }

        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
            background-color: var(--page-bg);
            color: var(--text-color-primary);
            transition: background-color 0.5s ease, color 0.5s ease;
        }
        .header-dynamic {
            background-color: var(--header-bg);
            box-shadow: 0 2px 4px var(--header-shadow);
            transition: background-color 0.5s ease, box-shadow 0.5s ease;
        }
        .header-dynamic h1 {
            color: var(--text-color-primary);
            transition: color 0.5s ease;
        }
        .header-dynamic p {
            color: var(--text-color-secondary);
            transition: color 0.5s ease;
        }

        .sticky-nav-dynamic {
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: var(--nav-bg);
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 4px var(--nav-shadow);
            transition: background-color 0.5s ease, box-shadow 0.5s ease;
        }
        .nav-link-dynamic {
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
            color: var(--nav-text-color);
        }
        .nav-link-dynamic:hover {
            background-color: var(--nav-bg-hover-color);
            color: var(--nav-text-hover-color);
        }

        .palette-section {
            padding-top: 2rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid var(--section-border-color);
            transition: border-color 0.5s ease;
        }
        .palette-section:last-child {
            border-bottom: none;
        }
        .palette-section h2 {
            color: var(--section-title-color);
            transition: color 0.5s ease;
        }
        .example-card {
            background-color: var(--card-bg);
            transition: background-color 0.5s ease;
        }
         .example-card h4 {
            color: var(--section-title-color); /* Samme som seksjonstittel for konsistens */
             transition: color 0.5s ease;
        }


        .color-box {
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1rem;
            line-height: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            margin-bottom: 1.5rem;
            /* Tekstfarge i boksene settes av .color-box-dark-text eller .color-box-light-text */
        }
        .color-box-dark-text { color: #1f2937; }
        .color-box-light-text { color: #f9fafb; }

        .footer-dynamic {
            background-color: var(--footer-bg);
            color: var(--footer-text-color);
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');
    </style>
</head>
<body class="bg-gray-50">

    <header class="text-center py-8 sm:py-12 bg-white shadow-md header-dynamic">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-800">
            Fargepaletter for 2025
        </h1>
        <p class="text-gray-600 mt-3 text-lg">En visuell guide til årets fargetrender</p>
    </header>

    <nav class="sticky-nav-dynamic">
        <div class="container mx-auto max-w-6xl px-4">
            <ul class="flex flex-wrap justify-center sm:justify-around py-3">
                <li><a href="#palette6" class="nav-link-dynamic font-medium text-sm sm:text-base">Eiriks Palett</a></li>
                <li><a href="#palette1" class="nav-link-dynamic font-medium text-sm sm:text-base">Blåtoner</a></li>
                <li><a href="#palette2" class="nav-link-dynamic font-medium text-sm sm:text-base">Pasteller</a></li>
                <li><a href="#palette3" class="nav-link-dynamic font-medium text-sm sm:text-base">Jordfarger</a></li>
                <li><a href="#palette4" class="nav-link-dynamic font-medium text-sm sm:text-base">Grønn Palett</a></li>
                <li><a href="#palette5" class="nav-link-dynamic font-medium text-sm sm:text-base">Optimistiske Aksenter</a></li>
            </ul>
        </div>
    </nav>

    <main class="container mx-auto max-w-5xl p-4 sm:p-8">

        <section id="palette6" class="palette-section" data-theme="eirik">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                1. Eiriks Utvalgte Palett
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-light-text" style="background-color: #2D978D;"><h3 class="text-xl font-semibold">Primærfarge</h3><p>Frisk Sjøgrønn</p><p class="font-mono text-md mt-1">#2D978D</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #97BEAB;"><h3 class="text-xl font-semibold">Sekundærfarge</h3><p>Demped Salvie</p><p class="font-mono text-md mt-1">#97BEAB</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #EBE7DE;"><h3 class="text-xl font-semibold">Støttefarge 1</h3><p>Lys Lin</p><p class="font-mono text-md mt-1">#EBE7DE</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #F2F2F2;"><h3 class="text-xl font-semibold">Støttefarge 2</h3><p>Klar Gråhvit</p><p class="font-mono text-md mt-1">#F2F2F2</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #FF6F61;"><h3 class="text-xl font-semibold">Kontrastfarge</h3><p>Varm Korall</p><p class="font-mono text-md mt-1">#FF6F61</p></div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm example-card">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #2D978D; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #1f2937; background-color: #FF6F61; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

        <section id="palette1" class="palette-section" data-theme="blueish">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                2. Myke og Luftige Blåtoner
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-dark-text" style="background-color: #A0D2DB;"><h3 class="text-xl font-semibold">Primærfarge</h3><p>Dus Himmelblå</p><p class="font-mono text-md mt-1">#A0D2DB</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #C7E8E3;"><h3 class="text-xl font-semibold">Sekundærfarge</h3><p>Myk Akvamarin</p><p class="font-mono text-md mt-1">#C7E8E3</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #F4F8F7;"><h3 class="text-xl font-semibold">Støttefarge 1</h3><p>Perlehvit</p><p class="font-mono text-md mt-1">#F4F8F7</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #E8ECEF;"><h3 class="text-xl font-semibold">Støttefarge 2</h3><p>Svært Lys Grå</p><p class="font-mono text-md mt-1">#E8ECEF</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #FFB38A;"><h3 class="text-xl font-semibold">Kontrastfarge</h3><p>Dempet Oransje-Korall</p><p class="font-mono text-md mt-1">#FFB38A</p></div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm example-card">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #333333; background-color: #A0D2DB; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk grå tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #1f2937; background-color: #FFB38A; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

        <section id="palette2" class="palette-section" data-theme="pastel">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                3. Dempede Pasteller
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-dark-text" style="background-color: #D4C4E0;"><h3 class="text-xl font-semibold">Primærfarge</h3><p>Dus Lavendel</p><p class="font-mono text-md mt-1">#D4C4E0</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #E8B4B8;"><h3 class="text-xl font-semibold">Sekundærfarge</h3><p>Støvet Rose</p><p class="font-mono text-md mt-1">#E8B4B8</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #FDEBD0;"><h3 class="text-xl font-semibold">Støttefarge 1</h3><p>Kremet Fersken</p><p class="font-mono text-md mt-1">#FDEBD0</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #D1E7DD;"><h3 class="text-xl font-semibold">Støttefarge 2</h3><p>Lys Mint</p><p class="font-mono text-md mt-1">#D1E7DD</p></div>
                <div class="color-box color-box-light-text" style="background-color: #8A5063;"><h3 class="text-xl font-semibold">Kontrastfarge</h3><p>Dyp Bær</p><p class="font-mono text-md mt-1">#8A5063</p></div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm example-card">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #333333; background-color: #D4C4E0; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk grå tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #8A5063; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

        <section id="palette3" class="palette-section" data-theme="earthy">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                4. Jordfarger i Sentrum
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-light-text" style="background-color: #B87333;"><h3 class="text-xl font-semibold">Primærfarge</h3><p>Varm Terrakotta</p><p class="font-mono text-md mt-1">#B87333</p></div>
                <div class="color-box color-box-light-text" style="background-color: #4A5D23;"><h3 class="text-xl font-semibold">Sekundærfarge</h3><p>Dyp Skoggrønn</p><p class="font-mono text-md mt-1">#4A5D23</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #EDE0C8;"><h3 class="text-xl font-semibold">Støttefarge 1</h3><p>Lys Sand</p><p class="font-mono text-md mt-1">#EDE0C8</p></div>
                <div class="color-box color-box-light-text" style="background-color: #AFA090;"><h3 class="text-xl font-semibold">Støttefarge 2</h3><p>Dempet Leire</p><p class="font-mono text-md mt-1">#AFA090</p></div>
                <div class="color-box color-box-light-text" style="background-color: #CC5500;"><h3 class="text-xl font-semibold">Kontrastfarge</h3><p>Brent Oransje</p><p class="font-mono text-md mt-1">#CC5500</p></div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm example-card">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #B87333; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #CC5500; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

        <section id="palette4" class="palette-section" data-theme="naturegreen">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                5. Naturens Grønne Palett
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-light-text" style="background-color: #3A5F0B;"><h3 class="text-xl font-semibold">Primærfarge</h3><p>Dyp Skogsgrønn</p><p class="font-mono text-md mt-1">#3A5F0B</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #9DC183;"><h3 class="text-xl font-semibold">Sekundærfarge</h3><p>Salviegrønn</p><p class="font-mono text-md mt-1">#9DC183</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #C1D39F;"><h3 class="text-xl font-semibold">Støttefarge 1</h3><p>Lys Mosegrønn</p><p class="font-mono text-md mt-1">#C1D39F</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #F0F3E5;"><h3 class="text-xl font-semibold">Støttefarge 2</h3><p>Kremet Bjørk</p><p class="font-mono text-md mt-1">#F0F3E5</p></div>
                <div class="color-box color-box-light-text" style="background-color: #B7410E;"><h3 class="text-xl font-semibold">Kontrastfarge</h3><p>Rustrød</p><p class="font-mono text-md mt-1">#B7410E</p></div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm example-card">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #3A5F0B; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #FFFFFF; background-color: #B7410E; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Hvit tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

        <section id="palette5" class="palette-section" data-theme="optimistic">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-10">
                6. Varme og Optimistiske Aksenter
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="color-box color-box-dark-text" style="background-color: #FFD700;"><h3 class="text-xl font-semibold">Primærfarge</h3><p>Solgul</p><p class="font-mono text-md mt-1">#FFD700</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #FF6347;"><h3 class="text-xl font-semibold">Sekundærfarge</h3><p>Livlig Korall</p><p class="font-mono text-md mt-1">#FF6347</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #FFF8DC;"><h3 class="text-xl font-semibold">Støttefarge 1</h3><p>Kremhvit</p><p class="font-mono text-md mt-1">#FFF8DC</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #FFE4B5;"><h3 class="text-xl font-semibold">Støttefarge 2</h3><p>Lys Fersken</p><p class="font-mono text-md mt-1">#FFE4B5</p></div>
                <div class="color-box color-box-dark-text" style="background-color: #87CEFA;"><h3 class="text-xl font-semibold">Kontrastfarge</h3><p>Klar Himmelblå</p><p class="font-mono text-md mt-1">#87CEFA</p></div>
            </div>
            <div class="mt-10 p-6 bg-white rounded-lg shadow-sm example-card">
                <h4 class="text-lg font-semibold text-gray-700 mb-3">Eksempler på bruk:</h4>
                <p class="mb-2"><span style="color: #1f2937; background-color: #FFD700; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk grå tekst</span> på Primærfarge.</p>
                <p class="mb-2"><span style="color: #1f2937; background-color: #87CEFA; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Mørk grå tekst</span> på Kontrastfarge (knapp/CTA).</p>
            </div>
        </section>

    </main>

    <footer class="text-center py-8 mt-8 bg-gray-800 text-gray-300 footer-dynamic">
        <p>&copy; <span id="currentYear"></span> Din Nettside. Fargepaletter generert <span id="currentDate"></span>.</p>
    </footer>

    <script>
        // Definer fargetemaer for hver seksjon
        const themes = {
            default: { // Standardtema hvis ingen seksjon er aktiv
                pageBg: '#F9FAFB',
                textColorPrimary: '#1F2937',
                textColorSecondary: '#4B5563',
                headerBg: '#FFFFFF',
                headerShadow: 'rgba(0,0,0,0.1)',
                navBg: 'rgba(255, 255, 255, 0.95)',
                navTextColor: '#374151',
                navTextHoverColor: '#1f2937',
                navBgHoverColor: '#e5e7eb',
                navShadow: 'rgba(0,0,0,0.1)',
                sectionTitleColor: '#374151',
                sectionBorderColor: '#e5e7eb',
                cardBg: '#FFFFFF',
                footerBg: '#1f2937',
                footerTextColor: '#D1D5DB'
            },
            eirik: {
                pageBg: '#F2F2F2', // Klar Gråhvit (Støttefarge 2)
                textColorPrimary: '#2D3748', // Mørkere grå for kontrast
                textColorSecondary: '#4A5568', // Litt lysere mørk grå
                headerBg: '#EBE7DE', // Lys Lin (Støttefarge 1)
                headerShadow: 'rgba(45,151,141,0.2)',
                navBg: 'rgba(235, 231, 222, 0.95)', // Lys Lin med alpha
                navTextColor: '#2D978D', // Frisk Sjøgrønn
                navTextHoverColor: '#FFFFFF',
                navBgHoverColor: '#2D978D',
                navShadow: 'rgba(45,151,141,0.2)',
                sectionTitleColor: '#2D978D', // Frisk Sjøgrønn
                sectionBorderColor: '#D1D5DB', // Lysere grå
                cardBg: '#FFFFFF',
                footerBg: '#2D978D', // Frisk Sjøgrønn
                footerTextColor: '#EBE7DE' // Lys Lin
            },
            blueish: {
                pageBg: '#F0F8FF', // AliceBlue - en veldig lys blå
                textColorPrimary: '#2C3E50', // Mørk marineblå
                textColorSecondary: '#34495E',
                headerBg: '#A0D2DB', // Dus Himmelblå
                headerShadow: 'rgba(160,210,219,0.3)',
                navBg: 'rgba(160, 210, 219, 0.95)', // Dus Himmelblå med alpha
                navTextColor: '#2C3E50',
                navTextHoverColor: '#FFFFFF',
                navBgHoverColor: '#87AFC7', // Litt mørkere blå
                navShadow: 'rgba(160,210,219,0.3)',
                sectionTitleColor: '#0077BE', // Sterkere blå
                sectionBorderColor: '#B0E0E6', // PowderBlue
                cardBg: '#FFFFFF',
                footerBg: '#005A8C', // Mørkere blå
                footerTextColor: '#A0D2DB'
            },
            pastel: {
                pageBg: '#FFF5E1', // Veldig lys fersken/krem
                textColorPrimary: '#5D4037', // Brunlig for pastellkontrast
                textColorSecondary: '#795548',
                headerBg: '#D4C4E0', // Dus Lavendel
                headerShadow: 'rgba(212,196,224,0.3)',
                navBg: 'rgba(212, 196, 224, 0.95)', // Dus Lavendel med alpha
                navTextColor: '#5D4037',
                navTextHoverColor: '#FFFFFF',
                navBgHoverColor: '#BEA8D3', // Litt mørkere lavendel
                navShadow: 'rgba(212,196,224,0.3)',
                sectionTitleColor: '#8A5063', // Dyp Bær
                sectionBorderColor: '#F8BBD0', // Lys rosa
                cardBg: '#FFFFFF',
                footerBg: '#8A5063', // Dyp Bær
                footerTextColor: '#FDEBD0' // Kremet Fersken
            },
            earthy: {
                pageBg: '#F5F5DC', // Beige
                textColorPrimary: '#4E342E', // Mørk brun
                textColorSecondary: '#6D4C41',
                headerBg: '#B87333', // Varm Terrakotta
                headerShadow: 'rgba(184,115,51,0.3)',
                navBg: 'rgba(184, 115, 51, 0.95)', // Varm Terrakotta med alpha
                navTextColor: '#F5F5DC', // Beige tekst på terrakotta
                navTextHoverColor: '#FFFFFF',
                navBgHoverColor: '#A0522D', // Sienna (mørkere terrakotta)
                navShadow: 'rgba(184,115,51,0.3)',
                sectionTitleColor: '#4A5D23', // Dyp Skoggrønn
                sectionBorderColor: '#D2B48C', // Tan
                cardBg: '#FFF8DC', // Cornsilk
                footerBg: '#4A5D23', // Dyp Skoggrønn
                footerTextColor: '#EDE0C8' // Lys Sand
            },
            naturegreen: {
                pageBg: '#F0FFF0', // Honeydew (veldig lys grønn)
                textColorPrimary: '#2F4F2F', // DarkGreen
                textColorSecondary: '#556B2F', // DarkOliveGreen
                headerBg: '#9DC183', // Salviegrønn
                headerShadow: 'rgba(157,193,131,0.3)',
                navBg: 'rgba(157, 193, 131, 0.95)', // Salviegrønn med alpha
                navTextColor: '#2F4F2F',
                navTextHoverColor: '#FFFFFF',
                navBgHoverColor: '#8FBC8F', // DarkSeaGreen
                navShadow: 'rgba(157,193,131,0.3)',
                sectionTitleColor: '#3A5F0B', // Dyp Skogsgrønn
                sectionBorderColor: '#90EE90', // LightGreen
                cardBg: '#FFFFFF',
                footerBg: '#3A5F0B', // Dyp Skogsgrønn
                footerTextColor: '#F0F3E5' // Kremet Bjørk
            },
            optimistic: {
                pageBg: '#FFFACD', // LemonChiffon
                textColorPrimary: '#C75B12', // Varm oransje-brun
                textColorSecondary: '#D97904',
                headerBg: '#FFD700', // Solgul
                headerShadow: 'rgba(255,215,0,0.3)',
                navBg: 'rgba(255, 215, 0, 0.95)', // Solgul med alpha
                navTextColor: '#C75B12',
                navTextHoverColor: '#8B4513', // SaddleBrown
                navBgHoverColor: '#FFEEAA', // Lysere gul
                navShadow: 'rgba(255,215,0,0.3)',
                sectionTitleColor: '#FF6347', // Livlig Korall
                sectionBorderColor: '#FFDAB9', // PeachPuff
                cardBg: '#FFFFFF',
                footerBg: '#FF6347', // Livlig Korall
                footerTextColor: '#FFF8DC' // Kremhvit
            }
        };

        // Funksjon for å bytte tema
        function applyTheme(themeName) {
            const selectedTheme = themes[themeName] || themes.default;
            const root = document.documentElement;

            for (const property in selectedTheme) {
                root.style.setProperty(`--${property.replace(/([A-Z])/g, '-$1').toLowerCase()}`, selectedTheme[property]);
            }
        }

        // Intersection Observer for å bytte tema ved scrolling
        const sections = document.querySelectorAll('.palette-section');
        const observerOptions = {
            root: null, // Relativt til viewport
            rootMargin: '0px',
            threshold: 0.5 // 50% av seksjonen må være synlig
        };

        let activeTheme = 'default'; // Holder styr på gjeldende tema

        const observer = new IntersectionObserver((entries) => {
            let newActiveThemeFound = false;
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const themeName = entry.target.dataset.theme;
                    if (themeName && themeName !== activeTheme) {
                        applyTheme(themeName);
                        activeTheme = themeName;
                        newActiveThemeFound = true;

                        // Oppdater aktiv lenke i nav
                        document.querySelectorAll('nav a').forEach(a => {
                            a.classList.remove('font-bold', 'text-indigo-600'); // Eksempel på aktiv stil
                            if (a.getAttribute('href') === `#${entry.target.id}`) {
                                a.classList.add('font-bold', 'text-indigo-600');
                            }
                        });
                    }
                }
            });

             // Fallback til default hvis ingen spesifikk seksjon er "aktiv" nok
            if (!newActiveThemeFound) {
                // Sjekk om noen av de observerte elementene fortsatt er i viewport
                const anySectionInView = entries.some(entry => entry.isIntersecting && themes[entry.target.dataset.theme]);
                if (!anySectionInView && activeTheme !== 'default') {
                    // Dette er en forenkling. Ideelt sett vil du kanskje ha logikk for å
                    // finne den "mest synlige" eller den øverste synlige hvis ingen er over 50%.
                    // For nå, hvis ingen er >50% og vi ikke allerede er på default, bytt til default.
                    // Dette kan forhindre at siden blir "sittende fast" på et tema når man scroller fort ut av en seksjon.
                    // En mer robust løsning ville kanskje bruke en lavere threshold for å "forlate" et tema.
                }
            }

        }, observerOptions);

        sections.forEach(section => {
            observer.observe(section);
        });

        // Sett initialt tema (kan være basert på URL hash eller første seksjon)
        // For enkelhets skyld setter vi Eiriks palett som default hvis den er øverst
        if (window.location.hash) {
            const initialThemeName = document.querySelector(window.location.hash)?.dataset.theme;
            if (initialThemeName && themes[initialThemeName]) {
                 applyTheme(initialThemeName);
                 activeTheme = initialThemeName;
            } else {
                applyTheme('eirik'); // Fallback til Eiriks hvis hash er ugyldig
                activeTheme = 'eirik';
            }
        } else {
            applyTheme('eirik'); // Eiriks palett som standard ved første lasting
            activeTheme = 'eirik';
        }


        // Liten script for å sette inn nåværende år og dato i footeren
        document.getElementById('currentYear').textContent = new Date().getFullYear();
        const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('nb-NO', dateOptions);

        // Myk scrolling for nav-lenker
        document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const navHeight = document.querySelector('.sticky-nav-dynamic')?.offsetHeight || 0;
                    const offsetTop = targetElement.offsetTop - navHeight - 20; // 20px ekstra margin
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
