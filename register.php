<?php
session_start(); // Start session to store role selection
require 'database/database.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; 
    
    $role_id = isset($_SESSION['role_id']) ? $_SESSION['role_id'] : null;
    
    if (!$role_id) {
        die("Role selection is required.");
    }

    // Check if the email already exists
    $checkQuery = "SELECT uid FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "User already exists.";  // Error message if email is taken
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database with role_id
        $insertQuery = "INSERT INTO users (username, fullname, email, password, role_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        if (!$stmt) {
            die("Query preparation failed: " . $conn->error);
        }
        $stmt->bind_param("ssssi", $username, $fullname, $email, $hashed_password, $role_id);

        if ($stmt->execute()) {
            unset($_SESSION['role_id']);
            
            // Redirect to login page
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;  // Show error message if insert fails
        }
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
    <title>Get Started</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
    <script>
        function setRole(roleId) {
            // Send role selection to session.php via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "session.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("role_id=" + roleId);

            // Set role_id in a hidden input field for form submission
            document.getElementById("role_id").value = roleId;
        }

        function validateForm() {
            var roleId = document.getElementById("role_id").value;
            if (!roleId) {
                alert("Please select a role (Employee or Manager) before registering.");
                return false;
            }
            return true;
        }
    </script>
</head>

<body class="reg-page">
    <nav class="reg-navbar">
        <div class="reg-logo"><a href="index.php" class="home">TrackIT</a></div>
        <div class="reg-nav-buttons">
            <a href="personal.php" id="employee" onclick="setRole(1)">Personal</a>
            <a href="business.php" id="manager" onclick="setRole(2)">Business</a>
        </div>
    </nav>  
    <div id="reg-container">
        <h2>WELCOME BACK.</h2>
        <p>Simplify your workflow, amplify your productivity.</p>
        <br><br>
        
        <!-- Update the action and validate form submission -->
        <form method="POST" action="register.php" onsubmit="return validateForm();">
            <input type="hidden" id="role_id" name="role_id" value="">

            <label for="username" class="reg-username">Username</label>   
            <input type="text" name="username" class="input-field" placeholder="Enter Username" required>

            <label for="firstname" class="reg-fullname">First Name</label>
            <input type="text" name="fullname" class="input-field" placeholder="Enter Full Name" required>

            <label for="lastname" class="reg-fullname">Last Name</label>
            <input type="text" name="fullname" class="input-field" placeholder="Enter Full Name" required>

            <label for="email" class="reg-email">Email</label>
            <input type="email" name="email" class="input-field" placeholder="Enter Email" required>

            <label for="password" class="reg-pass">Password</label>    
            <input type="password" name="password" class="input-field" placeholder="Your Password" required>

            <button type="submit" class="reg-btn">Sign Up</button>

            <div class="login-link">
                Already have an account? <a href="login.php">Log in here.</a>
            </div>
        </form>
    </div>

<!--FOOTER-->   
    <footer class="footer-bg-reg">
        <div class="footer-container-reg">
            <div class="footer-top-reg">
                <h2>TRACKIT®</h2>
            </div>

            <div class="footer-bottom-reg">
                <div class="footer-column-reg">
                    <h3>COMPANY</h3>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Team</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><button class="contact-btn-reg">CONTACT US</button></li>
                    </ul>
                </div>
                <div class="footer-column-reg">
                    <h3>SUPPORT</h3>
                    <ul>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Help Center</a></li>
                        <li><button class="support-btn-reg">ASK FOR SUPPORT</button></li>
                    </ul>
                </div>
            </div>

            <div class="footer-legal-reg">
                <p>© 2025 Foreal Solutions</p>
                <ul>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>