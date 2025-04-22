<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Google Workspace for Bedrifter | Akarit'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Styrk bedriften med Google Workspace. Akarit tilbyr ekspertise.'; ?>">

    <!-- Start cookieyes banner --> <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/edac3f0861b009c0f5dbf4b9/script.js"></script> <!-- End cookieyes banner -->

    <!-- Google Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Link til VÅR CSS-fil -->
    <link rel="stylesheet" href="/assets/css/style.css">

</head>
<body class="bg-akarit-light-green text-gray-800 font-body antialiased">

<header>
     <div class="container header-container">
        <!-- Logo -->
        <a href="#hjem" class="logo">Akarit <span class="sub-logo">av Akari</span></a>

        <!-- Desktop Navigasjon -->
        <nav class="desktop-nav">
            <ul>
                <li><a href="#fordeler">Fordeler</a></li>
                <li><a href="#produkter">Produkter</a></li>
                <li><a href="#hvorfor-oss">Hvorfor Akarit?</a></li>
                <li><a href="#kontakt">Kontakt</a></li>
            </ul>
        </nav>

         <!-- Mobilmeny-knapp -->
         <div class="mobile-menu-button-container">
             <button id="mobile-menu-button" aria-label="Meny" aria-expanded="false" aria-controls="mobile-menu">
                 <svg class="hamburger-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     <line x1="3" y1="12" x2="21" y2="12"></line>
                     <line x1="3" y1="6" x2="21" y2="6"></line>
                     <line x1="3" y1="18" x2="21" y2="18"></line>
                 </svg>
             </button>
         </div>
    </div>
     <!-- Mobilmeny -->
     <div id="mobile-menu" class="mobile-menu-panel">
        <button id="mobile-menu-close-button" aria-label="Lukk meny">
             <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                 <line x1="18" y1="6" x2="6" y2="18"></line>
                 <line x1="6" y1="6" x2="18" y2="18"></line>
             </svg>
        </button>
         <ul>
             <li><a href="#fordeler">Fordeler</a></li>
             <li><a href="#produkter">Produkter</a></li>
             <li><a href="#hvorfor-oss">Hvorfor Akarit?</a></li>
             <li><a href="#kontakt">Kontakt</a></li>
         </ul>
     </div>
</header>

<main>
