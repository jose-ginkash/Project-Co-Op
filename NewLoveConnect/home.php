<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

// Initialize match index
if (!isset($_SESSION['match_index'])) {
    $_SESSION['match_index'] = 0;
}

// Handle Like/Pass
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['like']) || isset($_POST['pass'])) {
        $match_index = $_SESSION['match_index'];
        $matches = $_SESSION['matches_data'] ?? [];

        if (isset($matches[$match_index])) {
            $matched_user = $matches[$match_index];
            $receiver_id = $matched_user['user_id'];

            if (isset($_POST['like'])) {
                $getName = $conn->prepare("SELECT name FROM users WHERE id = ?");
                $getName->bind_param("i", $user_id);
                $getName->execute();
                $getName->bind_result($sender_name);
                $getName->fetch();
                $getName->close();

                $msg = "$sender_name liked your profile!";
                $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $user_id, $receiver_id, $msg);
                $stmt->execute();
            }

            $_SESSION['match_index']++;
        }

        header("Location: home.php");
        exit();
    }
}

// Load fresh matches
$stmt = $conn->prepare("SELECT hobbies FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($my_hobbies);
$stmt->fetch();
$stmt->close();

$my_hobbies = explode(',', strtolower($my_hobbies));

$query = "SELECT user_id, name, age, bio, profile_pic, hobbies FROM profiles WHERE user_id != ?";
$results = $conn->prepare($query);
$results->bind_param("i", $user_id);
$results->execute();
$result_set = $results->get_result();
$matches = [];

while ($row = $result_set->fetch_assoc()) {
    $text = ($row['bio'] ?? '') . ' ' . ($row['hobbies'] ?? '');
    $other_hobbies = explode(',', strtolower($text));
    $match_score = count(array_intersect($my_hobbies, $other_hobbies));
    if ($match_score > 0) {
        $matches[] = $row;
    }
}

if (count($matches) === 0) {
    $random_query = $conn->prepare("SELECT user_id, name, age, bio, profile_pic FROM profiles WHERE user_id != ? ORDER BY RAND() LIMIT 5");
    $random_query->bind_param("i", $user_id);
    $random_query->execute();
    $matches = $random_query->get_result()->fetch_all(MYSQLI_ASSOC);
}

$_SESSION['matches_data'] = $matches;

$current_index = $_SESSION['match_index'];
$current_match = $matches[$current_index] ?? null;

// Notifications
$notif_check = $conn->prepare("SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND seen = 0");
$notif_check->bind_param("i", $user_id);
$notif_check->execute();
$notif_check->bind_result($unseenCount);
$notif_check->fetch();
$notif_check->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home - LoveConnect</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- 🔔 Notification + Connections + Profile -->
<div class="notification-bar">
    <button id="notif-btn" onclick="window.location.href='notifications.php';">
        Notifications
    </button>
    <button onclick="window.location.href='connections.php';">
        Connections
    </button>
</div>

<div class="profile-btn">
    <a href="edit_profile.php" class="btn">My Profile</a>
</div>

<!-- 🔥 Main Content -->
<div class="container">
    <h2>Welcome to LoveConnect</h2>

    <?php if ($current_match): ?>
        <div class="match-card">
            <img src="<?php echo $current_match['profile_pic']; ?>" alt="Profile Picture">
            <h3><?php echo htmlspecialchars($current_match['name']); ?> (<?php echo $current_match['age']; ?>)</h3>
            <p><?php echo htmlspecialchars($current_match['bio']); ?></p>

            <form method="post" class="swipe-buttons">
                <button type="submit" name="like" class="like">❤️ Like</button>
                <button type="submit" name="pass" class="pass">❌ Pass</button>
            </form>

            <a href="message.php?user=<?php echo $current_match['user_id']; ?>" class="chat-button">Chat 💬</a>
        </div>
    <?php else: ?>
        <h3>Sorry, we were not able to match you 😢</h3>
    <?php endif; ?>

    <p><a href="logout.php">Logout</a></p>
</div>

<!-- 🔊 Notification sound & toast -->
<audio id="notif-sound" src="notification.mp3" preload="auto"></audio>
<div class="toast" id="toast">📬 New message received!</div>

<script>
    let lastCount = 0;

    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    function checkNotifications() {
        fetch('check_notifications.php')
            .then(res => res.json())
            .then(data => {
                const notifBtn = document.getElementById("notif-btn");
                const newCount = data.count;

                if (newCount > 0) {
                    notifBtn.innerText = `Notifications 🔔 (${newCount})`;
                    if (newCount > lastCount) {
                        document.getElementById("notif-sound").play();
                        showToast("📬 New message received!");
                    }
                } else {
                    notifBtn.innerText = "Notifications";
                }

                lastCount = newCount;
            });
    }

    document.addEventListener("DOMContentLoaded", () => {
        checkNotifications();
        setInterval(checkNotifications, 5000);
    });
</script>

</body>
</html>
