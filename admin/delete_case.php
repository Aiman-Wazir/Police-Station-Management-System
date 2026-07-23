<?php
include(__DIR__ . '/../includes/db_connect.php');
include(__DIR__ . '/../includes/auth_check.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);



if (isset($_GET['case_number'])) {

    $case_number = $_GET['case_number'];

    // Debug: show value
    echo "Case Number: " . $case_number . "<br>";

    $stmt = $conn->prepare("DELETE FROM case_records WHERE case_number = ?");
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $case_number);

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            echo "✅ Case deleted successfully!";
        } else {
            echo "❌ No record found (check case number)";
        }

    } else {
        echo "❌ Execute failed: " . $stmt->error;
    }

} else {
    echo "❌ case_number not received";
}
?>