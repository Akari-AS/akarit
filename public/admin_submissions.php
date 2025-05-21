<?php
session_start(); 

// ----------- Passordbeskyttelse -----------
define('ADMIN_USER', getenv('ADMIN_USERNAME') ?: 'admin');
define('ADMIN_PASS', getenv('ADMIN_PASSWORD') ?: 'secretPassword123!');

$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$actionMessage = ''; // For sletting og andre handlinger

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === ADMIN_USER && $_POST['password'] === ADMIN_PASS) {
        $_SESSION['loggedin'] = true;
        $isLoggedIn = true;
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // Fjern POST-data og query string
        exit;
    } else {
        $loginError = "Feil brukernavn eller passord.";
    }
}

// Hent databaseinnstillinger
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE');
$dbUser = getenv('DB_USERNAME');
$dbPass = getenv('DB_PASSWORD');
$pdo = null; // Initialiser PDO

if ($isLoggedIn && (empty($dbName) || empty($dbUser) || empty($dbPass))) {
    $dbError = "Databasekonfigurasjon mangler. Kan ikke hente henvendelser.";
    error_log("Database credentials missing for admin_submissions.php");
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

// ----------- Håndter Sletting FØR CSV/HTML hvis logget inn og PDO er OK -----------
if ($isLoggedIn && $pdo && isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    if (isset($_GET['confirm_delete']) && $_GET['confirm_delete'] === 'yes') {
        try {
            $stmt_delete = $pdo->prepare("DELETE FROM contact_form_submissions WHERE id = ?");
            if ($stmt_delete->execute([$delete_id])) {
                $actionMessage = "Henvendelse med ID " . $delete_id . " er slettet.";
                $_SESSION['action_message'] = $actionMessage; // Lagre melding for visning etter redirect
                header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // Fjern query string
                exit;
            } else {
                $actionMessage = "Kunne ikke slette henvendelse med ID " . $delete_id . ".";
            }
        } catch (\PDOException $e) {
            $actionMessage = "Databasefeil ved sletting: " . $e->getMessage();
            error_log("Database Error (Delete) in admin_submissions.php: " . $e->getMessage());
        }
    } else {
        // Vis bekreftelsesside før sletting (kan gjøres mer elegant med JavaScript senere)
        // For nå, legg til en enkel bekreftelsesmelding og en knapp for å gå tilbake.
        $actionMessage = "Er du sikker på at du vil slette henvendelse med ID " . $delete_id . "? ";
        $actionMessage .= "<a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "?delete_id=" . $delete_id . "&confirm_delete=yes' style='color:red;font-weight:bold;'>Ja, slett</a>";
        $actionMessage .= " | <a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>Avbryt</a>";
    }
}
if (isset($_SESSION['action_message'])) {
    $actionMessage = $_SESSION['action_message'];
    unset($_SESSION['action_message']);
}


// ----------- Håndter CSV nedlasting FØR HTML output hvis logget inn og PDO er OK -----------
if ($isLoggedIn && $pdo && isset($_GET['download_csv']) && $_GET['download_csv'] === 'true') {
    // Hent datoer for filtrering hvis de er satt for CSV
    $filterDateFromCsv = $_SESSION['filter_date_from'] ?? null;
    $filterDateToCsv = $_SESSION['filter_date_to'] ?? null;
    
    $sqlCsv = "SELECT id, submitted_at, firstname, lastname, company, email, phone, package_interest, message, privacy_agreed, form_source_location FROM contact_form_submissions";
    $paramsCsv = [];
    $whereClausesCsv = [];

    if ($filterDateFromCsv) {
        $whereClausesCsv[] = "DATE(submitted_at) >= ?";
        $paramsCsv[] = $filterDateFromCsv;
    }
    if ($filterDateToCsv) {
        $whereClausesCsv[] = "DATE(submitted_at) <= ?";
        $paramsCsv[] = $filterDateToCsv;
    }

    if (!empty($whereClausesCsv)) {
        $sqlCsv .= " WHERE " . implode(" AND ", $whereClausesCsv);
    }
    $sqlCsv .= " ORDER BY submitted_at DESC";
    
    $stmt_dl = $pdo->prepare($sqlCsv);
    $stmt_dl->execute($paramsCsv);
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="henvendelser-' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF"); 
    fputcsv($output, ['ID', 'Dato Innsendt', 'Fornavn', 'Etternavn', 'Firma', 'E-post', 'Telefon', 'Interessert i Pakke', 'Melding', 'Personvern Godtatt', 'Kilde/Lokasjon'], ';');

    while ($row_dl = $stmt_dl->fetch()) {
        fputcsv($output, array_values($row_dl), ';');
    }
    fclose($output);
    exit;
}


