<?php
include '../config.php'; // Adjust the path to config.php

session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    $assigned_user_id = $_POST['assigned_user_id'];
    $stmt = $conn->prepare("UPDATE tasks SET status = ?, assigned_user_id = ? WHERE id = ? AND EXISTS (SELECT 1 FROM lists WHERE id = tasks.list_id AND user_id = ?)");
    $stmt->bind_param("siii", $status, $assigned_user_id, $task_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_tasks.php?list_id=" . $_POST['list_id']);
    exit();
}

if (!isset($_GET['task_id'])) {
    die("Task ID not provided");
}

$task_id = $_GET['task_id'];
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND EXISTS (SELECT 1 FROM lists WHERE id = tasks.list_id AND user_id = ?)");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <link rel="stylesheet" type="text/css" href="../style.css"> <!-- Adjust the path to style.css -->
</head>
<body>
    <h1>Edit Task</h1>
    <form method="POST">
        <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
        <input type="hidden" name="list_id" value="<?php echo htmlspecialchars($task['list_id']); ?>">
        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="pending" <?php if ($task['status'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="completed" <?php if ($task['status'] == 'completed') echo 'selected'; ?>>Completed</option>
        </select>
        <label for="assigned_user_id">Assign to User ID:</label>
        <input type="number" name="assigned_user_id" id="assigned_user_id" value="<?php echo htmlspecialchars($task['assigned_user_id']); ?>">
        <button type="submit">Update Task</button>
    </form>
</body>
</html>
