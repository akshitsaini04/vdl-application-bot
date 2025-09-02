<?php
// This file handles all interactions from Discord (button clicks and modal submits).

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
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } else if ($method === 'PATCH') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_code < 200 || $http_code >= 300) {
        error_log("Discord API Error ($http_code) for $url: $response");
    }

    curl_close($ch);
    return json_decode($response, true);
}

// --- Main Interaction Logic ---

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data === null) { http_response_code(400); exit(); }

// 1. Verify the signature (Security check from Discord)
$signature = $_SERVER['HTTP_X_SIGNATURE_ED25519'] ?? '';
$timestamp = $_SERVER['HTTP_X_SIGNATURE_TIMESTAMP'] ?? '';

try {
    $is_valid = sodium_crypto_sign_verify_detached(hex2bin($signature), $timestamp . $input, hex2bin(PUBLIC_KEY));
} catch (Exception $e) {
    error_log("Sodium verification failed: " . $e->getMessage());
    $is_valid = false;
}


if (!$is_valid) {
    error_log("Invalid request signature. Timestamp: $timestamp");
    http_response_code(401); // Unauthorized
    exit('Invalid request signature');
}

// 2. Handle different interaction types
$type = $data['type'];

if ($type === 1) { // Ping from Discord
    header('Content-Type: application/json');
    echo json_encode(['type' => 1]); // Respond with a Pong
    exit();
}

// --- A) User clicks a button ---
if ($type === 2) { 
    $custom_id = $data['data']['custom_id'];
    $parts = explode('_', $custom_id);
    $action = $parts[0];

    // IF "ACCEPT" IS CLICKED
    if ($action === 'accept') {
        // STEP 1: Acknowledge the interaction immediately to prevent "Interaction Failed" error
        header('Content-Type: application/json');
        echo json_encode(['type' => 6]); // DEFERRED_UPDATE_MESSAGE
        
        // Ensure the response is sent before continuing
        if (function_exists('fastcgi_finish_request')) { fastcgi_finish_request(); } else { ob_flush(); flush(); }

        // STEP 2: Now do the slow work in the background
        $applicant_id = $parts[2];
        $admin_id = $data['member']['user']['id'];
        $department = str_replace('New Application: ', '', $data['message']['embeds'][0]['title']);

        // Send public message
        $public_message_url = "https://discord.com/api/v10/channels/" . PUBLIC_CHANNEL_ID . "/messages";
        send_discord_request($public_message_url, 'POST', [
            'content' => "<@{$applicant_id}>",
            'embeds' => [[
                "title" => "Application Update", "description" => "Your application for the **{$department}** has been reviewed.", "color" => 3066993, // Green
                "fields" => [
                    ["name" => "Applicant", "value" => "<@{$applicant_id}>", "inline" => true],
                    ["name" => "Status", "value" => "**Accepted**", "inline" => true],
                    ["name" => "Reviewed By", "value" => "<@{$admin_id}>", "inline" => true]
                ], "footer" => ["text" => "VDL Roleplay Applications"], "timestamp" => date("c")
            ]]
        ]);
        
        // Edit original admin message
        $interaction_token = $data['token'];
        $edit_url = "https://discord.com/api/v10/webhooks/" . APPLICATION_ID . "/" . $interaction_token . "/messages/@original";
        $original_embed = $data['message']['embeds'][0];
        $original_embed['color'] = 3066993; // Green
        $original_embed['fields'][] = ["name" => "STATUS", "value" => "**Accepted by <@{$admin_id}>**"];
        send_discord_request($edit_url, 'PATCH', ['embeds' => [$original_embed], 'components' => []]);

        exit();
    }
    
    // IF "REJECT" IS CLICKED (This shows the pop-up form)
    if ($action === 'reject') {
        header('Content-Type: application/json');
        echo json_encode([
            "type" => 9, // This type opens a Modal
            "data" => [
                "custom_id" => "rejection_reason_" . $parts[1] . "_" . $parts[2], // Pass info to the modal
                "title" => "Rejection Reason",
                "components" => [[
                    "type" => 1,
                    "components" => [[
                        "type" => 4, // Text Input
                        "custom_id" => "reason_input",
                        "label" => "Reason for Rejection (Required)",
                        "style" => 2, // Paragraph style
                        "min_length" => 10,
                        "placeholder" => "Please provide a clear reason for rejecting this application.",
                        "required" => true
                    ]]
                ]]
            ]
        ]);
        exit();
    }
}

// --- B) Admin submits the reason from the modal pop-up ---
if ($type === 5) {
    // STEP 1: Acknowledge immediately
    header('Content-Type: application/json');
    echo json_encode(['type' => 6]); // DEFERRED_UPDATE_MESSAGE
    if (function_exists('fastcgi_finish_request')) { fastcgi_finish_request(); } else { ob_flush(); flush(); }
    
    // STEP 2: Do the slow work
    $custom_id = $data['data']['custom_id'];
    $reason = $data['data']['components'][0]['components'][0]['value'];
    $parts = explode('_', $custom_id);
    $applicant_id = $parts[2];
    $admin_id = $data['member']['user']['id'];
    $department = str_replace('New Application: ', '', $data['message']['embeds'][0]['title']);

    // Send public message WITH the reason
    $public_message_url = "https://discord.com/api/v10/channels/" . PUBLIC_CHANNEL_ID . "/messages";
    send_discord_request($public_message_url, 'POST', [
        'content' => "<@{$applicant_id}>",
        'embeds' => [[
            "title" => "Application Update", "description" => "Your application for the **{$department}** has been reviewed.", "color" => 15158332, // Red
            "fields" => [
                ["name" => "Applicant", "value" => "<@{$applicant_id}>", "inline" => true],
                ["name" => "Status", "value" => "**Rejected**", "inline" => true],
                ["name" => "Reviewed By", "value" => "<@{$admin_id}>", "inline" => true],
                ["name" => "Reason", "value" => htmlspecialchars($reason), "inline" => false]
            ], "footer" => ["text" => "VDL Roleplay Applications"], "timestamp" => date("c")
        ]]
    ]);
    
    // Edit original admin message WITH the reason
    $interaction_token = $data['token'];
    $edit_url = "https://discord.com/api/v10/webhooks/" . APPLICATION_ID . "/" . $interaction_token . "/messages/@original";
    $original_embed = $data['message']['embeds'][0];
    $original_embed['color'] = 15158332; // Red
    $original_embed['fields'][] = ["name" => "STATUS", "value" => "**Rejected by <@{$admin_id}>**"];
    $original_embed['fields'][] = ["name" => "Reason Given", "value" => ">>> " . htmlspecialchars($reason)];
    send_discord_request($edit_url, 'PATCH', ['embeds' => [$original_embed], 'components' => []]);

    exit();
}
?>
