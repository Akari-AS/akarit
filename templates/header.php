<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Google Workspace for Bedrifter | Akarit'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Styrk bedriften med Google Workspace. Akarit tilbyr ekspertise.'; ?>">

    <!-- Google Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Link til VÅR CSS-fil -->
    <link rel="stylesheet" href="/assets/css/style.css">

</head>
<body> <?php // Body tag startes her ?>

<header>
     <div class="container">
        <!-- Logo -->
        <a href="#hjem" class="logo">Akarit <span class="sub-logo">av Akari</span></a>
        <!-- Desktop Navigasjon -->
        <nav>
            <ul>
                <li><a href="#fordeler">Fordeler</a></li>
                <li><a href="#produkter">Produkter</a></li>
                <li><a href="#hvorfor-oss">Hvorfor Akarit?</a></li>
                <li><a href="#kontakt">Kontakt</a></li>
            </ul>
        </nav>
         <!-- Mobilmeny-knapp -->
         <div class="mobile-menu-button-container">
             <button id="mobile-menu-button" aria-label="Åpne meny" aria-expanded="false">
                 <svg class="h-6 w-6 text-gray-300 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                 </svg>
             </button>
         </div>
    </div>
     <!-- Mobilmeny -->
     <div id="mobile-menu">
         <ul>
             <li><a href="#fordeler">Fordeler</a></li>
             <li><a href="#produkter">Produkter</a></li>
             <li><a href="#hvorfor-oss">Hvorfor Akarit?</a></li>
             <li><a href="#kontakt">Kontakt</a></li>
         </ul>
     </div>
</header>

<main> <?php // Main tag startes her ?>
