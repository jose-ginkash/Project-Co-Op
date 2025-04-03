<?php
session_start();
if (!isset($_SESSION['username'])) {
  exit();
}

$host = "localhost";
$db = "loveconnect";
$user = "root";
$pass = "";



$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  exit("DB connection error.");
}

$sender = $_SESSION['username'];
$receiver = $_POST['receiver'] ?? '';

if ($receiver === '') {
  exit("Missing receiver.");
}

$conn->query("INSERT INTO likes (sender, receiver) VALUES ('$sender', '$receiver')");

$check = $conn->query("SELECT * FROM likes WHERE sender='$receiver' AND receiver='$sender'");
if ($check->num_rows > 0) {
  echo 'match';  // Match found
} else {
  echo 'liked';
}
?>
