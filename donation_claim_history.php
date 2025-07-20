<?php
session_start();
include('database.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your donation and claim history.'); window.location.href = 'login.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Query to fetch donations made by the user (both available and claimed)
$donations_sql = "SELECT * FROM donations WHERE user_id = ?";
$donations_stmt = $conn->prepare($donations_sql);
$donations_stmt->bind_param("i", $user_id);
$donations_stmt->execute();
$donations_result = $donations_stmt->get_result();

// Query to fetch claims made by the user
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
    <title>Your Donation & Claim History - Food Waste Reduction Platform</title>
    <link rel="stylesheet" href="style.css">
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
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
        }
        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Donation & Claim History</h1>
            <a href="userop.html" class="back-btn">Back to Dashboard</a>
        </div>

        <!-- Donation History -->
        <div class="donations">
            <h2>Your Donations</h2>
            <?php if ($donations_result->num_rows > 0): ?>
                <table>
                    <tr><th>Donation ID</th><th>Food Name</th><th>Quantity</th><th>Expiry Date</th><th>Type of Food</th><th>Location</th><th>Status</th></tr>
                    <?php while ($row = $donations_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['donation_id']) ?></td>
                            <td><?= htmlspecialchars($row['food_item']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['expiry_date']) ?></td>
                            <td><?= htmlspecialchars($row['type_of_food']) ?></td>
                            <td><?= htmlspecialchars($row['location']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No donations found.</p>
            <?php endif; ?>
        </div>

        <!-- Claim History -->
        <div class="claims">
            <h2>Your Claims</h2>
            <?php if ($claims_result->num_rows > 0): ?>
                <table>
                    <tr><th>Claim ID</th><th>Food Name</th><th>Quantity</th><th>Expiry Date</th><th>Type of Food</th><th>Location</th><th>Claim Date</th></tr>
                    <?php while ($row = $claims_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['claim_id']) ?></td>
                            <td><?= htmlspecialchars($row['food_item']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['expiry_date']) ?></td>
                            <td><?= htmlspecialchars($row['type_of_food']) ?></td>
                            <td><?= htmlspecialchars($row['location']) ?></td>
                            <td><?= htmlspecialchars($row['claim_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No claims found.</p>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>

<?php
// Close the database connection
$donations_stmt->close();
$claims_stmt->close();
$conn->close();
?>
