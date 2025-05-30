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
    if ($pageType === 'article_single' && isset($articleData['slug'])) {
        $canonicalPath = '/artikler/' . htmlspecialchars($articleData['slug']) . '/';
    } elseif ($pageType === 'article_listing') {
        $canonicalPath = '/artikler/';
    } elseif ($pageType === 'landingpage' && !empty($currentLocationSlug)) {
        $canonicalPath = '/' . htmlspecialchars($currentLocationSlug) . '/';
    }
    ?>
    <link rel="canonical" href="<?php echo rtrim($baseCanonicalUrl, '/') . $canonicalPath; ?>" />

    <link rel="icon" href="/assets/img/favicon_akari.png" type="image/png">
    <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/deeb08dcea17bd80d94b1dd6/script.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-592LJ3YFKH"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-592LJ3YFKH');
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&family=Red+Hat+Display:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/assets/css/style.css">

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
    <?php if (isset($currentLocationName) && $currentLocationName !== "Generell" && isset($currentLocationData) && !empty($currentLocationSlug) && $pageType === 'landingpage'): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org", "@type": "WebPage", "name": "<?php echo htmlspecialchars($pageTitle); ?>", "description": "<?php echo htmlspecialchars($pageDescription); ?>",
      "url": "<?php echo rtrim($baseCanonicalUrl, '/') . '/' . htmlspecialchars($currentLocationSlug) . '/'; ?>",
      "isPartOf": { "@type": "WebSite", "url": "<?php echo $baseCanonicalUrl; ?>/", "name": "Akari Google Workspace" },
      "spatialCoverage": { "@type": "Place", "name": "<?php echo htmlspecialchars($currentLocationName); ?>" }
    }
    </script>
    <?php elseif ($pageType === 'article_single' && isset($articleData['slug'])): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org", "@type": "Article",
      "mainEntityOfPage": { "@type": "WebPage", "@id": "<?php echo rtrim($baseCanonicalUrl, '/') . '/artikler/' . htmlspecialchars($articleData['slug']) . '/'; ?>" },
      "headline": "<?php echo htmlspecialchars($articleData['title'] ?? ''); ?>",
      <?php if (!empty($articleData['image'])): ?> "image": "<?php echo $baseCanonicalUrl . htmlspecialchars($articleData['image']); ?>", <?php endif; ?>
      "datePublished": "<?php echo isset($articleData['date']) ? date(DATE_ISO8601, strtotime($articleData['date'])) : ''; ?>",
      "author": { "@type": "Person", "name": "<?php echo htmlspecialchars($articleData['author'] ?? 'Akari'); ?>" },
      "publisher": { "@type": "Organization", "name": "Akari AS", "logo": { "@type": "ImageObject", "url": "<?php echo $baseCanonicalUrl; ?>/assets/img/Akari_jubileum.svg" }},
      "description": "<?php echo htmlspecialchars($articleData['excerpt'] ?? $pageDescription); ?>"
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
                <li><a href="/#fordeler">Google Workspace</a></li>
                <li><a href="/#prispakker">Priser</a></li>
                <li><a href="/artikler/">Artikler</a></li>
                <li><a href="/#hvorfor-oss">Hvorfor Akari?</a></li>
                <li><a href="/#kontakt">Kontakt</a></li>
                <li><a href="https://akari.no" target="_blank" class="nav-link-external">Til hovedside</a></li>
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
            <li><a href="/artikler/">Artikler</a></li>
            <li><a href="/#hvorfor-oss">Hvorfor Akari?</a></li>
            <li><a href="/#kontakt">Kontakt</a></li>
            <li class="mobile-nav-separator"><hr></li> 
            <li><a href="https://akari.no" target="_blank" class="nav-link-external mobile-external-link">Til hovedside</a></li>
         </ul>
     </div>
</header>

<main>
