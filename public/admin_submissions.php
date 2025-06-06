<?php
session_start(); 

// ----------- Passordbeskyttelse -----------
define('ADMIN_USER', getenv('ADMIN_USERNAME') ?: 'admin'); // Bruker ENV, med fallback
define('ADMIN_PASSWORD', getenv('ADMIN_PASSWORD') ?: 'secretPassword123!'); // Bruker ENV, med fallback

$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$actionMessage = ''; // For sletting og andre handlinger
$currentSource = $_GET['source'] ?? ($_SESSION['admin_source'] ?? 'contact'); // Default to contact
$_SESSION['admin_source'] = $currentSource;


if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === ADMIN_USER && $_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['loggedin'] = true;
        $isLoggedIn = true;
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // Fjern POST-data og query string
        exit;
    } else {
        $loginError = "Feil brukernavn eller passord.";
    }
}

// Hent databaseinnstillinger FRA MILJØVARIABLER
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE'); // Forventes satt
$dbUser = getenv('DB_USERNAME'); // Forventes satt
$dbPass = getenv('DB_PASSWORD'); // Forventes satt
$pdo = null; // Initialiser PDO

if ($isLoggedIn && (empty($dbName) || empty($dbUser) || empty($dbPass))) {
    $dbError = "Databasekonfigurasjon mangler. Sjekk miljøvariabler i Forge.";
    error_log("Database credentials missing from environment variables for admin_submissions.php");
} elseif ($isLoggedIn) {
    try {
        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    } catch (\PDOException $e) {
        $dbError = "Databasefeil: " . $e->getMessage();
        error_log("Database Error in admin_submissions.php (connection): " . $e->getMessage());
    }
}

// Tabell og feltnavn basert på kilde
$tableName = '';
$idField = 'id';
$dateField = 'submitted_at'; // Default for contact
$csvFilenamePrefix = 'data';
$pageTitle = 'Mottatt Data';
$columns = [];

if ($currentSource === 'contact') {
    $tableName = 'contact_form_submissions';
    $dateField = 'submitted_at';
    $csvFilenamePrefix = 'henvendelser';
    $pageTitle = 'Mottatte Henvendelser (Kontaktskjema)';
    $columns = [
        ['db' => 'id', 'label' => 'ID'],
        ['db' => 'submitted_at', 'label' => 'Dato', 'format_date' => 'd.m.Y H:i'],
        ['db_concat' => ['firstname', 'lastname'], 'label' => 'Navn'],
        ['db' => 'company', 'label' => 'Firma', 'default' => '-'],
        ['db' => 'email', 'label' => 'E-post', 'is_mailto' => true],
        ['db' => 'phone', 'label' => 'Telefon', 'is_tel' => true],
        ['db' => 'package_interest', 'label' => 'Pakke', 'default' => '-'],
        ['db' => 'message', 'label' => 'Melding', 'nl2br' => true, 'class' => 'message-col'],
        ['db' => 'form_source_location', 'label' => 'Kilde', 'default' => '-'],
        ['db' => 'privacy_agreed', 'label' => 'Personvern', 'format_bool' => true],
    ];
} elseif ($currentSource === 'seminar') {
    $tableName = 'seminar_registrations';
    $dateField = 'registered_at'; 
    $csvFilenamePrefix = 'seminar-paameldinger';
    $pageTitle = 'Seminarpåmeldinger';
     $columns = [
        ['db' => 'id', 'label' => 'ID'],
        ['db' => 'registered_at', 'label' => 'Påmeldt Dato', 'format_date' => 'd.m.Y H:i'],
        ['db' => 'seminar_title', 'label' => 'Seminar'], 
        ['db_concat' => ['firstname', 'lastname'], 'label' => 'Navn'],
        ['db' => 'company', 'label' => 'Firma', 'default' => '-'],
        ['db' => 'email', 'label' => 'E-post', 'is_mailto' => true],
        ['db' => 'phone', 'label' => 'Telefon', 'is_tel' => true],
        ['db' => 'num_attendees', 'label' => 'Antall', 'default' => '1'],
        ['db' => 'dietary_restrictions', 'label' => 'Kommentar', 'default' => '-', 'nl2br' => true, 'class' => 'message-col'], // Endret label her
        ['db' => 'privacy_agreed', 'label' => 'Personvern', 'format_bool' => true],
    ];
} else {
    if ($isLoggedIn) $dbError = "Ukjent datakilde valgt: " . htmlspecialchars($currentSource);
}

