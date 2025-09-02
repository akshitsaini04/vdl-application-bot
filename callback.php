<?php
$client_id = "1407114756053139588";
$client_secret = "FS3LE1ohKHgFTcz3Z0VOkEsZ76db0ZBa";
$redirect_uri = "http://localhost/discord-app/callback.php";

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $data = array(
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "grant_type" => "authorization_code",
        "code" => $code,
        "redirect_uri" => $redirect_uri,
        "scope" => "identify"
    );

    $ch = curl_init("https://discord.com/api/oauth2/token");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $token = json_decode($response, true);

    $ch = curl_init("https://discord.com/api/users/@me");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $token['access_token']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $user_response = curl_exec($ch);
    curl_close($ch);

    $user = json_decode($user_response, true);

    session_start();
    $_SESSION['user'] = $user;
    header("Location: form.php");
    exit();
} else {
    echo "No code returned from Discord.";
}
