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
            mysqli_query($con, "INSERT INTO users(FirstName, LastName, Username, Email, Password) VALUES('$first_name', '$last_name', '$username', '$email', '$password')") or die("Error Occurred");

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
</body>
</html>

<?php } ?>
