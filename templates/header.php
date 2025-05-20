<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Google Workspace Leverandør | Akari'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription ?? 'Akari er din erfarne Google Workspace leverandør. Vi tilbyr skreddersydde skyløsninger for bedrifter.'); ?>">

    <link rel="canonical" href="https://googleworkspace.akari.no/<?php echo isset($locationSlug) && !empty($locationSlug) ? htmlspecialchars($locationSlug) . '/' : ''; ?>" />

    <!-- Favicon -->
    <link rel="icon" href="/assets/img/favicon_akari.png" type="image/png">

    <!-- Start cookieyes banner --> <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/edac3f0861b009c0f5dbf4b9/script.js"></script> <!-- End cookieyes banner -->

    <!-- Google Fonts (Red Hat Display) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Link til VÅR CSS-fil -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Schema.org markup for Organization and Service -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Akari AS",
      "url": "https://googleworkspace.akari.no/<?php echo isset($locationSlug) && !empty($locationSlug) ? htmlspecialchars($locationSlug) . '/' : ''; ?>",
      "logo": "https://googleworkspace.akari.no/assets/img/Akari_jubileum.svg",
      "description": "Akari AS er en ledende norsk leverandør og sertifisert partner for Google Workspace, og tilbyr implementering, migrering, support og skreddersydde skyløsninger for bedrifter.",
      "contactPoint": [
        {
          "@type": "ContactPoint",
          "telephone": "+47-32-76-66-00",
          "contactType": "customer support",
          "email": "kreative@akari.no",
          "areaServed": "NO",
          "availableLanguage": ["Norwegian"]
        },
        {
          "@type": "ContactPoint",
          "telephone": "+47-32-76-66-00",
          "contactType": "technical support",
          "email": "support@akari.no",
          "areaServed": "NO",
          "availableLanguage": ["Norwegian"]
        }
      ],
      "sameAs": [
        "https://www.facebook.com/akarireklame",
        "https://www.instagram.com/akari_reklame/",
        "https://www.linkedin.com/company/11776262/",
        "https://www.youtube.com/@akarireklame"
      ],
      "knowsAbout": "Google Workspace",
      "serviceType": "IT Support, Cloud Computing Services, Google Workspace Reseller" // Eksempler, juster
    }
    </script>
    <?php if (isset($currentLocationName) && $currentLocationName !== "Generell" && isset($currentLocationData)): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "<?php echo htmlspecialchars($pageTitle); ?>",
      "description": "<?php echo htmlspecialchars($pageDescription); ?>",
      "url": "https://googleworkspace.akari.no/<?php echo htmlspecialchars($locationSlug); ?>/",
      "isPartOf": {
        "@type": "WebSite",
        "url": "https://googleworkspace.akari.no/",
        "name": "Akari Google Workspace"
      },
      "spatialCoverage": { // Indikerer at denne siden er spesifikk for en lokasjon
        "@type": "Place",
        "name": "<?php echo htmlspecialchars($currentLocationName); ?>"
        // Du kan legge til mer detaljert adresseinfo her hvis du har det i $currentLocationData
      }
    }
    </script>
    <?php endif; ?>


</head>
<body class="bg-akari-light-green text-gray-800 font-body antialiased">

<header>
     <div class="container header-container">
        <a href="#hjem" class="logo logo-image-container">
            <img src="/assets/img/Akari_jubileum.svg" alt="Akari Logo" class="header-logo-img">
        </a>
        <nav class="desktop-nav">
            <ul>
                <li><a href="#fordeler">Fordeler</a></li>
                <li><a href="#produkter">Produkter</a></li>
                <li><a href="#ai-funksjoner">AI-Funksjoner</a></li>
                <li><a href="#prispakker">Prispakker</a></li>
                <li><a href="#nrk-google-workspace">NRK & Google</a></li>
                <li><a href="#hvorfor-oss">Hvorfor Akari?</a></li>
                <li><a href="#kontakt">Kontakt</a></li>
            </ul>
        </nav>
         <div class="mobile-menu-button-container">
             <button id="mobile-menu-button" aria-label="Meny" aria-expanded="false" aria-controls="mobile-menu">
                 <svg class="hamburger-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     <line x1="3" y1="12" x2="21" y2="12"></line>
                     <line x1="3" y1="6" x2="21" y2="6"></line>
                     <line x1="3" y1="18" x2="21" y2="18"></line>
                 </svg>
                  <svg class="close-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="18" y1="6" x2="6" y2="18"></line>
                      <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
             </button>
         </div>
    </div>
     <div id="mobile-menu" class="mobile-menu-panel">
         <ul>
             <li><a href="#fordeler">Fordeler</a></li>
             <li><a href="#produkter">Produkter</a></li>
             <li><a href="#ai-funksjoner">AI-Funksjoner</a></li>
             <li><a href="#prispakker">Prispakker</a></li>
             <li><a href="#nrk-google-workspace">NRK & Google</a></li>
             <li><a href="#hvorfor-oss">Hvorfor Akari?</a></li>
             <li><a href="#kontakt">Kontakt</a></li>
         </ul>
     </div>
</header>

<main>
