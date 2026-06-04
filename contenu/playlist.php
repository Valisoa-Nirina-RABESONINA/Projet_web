<?php
// --- Connexion à la base de données ---
$pdo = new PDO('mysql:host=localhost;dbname=musique;charset=utf8', 'root', '');

// --- Récupération des morceaux ---
$stmt = $pdo->query("SELECT titre, chemin FROM morceaux ORDER BY titre ASC");
$morceaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Lecteur audio - Playlist dynamique</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #121212;
      color: #fff;
      margin: 0;
      padding: 2em;
    }
    .player {
      max-width: 600px;
      margin: auto;
      background: #1e1e1e;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.5);
    }
    .controls {
      display: flex;
      justify-content: space-around;
      align-items: center;
      margin-top: 10px;
    }
    button {
      background: #333;
      color: #fff;
      border: none;
      padding: 10px 15px;
      border-radius: 6px;
      cursor: pointer;
    }
    button:hover {
      background: #555;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    li {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #222;
      margin-bottom: 6px;
      padding: 8px;
      border-radius: 6px;
      cursor: pointer;
    }
    li.active {
      background: #444;
    }
    input[type=text] {
      width: 70%;
      padding: 5px;
      border-radius: 4px;
      border: none;
    }
  </style>
</head>
<body>
  <div class="player">
    <h2>🎵 Ma Playlist</h2>
    <audio id="audioPlayer" controls style="width:100%;"></audio>
    
    <div class="controls">
      <button id="prevBtn">⏮️</button>
      <button id="playPauseBtn">▶️ / ⏸️</button>
      <button id="nextBtn">⏭️</button>
      <button id="randomBtn">🔀</button>
      <button id="loopBtn">🔁</button>
    </div>

    <h3>Liste des morceaux</h3>
    <ul id="playlist">
      <?php foreach ($morceaux as $m): ?>
        <li data-src="<?= htmlspecialchars($m['chemin']) ?>">
          <?= htmlspecialchars($m['titre']) ?>
          <button class="removeBtn">🗑️</button>
        </li>
      <?php endforeach; ?>
    </ul>

    <div style="margin-top:15px;">
      <input type="text" id="newTitle" placeholder="Titre du morceau">
      <input type="text" id="newPath" placeholder="Chemin du fichier (URL absolue)">
      <button id="addBtn">➕ Ajouter</button>
    </div>
  </div>

  <script>
    const audio = document.getElementById('audioPlayer');
    const playlist = document.getElementById('playlist');
    const items = () => playlist.querySelectorAll('li');
    let current = 0;
    let random = false;
    let loop = false;

    function load(index) {
      const li = items()[index];
      if (!li) return;
      audio.src = li.dataset.src;
      items().forEach(el => el.classList.remove('active'));
      li.classList.add('active');
      current = index;
      audio.play();
    }

    document.getElementById('playPauseBtn').onclick = () => {
      if (audio.paused) audio.play(); else audio.pause();
    };

    document.getElementById('prevBtn').onclick = () => {
      load((current - 1 + items().length) % items().length);
    };

    document.getElementById('nextBtn').onclick = () => {
      nextTrack();
    };

    document.getElementById('randomBtn').onclick = () => {
      random = !random;
      alert("Lecture aléatoire " + (random ? "activée" : "désactivée"));
    };

    document.getElementById('loopBtn').onclick = () => {
      loop = !loop;
      alert("Lecture en boucle " + (loop ? "activée" : "désactivée"));
    };

    audio.addEventListener('ended', nextTrack);

    function nextTrack() {
      if (random) {
        load(Math.floor(Math.random() * items().length));
      } else if (current < items().length - 1) {
        load(current + 1);
      } else if (loop) {
        load(0);
      }
    }

    // Cliquer sur un morceau
    playlist.addEventListener('click', e => {
      if (e.target.tagName === 'LI') {
        load([...items()].indexOf(e.target));
      }
    });

    // Supprimer un morceau
    playlist.addEventListener('click', e => {
      if (e.target.classList.contains('removeBtn')) {
        e.stopPropagation();
        e.target.parentElement.remove();
      }
    });

    // Ajouter un morceau
    document.getElementById('addBtn').onclick = () => {
      const title = document.getElementById('newTitle').value.trim();
      const path = document.getElementById('newPath').value.trim();
      if (!title || !path) return alert("Veuillez remplir les deux champs.");
      const li = document.createElement('li');
      li.dataset.src = path;
      li.innerHTML = `${title} <button class="removeBtn">🗑️</button>`;
      playlist.appendChild(li);
      document.getElementById('newTitle').value = '';
      document.getElementById('newPath').value = '';
    };

    // Charger le premier morceau automatiquement
    if (items().length > 0) load(0);
  </script>
</body>
</html>