if (!$isLoggedIn) {
    // Vis påloggingsskjema (samme som før)
    ?>
    <!DOCTYPE html>
    <html lang="no"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Logg inn - Henvendelser</title>
    <style> body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f2f5; margin: 0; } .login-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 300px; text-align: center; } .login-container h1 { margin-top: 0; margin-bottom: 20px; font-size: 1.5em; color: #333; } .login-container input[type="text"], .login-container input[type="password"] { width: calc(100% - 20px); padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; } .login-container button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; } .login-container button:hover { background-color: #0056b3; } .error-message { color: red; margin-bottom: 15px; font-size: 0.9em; } </style>
    </head><body> <div class="login-container"> <h1>Logg inn for å se henvendelser</h1> <?php if (isset($loginError)): ?> <p class="error-message"><?php echo htmlspecialchars($loginError); ?></p> <?php endif; ?> <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"> <input type="text" name="username" placeholder="Brukernavn" required><br> <input type="password" name="password" placeholder="Passord" required><br> <button type="submit">Logg inn</button> </form> </div> </body></html>
    <?php
    exit; 
}

// ----------- Hent data fra databasen for visning på siden -----------
$submissions = [];
$dbError = ''; // Ble satt tidligere hvis det var feil med tilkobling

// Datofiltrering
$filterDateFrom = $_GET['date_from'] ?? ($_SESSION['filter_date_from'] ?? '');
$filterDateTo = $_GET['date_to'] ?? ($_SESSION['filter_date_to'] ?? '');

// Lagre filter i session slik at det huskes og brukes av CSV-eksport
$_SESSION['filter_date_from'] = $filterDateFrom;
$_SESSION['filter_date_to'] = $filterDateTo;

