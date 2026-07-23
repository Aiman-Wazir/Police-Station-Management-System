
<!-- here -->
 <?php

include '../includes/session.php';
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: login.php");
    exit();
}

$username = trim($_POST['username']);
$password = $_POST['password'];
$ip = $_SERVER['REMOTE_ADDR'];

/* ==========================
   CHECK IF ACCOUNT IS LOCKED
========================== */

$stmt = $conn->prepare("
SELECT * FROM login_attempts
WHERE username=? AND ip_address=?
");

$stmt->bind_param("ss", $username, $ip);
$stmt->execute();

$lockResult = $stmt->get_result();

if ($lockResult->num_rows > 0) {

    $lock = $lockResult->fetch_assoc();

    if (
        $lock['lock_until'] != NULL &&
        strtotime($lock['lock_until']) > time()
    ) {

        echo "<script>
        alert('Account Locked. Try again after ".$lock['lock_until']."');
        window.location='login.php';
        </script>";
        exit();
    }
}

/* ==========================
   CHECK USERNAME
========================== */

$stmt = $conn->prepare("SELECT * FROM admins WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {

    $admin = $result->fetch_assoc();

    if (password_verify($password, $admin['password'])) {

        session_regenerate_id(true);

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['LAST_ACTIVITY'] = time();

        /* Reset Login Attempts */

        $stmt = $conn->prepare("
        DELETE FROM login_attempts
        WHERE username=? AND ip_address=?
        ");

        $stmt->bind_param("ss", $username, $ip);
        $stmt->execute();

        /* Save Login History */

        $stmt = $conn->prepare("
        INSERT INTO login_history
        (username,ip_address,login_time,status,reason)
        VALUES
        (?, ?, NOW(), 'Success', 'Logged In')
        ");

        $stmt->bind_param("ss", $username, $ip);
        $stmt->execute();

        header("Location: ../admin/dashboard.php");
        exit();

    }

}

/* ==========================
   LOGIN FAILED
========================== */

$stmt = $conn->prepare("
SELECT * FROM login_attempts
WHERE username=? AND ip_address=?
");

$stmt->bind_param("ss", $username, $ip);
$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows > 0) {

    $row = $res->fetch_assoc();

    $attempts = $row['attempts'] + 1;

    if ($attempts >= 3) {

        $stmt = $conn->prepare("
        UPDATE login_attempts
        SET attempts=?,
            lock_until=DATE_ADD(NOW(), INTERVAL 1 HOUR)
        WHERE username=? AND ip_address=?
        ");

        $stmt->bind_param("iss", $attempts, $username, $ip);
        $stmt->execute();

        $status = "Locked";
        $reason = "Exceeded Maximum Attempts";

    } else {

        $stmt = $conn->prepare("
        UPDATE login_attempts
        SET attempts=?
        WHERE username=? AND ip_address=?
        ");

        $stmt->bind_param("iss", $attempts, $username, $ip);
        $stmt->execute();

        $status = "Failed";
        $reason = "Wrong Password";

    }

} else {

    $attempts = 1;

    $stmt = $conn->prepare("
    INSERT INTO login_attempts
    (username,ip_address,attempts)
    VALUES
    (?, ?, 1)
    ");

    $stmt->bind_param("ss", $username, $ip);
    $stmt->execute();

    $status = "Failed";
    $reason = "Wrong Password";

}

/* ==========================
   SAVE LOGIN HISTORY
========================== */

$stmt = $conn->prepare("
INSERT INTO login_history
(username,ip_address,login_time,status,reason)
VALUES
(?, ?, NOW(), ?, ?)
");

$stmt->bind_param("ssss", $username, $ip, $status, $reason);
$stmt->execute();

/* Remaining Attempts */

$remaining = max(0, 3 - $attempts);

if ($status == "Locked") {

    echo "<script>
    alert('Account Locked for 1 Hour');
    window.location='login.php';
    </script>";

} else {

    echo "<script>
    alert('Invalid Username or Password\\nRemaining Attempts: $remaining');
    window.location='login.php';
    </script>";

}

exit();

?>