<?php
// This file is for testing the bot connection directly.
echo "<pre style='font-family: monospace; background: #222; color: #eee; padding: 20px; border-radius: 10px;'>";
echo "--- VDL BOT TEST SCRIPT ---<br><br>";

require_once 'config.php';

// Check if constants are defined
if (!defined('BOT_TOKEN') || !defined('ADMIN_CHANNEL_ID')) {
    die("ERROR: BOT_TOKEN or ADMIN_CHANNEL_ID is not defined in config.php. Please check your configuration.");
}
if (BOT_TOKEN === 'YOUR_BOT_TOKEN_HERE') {
    die("ERROR: Please replace 'YOUR_BOT_TOKEN_HERE' with your actual bot token in config.php.");
}
echo "Config file loaded successfully.<br>";

// --- SAMPLE DATA ---
$department = "Test Department";
$discord_name = "TestUser";
$discord_id = "1234567890";
$application_id = time();

// --- BUILD EMBED & COMPONENTS (Same as submit_application.php) ---
$embed = [
    "title" => "BOT TEST: " . $department,
    "description" => "This is a test message from the bot script.",
    "color" => hexdec("22C55E"), // Green for test
    "fields" => [
        ["name" => "Status", "value" => "If you see this, the bot can send messages.", "inline" => false],
    ],
    "footer" => ["text" => "Test ID: " . $application_id],
    "timestamp" => date("c")
];

$components = [[
    "type" => 1,
    "components" => [
        ["type" => 2, "style" => 3, "label" => "Test Accept", "custom_id" => "accept_test_1_1"],
        ["type" => 2, "style" => 4, "label" => "Test Reject", "custom_id" => "reject_test_1_1"]
    ]
]];

$payload = json_encode(["embeds" => [$embed], "components" => $components]);
$url = "https://discord.com/api/v10/channels/" . ADMIN_CHANNEL_ID . "/messages";

echo "Attempting to send message to Channel ID: " . ADMIN_CHANNEL_ID . "<br>";

// --- SEND REQUEST TO DISCORD ---
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bot ' . BOT_TOKEN
    ],
    CURLOPT_RETURNTRANSFER => true
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// --- SHOW THE RESULT ---
echo "---------------------------<br>";
echo "HTTP Response Code: " . $http_code . "<br><br>";
echo "Discord's Response:<br>";
print_r($response);
echo "<br><br>";

if ($http_code >= 200 && $http_code < 300) {
    echo "--- RESULT: SUCCESS! ---<br>";
    echo "The bot successfully sent a message to your Discord channel. Please check your admin channel now.";
} else {
    echo "--- RESULT: FAILED! ---<br>";
    echo "The bot could not send a message. The error above from Discord will tell you why. Common reasons:<br>";
    echo "- **401 Unauthorized:** Your BOT_TOKEN is incorrect in config.php.<br>";
    echo "- **404 Not Found:** Your ADMIN_CHANNEL_ID is incorrect in config.php.<br>";
    echo "- **403 Forbidden:** The bot does not have permission to send messages in that channel.";
}

echo "</pre>";
?>


XDHqgo113s1CT