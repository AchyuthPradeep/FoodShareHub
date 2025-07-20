<?php
// Start the session to retrieve the logged-in user ID
session_start();

// Include the database connection file
include('database.php');

// Check if the donation_id is passed in the URL
if (isset($_GET['donation_id'])) {
    $donation_id = $_GET['donation_id'];

    // Prepare the SQL query to delete the donation
    $sql = "DELETE FROM donations WHERE donation_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    
    // Check if statement preparation is successful
    if ($stmt) {
        // Bind the donation_id and user_id to the query
        $stmt->bind_param("ii", $donation_id, $_SESSION['user_id']);
        
        // Execute the statement and check if the deletion was successful
        if ($stmt->execute()) {
            echo "<script>alert('Donation deleted successfully.'); window.location.href = 'active_listings.php';</script>";
        } else {
            echo "<script>alert('Error deleting donation: " . $stmt->error . "'); window.location.href = 'active_listings.php';</script>";
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing delete statement.'); window.location.href = 'active_listings.php';</script>";
    }
} else {
    echo "<script>alert('No donation ID specified.'); window.location.href = 'active_listings.php';</script>";
}

// Close the database connection
$conn->close();
?>
