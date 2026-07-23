<?php
$conn = mysqli_connect(
"DB_HOST",
"DB_USER",
"DB_PASSWORD",
"DB_NAME"
);;

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
## Database Setup

1. Create a MySQL database
2. Import database.sql
3. Update includes/db_connect.php with your credentials