<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php'; // Ensure you have a working database connection

// Handle form submission for adding or editing a candidate
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $candidate_name = $_POST['candidate_name'];
    $party_name = $_POST['party_name'];
    $position = $_POST['position'];
    $candidate_quote = $_POST['candidate_quote'];
    $photo = '';

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploads_dir = 'uploads';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }
        $photo = $uploads_dir . '/' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    }

    if (isset($_POST['edit'])) {
        // Update existing candidate
        $sql = "UPDATE candidates SET party_name='$party_name', position='$position', candidate_quote='$candidate_quote', photo='$photo' WHERE candidate_name='$candidate_name'";
    } else {
        // Add new candidate
        $sql = "INSERT INTO candidates (candidate_name, party_name, position, candidate_quote, photo) VALUES ('$candidate_name', '$party_name', '$position', '$candidate_quote', '$photo')";
    }

    $conn->query($sql);
}

// Handle delete request
if (isset($_GET['delete'])) {
    $candidate_name = $_GET['delete'];
    $sql = "DELETE FROM candidates WHERE candidate_name='$candidate_name'";
    $conn->query($sql);
}

// Fetch party lists from the database for the dropdown
$party_sql = "SELECT party_name FROM party_lists";
$party_result = $conn->query($party_sql);

// Predefined list of positions
$positions = ['president', 'vice_president', 'secretary', 'treasurer', 'public_information_officer', 'representative'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Management</title>
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

        /* Candidate Management Section */
        .container {
            padding: 20px;
            max-width: 900px;
            margin: 120px auto 20px; /* Adjusted margin to lower the container */
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

        .status-approved {
            color: green;
        }

        .status-rejected {
            color: red;
        }

        /* Add/Edit Candidate Form */
        .form-container {
            margin-top: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4caf50;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
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

<!-- Candidate Management Section -->
<div class="container">
    <h1>Candidate Management</h1>
    <!-- Add/Edit Candidate -->
    <section id="add-candidate" class="card">
        <h3>Add/Edit Candidate</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="candidate_name">Candidate Name:</label>
                <input type="text" id="candidate_name" name="candidate_name" required>
            </div>

            <div class="form-group">
                <label for="party_name">Party Name:</label>
                <select id="party_name" name="party_name" required>
                    <option value="" disabled selected>Select Party</option>
                    <?php
                    while ($party_row = $party_result->fetch_assoc()) {
                        echo "<option value='{$party_row['party_name']}'>{$party_row['party_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="position">Position:</label>
                <select id="position" name="position" required>
                    <option value="" disabled selected>Select Position</option>
                    <?php
                    foreach ($positions as $position) {
                        echo "<option value='$position'>" . ucfirst(str_replace('_', ' ', $position)) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="candidate_quote">Enter Your Quote/Motto:</label>
                <textarea id="candidate_quote" name="candidate_quote" rows="3" placeholder="Your inspiring quote..." required></textarea>
            </div>

            <div class="form-group">
                <label for="photo">Upload Photo:</label>
                <input type="file" id="photo" name="photo">
            </div>

            <button type="submit" name="add">Add Candidate</button>
            <button type="submit" name="edit">Edit Candidate</button>
        </form>
    </section>

    <!-- Candidate List -->
    <section id="candidate-list" class="card">
        <h3>Candidate List</h3>
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Candidate Name</th>
                    <th>Party Name</th>
                    <th>Position</th>
                    <th>Quote</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch candidate lists from the database
                $sql = "SELECT candidate_name, party_name, position, candidate_quote, photo FROM candidates";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='{$row['photo']}' alt='Photo' style='width: 50px; height: 50px;'></td>";
                    echo "<td>{$row['candidate_name']}</td>";
                    echo "<td>{$row['party_name']}</td>";
                    echo "<td>{$row['position']}</td>";
                    echo "<td class='candidate-card'><p>{$row['candidate_quote']}</p></td>";
                    echo "<td>
                            <button class='edit-btn' onclick='editCandidate(\"{$row['candidate_name']}\", \"{$row['party_name']}\", \"{$row['position']}\", \"{$row['candidate_quote']}\", \"{$row['photo']}\")'>Edit</button>
                            <button class='delete-btn' onclick='deleteCandidate(\"{$row['candidate_name']}\")'>Delete</button>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</div>

<script>
    function editCandidate(candidate_name, party_name, position, candidate_quote, photo) {
        document.getElementById('candidate_name').value = candidate_name;
        document.getElementById('party_name').value = party_name;
        document.getElementById('position').value = position;
        document.getElementById('candidate_quote').value = candidate_quote;
        // Note: Photo upload field cannot be pre-filled for security reasons
    }

    function deleteCandidate(candidate_name) {
        if (confirm('Are you sure you want to delete this candidate?')) {
            window.location.href = 'candidate_management.php?delete=' + encodeURIComponent(candidate_name);
        }
    }
</script>
</body>
</html>