<?php
session_start();
// Include the secure file with all our secrets
require_once 'config.php';

// Check if Discord sent back a "code"
if (!isset($_GET['code'])) {
    header('Location: index.php?error=discord_login_failed');
    exit();
}

$code = $_GET['code'];

// The redirect_uri MUST EXACTLY MATCH the one in your Discord Developer Portal
// NOTE: On a live server, this would be your public URL, not localhost.
$redirect_uri = 'http://localhost/discord-app/callback.php';

// Prepare the data to exchange the code for an access token
$token_request_data = [
    'client_id' => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri,
];

// Use cURL to send the request to Discord's servers
$ch = curl_init('https://discord.com/api/oauth2/token');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_request_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$token_data = json_decode($response, true);

// Check if we got a valid access token
if (!isset($token_data['access_token'])) {
    // Redirect back to the main page with an error
    $_SESSION['login_error'] = 'Failed to get access token from Discord.';
    header('Location: index.php?error=token_error');
    exit();
}

// Now use the access token to get the user's details
$access_token = $token_data['access_token'];
$user_url = 'https://discord.com/api/users/@me';

$ch_user = curl_init($user_url);
curl_setopt($ch_user, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token]);
curl_setopt($ch_user, CURLOPT_RETURNTRANSFER, true);
$user_response = curl_exec($ch_user);
curl_close($ch_user);

$user_data = json_decode($user_response, true);

// Store the essential user info in the session
$_SESSION['discord_user'] = [
    'id' => $user_data['id'],
    'username' => $user_data['username'],
    'avatar' => $user_data['avatar']
];

// Redirect the user to the application form page
header('Location: form.php');
exit();
?>

