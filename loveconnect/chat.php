<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['with'])) {
  header("Location: home.php");
  exit();
}
$me = $_SESSION['username'];
$other = $_GET['with'];

$host = "localhost";
$db = "loveconnect";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Chat with <?php echo htmlspecialchars($other); ?></title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f7f8f8, #acbb78);
      margin: 0;
      padding: 0;
    }
    header {
      background: #2d2d2d;
      color: white;
      padding: 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    header a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      background: #444;
      padding: 6px 12px;
      border-radius: 6px;
    }
    h2 {
      margin: 0;
    }
    #chat-box {
      background: white;
      max-width: 700px;
      margin: 30px auto;
      padding: 20px;
      border-radius: 16px;
      height: 400px;
      overflow-y: scroll;
      box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    .msg {
      margin: 10px;
      padding: 10px 14px;
      border-radius: 16px;
      max-width: 75%;
      word-wrap: break-word;
      font-size: 15px;
    }
    .me {
      background: #caffbf;
      margin-left: auto;
      text-align: right;
    }
    .them {
      background: #d0d0d0;
      margin-right: auto;
      text-align: left;
    }
    #form-box {
      max-width: 700px;
      margin: 10px auto 30px;
      display: flex;
      gap: 10px;
    }
    input[type="text"] {
      flex: 1;
      padding: 12px;
      font-size: 16px;
      border-radius: 10px;
      border: 1px solid #ccc;
    }
    button {
      padding: 12px 20px;
      font-size: 16px;
      background: #3cba54;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }
    #seen {
      text-align: center;
      color: #333;
      font-size: 13px;
      margin-top: 8px;
    }
  </style>
</head>
<body>
  <header>
    <h2>Chat with <?php echo htmlspecialchars($other); ?></h2>
    <a href="home.php">🏠 Back to Home</a>
  </header>

  <div id="chat-box"></div>
  <div id="seen"></div>

  <form id="form-box" onsubmit="sendMessage(); return false;">
    <input type="text" id="msg" placeholder="Type a message..." required autocomplete="off">
    <button type="submit">Send</button>
  </form>

  <audio id="notif-sound" src="https://www.soundjay.com/button/beep-07.wav" preload="auto"></audio>

  <script>
    const me = "<?php echo $me; ?>";
    const other = "<?php echo $other; ?>";
    let lastMsgCount = 0;

    function loadMessages() {
      fetch('load_messages.php?with=' + other)
        .then(res => res.json())
        .then(data => {
          const chatBox = document.getElementById("chat-box");
          chatBox.innerHTML = "";
          data.messages.forEach(msg => {
            const div = document.createElement("div");
            div.classList.add("msg");
            div.classList.add(msg.sender === me ? "me" : "them");
            div.textContent = msg.message;
            chatBox.appendChild(div);
          });
          if (data.messages.length > lastMsgCount) {
            document.getElementById("notif-sound").play();
            lastMsgCount = data.messages.length;
          }
          chatBox.scrollTop = chatBox.scrollHeight;
          document.getElementById("seen").innerText = data.seen;
        });
    }

    function sendMessage() {
      const msg = document.getElementById("msg").value;
      fetch("messages.php", {
        method: "POST",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: "receiver=" + other + "&message=" + encodeURIComponent(msg)
      }).then(() => {
        document.getElementById("msg").value = "";
        loadMessages();
      });
    }

    loadMessages();
    setInterval(loadMessages, 3000);
  </script>
</body>
</html>