if (isset($_GET['clear_filters']) && $_GET['clear_filters'] === '1') {
    unset($_SESSION["filter_date_from_{$currentSource}"]);
    unset($_SESSION["filter_date_to_{$currentSource}"]);
    if ($currentSource === 'seminar') {
        unset($_SESSION["filter_seminar_slug"]);
    }
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?source=" . $currentSource);
    exit;
}


// ----------- Håndter Sletting FØR CSV/HTML -----------
if ($isLoggedIn && $pdo && !empty($tableName) && isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    if (isset($_GET['confirm_delete']) && $_GET['confirm_delete'] === 'yes') {
        try {
            $stmt_delete = $pdo->prepare("DELETE FROM {$tableName} WHERE {$idField} = ?");
            if ($stmt_delete->execute([$delete_id])) {
                $entry_type = ($currentSource === 'seminar') ? 'Påmelding' : 'Henvendelse';
                $actionMessage = "{$entry_type} med ID {$delete_id} er slettet.";
                $_SESSION['action_message'] = $actionMessage;
                header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?source=" . $currentSource);
                exit;
            } else {
                $actionMessage = "Kunne ikke slette ID {$delete_id} fra {$tableName}.";
            }
        } catch (\PDOException $e) {
            $actionMessage = "Databasefeil ved sletting: " . $e->getMessage();
            error_log("Database Error (Delete {$tableName}) in admin_submissions.php: " . $e->getMessage());
        }
    } else {
        $entry_type = ($currentSource === 'seminar') ? 'påmelding' : 'henvendelse';
        $actionMessage = "Er du sikker på at du vil slette {$entry_type} med ID {$delete_id}? ";
        $actionMessage .= "<a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "?source=" . $currentSource . "&delete_id=" . $delete_id . "&confirm_delete=yes' style='color:red;font-weight:bold;'>Ja, slett</a>";
        $actionMessage .= " | <a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "?source=" . $currentSource . "'>Avbryt</a>";
    }
}
if (isset($_SESSION['action_message'])) {
    $actionMessage = $_SESSION['action_message'];
    unset($_SESSION['action_message']);
}

