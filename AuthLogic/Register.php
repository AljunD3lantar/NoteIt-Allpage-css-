<?php
require_once '../Backend/User.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // You may want to add email to your database and User class if needed
    if ($user->register($username, $password)) {
        $message = "<p class='success-msg'>Registration successful!</p>";
    } else {
        $message = "<p class='error-msg'>Registration failed!</p>";
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
    <title>Registration Form</title>
</head>
<body>
     <nav>            
        <label class="logo">Note<span>It!</span></label>
        <ul>
            <li><a href="../Route/index.php">HOME</a></li>
            <li><a href="../AuthLogic/Register.php">REGISTER</a></li>
            <li><a href="../AuthLogic/Login.php">SIGN IN</a></li>
        </ul>
    </nav>

    <div class="wrapper">
        <div class="register-content">
            <div class="left">
                <img src="../Frontend/images/kids.png" alt="logo">
            </div>
            <div class="right">
                <div class="greeting">Hello, friend!</div>
                <?php echo $message; ?>
                <form method="post" class="form">
                    <div class="input-box">
                        <input type="text" name="username" placeholder="Name" required>
                    </div>
                    <div class="input-box">
                        <input type="email" name="email" placeholder="E-mail" required>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="terms-container">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">I've read and agree to Terms & Conditions</label>
                    </div>
                    <div class="button">
                        <button type="submit" class="primary">CREATE ACCOUNT</button>
                    </div>
                    <div class="signin-link">
                        Already have an account? <a href="../AuthLogic/Login.php">Sign in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
