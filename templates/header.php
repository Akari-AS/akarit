<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Google Workspace Leverandør | Akari'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription ?? 'Akari er din erfarne Google Workspace leverandør. Vi tilbyr skreddersydde skyløsninger for bedrifter.'); ?>">

    <?php
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST']; 
    $baseCanonicalUrl = $protocol . $host;

    $canonicalPath = '/'; 
    if ($pageType === 'article_single' && isset($contentData['slug'])) {
        $canonicalPath = '/artikler/' . htmlspecialchars($contentData['slug']) . '/';
    } elseif ($pageType === 'article_listing') {
        $canonicalPath = '/artikler/';
    } elseif ($pageType === 'seminar_single' && isset($contentData['slug'])) {
        $canonicalPath = '/seminarer/' . htmlspecialchars($contentData['slug']) . '/';
    } elseif ($pageType === 'seminar_listing') {
        $canonicalPath = '/seminarer/';
    } elseif ($pageType === 'location_listing') {
        $canonicalPath = '/lokasjoner/';
    } elseif ($pageType === 'landingpage' && !empty($currentLocationSlug)) {
        $canonicalPath = '/' . htmlspecialchars($currentLocationSlug) . '/';
    }
    ?>
    <link rel="canonical" href="<?php echo rtrim($baseCanonicalUrl, '/') . $canonicalPath; ?>" />

    <!-- Open Graph Meta Tags START -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle ?? 'Google Workspace Leverandør | Akari'); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription ?? 'Akari er din erfarne Google Workspace leverandør.'); ?>" />
    <meta property="og:type" content="<?php echo ($pageType === 'article_single' || $pageType === 'seminar_single') ? 'article' : 'website'; ?>" />
    <meta property="og:url" content="<?php echo rtrim($baseCanonicalUrl, '/') . $canonicalPath; ?>" />
    
    <?php 
    // Bestem hvilket bilde som skal brukes for og:image
    $ogImage = $baseCanonicalUrl . '/assets/img/seminars/banner.png'; // MIDLERTIDIG Standard delebilde
    if ($pageType === 'seminar_single' && isset($contentData['image']) && !empty($contentData['image'])) {
        // Bruk seminarspesifikt bilde hvis det finnes
        $ogImage = $baseCanonicalUrl . htmlspecialchars($contentData['image']);
    } elseif ($pageType === 'article_single' && isset($contentData['image']) && !empty($contentData['image'])) {
        // Bruk artikkelspesifikt bilde hvis det finnes (du har dette allerede)
        $ogImage = $baseCanonicalUrl . htmlspecialchars($contentData['image']);
    }
    ?>
    <meta property="og:image" content="<?php echo $ogImage; ?>" />
    <meta property="og:image:width" content="1200" /> <?php // Anbefalt bredde for LinkedIn/FB ?>
    <meta property="og:image:height" content="630" /> <?php // Anbefalt høyde for LinkedIn/FB ?>
    <meta property="og:site_name" content="Akari Google Workspace" />
    <meta property="og:locale" content="nb_NO" />
    
    <?php if ($pageType === 'article_single' && isset($contentData['author'])): ?>
    <meta property="article:author" content="<?php echo htmlspecialchars($contentData['author']); ?>" />
    <meta property="article:published_time" content="<?php echo isset($contentData['date']) ? date(DATE_ISO8601, strtotime($contentData['date'])) : ''; ?>" />
    <meta property="article:section" content="Google Workspace" />
    <meta property="article:tag" content="Google Workspace, AI, Produktivitet, Samarbeid" />
    <?php endif; ?>

    <!-- Twitter Card Tags (kan gjenbruke mye fra OG) -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle ?? 'Google Workspace Leverandør | Akari'); ?>" />
    <meta name="twitter:description" content="<?php echo htmlspecialchars($pageDescription ?? 'Akari er din erfarne Google Workspace leverandør.'); ?>" />
    <meta name="twitter:image" content="<?php echo $ogImage; ?>" /> 
    <?php // Hvis du har en Twitter-bruker: <meta name="twitter:site" content="@dinTwitterBrukernavn" /> ?>
    <!-- Open Graph Meta Tags END -->


  <link rel="icon" href="/assets/img/favicon_akari.png" type="image/png">
  <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/deeb08dcea17bd80d94b1dd6/script.js"></script>
  <script defer data-domain="googleworkspace.akari.no" src="https://plausible.akarisafari.no/js/script.js"></script>   <script async src="https://www.googletagmanager.com/gtag/js?id=G-592LJ3YFKH"></script>
  <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-592LJ3YFKH');
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&family=Red+Hat+Display:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo filemtime(__DIR__.'/../public/assets/css/style.css'); ?>">


    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Akari AS",
      "url": "<?php echo rtrim($baseCanonicalUrl, '/') . $canonicalPath; ?>",
      "logo": "<?php echo $baseCanonicalUrl; ?>/assets/img/Akari_jubileum.svg",
      "description": "Akari AS er en ledende norsk leverandør og sertifisert partner for Google Workspace, og tilbyr implementering, migrering, support og skreddersydde skyløsninger for bedrifter.",
      "contactPoint": [
        { "@type": "ContactPoint", "telephone": "+47-32-76-66-00", "contactType": "customer support", "email": "kreative@akari.no", "areaServed": "NO", "availableLanguage": ["Norwegian"] },
        { "@type": "ContactPoint", "telephone": "+47-32-76-66-00", "contactType": "technical support", "email": "support@akari.no", "areaServed": "NO", "availableLanguage": ["Norwegian"] }
      ],
      "sameAs": [ "https://www.facebook.com/akarireklame", "https://www.instagram.com/akari_reklame/", "https://www.linkedin.com/company/11776262/", "https://www.youtube.com/@akarireklame" ],
      "knowsAbout": "Google Workspace",
      "serviceType": "IT Support, Cloud Computing Services, Google Workspace Reseller"
    }
    </script>
    <?php if (isset($currentLocationName) && $currentLocationName !== "Generell" && $currentLocationName !== "Lokasjoner" && isset($currentLocationData) && !empty($currentLocationSlug) && $pageType === 'landingpage'): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org", "@type": "WebPage", "name": "<?php echo htmlspecialchars($pageTitle); ?>", "description": "<?php echo htmlspecialchars($pageDescription); ?>",
      "url": "<?php echo rtrim($baseCanonicalUrl, '/') . '/' . htmlspecialchars($currentLocationSlug) . '/'; ?>",
      "isPartOf": { "@type": "WebSite", "url": "<?php echo $baseCanonicalUrl; ?>/", "name": "Akari Google Workspace" },
      "spatialCoverage": { "@type": "Place", "name": "<?php echo htmlspecialchars($currentLocationName); ?>" }
    }
    </script>
    <?php elseif ($pageType === 'article_single' && isset($contentData['slug'])): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org", "@type": "Article",
      "mainEntityOfPage": { "@type": "WebPage", "@id": "<?php echo rtrim($baseCanonicalUrl, '/') . '/artikler/' . htmlspecialchars($contentData['slug']) . '/'; ?>" },
      "headline": "<?php echo htmlspecialchars($contentData['title'] ?? ''); ?>",
      <?php if (!empty($contentData['image'])): ?> "image": "<?php echo $baseCanonicalUrl . htmlspecialchars($contentData['image']); ?>", <?php endif; ?>
      "datePublished": "<?php echo isset($contentData['date']) ? date(DATE_ISO8601, strtotime($contentData['date'])) : ''; ?>",
      "author": { "@type": "Person", "name": "<?php echo htmlspecialchars($contentData['author'] ?? 'Akari'); ?>" },
      "publisher": { "@type": "Organization", "name": "Akari AS", "logo": { "@type": "ImageObject", "url": "<?php echo $baseCanonicalUrl; ?>/assets/img/Akari_jubileum.svg" }},
      "description": "<?php echo htmlspecialchars($contentData['excerpt'] ?? $pageDescription); ?>"
    }
    </script>
    <?php elseif ($pageType === 'seminar_single' && isset($contentData['slug'])): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Event",
      "name": "<?php echo htmlspecialchars($contentData['title'] ?? ''); ?>",
      "startDate": "<?php echo isset($contentData['date']) ? date(DATE_ISO8601, strtotime($contentData['date'])) : ''; ?>",
      "location": {
        "@type": "Place",
        "name": "<?php echo htmlspecialchars($contentData['location'] ?? 'Akari'); ?>",
        "address": "<?php echo htmlspecialchars($contentData['location'] ?? 'Norge'); ?>"
      },
      "description": "<?php echo htmlspecialchars($contentData['excerpt'] ?? $pageDescription); ?>",
      <?php if (!empty($contentData['image'])): ?> "image": "<?php echo $baseCanonicalUrl . htmlspecialchars($contentData['image']); ?>", <?php endif; ?>
      "eventStatus": "https://schema.org/EventScheduled",
      "offers": {
        "@type": "Offer",
        "url": "<?php echo rtrim($baseCanonicalUrl, '/') . '/seminarer/' . htmlspecialchars($contentData['slug']) . '/'; ?>",
        "price": "0", 
        "priceCurrency": "NOK",
        "availability": "https://schema.org/InStock" 
      },
      "organizer": {
        "@type": "Organization",
        "name": "Akari AS",
        "url": "<?php echo $baseCanonicalUrl; ?>/"
      }
    }
    </script>
    <?php elseif ($pageType === 'location_listing'): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "<?php echo htmlspecialchars($pageTitle); ?>",
      "description": "<?php echo htmlspecialchars($pageDescription); ?>",
      "url": "<?php echo rtrim($baseCanonicalUrl, '/') . '/lokasjoner/'; ?>"
    }
    </script>
    <?php endif; ?>

