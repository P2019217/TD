<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$current_password = $data['current_password'];

// Fetch the current password hash
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($password_hash);
$stmt->fetch();
$stmt->close();

if (password_verify($current_password, $password_hash)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
$conn->close();
?>
