<?php
include 'config.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT id, first_name, last_name, email, username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($id, $first_name, $last_name, $email, $username);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .info-container {
            margin-top: 20px;
            width: 100%;
            max-width: 600px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .info-item label {
            font-weight: bold;
        }
        .info-item input {
            width: 70%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .info-item .edit-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .info-item .edit-btn:hover {
            background-color: #0056b3;
        }
        .info-item .delete-btn {
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .info-item .delete-btn:hover {
            background-color: #cc0000;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        .success-message {
            color: green;
            font-size: 14px;
            margin-top: 10px;
        }
        body.dark-theme .modal-content {
            background-color: #444;
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <a href="homepage.php" class="logo">MySite</a>
        <button id="theme-toggle" onclick="toggleTheme()">🌙</button>
        <nav>
            <a href="logout.php" class="nav-link">Logout</a>
        </nav>
    </header>
    <div class="signup-container">
        <h2 id="greeting">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <h2>Users Information</h2>
        <div class="info-container">
            <div class="info-item">
                <label for="id">ID:</label>
                <span><?php echo htmlspecialchars($id); ?></span>
            </div>
            <div class="info-item">
                <label for="first_name">First Name:</label>
                <span><?php echo htmlspecialchars($first_name); ?></span>
            </div>
            <div class="info-item">
                <label for="last_name">Last Name:</label>
                <span><?php echo htmlspecialchars($last_name); ?></span>
            </div>
            <div class="info-item">
                <label for="email">Email:</label>
                <span><?php echo htmlspecialchars($email); ?></span>
            </div>
            <div class="info-item">
                <label for="username">Username:</label>
                <span id="username"><?php echo htmlspecialchars($username); ?></span>
                <button class="edit-btn" onclick="editField('username')">Edit</button>
            </div>
            <div class="info-item">
                <label for="password">Password:</label>
                <input type="password" id="password" value="********" readonly>
                <button class="edit-btn" onclick="showPasswordForm()">Change Password</button>
            </div>
            <div class="info-item">
                <button class="delete-btn" onclick="confirmDelete()">Delete User</button>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="POST" class="modal-form" onsubmit="return validatePasswordChange()">
                <h2>Change Password</h2>
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <small class="error-message" id="password-error"></small>
                <small class="success-message" id="password-success"></small>
                <button type="submit" class="btn">Update Password</button>
            </form>
        </div>
    </div>

    <script>
        function editField(field) {
            const span = document.getElementById(field);
            const value = span.innerText;
            span.innerHTML = `<input type="text" id="new_${field}" value="${value}" /> <button onclick="updateField('${field}')">Save</button>`;
        }

        function updateField(field) {
            const newValue = document.getElementById(`new_${field}`).value;
            const formData = new FormData();
            formData.append(`update_${field}`, true);
            formData.append(`new_${field}`, newValue);

            fetch('update_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.open();
                document.write(data);
                document.close();
            });
        }

        function showPasswordForm() {
            document.getElementById('passwordModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('passwordModal').style.display = 'none';
        }

        function toggleTheme() {
            const body = document.body;
            body.classList.toggle('dark-theme');
            const themeToggleButton = document.getElementById('theme-toggle');
            const theme = body.classList.contains('dark-theme') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
            themeToggleButton.textContent = theme === 'dark' ? '☀️' : '🌙';
        }

        function confirmDelete() {
            if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
                window.location.href = 'delete_user.php';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const theme = localStorage.getItem('theme') || 'light';
            const themeToggleButton = document.getElementById('theme-toggle');
            if (theme === 'dark') {
                document.body.classList.add('dark-theme');
                themeToggleButton.textContent = '☀️';
            } else {
                themeToggleButton.textContent = '🌙';
            }
        });

        function validatePasswordChange() {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const errorMessage = document.getElementById('password-error');
            const successMessage = document.getElementById('password-success');
            const pattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            // Reset messages
            errorMessage.textContent = '';
            successMessage.textContent = '';

            // Validate new password criteria
            if (!pattern.test(newPassword)) {
                errorMessage.textContent = "Password must contain at least one capital letter, at least one number, and one symbol.";
                return false;
            }

            // Check current password
            fetch('update_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ current_password: currentPassword, new_password: newPassword })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successMessage.textContent = "Your password was updated successfully. Redirecting to your profile...";
                    setTimeout(() => {
                        window.location.href = 'user.php';
                    }, 5000);
                } else if (data.error) {
                    errorMessage.textContent = data.error;
                }
            });

            return false;
        }
    </script>
</body>
</html>