</head>
<body class="bg-akari-light-green text-gray-800 font-body antialiased">
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5B2PFWHX" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<header>
     <div class="container header-container">
        <a href="/#hjem" class="logo logo-image-container"> 
            <img src="/assets/img/Akari_jubileum.svg" alt="Akari Logo" class="header-logo-img">
        </a>
        <nav class="desktop-nav">
            <ul>
                <li><a href="/#fordeler" class="<?php echo ($pageType === 'landingpage' && empty($_GET['path'])) ? 'active' : ''; // Eksempel på aktiv klasse for forside ?>">Google Workspace</a></li>
                <li><a href="/#prispakker">Priser</a></li>
                <li><a href="/seminarer/" class="<?php echo ($pageType === 'seminar_listing' || $pageType === 'seminar_single') ? 'active' : ''; ?>">Seminarer</a></li>
                <li><a href="/artikler/" class="<?php echo ($pageType === 'article_listing' || $pageType === 'article_single') ? 'active' : ''; ?>">Artikler</a></li>
                <li><a href="/#hvorfor-oss">Hvorfor Akari?</a></li>
                <li><a href="/#kontakt">Kontakt</a></li>
                <li>
                    <a href="https://akari.no" target="_blank" class="nav-link-external">
                        Til hovedside 
                        <svg class="external-link-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>
         <div class="mobile-menu-button-container">
             <button id="mobile-menu-button" aria-label="Meny" aria-expanded="false" aria-controls="mobile-menu">
                 <svg class="hamburger-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     <line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line>
                 </svg>
                  <svg class="close-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
             </button>
         </div>
    </div>
     <div id="mobile-menu" class="mobile-menu-panel">
         <ul>
            <li><a href="/#fordeler">Google Workspace</a></li>
            <li><a href="/#prispakker">Priser</a></li>
            <li><a href="/seminarer/">Seminarer</a></li>
            <li><a href="/artikler/">Artikler</a></li>
            <li><a href="/#hvorfor-oss">Hvorfor Akari?</a></li>
            <li><a href="/#kontakt">Kontakt</a></li>
            <li><a href="https://akari.no" target="_blank" class="nav-link-external mobile-external-link">Til hovedside</a></li>
         </ul>
     </div>
</header>

<main>