if ($pdo) { // Fortsett kun hvis PDO-tilkobling er OK
    try {
        $sql = "SELECT id, firstname, lastname, company, email, phone, package_interest, message, privacy_agreed, form_source_location, submitted_at FROM contact_form_submissions";
        $params = [];
        $whereClauses = [];

        if (!empty($filterDateFrom)) {
            $whereClauses[] = "DATE(submitted_at) >= ?";
            $params[] = $filterDateFrom;
        }
        if (!empty($filterDateTo)) {
            $whereClauses[] = "DATE(submitted_at) <= ?";
            $params[] = $filterDateTo;
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }
        $sql .= " ORDER BY submitted_at DESC LIMIT 100"; // Behold LIMIT for sidevisning

        $stmt_display = $pdo->prepare($sql);
        $stmt_display->execute($params);
        $submissions = $stmt_display->fetchAll();

    } catch (\PDOException $e_display) {
        $dbError = "Databasefeil ved visning: " . $e_display->getMessage();
        error_log("Database Error in admin_submissions.php (display): " . $e_display->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Henvendelser - Akari Google Workspace</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f7f6; color: #333; }
        .header-actions { display: flex; flex-wrap:wrap; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom:15px; border-bottom: 1px solid #ccc;}
        h1 { text-align: left; color: #064f55; margin-top:0; margin-bottom:0; flex-basis:100%; margin-bottom:10px;}
        .filter-form { display: flex; gap: 10px; align-items: center; margin-bottom:10px; flex-wrap:wrap; }
        .filter-form label {font-size:0.9em;}
        .filter-form input[type="date"], .filter-form button { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9em; }
        .filter-form button { background-color: #007bff; color:white; cursor:pointer; }
        .filter-form button:hover { background-color: #0056b3; }
        .action-buttons { display: flex; gap: 10px; margin-left:auto; align-self: flex-start; } /* Justert for plassering */
        .button-link { text-decoration: none; color: white; padding: 8px 15px; border-radius: 4px; font-size: 0.9em; display:inline-block; text-align:center;}
        .button-link.csv { background-color: #28a745; }
        .button-link.csv:hover { background-color: #218838; }
        .button-link.logout { background-color: #dc3545; }
        .button-link.logout:hover { background-color: #c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; font-size: 0.85em; }
        th { background-color: #00a99d; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .message-col { max-width: 250px; min-width: 150px; word-wrap: break-word; white-space: pre-wrap; }
        .action-col {width: 80px; text-align:center;}
        .action-col a {color: #dc3545; text-decoration:none; font-weight:bold;}
        .action-col a:hover {text-decoration:underline;}
        .no-submissions { text-align: center; padding: 20px; font-style: italic; color: #777; }
        .status-message { text-align:center; padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; }
        .status-message.success { background-color: #d4edda; border:1px solid #c3e6cb; color: #155724; }
        .status-message.error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .status-message.info { background-color: #d1ecf1; border:1px solid #bee5eb; color: #0c5460; }
    </style>
    <script>
        function confirmDelete(id) {
            return confirm("Er du helt sikker på at du vil slette henvendelse med ID " + id + "? Dette kan ikke angres.");
        }
    </script>
</head>
<body>
    <div class="header-actions">
        <h1>Mottatte Henvendelser</h1>
        <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="filter-form">
            <label for="date_from">Fra:</label>
            <input type="date" name="date_from" id="date_from" value="<?php echo htmlspecialchars($filterDateFrom); ?>">
            <label for="date_to">Til:</label>
            <input type="date" name="date_to" id="date_to" value="<?php echo htmlspecialchars($filterDateTo); ?>">
            <button type="submit">Filtrer</button>
            <a href="<?php echo htmlspecialchars(strtok($_SERVER["REQUEST_URI"], '?')); ?>" style="margin-left:5px; font-size:0.9em;">Nullstill filter</a>
        </form>
        <div class="action-buttons">
            <a href="?download_csv=true" class="button-link csv">Last ned CSV</a>
            <a href="?logout=1" class="button-link logout">Logg ut</a>
        </div>
    </div>

    <?php if (isset($_GET['logout'])): ?>
        <?php
        $_SESSION = array(); 
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        echo "<p class='status-message success'>Du er nå logget ut.</p>"; // Bruker status-message klassen
        echo "<p style='text-align:center;'><a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>Logg inn igjen</a></p>";
        ?>
    </body></html><?php exit; endif; ?>

    <?php if (!empty($actionMessage)): ?>
        <p class="status-message info"><?php echo $actionMessage; /* HTML er allerede bygget for slettebekreftelse */ ?></p>
    <?php endif; ?>
    <?php if (!empty($dbError)): ?>
        <p class="status-message error"><?php echo htmlspecialchars($dbError); ?></p>
    <?php endif; ?>

    <?php if (empty($submissions) && empty($dbError) && !str_contains($actionMessage, 'Er du sikker')): // Ikke vis "ingen henvendelser" hvis vi viser slettebekreftelse ?>
        <p class="no-submissions">Ingen henvendelser funnet for valgt periode.</p>
    <?php elseif (!empty($submissions)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Dato</th>
                    <th>Navn</th>
                    <th>Firma</th>
                    <th>E-post</th>
                    <th>Telefon</th>
                    <th>Pakke</th>
                    <th class="message-col">Melding</th>
                    <th>Kilde</th>
                    <th>Personvern</th>
                    <th class="action-col">Handling</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($row['submitted_at']))); ?></td>
                        <td><?php echo htmlspecialchars(trim($row['firstname'] . ' ' . $row['lastname'])); ?></td>
                        <td><?php echo htmlspecialchars($row['company'] ?: '-'); ?></td>
                        <td><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a></td>
                        <td><a href="tel:<?php echo htmlspecialchars($row['phone']); ?>"><?php echo htmlspecialchars($row['phone']); ?></a></td>
                        <td><?php echo htmlspecialchars($row['package_interest'] ?: '-'); ?></td>
                        <td class="message-col"><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td><?php echo htmlspecialchars($row['form_source_location'] ?: '-'); ?></td>
                        <td><?php echo $row['privacy_agreed'] ? 'Ja' : 'Nei'; ?></td>
                        <td class="action-col">
                            <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirmDelete(<?php echo $row['id']; ?>);">Slett</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