// ----------- Håndter CSV nedlasting FØR HTML output -----------
if ($isLoggedIn && $pdo && !empty($tableName) && isset($_GET['download_csv']) && $_GET['download_csv'] === 'true') {
    $filterDateFromCsv = $_SESSION["filter_date_from_{$currentSource}"] ?? null;
    $filterDateToCsv = $_SESSION["filter_date_to_{$currentSource}"] ?? null;
    $filterSeminarSlugCsv = ($currentSource === 'seminar') ? ($_SESSION["filter_seminar_slug"] ?? null) : null;

    $csvSelectParts = [];
    foreach ($columns as $col) {
        if (isset($col['db_concat'])) {
            $concatFields = array_map(function($field) { return "COALESCE({$field}, '')"; }, $col['db_concat']);
            $csvSelectParts[] = "TRIM(CONCAT_WS(' ', " . implode(", ", $concatFields) . ")) AS `" . str_replace(' ', '_', $col['label']) . "`";
        } elseif (isset($col['db'])) {
            $csvSelectParts[] = $col['db'] . " AS `" . str_replace(' ', '_', $col['label']) . "`";
        }
    }
    if (empty($csvSelectParts)) { 
        die("CSV eksportfeil: Ingen kolonner definert for kilde '$currentSource'.");
    }

    $sqlCsv = "SELECT " . implode(", ", $csvSelectParts) . " FROM {$tableName}";
    $paramsCsv = [];
    $whereClausesCsv = [];

    if ($filterDateFromCsv) {
        $whereClausesCsv[] = "DATE({$dateField}) >= ?";
        $paramsCsv[] = $filterDateFromCsv;
    }
    if ($filterDateToCsv) {
        $whereClausesCsv[] = "DATE({$dateField}) <= ?";
        $paramsCsv[] = $filterDateToCsv;
    }
    if ($currentSource === 'seminar' && !empty($filterSeminarSlugCsv)) {
        $whereClausesCsv[] = "seminar_slug = ?";
        $paramsCsv[] = $filterSeminarSlugCsv;
    }


    if (!empty($whereClausesCsv)) {
        $sqlCsv .= " WHERE " . implode(" AND ", $whereClausesCsv);
    }
    $sqlCsv .= " ORDER BY {$dateField} DESC";
    
    $stmt_dl = $pdo->prepare($sqlCsv);
    $stmt_dl->execute($paramsCsv);
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $csvFilenamePrefix . '-' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF"); 
    
    $csvHeaders = array_map(function($col) { return $col['label']; }, $columns);
    fputcsv($output, $csvHeaders, ';');

    while ($row_dl = $stmt_dl->fetch(PDO::FETCH_ASSOC)) {
        $csvRow = [];
        foreach ($columns as $col) {
            $col_key_for_row = str_replace(' ', '_', $col['label']);
            $value = $row_dl[$col_key_for_row] ?? ($col['default'] ?? '');

            if (isset($col['format_bool']) && $col['format_bool']) {
                $value = $value ? 'Ja' : 'Nei';
            } elseif (isset($col['format_date']) && !empty($value) && isset($col['db']) && isset($row_dl[$col['db']])) {
                 // For CSV, vi har allerede hentet den formaterte verdien via AS `Label`
                 // Men hvis vi trenger å formatere en *annen* dato-kolonne enn den som har labelen,
                 // må vi hente den originale db-verdien.
                 // For nå, anta at AS-verdien er ok for CSV hvis format_date er satt.
                 // En mer robust løsning ville kreve å vite *hvilken* kolonne som skal hentes *før* AS.
                 // For enkelhets skyld, hvis format_date er satt, prøver vi å re-formatere hvis den *originale* db-kolonnen er tilgjengelig.
                $original_db_col_for_date = $col['db']; // Anta at 'db' peker på den originale datokollonnen
                if (isset($row_dl[$original_db_col_for_date])) {
                    try {
                        $dt = new DateTime($row_dl[$original_db_col_for_date]);
                        $value = $dt->format($col['format_date']);
                    } catch (Exception $e) { /* la verdien være som den er hvis datoformatering feiler */ }
                }
            }
            $csvRow[] = $value;
        }
        fputcsv($output, $csvRow, ';');
    }
    fclose($output);
    exit;
}

// ----- PÅLOGGINGSSKJEMA -----
if (!$isLoggedIn) {
    ?>
    <!DOCTYPE html>
    <html lang="no"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Logg inn - Admin</title>
    <style> body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f2f5; margin: 0; } .login-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 300px; text-align: center; } .login-container h1 { margin-top: 0; margin-bottom: 20px; font-size: 1.5em; color: #333; } .login-container input[type="text"], .login-container input[type="password"] { width: calc(100% - 20px); padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; } .login-container button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; } .login-container button:hover { background-color: #0056b3; } .error-message { color: red; margin-bottom: 15px; font-size: 0.9em; } </style>
    </head><body> <div class="login-container"> <h1>Logg inn for å se data</h1> <?php if (isset($loginError)): ?> <p class="error-message"><?php echo htmlspecialchars($loginError); ?></p> <?php endif; ?> <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"> <input type="text" name="username" placeholder="Brukernavn" required><br> <input type="password" name="password" placeholder="Passord" required><br> <button type="submit">Logg inn</button> </form> </div> </body></html>
    <?php
    exit; 
}

