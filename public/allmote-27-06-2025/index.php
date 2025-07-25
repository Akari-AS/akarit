<?php
// --- Konfigurasjon ---
$slides = [
    'slides/slide-1.php', // Tittel (Uendret)
    'slides/slide-2.php', // Problemet: "Regneark-fellen" (Ny)
    'slides/slide-3.php', // Beviset: Digital Dominans (Ny)
    'slides/slide-4.php', // Motoren: Vår AI-ekspertise (Ny)
    'slides/slide-5.php', // Planen: Frokostseminarer (Ny)
    'slides/slide-6.php', // Løsningen i Praksis: Før vs. Etter (Ny)
    'slides/slide-7.php', // Visjonen: Mer enn apper (Ny)
    'slides/slide-8.php'  // Call to Action (Ny, tidligere slide 7)
];
$total_slides = count($slides);

// --- Hent og valider nåværende slide ---
$current_slide_index = isset($_GET['slide']) ? (int)$_GET['slide'] : 0;
if ($current_slide_index < 0 || $current_slide_index >= $total_slides) {
    $current_slide_index = 0;
}

// --- Kalkuler navigasjon ---
$prev_slide_index = ($current_slide_index > 0) ? $current_slide_index - 1 : null;
$next_slide_index = ($current_slide_index < $total_slides - 1) ? $current_slide_index + 1 : null;

?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akari | Google Workspace Presentasjon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Red+Hat+Display:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <script>
        const config = {
            currentSlide: <?php echo $current_slide_index; ?>,
            totalSlides: <?php echo $total_slides; ?>,
            prevSlideUrl: <?php echo $prev_slide_index !== null ? "'index.php?slide=$prev_slide_index'" : 'null'; ?>,
            nextSlideUrl: <?php echo $next_slide_index !== null ? "'index.php?slide=$next_slide_index'" : 'null'; ?>
        };
    </script>
</head>
<body class="text-gray-800">

    <main>
        <?php include $slides[$current_slide_index]; ?>
    </main>

    <div class="slide-nav">
        <?php if ($prev_slide_index !== null): ?>
            <a href="index.php?slide=<?php echo $prev_slide_index; ?>" id="prevBtn" class="slide-nav-arrow" title="Forrige slide"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></a>
        <?php else: ?>
            <span class="slide-nav-arrow disabled"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></span>
        <?php endif; ?>
        
        <span class="slide-nav-counter"><?php echo $current_slide_index + 1; ?> / <?php echo $total_slides; ?></span>

        <?php if ($next_slide_index !== null): ?>
            <a href="index.php?slide=<?php echo $next_slide_index; ?>" id="nextBtn" class="slide-nav-arrow" title="Neste slide"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></a>
        <?php else: ?>
            <span class="slide-nav-arrow disabled"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></span>
        <?php endif; ?>
    </div>
    
    <script src="js/script.js" defer></script>
</body>
</html>
