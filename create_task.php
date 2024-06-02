<?php
include '../config.php'; // Adjust the path to config.php

session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $list_id = $_POST['list_id'];
    $stmt = $conn->prepare("INSERT INTO tasks (title, list_id) VALUES (?, ?)");
    $stmt->bind_param("si", $title, $list_id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_tasks.php?list_id=$list_id");
    exit();
}

if (!isset($_GET['list_id'])) {
    die("List ID not provided");
}

$list_id = $_GET['list_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Task</title>
    <link rel="stylesheet" type="text/css" href="../style.css"> <!-- Adjust the path to style.css -->
</head>
<body>
    <h1>Create Task</h1>
    <form method="POST">
        <input type="hidden" name="list_id" value="<?php echo htmlspecialchars($list_id); ?>">
        <input type="text" name="title" placeholder="Task Title" required>
        <button type="submit">Create Task</button>
    </form>
</body>
</html>
