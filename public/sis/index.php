
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oversikt over Fargepaletter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Egendefinert stil for font og generelt utseende */
        body {
            font-family: 'Inter', sans-serif;
        }
        .link-container {
            background-color: #ffffff;
            border-radius: 0.5rem; /* 8px */
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            padding: 1.5rem; /* 24px */
            margin-bottom: 1.5rem; /* 24px */
        }
        .link-item a {
            display: block;
            padding: 0.75rem 1rem; /* 12px 16px */
            color: #1d4ed8; /* En fin blåfarge for linker */
            font-weight: 500;
            border-radius: 0.375rem; /* 6px */
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }
        .link-item a:hover {
            background-color: #eff6ff; /* Lys blå bakgrunn ved hover */
            color: #1e3a8a; /* Mørkere blå tekst ved hover */
        }
        /* Sikrer at Inter lastes hvis tilgjengelig */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');
    </style>
</head>
<body class="bg-gray-100 p-4 sm:p-8 flex flex-col items-center min-h-screen">

    <div class="container mx-auto max-w-2xl">
        <header class="text-center mb-10 sm:mb-16">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">
                Mine Fargepaletter for 2025
            </h1>
            <p class="text-gray-600 mt-2">En samling av ulike fargetrender og -temaer.</p>
        </header>

        <main class="w-full">
            <div class="link-container">
                <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center sm:text-left">Naviger til paletter:</h2>
                <ul class="space-y-3">
                    <li class="link-item">
                        <a href="fp-1.html">1. Myke og Luftige Blåtoner</a>
                    </li>
                    <li class="link-item">
                        <a href="fp-2.html">2. Dempede Pasteller</a>
                    </li>
                    <li class="link-item">
                        <a href="fp-3.html">3. Jordfarger i Sentrum</a>
                    </li>
                    <li class="link-item">
                        <a href="fp-4.html">4. Naturens Grønne Palett</a>
                    </li>
                    <li class="link-item">
                        <a href="fp-5.html">5. Varme og Optimistiske Aksenter</a>
                    </li>
                </ul>
            </div>
        </main>

        <footer class="text-center mt-12 text-gray-500 text-sm">
            <p>&copy; <span id="currentYear"></span> Din Nettside. Alle rettigheter reservert.</p>
        </footer>
    </div>

    <script>
        // Liten script for å sette inn nåværende år i footeren
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>

</body>
</html>
