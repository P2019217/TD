<?php
include '../config.php'; // Adjust the path to config.php

session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_list'])) {
        $name = $_POST['list_name'];
        $stmt = $conn->prepare("INSERT INTO lists (name, user_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['delete_list'])) {
        $list_id = $_POST['list_id'];
        $stmt = $conn->prepare("DELETE FROM lists WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $list_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM lists WHERE user_id = $user_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Task Lists</title>
    <link rel="stylesheet" type="text/css" href="../style.css"> <!-- Adjust the path to style.css -->
</head>
<body>
    <h1>Manage Task Lists</h1>

    <form method="POST">
        <input type="text" name="list_name" placeholder="New List Name" required>
        <button type="submit" name="create_list">Create List</button>
    </form>

    <h2>Your Task Lists</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo htmlspecialchars($row['name']); ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="list_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete_list">Delete</button>
                </form>
                <a href="view_tasks.php?list_id=<?php echo $row['id']; ?>">View Tasks</a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