// ----------- Hent data for visning på siden -----------
$submissions = [];
$filterDateFrom = $_GET['date_from'] ?? ($_SESSION["filter_date_from_{$currentSource}"] ?? '');
$filterDateTo = $_GET['date_to'] ?? ($_SESSION["filter_date_to_{$currentSource}"] ?? '');
$_SESSION["filter_date_from_{$currentSource}"] = $filterDateFrom;
$_SESSION["filter_date_to_{$currentSource}"] = $filterDateTo;

$filterSeminarSlug = null;
if ($currentSource === 'seminar') {
    if (isset($_GET['filter_seminar_slug'])) {
        $filterSeminarSlug = $_GET['filter_seminar_slug'];
        $_SESSION["filter_seminar_slug"] = $filterSeminarSlug;
    } elseif (isset($_SESSION["filter_seminar_slug"])) {
        $filterSeminarSlug = $_SESSION["filter_seminar_slug"];
    }
}


if (empty($dbError) && $pdo && !empty($tableName)) {
    try {
        $selectFieldsDb = [];
        foreach ($columns as $col) {
            if (isset($col['db_concat'])) {
                foreach ($col['db_concat'] as $field) $selectFieldsDb[] = $field;
            } elseif (isset($col['db'])) {
                $selectFieldsDb[] = $col['db'];
            }
        }
        $selectFieldsDb = array_unique(array_filter($selectFieldsDb));
        if (empty($selectFieldsDb) && !empty($columns)) die("Visningsfeil: Ingen gyldige datakolonner for SQL-select for kilde '$currentSource'.");


        $sql = "SELECT " . implode(", ", $selectFieldsDb) . " FROM {$tableName}";
        $params = [];
        $whereClauses = [];

        if (!empty($filterDateFrom)) {
            $whereClauses[] = "DATE({$dateField}) >= ?";
            $params[] = $filterDateFrom;
        }
        if (!empty($filterDateTo)) {
            $whereClauses[] = "DATE({$dateField}) <= ?";
            $params[] = $filterDateTo;
        }
        if ($currentSource === 'seminar' && !empty($filterSeminarSlug)) {
            $whereClauses[] = "seminar_slug = ?";
            $params[] = $filterSeminarSlug;
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }
        $sql .= " ORDER BY {$dateField} DESC LIMIT 100"; 

        $stmt_display = $pdo->prepare($sql);
        $stmt_display->execute($params);
        $submissions = $stmt_display->fetchAll();

    } catch (\PDOException $e_display) {
        $dbError = "Databasefeil ({$tableName}) ved visning: " . $e_display->getMessage();
        error_log("Database Error in admin_submissions.php (display {$tableName}): " . $e_display->getMessage());
    }
}

// Hent unike seminar slugs for filter dropdown
$seminarSlugs = [];
if ($isLoggedIn && $pdo) {
    try {
        $stmt_slugs = $pdo->query("SELECT DISTINCT seminar_slug, seminar_title FROM seminar_registrations WHERE seminar_slug IS NOT NULL AND seminar_slug != '' ORDER BY seminar_title");
        $seminarSlugs = $stmt_slugs->fetchAll(PDO::FETCH_KEY_PAIR);
    } catch (\PDOException $e_slugs) {
        error_log("Database Error (fetching seminar slugs): " . $e_slugs->getMessage());
    }
}

