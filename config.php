<?php
// This file securely loads your secrets from Vercel's Environment Variables.
// NEVER put your actual tokens in this file when using Vercel.

// These values are pulled from your Vercel project's "Settings > Environment Variables" tab.
define('APPLICATION_ID', $_ENV['APPLICATION_ID'] ?? null);
define('CLIENT_ID', $_ENV['CLIENT_ID'] ?? null);
define('CLIENT_SECRET', $_ENV['CLIENT_SECRET'] ?? null);
define('BOT_TOKEN', $_ENV['BOT_TOKEN'] ?? null);
define('PUBLIC_KEY', $_ENV['PUBLIC_KEY'] ?? null);
define('ADMIN_CHANNEL_ID', $_ENV['ADMIN_CHANNEL_ID'] ?? null);
define('PUBLIC_CHANNEL_ID', $_ENV['PUBLIC_CHANNEL_ID'] ?? null);

?>

