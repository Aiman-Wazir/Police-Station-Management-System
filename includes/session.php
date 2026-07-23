<?php
// Secure session settings
session_set_cookie_params([
    'lifetime' => 0,          // Session ends when browser closes
    'path' => '/',
    'httponly' => true,       // Prevent JavaScript access
    'samesite' => 'Strict'    // Protect against CSRF
]);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>