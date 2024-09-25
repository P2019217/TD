<?php
include '../config.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");  // Corrected path to login.php
    exit();
}

$user_id = $_SESSION['user_id'];

// Test the database connection first
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Debug connection error
}

// Prepare SQL query to fetch tasks for the logged-in user
$sql = "SELECT id, title, status, list_id FROM tasks WHERE assigned_user_id = ?";

// Check if the query can be prepared
if (!($stmt = $conn->prepare($sql))) {
    die('SQL Query Error (tasks): ' . $sql . '<br> SQL Error: ' . $conn->error);
}

// Bind the user ID to the query
if (!$stmt->bind_param("i", $user_id)) {
    die('Error binding parameters (tasks): ' . $stmt->error);
}

// Execute the query
if (!$stmt->execute()) {
    die('Error executing statement (tasks): ' . $stmt->error);
}

// Bind the result columns to variables
$stmt->bind_result($id, $title, $status, $list_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <link rel="stylesheet" href="../style.css"> <!-- Adjusted path for the CSS -->
    <style>
        .header-container {
            position: relative;
            height: 50px;
        }
        .theme-toggle {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 50%;
            transform: translate(-50%, -50%);
            cursor: pointer;
            font-size: 24px;
        }
        .button-group {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }
        .button-group a {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .button-group a:nth-child(1) {
            background-color: #dc3545; /* Login button */
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <!-- Center: Dark/Light Theme Toggle -->
            <div class="theme-toggle">
                <span id="dark-mode" style="display: none;">🌙</span>
                <span id="light-mode">☀️</span>
            </div>

            <!-- Right: Login and Dashboard Buttons placed in the top-right corner -->
            <div class="button-group">
                <a href="../user/login.php" class="nav-link">Login</a> <!-- Corrected path for login.php -->
                <a href="../user/user.php" class="nav-link">Dashboard</a> <!-- Corrected path for user dashboard -->
            </div>
        </div>
    </header>

    <div class="signup-container">
        <!-- Your Tasks Header -->
        <h2>Your Tasks</h2>

        <!-- Create Task button under "Your Tasks" -->
        <div class="task-container">
            <a href="create_task.php" class="nav-link" style="padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">Create Task</a>
        </div>

        <!-- Task table placed below the header -->
        <table border="1" cellpadding="10" cellspacing="0" style="margin-top: 20px;">
            <tr>
                <th>Task ID</th>
                <th>Task Title</th>
                <th>Status</th>
                <th>List ID</th>
            </tr>
            <?php
            // Fetch tasks and display them in the table
            while ($stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($id) . "</td>";         // Display Task ID
                echo "<td>" . htmlspecialchars($title) . "</td>";      // Display Task Title
                echo "<td>" . htmlspecialchars($status) . "</td>";     // Display Status
                echo "<td>" . htmlspecialchars($list_id) . "</td>";    // Display List ID
                echo "</tr>";
            }

            // Close the task query after fetching the results
            $stmt->close();
            ?>
        </table>
    </div>

    <script>
        // JavaScript for theme toggle
        const darkModeToggle = document.getElementById('dark-mode');
        const lightModeToggle = document.getElementById('light-mode');
        let isDarkMode = false;

        darkModeToggle.addEventListener('click', () => {
            document.body.classList.add('dark-theme');
            darkModeToggle.style.display = 'none';
            lightModeToggle.style.display = 'inline';
            isDarkMode = true;
        });

        lightModeToggle.addEventListener('click', () => {
            document.body.classList.remove('dark-theme');
            lightModeToggle.style.display = 'none';
            darkModeToggle.style.display = 'inline';
            isDarkMode = false;
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
