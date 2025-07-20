<?php
session_start();
include('database.php');

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Fetch all donations along with the user_id from the users table
$sql = "SELECT donations.*, users.user_id FROM donations
        JOIN users ON donations.user_id = users.user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Donations - Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .delete-btn {
            padding: 5px 10px;
            color: white;
            background-color: #e74c3c;
            text-decoration: none;
            border-radius: 3px;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>View Donations</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Donation ID</th>
                    <th>User ID</th>
                    <th>Food Item</th>
                    <th>Quantity</th>
                    <th>Expiry Date</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['donation_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['food_item']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td>
                            <?php
                            // Check if the current date is after the expiry date (excluding time)
                            $expiry_date = date('Y-m-d', strtotime($row['expiry_date']));
                            $current_date = date('Y-m-d');
                            echo ($current_date > $expiry_date) ? "Expired" : htmlspecialchars($expiry_date);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <a href="delete_donation.php?id=<?php echo $row['donation_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this donation?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No donations found.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>
</body>
</html>
