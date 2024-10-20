<?php
session_start();
include("connect.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!empty($email) && !empty($password) && !is_numeric($email)) {
        $query = "INSERT INTO form (email, username, password, status) VALUES ('$email', '$username', '$password', 'pending')";

        if(mysqli_query($con, $query)) {
            echo "<script type='text/javascript'>alert('Successfully Registered. Awaiting admin approval.');</script>";
            echo "<script type='text/javascript'>window.location.href = 'login.php';</script>";
        } else {
            echo "<script type='text/javascript'>alert('Error: Could not register')</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('Please Enter Valid Information');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang = "en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up Page</title>
        <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="wrapper">
            <form action="" method="POST">
                <h1>Sign Up</h1>

                <div class="input-group">
                    <div class="input-field" id="emailField">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="Email" name="email" required>
                    </div>
                    <div class="input-field" id="nameField">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" placeholder="Username" name="username" required>
                    </div>

                    <div class="input-field" id="pwField">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" placeholder="Password" name="password" required>
                    </div>

                    <button type="submit" class="btn">Sign Up</button>

                    <div class="login-link">
                        <a href="login.php">Back to Login</a>
                    </div>

        </form>
    </div>
</body>
</html>