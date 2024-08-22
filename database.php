<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// AWS S3/MinIO client configuration
$s3Client = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1',
    'endpoint' => 'https://localhost:9000', // Your MinIO server URL
    'use_path_style_endpoint' => true, // Important for MinIO compatibility
    'credentials' => [
        'key'    => 'D32oRXTErlIYMEal1Odc', // Replace with your MinIO access key
        'secret' => 'zu63UssdRkHDEpksNPBwTzslHARDil66PmteliRS', // Replace with your MinIO secret key
    ],
    'scheme' => 'https', // Since the URL is using https
    'http' => [
        'verify' => false, // Skip SSL certificate verification (useful if self-signed)
    ],
]);

// Function to generate a pre-signed URL or return an error message if the file doesn't exist
function getPresignedUrlOrError($s3Client, $bucketName, $objectKey) {
    try {
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucketName,
            'Key'    => $objectKey,
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
        return (string)$request->getUri();
    } catch (AwsException $e) {
        return false;
    }
}

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
                $imageKey = $row['listing_image_url']; // Assuming the listing_image_url contains the image key (filename)
                $imageUrl = getPresignedUrlOrError($s3Client, 'bucket-test', $imageKey);
                if ($imageUrl) {
                    echo "<img src='" . htmlspecialchars($imageUrl) . "' alt='Listing Image' style='max-width:300px;'/>";
                } else {
                    echo "<p>Image not available.</p>";
                }
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