<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #ff758c, #ff7eb3);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      color: white;
    }
    form {
      background: rgba(0, 0, 0, 0.2);
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
      color: #ff4081;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <form action="save_profile.php" method="POST" enctype="multipart/form-data">
    <h2>Create Your Profile</h2>
    <input type="text" name="name" placeholder="Full Name" required />
    <input type="number" name="age" placeholder="Age" required />
    <input type="text" name="games" placeholder="Favorite Games" required />
    <textarea name="bio" placeholder="Short Bio" required></textarea>
    <input type="file" name="picture" accept="image/*" required />
    <button type="submit">Save Profile</button>
  </form>
</body>
</html>