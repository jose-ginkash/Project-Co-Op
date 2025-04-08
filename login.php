<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $id;

            $profile_check = $conn->prepare("SELECT id FROM profiles WHERE user_id = ?");
            $profile_check->bind_param("i", $id);
            $profile_check->execute();
            $profile_check->store_result();

            if ($profile_check->num_rows > 0) {
                header("Location: home.php");
            } else {
                header("Location: create_profile.php");
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "User not found. <a href='register.php'>Create an account</a>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - LoveConnect</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if (isset($_GET['registered']) && $_GET['registered'] == 'success'): ?>
            <p style="color: green;">Registration successful! Please log in.</p>
        <?php endif; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="post">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="primary-btn">Login</button>
        </form>

        <p>Don’t have an account? <a href="register.php">Register here</a></p>

        <!-- ✅ Styled Discord Login Button -->
        <div class="discord-login">
            <a href="login-discord.php" class="discord-btn">Login with Discord</a>
        </div>
    </div>
</body>
</html>
