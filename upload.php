<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select and Upload Image</title>
</head>
<body>
    <form id="uploadForm" method="post" enctype="multipart/form-data">
        <label for="fileInput">Select an image to upload:</label>
        <input type="file" id="fileInput" name="image" accept="image/*" required>
        <input type="submit" value="Upload">
    </form>

    <?php
    require 'vendor/autoload.php';

    use Aws\S3\S3Client;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
        $s3Client = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'endpoint' => 'https://localhost:9000', // Your MinIO server URL
            'use_path_style_endpoint' => true, // This is important for MinIO compatibility
            'credentials' => [
                'key'    => 'D32oRXTErlIYMEal1Odc', // Replace with your MinIO access key
                'secret' => 'zu63UssdRkHDEpksNPBwTzslHARDil66PmteliRS', // Replace with your MinIO secret key
            ],
            'scheme' => 'https', // Since the URL is using https
            'http' => [
                'verify' => false, // Skip SSL certificate verification (useful if self-signed)
            ],
        ]);

        $bucketName = 'bucket-test';
        $file = $_FILES['image']['tmp_name'];
        $objectKey = $_FILES['image']['name'];

        try {
            $result = $s3Client->putObject([
                'Bucket' => $bucketName,
                'Key'    => $objectKey,
                'SourceFile' => $file,
            ]);
            echo "File upload successful to $bucketName as $objectKey.\n";
        } catch (AwsException $e) {
            echo "Error: " . $e->getAwsErrorMessage();
        }
    }
    ?>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];

            if (!file) {
                event.preventDefault();
                alert('Please select a file first.');
            }
        });
    </script>
</body>
</html>