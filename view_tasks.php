<?php
include '../config.php'; // Adjust the path to config.php

session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login

if (!isset($_GET['list_id'])) {
    die("List ID not provided");
}

$list_id = $_GET['list_id'];
$stmt = $conn->prepare("SELECT * FROM tasks WHERE list_id = ? AND EXISTS (SELECT 1 FROM lists WHERE id = ? AND user_id = ?)");
$stmt->bind_param("iii", $list_id, $list_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Tasks</title>
    <link rel="stylesheet" type="text/css" href="../style.css"> <!-- Adjust the path to style.css -->
</head>
<body>
    <h1>Tasks in List</h1>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo htmlspecialchars($row['title']); ?> - <?php echo htmlspecialchars($row['status']); ?>
                <a href="edit_task.php?task_id=<?php echo $row['id']; ?>">Edit</a>
                <form method="POST" action="delete_task.php" style="display:inline;">
                    <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                    <button type="submit">Delete</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
    <a href="create_task.php?list_id=<?php echo htmlspecialchars($list_id); ?>">Create New Task</a>
</body>
</html>
