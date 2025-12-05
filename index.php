<?php
// Cấu hình session để hoạt động tốt với ngrok
if (session_status() === PHP_SESSION_NONE) {
    // Đặt cookie session cho tất cả path
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

require_once "app/config.php";   
require_once "app/DB.php";
require_once "app/Controller.php";
require_once "app/App.php";      

$App = new App();
?> 