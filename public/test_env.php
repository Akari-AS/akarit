<?php // /home/forge/googleworkspace.akari.no/public/test_env.php
phpinfo(); // Viser all PHP-konfigurasjon, inkludert miljøvariabler

echo "<hr>Testing getenv():<br>";
echo "MAIL_FROM_ADDRESS: " . (getenv('MAIL_FROM_ADDRESS') ?: 'NOT SET') . "<br>";
echo "MAIL_FROM_NAME: " . (getenv('MAIL_FROM_NAME') ?: 'NOT SET') . "<br>";
echo "MAILGUN_SMTP_USERNAME: " . (getenv('MAILGUN_SMTP_USERNAME') ?: 'NOT SET') . "<br>";
echo "MAILGUN_SMTP_PASSWORD: " . (getenv('MAILGUN_SMTP_PASSWORD') ? 'SET (hidden)' : 'NOT SET') . "<br>"; // Ikke skriv ut passordet!
echo "MAIL_RECIPIENT_ADDRESS: " . (getenv('MAIL_RECIPIENT_ADDRESS') ?: 'NOT SET') . "<br>";
echo "APP_URL: " . (getenv('APP_URL') ?: 'NOT SET') . "<br>";

echo "<hr>Direct SERVER vars (if set by web server):<br>";
echo "SERVER[MAIL_FROM_ADDRESS]: " . ($_SERVER['MAIL_FROM_ADDRESS'] ?? 'NOT SET IN SERVER') . "<br>";
?>
