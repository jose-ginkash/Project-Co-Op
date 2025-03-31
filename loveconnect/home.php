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

$username = $_SESSION['username'];
$current_user = $conn->query("SELECT * FROM profiles WHERE username = '$username'")->fetch_assoc();

$matches_result = $conn->query("SELECT * FROM profiles WHERE username != '$username'");
$matches = [];
while ($row = $matches_result->fetch_assoc()) {
  $matches[] = $row;
}

$total = count($matches);
$index = isset($_GET['index']) ? intval($_GET['index']) : 0;
$index = max(0, min($index, $total - 1));  // clamp within bounds
$match = $matches[$index] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>LoveConnect - Swipe</title>
  <script src="swipe.js" defer></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #8360c3, #2ebf91);
      color: white;
      text-align: center;
      margin: 0;
      padding: 0;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      min-height: 100vh;
    }

    .card {
      background: rgba(0, 0, 0, 0.3);
      padding: 2rem;
      border-radius: 20px;
      width: 360px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.4);
      margin-bottom: 20px;
    }

    img {
      width: 180px;
      height: 180px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 1rem;
    }

    button {
      margin: 8px;
      padding: 10px 20px;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }

    .like { background-color: #4CAF50; color: white; }
    .pass { background-color: #ff69b4; color: white; } /* pink pass button */
    .nav-btn { background: #00000044; color: white; }

    a.chat {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 16px;
      background: #333;
      color: white;
      text-decoration: none;
      border-radius: 8px;
    }

    .top-links a {
      background: #1f1f1f;
      padding: 10px 20px;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      margin: 10px;
    }

    .nav {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($current_user['name']); ?>! ❤️</h2>
    <p>Swipe through users and start matching!</p>

    <div class="top-links">
      <a href="edit_profile.php">📝 Edit Profile</a>
      <a href="logout.php">🚪 Logout</a>
    </div>

    <?php if ($match): ?>
      <div class="card" id="card-<?php echo $match['username']; ?>">
        <img src="<?php echo $match['picture']; ?>" alt="Profile Picture" />
        <h3><?php echo htmlspecialchars($match['name']); ?> (<?php echo $match['age']; ?>)</h3>
        <p><strong>Games:</strong> <?php echo htmlspecialchars($match['games']); ?></p>
        <p><strong>Bio:</strong> <?php echo htmlspecialchars($match['bio']); ?></p>

        <button class="like" onclick="likeUser('<?php echo $match['username']; ?>')">❤️ Like</button>
        <button class="pass" onclick="document.getElementById('card-<?php echo $match['username']; ?>').style.display='none';">❌ Pass</button>
        <br>
        <a class="chat" href="chat.php?with=<?php echo $match['username']; ?>">💬 Chat</a>
      </div>

      <div class="nav">
        <?php if ($index > 0): ?>
          <a href="?index=<?php echo $index - 1; ?>"><button class="nav-btn">⬅️ Previous</button></a>
        <?php endif; ?>
        <?php if ($index < $total - 1): ?>
          <a href="?index=<?php echo $index + 1; ?>"><button class="nav-btn">➡️ Next</button></a>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <p>No users found to swipe on!</p>
    <?php endif; ?>
  </div>
</body>
</html>