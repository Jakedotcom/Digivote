<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'voter') {
    header("Location: login.php");
    exit();
}

// Check if the user has voted
if (!isset($_SESSION['has_voted']) || !$_SESSION['has_voted']) {
    header("Location: voting_process.php");
    exit();
}

// Destroy the session to log out the user
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #1a410e;
        }

        .container {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 20px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thank You for Voting!</h2>
        <p>Your vote has been successfully submitted.</p>
        <a href="login.php" class="btn">Logout</a>
    </div>
</body>
</html>