?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo htmlspecialchars($pageTitle); ?> - Akari</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f7f6; color: #333; }
        .container { max-width: 95%; margin: 0 auto; }
        .header-actions { display: flex; flex-wrap:wrap; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom:15px; border-bottom: 1px solid #ccc;}
        .header-actions h1 { text-align: left; color: #064f55; margin-top:0; margin-bottom:10px; font-size: 1.8em; width:100%;}
        .source-switcher { margin-bottom:15px; width:100%; }
        .source-switcher a { margin-right: 10px; padding: 8px 12px; text-decoration: none; background-color: #e0e0e0; color: #333; border-radius: 4px; font-size: 0.9em; }
        .source-switcher a.active { background-color: #007bff; color: white; font-weight: bold; }
        .filter-form { display: flex; gap: 10px; align-items: center; margin-bottom:10px; flex-wrap:wrap; }
        .filter-form label {font-size:0.9em;}
        .filter-form input[type="date"], .filter-form select, .filter-form button { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9em; }
        .filter-form button { background-color: #007bff; color:white; cursor:pointer; }
        .filter-form button:hover { background-color: #0056b3; }
        .action-buttons { display: flex; gap: 10px; margin-left:auto; align-self: flex-start; }
        .button-link { text-decoration: none; color: white; padding: 8px 15px; border-radius: 4px; font-size: 0.9em; display:inline-block; text-align:center;}
        .button-link.csv { background-color: #28a745; }
        .button-link.csv:hover { background-color: #218838; }
        .button-link.logout { background-color: #dc3545; }
        .button-link.logout:hover { background-color: #c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; font-size: 0.85em; word-break: break-word; }
        th { background-color: #00a99d; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .message-col { max-width: 250px; min-width: 150px; white-space: pre-wrap; }
        .action-col {width: 80px; text-align:center;}
        .action-col a {color: #dc3545; text-decoration:none; font-weight:bold;}
        .action-col a:hover {text-decoration:underline;}
        .no-submissions { text-align: center; padding: 20px; font-style: italic; color: #777; }
        .status-message { text-align:center; padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; }
        .status-message.success { background-color: #d4edda; border:1px solid #c3e6cb; color: #155724; }
        .status-message.error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .status-message.info { background-color: #d1ecf1; border:1px solid #bee5eb; color: #0c5460; }
        @media (max-width: 768px) {
            .filter-form, .action-buttons { flex-direction: column; align-items: stretch; width: 100%; }
            .filter-form > *, .action-buttons > * { width: auto; margin-bottom: 5px; }
            .action-buttons { margin-left: 0; margin-top: 10px; }
            th, td { font-size: 0.8em; padding: 6px 8px;}
        }
    </style>
    <script>
        function confirmDelete(id) {
            return confirm("Er du helt sikker på at du vil slette denne? Dette kan ikke angres.");
        }
    </script>
</head>
<body>
<div class="container">
    <div class="header-actions">
        <h1><?php echo htmlspecialchars($pageTitle); ?></h1>

        <div class="source-switcher">
            Kilde:
            <a href="?source=contact&clear_filters=1" class="<?php echo ($currentSource === 'contact') ? 'active' : ''; ?>">Kontaktskjema</a>
            <a href="?source=seminar&clear_filters=1" class="<?php echo ($currentSource === 'seminar') ? 'active' : ''; ?>">Seminar</a>
        </div>
        
        <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="filter-form">
            <input type="hidden" name="source" value="<?php echo htmlspecialchars($currentSource); ?>">
            <label for="date_from">Fra:</label>
            <input type="date" name="date_from" id="date_from" value="<?php echo htmlspecialchars($filterDateFrom); ?>">
            <label for="date_to">Til:</label>
            <input type="date" name="date_to" id="date_to" value="<?php echo htmlspecialchars($filterDateTo); ?>">
            
            <?php if ($currentSource === 'seminar' && !empty($seminarSlugs)): ?>
                <label for="filter_seminar_slug">Seminar:</label>
                <select name="filter_seminar_slug" id="filter_seminar_slug">
                    <option value="">Alle seminarer</option>
                    <?php foreach ($seminarSlugs as $slug => $title): ?>
                        <option value="<?php echo htmlspecialchars($slug); ?>" <?php echo (($filterSeminarSlug ?? '') === $slug) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($title . (!empty($slug) ? ' (' . $slug . ')' : '')); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <button type="submit">Filtrer</button>
            <a href="<?php echo htmlspecialchars(strtok($_SERVER["REQUEST_URI"], '?')); ?>?source=<?php echo $currentSource; ?>&clear_filters=1" style="margin-left:5px; font-size:0.9em;">Nullstill filter</a>
        </form>
        <div class="action-buttons">
            <?php if (!empty($tableName)) : ?>
            <a href="?download_csv=true&source=<?php echo $currentSource; ?>" class="button-link csv">Last ned CSV</a>
            <?php endif; ?>
            <a href="?logout=1" class="button-link logout">Logg ut</a>
        </div>
    </div>

    <?php if (isset($_GET['logout'])): ?>
        <?php
        $_SESSION = array(); 
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();
        echo "<p class='status-message success'>Du er nå logget ut.</p>";
        echo "<p style='text-align:center;'><a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>Logg inn igjen</a></p>";
        ?>
    </div></body></html><?php exit; endif; ?>

    <?php if (!empty($actionMessage)): ?>
        <p class="status-message info"><?php echo $actionMessage; ?></p>
    <?php endif; ?>
    <?php if (!empty($dbError)): ?>
        <p class="status-message error"><?php echo htmlspecialchars($dbError); ?></p>
    <?php endif; ?>

    <?php if (empty($submissions) && empty($dbError) && !empty($tableName) && !str_contains((string)$actionMessage, 'Er du sikker')): ?>
        <p class="no-submissions">Ingen data funnet for valgt kilde og periode.</p>
    <?php elseif (!empty($submissions) && !empty($tableName)): ?>
        <table>
            <thead>
                <tr>
                    <?php foreach ($columns as $col): ?>
                        <th><?php echo htmlspecialchars($col['label']); ?></th>
                    <?php endforeach; ?>
                    <th class="action-col">Handling</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $row): ?>
                    <tr>
                        <?php foreach ($columns as $col): ?>
                            <td class="<?php echo htmlspecialchars($col['class'] ?? ''); ?>">
                                <?php
                                $value = '';
                                if (isset($col['db_concat'])) {
                                    $valuesToConcat = [];
                                    foreach ($col['db_concat'] as $field) {
                                        // Sjekk om feltet faktisk eksisterer i raden for å unngå feilmeldinger
                                        $valuesToConcat[] = htmlspecialchars($row[$field] ?? '');
                                    }
                                    $value = trim(implode(' ', array_filter($valuesToConcat))); 
                                    if(empty($value) && isset($col['default'])) $value = $col['default'];
                                } elseif (isset($col['db'])) {
                                     // Sjekk om feltet faktisk eksisterer i raden
                                    $value = $row[$col['db']] ?? ($col['default'] ?? '');
                                }

                                if (isset($col['format_bool']) && $col['format_bool']) {
                                    echo $value ? 'Ja' : 'Nei';
                                } elseif (isset($col['format_date']) && !empty($value)) {
                                    try {
                                        $dt = new DateTime($value);
                                        echo $dt->format($col['format_date']);
                                    } catch (Exception $e) {
                                        echo htmlspecialchars($value);
                                    }
                                } elseif (isset($col['is_mailto']) && $col['is_mailto'] && !empty($value)) {
                                    echo '<a href="mailto:' . htmlspecialchars($value) . '">' . htmlspecialchars($value) . '</a>';
                                } elseif (isset($col['is_tel']) && $col['is_tel'] && !empty($value)) {
                                    echo '<a href="tel:' . htmlspecialchars(str_replace(' ', '', $value)) . '">' . htmlspecialchars($value) . '</a>';
                                } elseif (isset($col['nl2br']) && $col['nl2br']) {
                                    echo nl2br(htmlspecialchars($value));
                                } else {
                                    echo htmlspecialchars($value);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                        <td class="action-col">
                            <a href="?source=<?php echo $currentSource; ?>&delete_id=<?php echo $row[$idField]; ?>" onclick="return confirmDelete(<?php echo $row[$idField]; ?>);">Slett</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
