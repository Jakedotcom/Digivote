<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'voter') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php'; // Ensure you have a working database connection

// Check if the user has already voted
if (isset($_SESSION['has_voted']) && $_SESSION['has_voted']) {
    die("You have already voted.");
}

// Handle form submission for voting
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $positions = ['president', 'vice_president', 'secretary', 'treasurer', 'public_information_officer', 'representative'];
    foreach ($positions as $position) {
        if (!empty($_POST[$position])) {
            $candidate_name = $_POST[$position];
            // Fetch the party name for the candidate
            $party_sql = "SELECT party_name FROM candidates WHERE candidate_name='$candidate_name'";
            $party_result = $conn->query($party_sql);
            $party_row = $party_result->fetch_assoc();
            $party_name = $party_row['party_name'];

            $vote_sql = "INSERT INTO votes (position, candidate_name, party_name) VALUES ('$position', '$candidate_name', '$party_name')";
            $conn->query($vote_sql);
        }
    }

    // Update the user's has_voted status
    $user_email = $_SESSION['user_email'];
    $update_sql = "UPDATE users SET has_voted = TRUE WHERE email = '$user_email'";
    $conn->query($update_sql);

    $_SESSION['has_voted'] = true;
    header("Location: receipt.php");
    exit();
}

// Fetch candidates from the database
$candidates_sql = "SELECT candidate_name, party_name, position, candidate_quote, photo FROM candidates";
$candidates_result = $conn->query($candidates_sql);

$candidates = [];
while ($row = $candidates_result->fetch_assoc()) {
    $candidates[$row['position']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote for Your Student Council</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background: url('https://assets.onecompiler.app/42v96pnwe/42yprvv49/name@gfis.edu.ph1.png') no-repeat center center fixed;
            background-size: cover;
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
            margin-right: 20px; /* Move the logout button 3px to the left */
        }

        .logout-btn:hover {
            background-color: #86b852;
            color: white;
        }

        /* Content Container */
        .container {
            width: 100%;
            max-width: 700px;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-top: 6px solid #4CAF50;
            text-align: center;
            margin-top: 100px;
            margin-left: auto;
            margin-right: auto;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .position-title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 5px;
            text-align: left;
        }

        .candidate {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: 0.3s ease;
            background-color: #f9f9f9;
            cursor: pointer;
        }

        .candidate:hover {
            background-color: #e8f5e9;
            border-color: #4CAF50;
        }

        .candidate img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 15px;
            border: 3px solid #4CAF50;
        }

        .candidate-info {
            flex: 1;
            text-align: left;
        }

        .candidate-info h3 {
            margin: 0;
            color: #333;
            transition: color 0.3s ease;
        }

        .candidate-info p {
            margin: 5px 0 0;
            color: #777;
        }

        /* Hide radio buttons */
        .candidate input[type="radio"] {
            display: none;
        }

        /* Highlight selected candidate */
        .candidate.selected {
            background-color: #c8e6c9;
            border: 2px solid #4CAF50;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #45a049;
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

<!-- Voting Container -->
<div class="container">
    <h2>Vote for Your Student Council</h2>

    <form action="voting_process.php" method="post">
        <?php foreach ($candidates as $position => $candidates_list): ?>
            <div class="position-title"><?php echo ucfirst(str_replace('_', ' ', $position)); ?></div>
            <?php foreach ($candidates_list as $candidate): ?>
                <label class="candidate">
                    <input type="radio" name="<?php echo $position; ?>" value="<?php echo $candidate['candidate_name']; ?>">
                    <img src="<?php echo $candidate['photo']; ?>" alt="<?php echo $candidate['candidate_name']; ?>">
                    <div class="candidate-info">
                        <h3><?php echo $candidate['candidate_name']; ?></h3>
                        <p>Team: <?php echo $candidate['party_name']; ?></p>
                        <p><?php echo $candidate['candidate_quote']; ?></p>
                    </div>
                </label>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <button type="submit" class="btn">Submit Vote</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const candidates = document.querySelectorAll(".candidate");

        candidates.forEach(candidate => {
            candidate.addEventListener("click", function () {
                const input = this.querySelector("input[type='radio']");
                const name = input.getAttribute("name");

                // Remove "selected" class from all candidates in the same category
                document.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
                    radio.closest(".candidate").classList.remove("selected");
                });

                // Add "selected" class to the clicked candidate
                this.classList.add("selected");

                // Select the radio button
                input.checked = true;
            });
        });
    });
</script>

</body>
</html>