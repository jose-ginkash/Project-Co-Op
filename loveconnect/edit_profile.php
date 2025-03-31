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
$result = $conn->query("SELECT * FROM profiles WHERE username = '$username'");
$profile = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #f6d365, #fda085);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      color: white;
    }
    form {
      background: rgba(0, 0, 0, 0.3);
      padding: 2rem;
      border-radius: 20px;
      width: 300px;
    }
    input, textarea {
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      border-radius: 10px;
      border: none;
    }
    button {
      width: 100%;
      padding: 10px;
      background: #fff;
      color: #ff7043;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <form action="save_profile.php" method="POST" enctype="multipart/form-data">
    <h2>Edit Your Profile</h2>
    <input type="text" name="name" placeholder="Full Name" value="<?php echo $profile['name']; ?>" required />
    <input type="number" name="age" placeholder="Age" value="<?php echo $profile['age']; ?>" required />
    <input type="text" name="games" placeholder="Favorite Games" value="<?php echo $profile['games']; ?>" required />
    <textarea name="bio" placeholder="Short Bio" required><?php echo $profile['bio']; ?></textarea>
    <input type="file" name="picture" accept="image/*" />
    <button type="submit">Update Profile</button>
  </form>
</body>
</html>