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

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5B2PFWHX');</script> <!-- DIN GTM ID -->
    <!-- End Google Tag Manager -->

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
      "serviceType": "IT Support, Cloud Computing Services, Google Workspace Reseller"
    }
    </script>
    <?php if (isset($currentLocationName) && $currentLocationName !== "Generell" && isset($currentLocationData) && isset($locationSlug)): ?>
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
      "spatialCoverage": { 
        "@type": "Place",
        "name": "<?php echo htmlspecialchars($currentLocationName); ?>"
      }
    }
    </script>
    <?php endif; ?>

</head>
<body class="bg-akari-light-green text-gray-800 font-body antialiased">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5B2PFWHX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> <!-- DIN GTM ID -->
    <!-- End Google Tag Manager (noscript) -->

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
