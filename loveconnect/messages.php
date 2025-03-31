<?php
session_start();
if (!isset($_SESSION['username'])) exit();

$host = "localhost";
$db = "loveconnect";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) exit();

$sender = $_SESSION['username'];
$receiver = $_POST['receiver'];
$message = $_POST['message'];

$conn->query("INSERT INTO messages (sender, receiver, message) VALUES ('$sender', '$receiver', '$message')");
?>