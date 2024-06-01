<?php 
   session_start();

   include("php/config.php");

   if(isset($_POST['submit'])){
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $duplicate = mysqli_query($con, "SELECT * FROM users WHERE Email='$email' OR Username='$username'");

        if (mysqli_num_rows($duplicate) > 0) {
            echo "<div class='message'>
                     <p>This email or username is already used, Try another one please!</p>
                  </div> <br>";
            echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
        } else {
            mysqli_query($con, "INSERT INTO users (FirstName, LastName, Username, Email, Password) VALUES ('$first_name', '$last_name', '$username', '$email', '$password')") or die("Error Occurred");

            echo "<div class='message'>
                      <p>Registration successfully!</p>
                  </div> <br>";
            echo "<a href='index.php'><button class='btn'>Login Now</button>";
        }

    } else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Register</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="index.php">Logo</a></p>
        </div>
        <div class="right-links">
            <a href="index.php">Login</a>
            <!-- Theme Switcher Button -->
            <div class="theme-switcher">
                <button id="theme-toggle">Switch to Dark Mode</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="box form-box">
            <header>Sign Up</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Register" required>
                </div>
                <div class="links">
                    Already a member? <a href="index.php">Sign In</a>
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

<?php } ?>
