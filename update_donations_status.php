<?php
// update_donations_status.php

// Include the database connection
include('database.php');

// Update the status of donations that have expired
$sql = "UPDATE donations 
        SET status = 'expired' 
        WHERE expiry_date < CURDATE() AND status = 'available'";

if ($conn->query($sql) === TRUE) {
    echo "Donations status updated successfully.";
} else {
    echo "Error updating donations status: " . $conn->error;
}

// Close the connection
$conn->close();
?>
