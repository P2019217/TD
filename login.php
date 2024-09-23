<?php
include 'config.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: user.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password);
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        header("Location: user.php");
        exit();
    } else {
        $error = "Invalid username or password.";
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
    <title>Login</title>
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
            margin-top: 100px; /* Ensure space below the header and theme toggle */
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

        .signup-link {
            display: block;
            margin-top: 20px;
            font-size: 14px;
        }

        .signup-link a {
            color: blue;
            text-decoration: none;
        }

        .signup-link a:hover {
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
            <h2>Login</h2>
            <?php if (isset($error)) { echo '<p class="error-message">' . htmlspecialchars($error) . '</p>'; } ?>
            <form method="POST" action="login.php" class="signup-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <p class="signup-link">Don't have an account? <a href="signup.php">Sign up here</a></p>
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

        document.addEventListener('DOMContentLoaded', checkTheme);
    </script>
</body>
</html>
