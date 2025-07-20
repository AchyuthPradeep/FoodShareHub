<?php
session_start();
include('database.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('You must be an admin to delete users.'); window.location.href = 'login.html';</script>";
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Delete the user
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully.'); window.location.href = 'manage_users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user.'); window.location.href = 'manage_users.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'manage_users.php';</script>";
}

$conn->close();
?>
