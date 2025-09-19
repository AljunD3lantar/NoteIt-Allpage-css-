<?php
require_once '../Backend/User.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $user = new User();
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($user->login($username, $password)) {
    $_SESSION['username'] = $username;
    header("Location: ../Route/Dashboard.php");
    exit();
    } else {
        echo "<p>Login failed!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Frontend/Allpage.css">
    <title>Login Form</title>
</head>
<body>
    <div class="wrapper">
        <nav>
            <label class="logo">Note<span>It!</span></label>
            <ul>
                <li><a href="../Route/index.php">HOME</a></li>
                <li><a href="../AuthLogic/Register.php">REGISTER</a></li>
                <li><a href="../AuthLogic/Login.php">SIGN IN</a></li>
            </ul>
        </nav>

        <div class="form-container">
            <form id="login-form" method="POST" action="../AuthLogic/Login.php">
                <h1>Note<span>It!</span></h1>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox">Remember Me</label>
                    <a href="#">Forgot Password?</a>
                </div>
               <button type="submit" class="btn">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>