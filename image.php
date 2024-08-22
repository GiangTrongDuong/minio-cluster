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

// Bucket and object details
$bucketName = 'bucket-test';
$image1Key = 'cowboy_downloaded.jpg';
$image2Key = 'cowboy2.png';

$image1Url = getPresignedUrlOrError($s3Client, $bucketName, $image1Key);
$image2Url = getPresignedUrlOrError($s3Client, $bucketName, $image2Key);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Display</title>
</head>
<body>
    <h1>Image Display</h1>
    
    <h2>Cowboy Downloaded.jpg</h2>
    <?php if ($image1Url): ?>
        <img src="<?php echo htmlspecialchars($image1Url); ?>" alt="Cowboy Downloaded" width="640" height="360">
        <br>
        <!-- Button to download the image -->
        <a href="<?php echo htmlspecialchars($image1Url); ?>" download="cowboy_downloaded.jpg">
            <button>Download Cowboy Downloaded.jpg</button>
        </a>
    <?php else: ?>
        <p>Cannot fetch Cowboy Downloaded.jpg</p>
    <?php endif; ?>

    <h2>Cowboy2.png</h2>
    <?php if ($image2Url): ?>
        <img src="<?php echo htmlspecialchars($image2Url); ?>" alt="Cowboy2" width="640" height="360">
    <?php else: ?>
        <p>Cannot fetch Cowboy2.png</p>
    <?php endif; ?>

</body>
</html>
