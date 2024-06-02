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
        .info-item .eye-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 5px;
            cursor: pointer;
            margin-left: 5px;
        }
        .info-item .eye-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <a href="homepage.php" class="logo">MySite</a>
        <nav>
            <a href="logout.php" class="nav-link">Logout</a>
        </nav>
    </header>
    <div class="signup-container">
        <button id="theme-toggle">Change to Dark</button>
        <h2>User Dashboard</h2>
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
                <button class="eye-btn" onclick="togglePasswordVisibility()">👁</button>
                <button class="edit-btn" onclick="promptChangePassword()">Change Password</button>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="POST" action="update_password.php" class="modal-form">
                <h2>Change Password</h2>
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                    <button type="button" class="eye-btn" onclick="toggleVisibility('current_password')">👁</button>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <button type="button" class="eye-btn" onclick="toggleVisibility('new_password')">👁</button>
                </div>
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

        function promptChangePassword() {
            document.getElementById('passwordModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('passwordModal').style.display = 'none';
        }

        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
        }

        function toggleVisibility(id) {
            const field = document.getElementById(id);
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
        }
    </script>
</body>
</html>
