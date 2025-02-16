<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php'; // Ensure you have a working database connection

// Handle form submission for setting registration period
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Check if a registration period already exists
    $check_sql = "SELECT COUNT(*) AS count FROM registration_period";
    $check_result = $conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();

    if ($check_row['count'] > 0) {
        // Update existing registration period
        $sql = "UPDATE registration_period SET start_date='$start_date', end_date='$end_date'";
    } else {
        // Insert new registration period
        $sql = "INSERT INTO registration_period (start_date, end_date) VALUES ('$start_date', '$end_date')";
    }

    $conn->query($sql);
}

// Fetch current registration period
$period_sql = "SELECT start_date, end_date FROM registration_period";
$period_result = $conn->query($period_sql);
$period_row = $period_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVote - Admin Dashboard</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1a410e;
            color: #333;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 20px;
        }

        .card {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 900px;
            text-align: center;
        }

        .card h3 {
            margin: 0 0 10px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #e4e2dd;
            padding: 15px 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            overflow-x: hidden; /* Prevent horizontal scrolling */
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

<div class="content">
    <!-- Set Registration Period -->
    <section id="set-registration-period" class="card">
        <h3>Set Voter Registration Period</h3>
        <form method="POST">
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo isset($period_row['start_date']) ? $period_row['start_date'] : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo isset($period_row['end_date']) ? $period_row['end_date'] : ''; ?>" required>
            </div>

            <button type="submit">Set Period</button>
        </form>
    </section>
</div>
</body>
</html>