<?php
session_start();
include('database.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('You must be an admin to view user history.'); window.location.href = 'login.html';</script>";
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch the donation history
    $donations_sql = "SELECT * FROM donations WHERE user_id = ?";
    $donations_stmt = $conn->prepare($donations_sql);
    $donations_stmt->bind_param("i", $user_id);
    $donations_stmt->execute();
    $donations_result = $donations_stmt->get_result();

    // Fetch the claims history
    $claims_sql = "SELECT claims.claim_id, donations.food_item, donations.quantity, donations.expiry_date, donations.type_of_food, donations.location, claims.claim_date
                   FROM claims
                   JOIN donations ON claims.donation_id = donations.donation_id
                   WHERE claims.user_id = ?";
    $claims_stmt = $conn->prepare($claims_sql);
    $claims_stmt->bind_param("i", $user_id);
    $claims_stmt->execute();
    $claims_result = $claims_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User History</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #4CAF50;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #f9f9f9;
        }

        /* Row hover effect */
        tr:hover {
            background-color: #eaf2e5;
        }

        /* Responsive button styling */
        .back-btn {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Donation and Claim History</h1>
        <a href="manage_users.php" class="back-btn">Back to User Management</a>

        <!-- Display Donations -->
        <h2>Donations</h2>
        <table>
            <thead>
                <tr>
                    <th>Donation ID</th>
                    <th>Food Item</th>
                    <th>Quantity</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $donations_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['donation_id']) ?></td>
                        <td><?= htmlspecialchars($row['food_item']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td><?= htmlspecialchars($row['expiry_date']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Display Claims -->
        <h2>Claims</h2>
        <table>
            <thead>
                <tr>
                    <th>Claim ID</th>
                    <th>Food Item</th>
                    <th>Quantity</th>
                    <th>Claim Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $claims_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['claim_id']) ?></td>
                        <td><?= htmlspecialchars($row['food_item']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td><?= htmlspecialchars($row['claim_date']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
    $donations_stmt->close();
    $claims_stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid user ID.'); window.location.href = 'manage_users.php';</script>";
}
?>
