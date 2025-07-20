<?php
session_start();
include('database.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('You must be an admin to manage users.'); window.location.href = 'login.html';</script>";
    exit();
}

// Fetch users and admins separately
$users_sql = "SELECT user_id, name, email, role, created_at FROM users WHERE role = 'user'";
$admins_sql = "SELECT user_id, name, email, role, created_at FROM users WHERE role = 'admin'";

$users_result = $conn->query($users_sql);
$admins_result = $conn->query($admins_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script>
        function toggleAddAdminForm() {
            var form = document.getElementById("addAdminForm");
            form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
        }
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Manage Users</h1>
        </header>

        <div class="actions">
            <button class="btn primary" onclick="toggleAddAdminForm()">Add New Admin</button>
        </div>

        <!-- Add New Admin Form -->
        <div id="addAdminForm" class="form-popup" style="display: none;">
            <h2>Add New Admin</h2>
            <form action="add_admin.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="btn success">Add Admin</button>
                <button type="button" class="btn danger" onclick="toggleAddAdminForm()">Cancel</button>
            </form>
        </div>

        <!-- Users List -->
        <h2>Users</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user_id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td>
                            <a href="view_user_history.php?user_id=<?= $row['user_id'] ?>" class="btn small">View History</a>
                            <a href="delete_user.php?user_id=<?= $row['user_id'] ?>" class="btn danger small" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Admins List -->
        <h2>Admins</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $admins_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user_id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td>
                            <a href="delete_user.php?user_id=<?= $row['user_id'] ?>" class="btn danger small" onclick="return confirm('Are you sure you want to delete this admin?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
