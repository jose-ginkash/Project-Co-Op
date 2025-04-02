<?php
session_start();

$host = "localhost";
$db = "loveconnect";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $_SESSION['username'] = $username;
    header("Location: welcome.php");
    exit();
  } else {
    header("Location: login.php?error=1");
    exit();
  }

  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login - LoveConnect</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #43cea2, #185a9d);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .card {
      background: #fff;
      padding: 2rem;
      border-radius: 20px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.2);
      width: 300px;
      text-align: center;
    }
    input, button {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 10px;
    }
    button {
      background: #185a9d;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background: #144c85;
    }
    .error-msg {
      color: red;
      background-color: #ffe0e0;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Login</h2>

    <?php if (isset($_GET['error'])): ?>
      <p class="error-msg">❌ Invalid username or password.</p>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.html">Register</a></p>
  </div>
</body>
</html>
