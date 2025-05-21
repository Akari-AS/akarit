<?php
session_start(); // Start session for å lagre påloggingsstatus

// ----------- Passordbeskyttelse -----------
// Hent fra miljøvariabler, med fallbacks (endre fallbacks før produksjon hvis du ikke bruker env)
define('ADMIN_USER', getenv('ADMIN_USERNAME') ?: 'admin');
define('ADMIN_PASS', getenv('ADMIN_PASSWORD') ?: 'secretPassword123!'); // ENDRE DENNE FALLBACKEN!

$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === ADMIN_USER && $_POST['password'] === ADMIN_PASS) {
        $_SESSION['loggedin'] = true;
        $isLoggedIn = true;
        // Omdiriger for å fjerne POST-data fra URL etter vellykket pålogging
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $loginError = "Feil brukernavn eller passord.";
    }
}

if (!$isLoggedIn) {
    // Vis påloggingsskjema
    ?>
    <!DOCTYPE html>
    <html lang="no">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logg inn - Henvendelser</title>
        <style>
            body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f2f5; margin: 0; }
            .login-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 300px; text-align: center; }
            .login-container h1 { margin-top: 0; margin-bottom: 20px; font-size: 1.5em; color: #333; }
            .login-container input[type="text"], .login-container input[type="password"] { width: calc(100% - 20px); padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
            .login-container button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
            .login-container button:hover { background-color: #0056b3; }
            .error-message { color: red; margin-bottom: 15px; font-size: 0.9em; }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h1>Logg inn for å se henvendelser</h1>
            <?php if (isset($loginError)): ?>
                <p class="error-message"><?php echo htmlspecialchars($loginError); ?></p>
            <?php endif; ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input type="text" name="username" placeholder="Brukernavn" required><br>
                <input type="password" name="password" placeholder="Passord" required><br>
                <button type="submit">Logg inn</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit; // Ikke vis noe mer hvis ikke logget inn
}

// ----------- Hent data fra databasen -----------
$submissions = [];
$dbError = '';

// Hent databaseinnstillinger (samme som i form_handler.php)
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE');
$dbUser = getenv('DB_USERNAME');
$dbPass = getenv('DB_PASSWORD');

if (empty($dbName) || empty($dbUser) || empty($dbPass)) {
    $dbError = "Databasekonfigurasjon mangler. Kan ikke hente henvendelser.";
    error_log("Database credentials missing for admin_submissions.php");
} else {
    $pdo = null;
    try {
        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

        // Hent de siste 100 innsendingene, nyeste først
        $stmt = $pdo->query("SELECT id, firstname, lastname, company, email, phone, package_interest, message, privacy_agreed, form_source_location, submitted_at FROM contact_form_submissions ORDER BY submitted_at DESC LIMIT 100");
        $submissions = $stmt->fetchAll();

    } catch (\PDOException $e) {
        $dbError = "Databasefeil: " . $e->getMessage();
        error_log("Database Error in admin_submissions.php: " . $e->getMessage());
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
        h1 { text-align: center; color: #064f55; margin-bottom:30px; }
        .logout-link { position: absolute; top: 20px; right: 20px; text-decoration: none; background-color: #dc3545; color: white; padding: 8px 15px; border-radius: 4px; font-size: 0.9em;}
        .logout-link:hover { background-color: #c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; font-size: 0.9em; }
        th { background-color: #00a99d; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .message-col { max-width: 300px; word-wrap: break-word; }
        .no-submissions { text-align: center; padding: 20px; font-style: italic; color: #777; }
        .error-message { color: red; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; margin-bottom:20px; text-align:center;}
    </style>
</head>
<body>
    <a href="?logout=1" class="logout-link">Logg ut</a>
    <h1>Mottatte Henvendelser</h1>

    <?php if (isset($_GET['logout'])): ?>
        <?php
        $_SESSION = array(); // Tøm alle session variabler
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        echo "<p style='text-align:center; color:green;'>Du er nå logget ut.</p>";
        echo "<p style='text-align:center;'><a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>Logg inn igjen</a></p>";
        // Stopp videre lasting av siden
        ?>
    </body></html>
        <?php
        exit;
    endif; ?>


    <?php if (!empty($dbError)): ?>
        <p class="error-message"><?php echo htmlspecialchars($dbError); ?></p>
    <?php endif; ?>

    <?php if (empty($submissions) && empty($dbError)): ?>
        <p class="no-submissions">Ingen henvendelser funnet.</p>
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
