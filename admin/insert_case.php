<?php
include("../includes/db_connect.php");
include(__DIR__ . '/../includes/auth_check.php');

$name = $_POST['name'];
$cnic = $_POST['cnic'];
$complaint = $_POST['complaint'];

$query = "INSERT INTO cases (name, cnic, complaint, status)
VALUES ('$name', '$cnic', '$complaint', 'Pending')";

mysqli_query($conn, $query);

header("Location: view_cases.php");
?>