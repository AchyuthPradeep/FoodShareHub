<?php
session_start();
include('database.php'); // Include database connection

// Check if the user is logged in as an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('You must be an admin to access this page.'); window.location.href = 'login.html';</script>";
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists in the database
    $check_sql = "SELECT email FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Email already exists, display alert
        echo "<script>alert('An account with this email already exists. Please use a different email.'); window.location.href = 'manage_users.php';</script>";
    } else {
        // Email does not exist, proceed to add new admin
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters and execute the query
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('New admin added successfully.'); window.location.href = 'manage_users.php';</script>";
            } else {
                echo "<script>alert('Error adding admin. Please try again.');</script>";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "<script>alert('Error preparing the query.');</script>";
        }
    }

    // Close check statement
    $check_stmt->close();
}

// Close the database connection
$conn->close();
?>
