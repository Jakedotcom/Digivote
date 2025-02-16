<?php
session_start();
include 'db_connect.php'; // Ensure you have a working database connection

// Fetch all users with the role of voter
$sql = "SELECT lrn, first_name, last_name, email, status FROM users WHERE role = 'voter'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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

        /* User Management Section */
        .container {
            padding: 20px;
            max-width: 900px;
            margin: 100px auto 20px; /* Adjusted margin to lower the container */
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4caf50;
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

        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #4caf50;
            color: white;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        .btn-delete:hover {
            background-color: #e53935;
        }

        .status-approved {
            color: green;
        }

        .status-rejected {
            color: red;
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

<!-- User Management Section -->
<div class="container">
    <h1>User Management</h1>
    <table>
        <thead>
            <tr>
                <th>LRN</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                $full_name = $row['first_name'] . ' ' . $row['last_name'];
                $status_class = '';
                if ($row['status'] == 'Approved') {
                    $status_class = 'status-approved';
                } elseif ($row['status'] == 'Rejected') {
                    $status_class = 'status-rejected';
                }
                echo "<tr>";
                echo "<td>{$row['lrn']}</td>";
                echo "<td>{$full_name}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td class='status {$status_class}'>{$row['status']}</td>";
                echo "<td>
                        <button class='btn btn-edit' onclick='updateStatus(\"{$row['email']}\", \"Approved\")'>Approve</button>
                        <button class='btn btn-delete' onclick='updateStatus(\"{$row['email']}\", \"Rejected\")'>Reject</button>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function updateStatus(email, status) {
        fetch('update_user_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'email=' + encodeURIComponent(email) + '&status=' + encodeURIComponent(status)
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        });
    }
</script>
</body>
</html>
