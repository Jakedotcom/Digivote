<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE email = ?");
    $stmt->bind_param("ss", $status, $email);

    if ($stmt->execute()) {
        echo "User status updated successfully.";
    } else {
        echo "Error updating user status: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
