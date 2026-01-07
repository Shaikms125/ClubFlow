<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generates a CSRF token if one doesn't exist and stores it in the session.
 * @return string The CSRF token.
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Outputs a hidden input field with the CSRF token.
 */
function csrf_token() {
    $token = generate_csrf_token();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

/**
 * Verifies the CSRF token from a POST request (or GET if specified).
 * Terminates execution if validation fails.
 * 
 * @param string $method 'POST' or 'GET'
 */
function verify_csrf_token($method = 'POST', $redirect_url = null) {
    $session_token = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : null;
    $provided_token = null;

    if ($method === 'POST') {
        $provided_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : null;
    } else {
        $provided_token = isset($_GET['csrf_token']) ? $_GET['csrf_token'] : null;
    }

    $valid = !empty($session_token) && !empty($provided_token) && hash_equals($session_token, $provided_token);
    if ($valid) return;

    if ($redirect_url === null) {
        $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../index.php?error=csrf';
    }

    if (!headers_sent()) {
        header("Location: $redirect_url");
    }
    exit;
}
?>
