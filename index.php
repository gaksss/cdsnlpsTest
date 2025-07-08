<?php

require_once('config.php');

// Récupère la valeur de recherche depuis le formulaire GET
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prépare la requête SQL avec un paramètre
$stmt = $pdo->prepare("SELECT * FROM catalogue WHERE artist LIKE :search");
$stmt->execute(['search' => "%$search%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


// var_dump($results);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CD and LP</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Recherche de disque</h1>

    <form action="index.php" method="GET" autocomplete="off">
        <input id="search" name="search" type="text" placeholder="Ecrire ici" value="<?= htmlspecialchars($search) ?>" autocomplete="off">
        <input id="submit" type="submit" value="Search">
        <ul id="suggestions" style="background:#fff;position:absolute;z-index:10;list-style:none;padding:0;margin:0;width:200px;"></ul>
    </form>

    <script>
    const searchInput = document.getElementById('search');
    const suggestions = document.getElementById('suggestions');

    searchInput.addEventListener('input', function() {
        const query = this.value;
        if (query.length < 2) {
            suggestions.innerHTML = '';
            return;
        }
        fetch('autocomplete.php?search=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestions.innerHTML = '';
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.textContent = item.artist + ' || ' + item.title;
                    li.style.cursor = 'pointer';
                    li.onclick = function() {
                        searchInput.value = item.artist;
                        suggestions.innerHTML = '';
                    };
                    suggestions.appendChild(li);
                });
            });
    });
    // Cacher les suggestions si on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target)) {
            suggestions.innerHTML = '';
        }
    });
    </script>

    <?php if (!empty($results)) { ?>
        <ul class="results" aria-live="polite">
        <?php foreach ($results as $result) { ?>
            <li tabindex="0">
                <span><strong><?= htmlspecialchars($result['artist']) ?></strong></span>
                <span><?= htmlspecialchars($result['title']) ?></span>
            </li>
        <?php } ?>
        </ul>
    <?php } else if ($search !== '') { ?>
        <p style="text-align:center;color:#888;">Aucun résultat trouvé.</p>
    <?php } ?>

</body>

</html>