<?php
session_start();
include("config.php");

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $query = mysqli_query($con, "SELECT * FROM users WHERE Username='$username' AND Password='$password'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $_SESSION['valid'] = true;
        $_SESSION['id'] = $row['Id'];
        $_SESSION['username'] = $row['Username'];
        header("Location: ../profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Wrong username or password";
        header("Location: ../index.php");
        exit();
    }
}
?>
