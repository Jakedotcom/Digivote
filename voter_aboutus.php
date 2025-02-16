<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'voter') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVote - About Us</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            min-height: 100vh;
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

        /* Logout Button */
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

        /* About Us Section */
        #about-us {
            background: #1a410e;
            background-size: cover;
            padding: 50px 20px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .about-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            flex: 1;
            background: rgba(228, 226, 221, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            color: #000;
            height: 500px;
        }

        .card h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
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

    <!-- About Us Section -->
    <section id="about-us">
        <div class="about-container">
            <div class="card">
                <h2>Contact Us</h2>
                <p>We would love to hear from you! Whether you have questions, suggestions, or feedback, feel free to reach out to us through any of the following ways:</p>
                <ul>
                    <li><strong>Phone:</strong> +1 (234) 567-8910</li>
                    <li><strong>Email:</strong> contact@digivote.com</li>
                </ul>
            </div>
            <div class="card">
                <h2>Hi, we're DigiVote.</h2>
                <p>"Empowering students with a seamless and transparent voting system. At Digivote, we believe in the power of democracy and technology, coming together to make your voice heard. This platform ensures a fair, efficient, and engaging way to participate in electing your student leaders. Join us in shaping a brighter future for our school community."</p>
            </div>
        </div>
    </section>
</body>
</html>
