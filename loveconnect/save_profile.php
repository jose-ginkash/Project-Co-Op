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
$name = $_POST['name'];
$age = $_POST['age'];
$games = $_POST['games'];
$bio = $_POST['bio'];

// Handle image upload
$img_name = $_FILES['picture']['name'];
$img_tmp = $_FILES['picture']['tmp_name'];
$img_path = "uploads/" . basename($img_name);

if (!is_dir("uploads")) {
  mkdir("uploads");
}
move_uploaded_file($img_tmp, $img_path);

// Insert into profiles table
$sql = "INSERT INTO profiles (username, name, age, games, bio, picture) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisss", $username, $name, $age, $games, $bio, $img_path);

if ($stmt->execute()) {
  header("Location: home.php");
  exit();
} else {
  echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>