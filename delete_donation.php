<?php
session_start();
include('database.php');

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Check if the donation ID is provided
if (isset($_GET['id'])) {
    $donation_id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM donations WHERE donation_id = ?");
    $stmt->bind_param("i", $donation_id);

    if ($stmt->execute()) {
        // If deletion was successful, redirect back to view_donations.php
        header("Location: view_donations.php");
        exit();
    } else {
        echo "<script>alert('Failed to delete donation.'); window.location.href = 'view_donations.php';</script>";
    }

    $stmt->close();
} else {
    header("Location: view_donations.php");
    exit();
}

$conn->close();
?>
