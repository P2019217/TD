<?php
session_start();
include("php/config.php");

if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];

$query = mysqli_query($con, "SELECT * FROM users WHERE Id='$id'");
$user = mysqli_fetch_assoc($query);

function randomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (isset($_POST['delete'])) {
    $randomFirstName = randomString(rand(10, 20));
    $randomLastName = randomString(rand(10, 20));
    $randomUsername = randomString(rand(10, 20));
    $randomEmail = randomString(rand(10, 20)) . '@example.com';
    $randomPassword = randomString(rand(10, 20));

    $updateQuery = "UPDATE users SET 
                    FirstName='$randomFirstName', 
                    LastName='$randomLastName', 
                    Username='$randomUsername', 
                    Email='$randomEmail', 
                    Password='$randomPassword' 
                    WHERE Id='$id'";
    mysqli_query($con, $updateQuery);

    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Profile</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">Logo</a></p>
        </div>
        <div class="right-links">
            <a href="php/logout.php"> <button class="btn">Log Out</button> </a>
            <!-- Theme Switcher Button -->
            <div class="theme-switcher">
                <button id="theme-toggle">Switch to Dark Mode</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="box form-box">
            <header>Profile</header>
            <form method="post" action="">
                <div class="field input">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" value="<?php echo $user['FirstName']; ?>" readonly>
                </div>
                <div class="field input">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" value="<?php echo $user['LastName']; ?>" readonly>
                </div>
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" id="username" value="<?php echo $user['Username']; ?>" readonly>
                </div>
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" id="email" value="<?php echo $user['Email']; ?>" readonly>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" id="password" value="<?php echo $user['Password']; ?>" readonly>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="delete" value="Delete Account" style="background-color: red;">
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Theme Switching -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const themeToggleBtn = document.getElementById('theme-toggle');
        const currentTheme = getCookie('theme') || 'light';
        document.body.classList.add(currentTheme + '-mode');
        themeToggleBtn.textContent = currentTheme === 'light' ? 'Switch to Dark Mode' : 'Switch to Light Mode';

        themeToggleBtn.addEventListener('click', function() {
            if (document.body.classList.contains('light-mode')) {
                document.body.classList.replace('light-mode', 'dark-mode');
                setCookie('theme', 'dark', 365);
                themeToggleBtn.textContent = 'Switch to Light Mode';
            } else {
                document.body.classList.replace('dark-mode', 'light-mode');
                setCookie('theme', 'light', 365);
                themeToggleBtn.textContent = 'Switch to Dark Mode';
            }
        });

        function setCookie(name, value, days) {
            const d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            const expires = "expires=" + d.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        }

        function getCookie(name) {
            const cname = name + "=";
            const decodedCookie = decodeURIComponent(document.cookie);
            const ca = decodedCookie.split(';');
            for(let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(cname) === 0) {
                    return c.substring(cname.length, c.length);
                }
            }
            return "";
        }
    });
    </script>
</body>
</html>
