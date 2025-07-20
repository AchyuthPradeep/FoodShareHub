<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Donations - Food Donation Platform</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS -->
    <style>
        /* Inline styling for quick setup */
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
        .back-btn, .claim-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .back-btn:hover, .claim-btn:hover {
            background-color: #45a049;
        }
        /* Styling the filter container to be at the top-right */
        .filter-container {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .filter-container select {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .filter-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .filter-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Available Donations</h1>
            <a href="userop.html" class="back-btn">Go back to Dashboard</a>
        </div>

        <!-- Filter Form Positioned at Top Right -->
        <div class="filter-container">
            <form action="available_donations.php" method="GET">
                <!-- Dropdown for Food Name -->
                <select name="food_name">
                    <option value="">Food Name</option>
                    <?php
                    // Fetch distinct food names from the donations table
                    include('database.php');
                    $sql_food = "SELECT DISTINCT food_item FROM donations WHERE status = 'available'";
                    $result_food = $conn->query($sql_food);

                    // Display food items in the dropdown
                    while ($row_food = $result_food->fetch_assoc()) {
                        $selected = (isset($_GET['food_name']) && $_GET['food_name'] == $row_food['food_item']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row_food['food_item']) . "' $selected>" . htmlspecialchars($row_food['food_item']) . "</option>";
                    }
                    ?>
                </select>

                <!-- Dropdown for Location -->
                <select name="location">
                    <option value="">Location</option>
                    <?php
                    // Fetch distinct locations from the donations table
                    $sql_location = "SELECT DISTINCT location FROM donations WHERE status = 'available'";
                    $result_location = $conn->query($sql_location);

                    // Display locations in the dropdown
                    while ($row_location = $result_location->fetch_assoc()) {
                        $selected = (isset($_GET['location']) && $_GET['location'] == $row_location['location']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row_location['location']) . "' $selected>" . htmlspecialchars($row_location['location']) . "</option>";
                    }
                    ?>
                </select>

                <button type="submit">Filter</button>
            </form>
        </div>

        <div class="donations">
            <?php
            // Start session and include database connection
            session_start();
            include('database.php');

            // Get the filter values from the form (with default as empty if not set)
            $food_name = isset($_GET['food_name']) ? $_GET['food_name'] : '';
            $location = isset($_GET['location']) ? $_GET['location'] : '';

            // Get today's date
            $today = date('Y-m-d');  // Format: YYYY-MM-DD

            // Get the current logged-in user's ID
            $current_user_id = $_SESSION['user_id'];

            // Fetch available donations with optional filtering, excluding the user's own donations
            $sql = "
                SELECT donations.*, users.name AS user_name, users.email AS user_email
                FROM donations
                JOIN users ON donations.user_id = users.user_id
                WHERE donations.status = 'available'
                AND donations.user_id != ?
            ";

            // Add filters to the SQL query if they are provided
            if ($food_name != '') {
                $sql .= " AND donations.food_item = ?";
            }
            if ($location != '') {
                $sql .= " AND donations.location = ?";
            }

            // Prepare the statement
            $stmt = $conn->prepare($sql);

            // Bind the parameters dynamically based on which filters are applied
            if ($food_name != '' && $location != '') {
                $stmt->bind_param("iss", $current_user_id, $food_name, $location);
            } elseif ($food_name != '') {
                $stmt->bind_param("is", $current_user_id, $food_name);
            } elseif ($location != '') {
                $stmt->bind_param("is", $current_user_id, $location);
            } else {
                $stmt->bind_param("i", $current_user_id);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any available donations
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Donation ID</th><th>Food Name</th><th>Quantity</th><th>Expiry Date</th><th>Type of Food</th><th>Location</th><th>Address</th><th>Donor Name</th><th>Donor Email</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    // Check if the expiry date has passed
                    $expiry_date = $row['expiry_date'];
                    if ($expiry_date < $today) {
                        $action = "Expired";
                    } else {
                        $action = "<a href='claim_donation.php?id=" . $row['donation_id'] . "' class='claim-btn'>Claim</a>";
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['donation_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['food_item']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['expiry_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['type_of_food']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['user_email']) . "</td>";
                    echo "<td>" . $action . "</td>";  
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No available donations found based on the filter.</p>";
            }

            // Close the connection
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
