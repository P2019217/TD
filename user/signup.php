<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Check password criteria
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d{4,})(?=.*[\W_]).{8,}$/', $password)) {
        $error = "Password must contain at least one capital letter, at least 4 numbers, and one symbol.";
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $username, $password_hashed, $email);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error creating account. Please try again.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-top: 60px; /* Space for fixed header */
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        body.light-theme {
            background-color: #f2f2f2;
            color: #333;
        }

        body.dark-theme {
            background-color: #333;
            color: #f2f2f2;
        }

        header {
            width: 100%;
            position: fixed; /* Fixed at the top */
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, color 0.3s;
            z-index: 1000; /* Ensure it stays on top */
        }

        body.dark-theme header {
            background-color: #444;
        }

        .logo {
            width: 100px;
        }

        #theme-toggle {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        #theme-toggle:hover {
            background-color: #0056b3;
        }

        .homepage-button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .homepage-button:hover {
            background-color: #0056b3;
        }

        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 150px; /* Adjust this to ensure proper spacing */
        }

        .signup-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark-theme .signup-container {
            background-color: #444;
        }

        .signup-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            background-color: #45a049;
            height: 35px;
            width: 100%;
            font-size: 16px;
            padding: 0 10px;
            border-radius: 5px;
            border: none;
            outline: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #379c39;
        }

        .error-message {
            display: block;
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }

        .login-link {
            display: block;
            margin-top: 20px;
            font-size: 14px;
        }

        .login-link a {
            color: blue;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <a href="homepage.php" class="homepage-button">Homepage</a>
        <button id="theme-toggle" onclick="toggleTheme()">🌙</button>
    </header>
    <div class="content">
        <div class="signup-container">
            <h2>Sign Up</h2>
            <?php if (isset($error)) { echo '<p class="error-message">' . htmlspecialchars($error) . '</p>'; } ?>
            <form method="POST" action="signup.php" class="signup-form" onsubmit="return validatePassword()">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="btn">Sign Up</button>
            </form>
            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const body = document.body;
            body.classList.toggle('dark-theme');
            const themeToggleButton = document.getElementById('theme-toggle');
            const theme = body.classList.contains('dark-theme') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
            themeToggleButton.textContent = theme === 'dark' ? '☀️' : '🌙';
        }

        function checkTheme() {
            const theme = localStorage.getItem('theme') || 'light';
            const themeToggleButton = document.getElementById('theme-toggle');
            if (theme === 'dark') {
                document.body.classList.add('dark-theme');
                themeToggleButton.textContent = '☀️';
            } else {
                themeToggleButton.textContent = '🌙';
            }
        }

        function validatePassword() {
            const password = document.getElementById('password').value;
            const errorMessage = document.querySelector('.error-message');
            const pattern = /^(?=.*[A-Z])(?=.*\d{4,})(?=.*[\W_]).{8,}$/;

            if (!pattern.test(password)) {
                errorMessage.textContent = "Password must contain at least one capital letter, at least 4 numbers, and one symbol.";
                return false;
            }

            return true;
        }

        document.addEventListener('DOMContentLoaded', checkTheme);
    </script>
</body>
</html>
