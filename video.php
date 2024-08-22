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
        'key'    => 'LLmoBCtzQGjzCcjpIHSz', // Replace with your MinIO access key
        'secret' => 'UmzsaUaxFJ0SthhcoT5tXwgYltPxzAqbTevT5FV8', // Replace with your MinIO secret key
    ],
    'scheme' => 'https', // Since the URL is using https
    'http' => [
        'verify' => false, // Skip SSL certificate verification (useful if self-signed)
    ],
]);

// Bucket and object details
$bucketName = 'bucket-test';
$objectKey = 'DJI_0001.MP4';

try {
    // Generate a pre-signed URL for the video file
    $cmd = $s3Client->getCommand('GetObject', [
        'Bucket' => $bucketName,
        'Key'    => $objectKey,
    ]);

    $request = $s3Client->createPresignedRequest($cmd, '+20 minutes'); // URL valid for 20 minutes
    $presignedUrl = (string)$request->getUri();
    
} catch (AwsException $e) {
    echo "Error generating pre-signed URL: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
