<?php
session_start();
include 'db_connect.php'; // Ensure you have a working database connection

// Fetch voting results from the database
$positions = ['president', 'vice_president', 'secretary', 'treasurer', 'public_information_officer', 'representative'];
$results = [];

foreach ($positions as $position) {
    $sql = "SELECT candidate_name, party_name, COUNT(*) as vote_count FROM votes WHERE position='$position' GROUP BY candidate_name, party_name ORDER BY vote_count DESC";
    $result = $conn->query($sql);
    if ($result) {
        $results[$position] = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $results[$position] = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1a410e;
            color: #333;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        header, footer {
            background-color: #4caf50;
            color: white;
            text-align: center;
            padding: 15px;
        }

        .container {
            padding: 20px;
            max-width: 900px;
            margin: 150px auto 20px; /* Adjusted margin to lower the container */
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        h3 {
            margin-bottom: 15px;
            color: #4caf50;
            text-align: center; /* Centered the heading */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center; /* Centered the table content */
        }

        table th {
            background-color: #4caf50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
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

        .logout-btn {
            text-decoration: none;
            font-size: 1rem;
            color: #112809;
            padding: 10px 20px;
            background-color: #e4e2dd;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
            margin-right: 25px; /* Move the logout button 3px to the left */
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

<div class="container">
    <section id="results">
        <!-- Table of Results -->
        <h3>Results by Position and Party</h3>
        <table>
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Candidate</th>
                    <th>Party</th>
                    <th>Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($positions as $position): ?>
                    <?php if (!empty($results[$position])): ?>
                        <?php foreach ($results[$position] as $result): ?>
                            <tr>
                                <td><?php echo ucfirst(str_replace('_', ' ', $position)); ?></td>
                                <td><?php echo $result['candidate_name']; ?></td>
                                <td><?php echo $result['party_name']; ?></td>
                                <td><?php echo $result['vote_count']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No votes recorded for this position.</td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>

</body>
</html>