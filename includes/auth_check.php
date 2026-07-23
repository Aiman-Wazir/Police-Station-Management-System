
<!-- here -->
 <?php

include(__DIR__ . '/session.php');

/* Check if admin is logged in */
if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] !== true
) {
    header("Location: ../auth/login.php");
    exit();
}

/* Auto logout after 15 minutes of inactivity */
$timeout = 900; // 900 seconds = 15 minutes

if (
    isset($_SESSION['LAST_ACTIVITY']) &&
    (time() - $_SESSION['LAST_ACTIVITY']) > $timeout
) {

    session_unset();
    session_destroy();

    header("Location: ../auth/login.php");
    exit();
}

/* Update last activity time */
$_SESSION['LAST_ACTIVITY'] = time();

?>