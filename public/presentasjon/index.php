<?php
// --- Konfigurasjon ---
// En liste over alle slidene i rekkefølge. Dette er vår "source of truth".
$slides = [
    'slides/slide-1.php',
    'slides/slide-2.php',
    'slides/slide-3.php',
    'slides/slide-4.php',
    'slides/slide-5.php',
    'slides/slide-6.php',
    'slides/slide-7.php',
];
$total_slides = count($slides);

// --- Hent og valider nåværende slide ---
// Hent slide-nummer fra URL (?slide=X). Standard til 0 (første slide).
$current_slide_index = isset($_GET['slide']) ? (int)$_GET['slide'] : 0;

// Sikkerhetssjekk: Sørg for at slide-nummeret er gyldig.
if ($current_slide_index < 0 || $current_slide_index >= $total_slides) {
    $current_slide_index = 0; // Gå til første slide hvis ugyldig
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

    <!-- Gi JavaScript informasjonen den trenger -->
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
        <?php
        // Inkluder den riktige slide-filen
        include $slides[$current_slide_index];
        ?>
    </main>

    <?php if ($prev_slide_index !== null): ?>
        <a href="index.php?slide=<?php echo $prev_slide_index; ?>" id="prevBtn" class="nav-button">Forrige</a>
    <?php endif; ?>

    <?php if ($next_slide_index !== null): ?>
        <a href="index.php?slide=<?php echo $next_slide_index; ?>" id="nextBtn" class="nav-button">Neste</a>
    <?php endif; ?>

    <script src="js/script.js" defer></script>
</body>
</html>
