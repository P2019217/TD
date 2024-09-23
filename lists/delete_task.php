<?php
include '../config.php'; // Adjust the path to config.php

session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND EXISTS (SELECT 1 FROM lists WHERE id = tasks.list_id AND user_id = ?)");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
