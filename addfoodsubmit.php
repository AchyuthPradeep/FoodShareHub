<?php
// Start the session to retrieve the logged-in user ID
session_start();

// Include the database connection file
include('database.php');

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user_id is set in the session
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('User ID not found in session. Please log in.'); window.location.href = 'login.html';</script>";
        exit();
    }

    // Retrieve form data
    $user_id = $_SESSION['user_id']; // This is the logged-in user's ID from the session
    $food_name = $_POST['food_name']; // This corresponds to 'food_item' column in DB
    $quantity = $_POST['quantity'];  // This corresponds to 'quantity' column in DB
    $expiry_date = $_POST['expiry_date']; // This corresponds to 'expiry_date' column in DB
    $food_type = $_POST['type_of_food']; // This corresponds to 'type_of_food' column in DB
    $location = $_POST['location']; // This corresponds to 'location' column in DB (city)
    $pickup_address = $_POST['pickup_address']; // This corresponds to 'address' column in DB

    // Prepare the SQL insert statement with error handling
    $sql = "INSERT INTO donations (user_id, food_item, quantity, expiry_date, type_of_food, location, address, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'available')";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    // Bind parameters - Corrected quantity as integer 'i' and other fields as strings 's'
    $stmt->bind_param("issssss", $user_id, $food_name, $quantity, $expiry_date, $food_type, $location, $pickup_address);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect on successful insertion to the active listings page
        echo "<script>alert('Food donation added successfully!'); window.location.href = 'active_listings.php';</script>";
    } else {
        // Display error message if execution failed
        echo "<script>alert('Error adding food donation: " . $stmt->error . "'); window.location.href = 'addfood.html';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request method. Please submit the form.'); window.location.href = 'addfood.html';</script>";
}
?>
