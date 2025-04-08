<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['count' => 0]);
    exit();
}

$user_id = $_SESSION['user'];

$stmt = $conn->prepare("SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND seen = 0");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

echo json_encode(['count' => $count]);
?>
