<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "UnixPassword123@";
$database = "real_estate_test";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch listings
$sql = "SELECT L.id, L.listing_name, L.listing_description, L.listing_address, L.listing_price, 
               L.listing_available, L.listing_image_url, U.username
        FROM Listings L
        LEFT JOIN Users U ON L.user_id = U.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listings</title>
</head>
<body>
<a href="../index.php">Go to Landing Pages</a>
    <h1>Available Listings</h1>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>" . htmlspecialchars($row['listing_name']) . "</h2>";
            echo "<p>" . htmlspecialchars($row['listing_description']) . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($row['listing_address']) . "</p>";
            echo "<p><strong>Price:</strong> $" . number_format($row['listing_price'], 2) . "</p>";
            echo "<p><strong>Available:</strong> " . ($row['listing_available'] ? 'Yes' : 'No') . "</p>";
            echo "<p><strong>Listed by:</strong> " . htmlspecialchars($row['username']) . "</p>";
            if (!empty($row['listing_image_url'])) {
                echo "<img src='" . htmlspecialchars($row['listing_image_url']) . "' alt='Listing Image' style='max-width:300px;'/>";
            }
            echo "</div><hr>";
        }
    } else {
        echo "<p>No listings available.</p>";
    }

    $conn->close();
    ?>
</body>
</html>