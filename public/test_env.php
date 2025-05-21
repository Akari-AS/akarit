<?php

echo "<h1>PHP Environment Test</h1>";

echo "<h2>phpinfo() output:</h2>";
// For sikkerhets skyld, vis kun Environment-delen hvis du vil begrense info
// For full info, bare bruk: phpinfo();
ob_start();
phpinfo(INFO_ENVIRONMENT); // Viser kun Environment-variabler
$phpinfo_env = ob_get_contents();
ob_end_clean();

// Fjern potensielt sensitiv info før visning hvis ønskelig,
// men for feilsøking nå er det greit å se det.
echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:20px; max-height: 400px; overflow-y:auto;'>";
echo $phpinfo_env;
echo "</div>";


echo "<hr style='margin: 20px 0;'>";
echo "<h2>getenv() specific variables:</h2>";
echo "<div style='border:1px solid #ccc; padding:10px;'>";
echo "<strong>Mailgun Settings:</strong><br>";
echo "MAILGUN_SMTP_HOST: " . (getenv('MAILGUN_SMTP_HOST') ?: 'NOT SET') . "<br>";
echo "MAILGUN_SMTP_PORT: " . (getenv('MAILGUN_SMTP_PORT') ?: 'NOT SET') . "<br>";
echo "MAILGUN_SMTP_USERNAME: " . (getenv('MAILGUN_SMTP_USERNAME') ?: 'NOT SET') . "<br>";
echo "MAILGUN_SMTP_PASSWORD: " . (getenv('MAILGUN_SMTP_PASSWORD') ? 'SET (value hidden)' : 'NOT SET') . "<br>"; // Skjuler passordet

echo "<br><strong>Mail From Settings:</strong><br>";
echo "MAIL_FROM_ADDRESS: " . (getenv('MAIL_FROM_ADDRESS') ?: 'NOT SET') . "<br>";
echo "MAIL_FROM_NAME: " . (getenv('MAIL_FROM_NAME') ?: 'NOT SET') . "<br>";

echo "<br><strong>Mail Recipient Settings:</strong><br>";
echo "MAIL_RECIPIENT_ADDRESS: " . (getenv('MAIL_RECIPIENT_ADDRESS') ?: 'NOT SET') . "<br>";

echo "<br><strong>Database Settings:</strong><br>";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET') . "<br>";
echo "DB_PORT: " . (getenv('DB_PORT') ?: 'NOT SET') . "<br>";
echo "DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NOT SET') . "<br>";
echo "DB_USERNAME: " . (getenv('DB_USERNAME') ?: 'NOT SET') . "<br>";
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') ? 'SET (value hidden)' : 'NOT SET') . "<br>"; // Skjuler passordet

echo "<br><strong>App Settings:</strong><br>";
echo "APP_NAME: " . (getenv('APP_NAME') ?: 'NOT SET') . "<br>";
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'NOT SET') . "<br>";
echo "APP_DEBUG: " . (getenv('APP_DEBUG') ?: 'NOT SET') . "<br>";
echo "APP_URL: " . (getenv('APP_URL') ?: 'NOT SET') . "<br>";
echo "</div>";

echo "<hr style='margin: 20px 0;'>";
echo "<h2>Direct \$_SERVER variables (if set by web server, less common for these types):</h2>";
echo "<div style='border:1px solid #ccc; padding:10px;'>";
echo "\$_SERVER['MAIL_FROM_ADDRESS']: " . ($_SERVER['MAIL_FROM_ADDRESS'] ?? 'NOT SET in $_SERVER') . "<br>";
echo "\$_SERVER['MAILGUN_SMTP_USERNAME']: " . ($_SERVER['MAILGUN_SMTP_USERNAME'] ?? 'NOT SET in $_SERVER') . "<br>";
echo "\$_SERVER['DB_DATABASE']: " . ($_SERVER['DB_DATABASE'] ?? 'NOT SET in $_SERVER') . "<br>";
echo "</div>";

?>
