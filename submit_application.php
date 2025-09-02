<?php
session_start();
require_once 'config.php';

// Function to send API requests to Discord
function send_discord_request($url, $method = 'POST', $data = null) {
    $ch = curl_init($url);
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bot ' . BOT_TOKEN,
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $response_data = json_decode($response, true);

    if ($http_code < 200 || $http_code >= 300) {
        $_SESSION['form_error'] = "Discord API Error: " . ($response_data['message'] ?? 'Unknown error.');
        return false;
    }
    return true;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.php');
    exit();
}

// --- Collect Form Data ---
$department = $_POST['department'] ?? 'N/A';
$age = $_POST['age'] ?? 'N/A';
$timezone = $_POST['timezone'] ?? 'N/A';
$availability = $_POST['availability'] ?? 'N/A';
$experience = $_POST['experience'] ?? 'N/A';
$why_choose = $_POST['why_choose'] ?? 'N/A';

// Applicant's Discord Info
$applicant_id = $_POST['discord_id'] ?? 'N/A';
$applicant_name = $_POST['discord_name'] ?? 'N/A';

// Create a unique ID for this application
$application_id = time();

// --- Build the Rich Embed for Discord ---
$embed = [
    "title" => "New Application: " . $department,
    "description" => "Submitted by **" . htmlspecialchars($applicant_name) . "** (<@{$applicant_id}>)",
    "color" => hexdec("3498DB"), // Blue for pending
    "fields" => [
        ["name" => "Age", "value" => $age, "inline" => true],
        ["name" => "Timezone", "value" => $timezone, "inline" => true],
        ["name" => "Weekly Hours", "value" => $availability, "inline" => true],
        ["name" => "Past Experience", "value" => ">>> " . htmlspecialchars($experience), "inline" => false],
        ["name" => "Why should we choose you?", "value" => ">>> " . htmlspecialchars($why_choose), "inline" => false],
    ],
    "footer" => ["text" => "Application ID: " . $application_id],
    "timestamp" => date("c")
];

// --- Build the Buttons (Components) ---
$components = [[
    "type" => 1, // Action Row
    "components" => [
        [
            "type" => 2, // Button
            "style" => 3, // Green
            "label" => "Accept",
            "custom_id" => "accept_{$application_id}_{$applicant_id}"
        ],
        [
            "type" => 2, // Button
            "style" => 4, // Red
            "label" => "Reject",
            // *** THIS IS THE ONLY LINE THAT CHANGED ***
            "custom_id" => "reject_modal_{$application_id}_{$applicant_id}"
        ]
    ]
]];

// --- Send to Discord ---
$payload = ["embeds" => [$embed], "components" => $components];
$url = "https://discord.com/api/v10/channels/" . ADMIN_CHANNEL_ID . "/messages";

if (send_discord_request($url, 'POST', $payload)) {
    header('Location: form.php?status=success');
} else {
    // The session variable 'form_error' is already set by the function
    header('Location: form.php?status=error');
}
exit();
?>