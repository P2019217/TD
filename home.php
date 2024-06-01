<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }

   $id = $_SESSION['id'];
   $query = mysqli_query($con,"SELECT FirstName, LastName, Username, Email FROM users WHERE Id=$id ");

   while($result = mysqli_fetch_assoc($query)){
       $res_Fname = $result['FirstName'];
       $res_Lname = $result['LastName'];
       $res_Uname = $result['Username'];
       $res_Email = $result['Email'];
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Home</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php"> Logo</a></p>
        </div>
        <div class="right-links">
            <a href="edit.php">Change Profile</a>
            <a href="php/logout.php"> <button class="btn">Log Out</button> </a>
            <!-- Theme Switcher Button -->
            <div class="theme-switcher">
                <button id="theme-toggle">Switch to Dark Mode</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="box">
            <header>Welcome, <?php echo $res_Fname . " " . $res_Lname; ?></header>
            <p>Your username: <?php echo $res_Uname; ?></p>
            <p>Your email: <?php echo $res_Email; ?></p>
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
