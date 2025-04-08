<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

// Get current profile data
$stmt = $conn->prepare("SELECT name, age, bio, hobbies, favorite_games, profile_pic FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $age, $bio, $hobbies, $favorite_games, $profile_pic);
$stmt->fetch();
$stmt->close();

// Handle update form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_name = htmlspecialchars($_POST['name']);
    $new_age = (int)$_POST['age'];
    $new_bio = htmlspecialchars($_POST['bio']);
    $new_hobbies = htmlspecialchars($_POST['hobbies']);
    $new_fav_games = htmlspecialchars($_POST['favorite_games']);
    $new_pic = $profile_pic;

    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        $new_pic = $target_dir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $new_pic);
    }

    $update = $conn->prepare("UPDATE profiles SET name = ?, age = ?, bio = ?, hobbies = ?, favorite_games = ?, profile_pic = ? WHERE user_id = ?");
    $update->bind_param("sissssi", $new_name, $new_age, $new_bio, $new_hobbies, $new_fav_games, $new_pic, $user_id);
    $update->execute();
    $update->close();

    echo "<script>alert('Profile updated successfully'); window.location.href='home.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input,
        textarea {
            width: 90%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: #28c76f;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #20b05c;
        }

        img.preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Edit Your Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <div>
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>

            <div>
                <label>Age:</label>
                <input type="number" name="age" value="<?php echo $age; ?>" required>
            </div>

            <div>
                <label>Bio:</label>
                <textarea name="bio" required><?php echo htmlspecialchars($bio); ?></textarea>
            </div>

            <div>
                <label>Favorite Games:</label>
                <input type="text" name="favorite_games" value="<?php echo htmlspecialchars($favorite_games); ?>">
            </div>

            <div>
                <label>Hobbies:</label>
                <input type="text" name="hobbies" value="<?php echo htmlspecialchars($hobbies); ?>">
            </div>

            <div>
                <label>Profile Picture:</label>
                <input type="file" name="profile_pic">
                <?php if (!empty($profile_pic)) { ?>
                    <img src="<?php echo $profile_pic; ?>" alt="Current Profile" class="preview">
                <?php } ?>
            </div>

            <div>
                <button type="submit">Update Profile</button>
            </div>
        </form>

        <p style="margin-top: 15px;"><a href="home.php">← Back to Home</a></p>
        <p style="margin-top: 15px;"><a href="logout.php">Logout →</a></p>
    </div>

</body>

</html>
