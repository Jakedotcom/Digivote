<?php
session_start();
include 'db_connect.php'; // Ensure you have a working database connection

// Fetch current registration period
$period_sql = "SELECT start_date, end_date FROM registration_period";
$period_result = $conn->query($period_sql);
$period_row = $period_result->fetch_assoc();

$current_date = date('Y-m-d');

$registration_closed = false;
if ($current_date < $period_row['start_date'] || $current_date > $period_row['end_date']) {
    $registration_closed = true;
}

// Handle form submission for user registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$registration_closed) {
    $lrn = $_POST['lrn'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'voter'; // Set the role to 'voter' by default

    // Insert the new user into the database
    $sql = "INSERT INTO users (lrn, first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $lrn, $first_name, $last_name, $email, $password, $role);

    if ($stmt->execute()) {
        // Redirect to a different page after successful registration
        header("Location: index.php");
        exit();
    } else {
        // Handle error if the registration fails
        $error_message = "Registration failed. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVote - Register</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            position: relative; 
        }

        /* Back button styles */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
        }

        .back-button button {
            background-color: #112809;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .back-button button:hover {
            background-color: #0d1f07;
        }

        .container {
            display: flex;
            width: 100%;
        }

        .left-section {
            background-color: #112809;
            color: white;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .left-section img {
            width: 550px; 
            height: 350px; 
            margin-bottom: 20px;
        }

        .left-section h1 {
            font-size: 24px;
            font-weight: bold;
            margin-top: 0;
        }

        .right-section {
            flex: 1;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-form {
            width: 80%;
            max-width: 400px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .register-form h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .register-form .input-group {
            margin-bottom: 15px;
            position: relative;
        }

        .register-form input,
        .register-form select {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 0 auto;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #e8e8e8;
        }

        .register-form .input-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #112809;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0d1f07;
        }

        .error-message {
            color: red;
            font-size: 12px;
            display: none;
        }

        .timer {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Registration Closed Message */
        .registration-closed {
            text-align: center;
            font-size: 20px;
            color: red;
            margin-top: 20px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-RXf+QSDCUQs7f7abkMxTpch4uOnfrK20g9p3fD61b8Pb56G4A1lpc74PRG3K29KtzOa3uKg7oF6lKHmZLf0qwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <!-- Back Button -->
    <a href="index.php" class="back-button">
        <button>Back</button>
    </a>

    <div class="container">
        <div class="left-section">
            <img src="https://assets.onecompiler.app/42v96pnwe/42yugrqs4/Screenshot%20(335).png" alt="School Logo">
        </div>
        <div class="right-section">
            <div class="register-form">
                <?php if ($registration_closed): ?>
                    <div class="registration-closed">
                        Registration is currently closed. Please try again during the registration period.
                    </div>
                <?php else: ?>
                    <h2>Register</h2>
                    <div class="timer" id="timer"></div>
                    <?php if (isset($error_message)): ?>
                        <p style="color: red;"><?= $error_message ?></p>
                    <?php endif; ?>
                    <form id="registerForm" method="POST">
                        <div class="input-group">
                            <input type="text" id="lrn" name="lrn" placeholder="LRN" required maxlength="12" oninput="validateLRN()">
                        </div>
                        <div class="input-group">
                            <input type="text" name="first_name" placeholder="First Name" required>
                        </div>
                        <div class="input-group">
                            <input type="text" name="last_name" placeholder="Last Name" required>
                        </div>
                        <div class="input-group">
                            <input type="email" id="email" name="email" placeholder="name@gfis.edu.ph" required oninput="validateEmail()">
                            <span class="error-message" id="emailError">Please enter a valid GFIS email address.</span>
                        </div>
                        <div class="input-group">
                            <input type="password" id="password" name="password" placeholder="Password" required>
                            <span class="input-icon" onclick="togglePassword('password', this)">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                        </div>
                        <div class="input-group">
                            <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required>
                            <span class="input-icon" onclick="togglePassword('confirm-password', this)">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                        </div>
                        <button type="submit">Register</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId, iconElement) {
            const field = document.getElementById(fieldId);
            const icon = iconElement.querySelector('i');
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove('fa-lock');
                icon.classList.add('fa-unlock');
            } else {
                field.type = "password";
                icon.classList.remove('fa-unlock');
                icon.classList.add('fa-lock');
            }
        }

        // Validate LRN to accept only digits and limit to 12 characters
        function validateLRN() {
            const lrnInput = document.getElementById('lrn');
            let lrnValue = lrnInput.value;
            // Remove any non-digit characters
            lrnValue = lrnValue.replace(/\D/g, '');
            // Limit to 12 digits
            if (lrnValue.length > 12) {
                lrnValue = lrnValue.slice(0, 12);
            }
            lrnInput.value = lrnValue;
        }

        // Validate email to ensure it ends with @gfis.edu.ph
        function validateEmail() {
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const emailValue = emailInput.value;

            // Check if the email ends with @gfis.edu.ph
            if (!emailValue.endsWith('@gfis.edu.ph')) {
                emailError.style.display = 'block';
                emailInput.setCustomValidity("Please enter a valid GFIS email address.");
            } else {
                emailError.style.display = 'none';
                emailInput.setCustomValidity(""); 
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function(event) {
            const emailInput = document.getElementById('email');
            if (!emailInput.checkValidity()) {
                event.preventDefault();
            }
        });

        // Countdown timer
        const endDate = new Date("<?php echo $period_row['end_date']; ?>").getTime();
        const timerElement = document.getElementById('timer');

        function updateTimer() {
            const now = new Date().getTime();
            const distance = endDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            timerElement.innerHTML = `Registration closes in: ${days}d ${hours}h ${minutes}m ${seconds}s`;

            if (distance < 0) {
                clearInterval(timerInterval);
                timerElement.innerHTML = "Registration is closed.";
            }
        }

        const timerInterval = setInterval(updateTimer, 1000);
    </script>
</body>
</html>
