<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit();
}

$host = "localhost";
$db = "loveconnect";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM profiles WHERE username != '" . $_SESSION['username'] . "'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Browse Users</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      padding: 2rem;
      color: white;
    }
    .user {
      background: rgba(0, 0, 0, 0.3);
      padding: 1rem;
      border-radius: 15px;
      margin-bottom: 1rem;
      max-width: 400px;
    }
    img {
      width: 100px;
      border-radius: 50%;
    }
  </style>
</head>
<body>
  <h2>Meet Other LoveConnect Users 💘</h2>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <div class="user">
      <img src="<?php echo $row['picture']; ?>" alt="Profile Picture" />
      <h3><?php echo htmlspecialchars($row['name']); ?> (<?php echo $row['age']; ?>)</h3>
      <p><strong>Games:</strong> <?php echo htmlspecialchars($row['games']); ?></p>
      <p><strong>Bio:</strong> <?php echo htmlspecialchars($row['bio']); ?></p>
    </div>
  <?php } ?>
  <br>
  <a href="home.php" style="color:white;">🔙 Back to Home</a>
</body>
</html>