<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Active Listings - Food Donation Platform</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS -->
    <style>
        /* Add some basic styling for the table */
        table {
            width: 100%;
            border-collapse: collapse;
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
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .back-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Active Listings</h1>
            <a href="userop.html" class="back-btn">Go back to Dashboard</a> 
            <a href="login.html" class="back-btn">Go to Login</a> 
        </div>

        <div class="listings">
           
            <?php
            
            session_start();
            
           
            include('database.php');

            
            $user_id = $_SESSION['user_id'];  
            $sql = "SELECT * FROM donations WHERE user_id = ? AND status = 'available'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);  

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                
                
                if ($result->num_rows > 0) {
                    
                    echo "<table>";
                    echo "<tr><th>Donation ID</th><th>Food Name</th><th>Quantity</th><th>Expiry Date</th><th>Type of Food</th><th>Location</th><th>Status</th><th>Action</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['donation_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['food_item']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['expiry_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['type_of_food']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td><a href='delete_listing.php?donation_id=" . $row['donation_id'] . "' class='delete-btn'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    
                    echo "<p>You have no active donations at the moment.</p>";
                }
            } else {
                echo "<p>Error fetching active donations: " . $stmt->error . "</p>";
            }

           
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
