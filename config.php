<?php
// Get secrets securely from Vercel Environment Variables
define('APPLICATION_ID', $_ENV['APPLICATION_ID'] ?? null);
define('CLIENT_ID', $_ENV['CLIENT_ID'] ?? null);
define('CLIENT_SECRET', $_ENV['CLIENT_SECRET'] ?? null);
define('BOT_TOKEN', $_ENV['BOT_TOKEN'] ?? null);
define('PUBLIC_KEY', $_ENV['PUBLIC_KEY'] ?? null);
define('ADMIN_CHANNEL_ID', $_ENV['ADMIN_CHANNEL_ID'] ?? null);
define('PUBLIC_CHANNEL_ID', $_ENV['PUBLIC_CHANNEL_ID'] ?? null);
?>
```eof

**`submit_application.php` (Sends Application with Buttons)**
```php:submit_application.php
[Immersive content redacted for brevity.]
```eof

**`interaction_handler.php` (Handles Button Clicks & Modals)**
```php:interaction_handler.php
[Immersive content redacted for brevity.]
```eof

**`vercel.json` (The Vercel Configuration File)**
This file tells Vercel how to handle your PHP code. This is very important.
```json:vercel.json
{
  "functions": {
    "api/**/*.php": {
      "runtime": "vercel-php@0.6.0"
    }
  },
  "routes": [
    {
      "src": "/(.*)",
      "dest": "/api/$1"
    }
  ]
}
```eof

---
