<?php
session_start();
include('database.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to claim a donation.'); window.location.href = 'login.html';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $donation_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Insert into claims table to register the claim
    $sql = "INSERT INTO claims (donation_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $donation_id, $user_id);

    if ($stmt->execute()) {
        // Update the status of the donation to 'claimed'
        $updateSql = "UPDATE donations SET status = 'claimed' WHERE donation_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $donation_id);
        $updateStmt->execute();
        $updateStmt->close();

        echo "<script>alert('Donation claimed successfully!'); window.location.href = 'available_donations.php';</script>";
    } else {
        echo "<script>alert('Error claiming donation. Please try again.'); window.location.href = 'available_donations.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
