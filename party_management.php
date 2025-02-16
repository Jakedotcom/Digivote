<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php'; // Ensure you have a working database connection

// Ensure the uploads directory exists
$uploads_dir = 'uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0777, true);
}

// Handle form submission for adding or editing a party
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $party_name = $_POST['party_name'];
    $quote = $_POST['quote'];
    $logo = '';

    // Handle file upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $logo = $uploads_dir . '/' . basename($_FILES['logo']['name']);
        move_uploaded_file($_FILES['logo']['tmp_name'], $logo);
    }

    if (isset($_POST['edit'])) {
        // Update existing party
        $sql = "UPDATE party_lists SET quote='$quote', logo='$logo' WHERE party_name='$party_name'";
    } else {
        // Add new party
        $sql = "INSERT INTO party_lists (party_name, logo, quote) VALUES ('$party_name', '$logo', '$quote')";
    }

    $conn->query($sql);
}

// Handle delete request
if (isset($_GET['delete'])) {
    $party_name = $_GET['delete'];

    // Check if there are any candidates associated with the party
    $check_sql = "SELECT COUNT(*) AS candidate_count FROM candidates WHERE party_name='$party_name'";
    $check_result = $conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();

    if ($check_row['candidate_count'] == 0) {
        $sql = "DELETE FROM party_lists WHERE party_name='$party_name'";
        $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVote - Party Management</title>
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
            overflow-x: hidden; /* Prevent horizontal scrolling */
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
            margin: 150px auto 20px; /* Adjusted margin to lower the container */
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

        /* General Styles */
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

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .edit-btn {
            background: blue;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete-btn {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
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
            white-space: nowrap;
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
    <!-- Add/Edit Party -->
    <section id="add-party" class="card">
        <h3>Add/Edit Party</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="party_name">Party Name:</label>
                <input type="text" id="party_name" name="party_name" required>
            </div>

            <div class="form-group">
                <label for="logo">Upload Logo:</label>
                <input type="file" id="logo" name="logo">
            </div>

            <div class="form-group">
                <label for="quote">Enter Your Quote/Motto:</label>
                <textarea id="quote" name="quote" rows="3" placeholder="Your inspiring quote..." required></textarea>
            </div>

            <button type="submit" name="add">Add Party</button>
            <button type="submit" name="edit">Edit Party</button>
        </form>
    </section>

    <!-- Party List -->
    <section id="party-list" class="card">
        <h3>Party List</h3>
        <table>
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Party Name</th>
                    <th>Quote</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch party lists from the database
                $sql = "SELECT party_name, logo, quote FROM party_lists";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='{$row['logo']}' alt='Logo' style='width: 50px; height: 50px;'></td>";
                    echo "<td>{$row['party_name']}</td>";
                    echo "<td class='party-card'><p>{$row['quote']}</p></td>";
                    echo "<td>
                            <button class='edit-btn' onclick='editParty(\"{$row['party_name']}\", \"{$row['quote']}\", \"{$row['logo']}\")'>Edit</button>
                            <button class='delete-btn' onclick='deleteParty(\"{$row['party_name']}\")'>Delete</button>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</div>

<script>
    function editParty(party_name, quote, logo) {
        document.getElementById('party_name').value = party_name;
        document.getElementById('quote').value = quote;
        // Note: Logo upload field cannot be pre-filled for security reasons
    }

    function deleteParty(party_name) {
        if (confirm('Are you sure you want to delete this party?')) {
            window.location.href = 'party_management.php?delete=' + encodeURIComponent(party_name);
        }
    }
</script>
</body>
</html>