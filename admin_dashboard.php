<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVote - Home</title>
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
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
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

        /* Home Section */
        #home {
            background: url('https://assets.onecompiler.app/42v96pnwe/42yugrqs4/Screenshot%20(346).png') no-repeat center center/cover;
            color: white;
            text-align: center;
            padding: 150px 20px;
            height: 100vh; 
            display: flex;
            flex-direction: column;
            justify-content: center; 
        }

        #home h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        #home h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .text-box {
            background: #e4e2dd;
            padding: 20px;
            border-radius: 10px;
            margin-top: 100px;
            color: #333;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        #home p {
            font-size: 1.2rem;
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
    </style>
</head>
<body>
<!-- Navigation Bar -->
<nav>
    <div class="logo">
        <a href="admin_dashboard.php"><span class="green">Digi</span><span>Vote</span></a>
    </div>
    <div class="nav-container">
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">Home</a></li>
            <li><a href="admin_aboutus.php">About Us</a></li>
            <li><a href="user_management.php">User Management</a></li>
            <li><a href="party_management.php">Party Management</a></li>
            <li><a href="candidate_management.php">Candidate Management</a></li>
            <li><a href="registration_period.php">Registration Period</a></li>
            <li><a href="results.php">Results</a></li>
        </ul>
    </div>
    <a href="logout.php" class="logout-btn">Logout</a>
</nav>

<!-- Home Section -->
<section id="home">
    <div class="content">
        <div class="text-box">
            <p>"Welcome to our platform, where innovation meets tradition. We are dedicated to providing a seamless digital voting experience for students, empowering you to make your voice heard in the election of our Supreme Student Government. With user-friendly interfaces, transparent processes, and a secure system, we aim to ensure that every vote counts. Explore our website to learn more about the candidates, their platforms, and how you can participate in shaping the future of our school. Your vote is your power—use it wisely."</p>
        </div>
    </div>
</section>
</body>
</html>
