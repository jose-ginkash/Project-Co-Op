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

if ($result->num_rows > 0) {
  header("Location: home.php");
} else {
  header("Location: profile.php");
}
exit();
?>