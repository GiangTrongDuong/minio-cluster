<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;

// Initialize the S3 Client to connect with MinIO
$s3Client = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1',
    'endpoint' => 'https://localhost:9000', // Your MinIO server URL
    'use_path_style_endpoint' => true, // This is important for MinIO compatibility
    'credentials' => [
        'key'    => 'LLmoBCtzQGjzCcjpIHSz', // Replace with your MinIO access key
        'secret' => 'UmzsaUaxFJ0SthhcoT5tXwgYltPxzAqbTevT5FV8', // Replace with your MinIO secret key
    ],
    'scheme' => 'https', // Since the URL is using https
    'http' => [
        'verify' => false, // Skip SSL certificate verification (useful if self-signed)
    ],
]);
// Example: Create a new bucket
$bucketName = 'bucket-test';
// $s3Client->createBucket(['Bucket' => $bucketName]);

// Example: Upload a file
$s3Client->putObject([
    'Bucket' => $bucketName,
    'Key'    => 'Untitled design.png',
    'SourceFile' => "C:\Users\gtdpr\Downloads\Untitled design.png",
]);

echo "File uploaded successfully.\n";
