<?php
include 'config.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Function to generate random string
function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

// Generate random strings
$randomString1 = generateRandomString(rand(10, 25));
$randomString2 = generateRandomString(rand(10, 25));
$randomString3 = generateRandomString(rand(10, 25));

// Update user details with random strings
$stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ? WHERE id = ?");
$stmt->bind_param("sssi", $randomString1, $randomString2, $randomString3, $user_id);
$stmt->execute();
$stmt->close();

// Log out the user
session_destroy();

// Redirect to homepage
header("Location: homepage.php");
exit();
?>
