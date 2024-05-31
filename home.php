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
        </div>
    </div>
    <div class="container">
        <div class="box">
            <header>Welcome, <?php echo $res_Fname . " " . $res_Lname; ?></header>
            <p>Your username: <?php echo $res_Uname; ?></p>
            <p>Your email: <?php echo $res_Email; ?></p>
        </div>
    </div>
</body>
</html>
