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
  echo "❌ Invalid username or password. <a href='login.html'>Try again</a>";
}

$stmt->close();
$conn->close();
?>