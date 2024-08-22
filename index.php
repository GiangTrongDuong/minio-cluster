<?php
// These are use to test if PHP is working on linux!!!!!
$title = "Welcome to Group 2 Landing Pages demo for real estate";
include 'component.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #007BFF;
        }
        p {
            font-size: 1.2em;
        }
        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background: #007BFF;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .cta-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <p>This site is working using php???.</p>
        // Called the component from php below: 
        <?php
        displayMessage($message);
        ?>

        <a href="../database.php">Go to Listing Page</a>
    </div>
</body>
</html>