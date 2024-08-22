<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// AWS S3/MinIO client configuration
$s3Client = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1',
    'endpoint' => 'https://localhost:9000', // Your MinIO server URL
    'use_path_style_endpoint' => true,
    'credentials' => [
        'key'    => 'D32oRXTErlIYMEal1Odc',
        'secret' => 'zu63UssdRkHDEpksNPBwTzslHARDil66PmteliRS',
    ],
    'scheme' => 'https',
    'http' => [
        'verify' => false,
    ],
]);

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        a {
            color: #0066cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .listing {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .listing img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .listing h2 {
            margin-top: 0;
        }
        .listing p {
            margin: 10px 0;
        }
        .listing p strong {
            color: #333;
        }
        hr {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../index.php">Go to Landing Pages</a>
        <h1>Available Listings</h1>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='listing'>";
                echo "<h2>" . htmlspecialchars($row['listing_name']) . "</h2>";
                echo "<p>" . htmlspecialchars($row['listing_description']) . "</p>";
                echo "<p><strong>Address:</strong> " . htmlspecialchars($row['listing_address']) . "</p>";
                echo "<p><strong>Price:</strong> $" . number_format($row['listing_price'], 2) . "</p>";
                echo "<p><strong>Available:</strong> " . ($row['listing_available'] ? 'Yes' : 'No') . "</p>";
                echo "<p><strong>Listed by:</strong> " . htmlspecialchars($row['username']) . "</p>";

                if (!empty($row['listing_image_url'])) {
                    $imageKey = $row['listing_image_url'];
                    $imageUrl = getPresignedUrlOrError($s3Client, 'bucket-test', $imageKey);
                    echo "<p>Image URL: " . htmlspecialchars($imageUrl) . "</p>";
                    if ($imageUrl) {
                        echo "<img src='" . htmlspecialchars($imageUrl) . "' alt='Listing Image'>";
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
    </div>
</body>
</html>
