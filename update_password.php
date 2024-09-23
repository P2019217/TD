<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$current_password = $data['current_password'];
$new_password = $data['new_password'];

// Fetch the current password hash
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($password_hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($current_password, $password_hash)) {
    echo json_encode(['error' => 'Current password is incorrect.']);
    exit();
}

// Validate new password criteria
if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_password)) {
    echo json_encode(['error' => 'Password must contain at least one capital letter, at least one number, and one symbol.']);
    exit();
}

$new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

// Update the password
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $new_password_hashed, $user_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Error updating password. Please try again.']);
}
$stmt->close();
$conn->close();
?>
