<?php
session_start();
$me = $_SESSION['username'];
$other = $_GET['with'];

$host = "localhost";
$db = "loveconnect";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);

$messages = [];
$result = $conn->query("SELECT * FROM messages WHERE (sender='$me' AND receiver='$other') OR (sender='$other' AND receiver='$me') ORDER BY timestamp ASC");
while ($row = $result->fetch_assoc()) {
  $messages[] = $row;
}

// Mark messages as seen
$conn->query("UPDATE messages SET seen = 1 WHERE receiver='$me' AND sender='$other'");

$seenText = "Last seen: just now";
echo json_encode(["messages" => $messages, "seen" => $seenText]);
?>