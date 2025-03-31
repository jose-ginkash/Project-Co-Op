function likeUser(username) {
  fetch('likes.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'receiver=' + username
  })
  .then(res => res.text())
  .then(data => {
    alert(data === 'match' ? '🎉 It's a Match!' : '❤️ You liked this user!');
    document.getElementById('card-' + username).style.display = 'none';
  });
}