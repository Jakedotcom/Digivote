<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'voter') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php'; // Ensure you have a working database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVote - Party Preview</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a410e;
            background-size: cover;
            min-height: 100vh;
            color: #333;
        }

        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #e4e2dd; 
            padding: 15px 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo a {
            text-decoration: none;
            color: inherit; 
        }

        .green {
            color: green;
        }

        .logo span {
            font-weight: bold;
        }

        .nav-container {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-links a {
            text-decoration: none;
            font-size: 1rem;
            color: #112809;
            padding: 5px 10px;
            transition: background 0.3s;
        }

        .nav-links a:hover {
            background: #86b852; 
            color: #ffffff;
            border-radius: 5px;
        }

        .login-btn {
            position: relative;
        }

        .login-btn a {
            text-decoration: none;
            font-size: 1rem;
            color: #112809;
            padding: 10px 20px;
            background-color: #e4e2dd;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
        }

        .login-btn a:hover {
            background-color: #d1cdbe;
            color: #000000;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #e4e2dd;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            margin-top: 5px;
        }

        .dropdown-content a {
            color: #112809;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            border-radius: 5px;
        }

        .dropdown-content a:hover {
            background-color: #86b852;
            color: #ffffff;
        }

        .login-btn:hover .dropdown-content {
            display: block;
        }

        .register-btn a {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            color: black;
            border: 2px solid transparent;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .register-btn a:hover {
            color: white;
            background-color: #86b852;
            border-color: #86b852;
        }

        .logout-btn {
            text-decoration: none;
            font-size: 1rem;
            color: #112809;
            padding: 10px 20px;
            background-color: #e4e2dd;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
        }

        .logout-btn:hover {
            background-color: #86b852;
            color: white;
        }

        /* Party Section */
        #parties {
            padding: 50px 20px;
            text-align: center;
        }

        .party-list {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .party-card {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px; /* Increased width */
            text-align: center;
            word-wrap: break-word;
            overflow: hidden;
        }

        .party-card img {
            width: 150px; /* Increased width */
            height: 150px; /* Increased height */
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .party-card h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .party-card p {
            font-size: 1rem;
            color: #555;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 4.5em; /* Limit the height */
            line-height: 1.5em; /* Adjust line height */
        }

        .vote-button-container {
            margin-top: 20px;
            text-align: center;
        }

        .vote-button {
            padding: 12px 25px;
            font-size: 1rem;
            color: white;
            background-color: #86b852;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .vote-button:hover {
            background-color: #74a43e;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="logo">
            <a href="voter_dashboard.php"><span class="green">Digi</span><span>Vote</span></a>
        </div>
        <div class="nav-container">
            <ul class="nav-links">
                <li><a href="voter_dashboard.php">Home</a></li>
                <li><a href="voter_aboutus.php">About Us</a></li>
                <li><a href="party_preview.php">Partylists</a></li>
            </ul>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </nav>

    <!-- Party Section -->
    <section id="parties">
        <div class="party-list">
            <?php
            // Fetch party lists from the database
            $sql = "SELECT party_name, logo, quote FROM party_lists";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<div class='party-card'>";
                echo "<img src='{$row['logo']}' alt='Party Logo'>";
                echo "<h3>{$row['party_name']}</h3>";
                echo "<p>{$row['quote']}</p>";
                echo "</div>";
            }
            ?>
        </div>
        <div class="vote-button-container">
            <a href="voting_process.php" class="vote-button">Vote Now</a>
        </div>
    </section>
</body>
</html>