
<!-- her -->
 <?php
session_start();

include '../includes/db_connect.php';

if (isset($_SESSION['username'])) {

    $username = $_SESSION['username'];
    $ip = $_SERVER['REMOTE_ADDR'];

    // Update the latest active login record with logout time
    $stmt = $conn->prepare("
        UPDATE login_history
        SET logout_time = NOW()
        WHERE username = ?
        AND ip_address = ?
        AND logout_time IS NULL
        ORDER BY id DESC
        LIMIT 1
    ");

    $stmt->bind_param("ss", $username, $ip);
    $stmt->execute();
}

// Clear all session data
$_SESSION = [];

// Destroy the session
session_destroy();

// Delete the session cookie (if one exists)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirect to login page
header("Location: login.php");
exit();
?>