<?php
// Start the session to store user data
session_start();

// Include the database connection file
include('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Prepare SQL query to fetch user data based on username and role
    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check if password matches
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Store user ID and role in the session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($role == 'user') {
                header('Location: userop.html'); // Redirect to user options page
            } else {
                header('Location: admin.html'); // Redirect to admin dashboard
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('No user found with the given username and role!'); window.location.href='login.html';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>