<?php
// Determine sur quelle page on se trouve

if (isset($_GET['page']) && !empty($_GET['page'])) {

    $currentPage = (int) strip_tags($_GET['page']);
} else {
    $currentPage = 1;
}


require_once('config.php');

// On détermine le nombre de disque total

// Récupère la valeur de recherche depuis le formulaire GET
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Requête pour compter le nombre de disques selon la recherche
if (!empty($search)) {
    $countStmt = $pdo->prepare("SELECT COUNT(*) AS nb_disques FROM catalogue WHERE artist LIKE :search OR title LIKE :search OR label LIKE :search OR pressing LIKE :search;");
    $countStmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
} else {
    $countStmt = $pdo->prepare("SELECT COUNT(*) AS nb_disques FROM catalogue;");
}

$countStmt->execute();
$result = $countStmt->fetch();
$nb_disques = (int) $result['nb_disques'];


// On détermine le nombre de disque par page

$parPage = 10;

// Calcul du nombre de page total

$pages = ceil($nb_disques / $parPage);

// Calcul du premier disque de la page

$premier = ($currentPage * $parPage) - $parPage;

// Récupère la valeur de recherche depuis le formulaire GET
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prépare la requête SQL avec un paramètre
$stmt = $pdo->prepare("SELECT * FROM catalogue WHERE artist LIKE :search OR title LIKE :search OR label LIKE :search OR pressing LIKE :search ORDER BY `id` ASC LIMIT :premier, :parpage;");

$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':premier', $premier, PDO::PARAM_INT);
$stmt->bindValue(':parpage', $parPage, PDO::PARAM_INT);

$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinyl Collection - Disques Vinyles d'Occasion</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <header class="header">
        <div class="container">
            <h1>Vinyl Collection</h1>

        </div>
    </header>

    <main class="container">
        <section class="search-section">
            <form class="search-form" action="index.php" method="GET" autocomplete="off">
                <div class="search-input-wrapper">
                    <input
                        id="search"
                        name="search"
                        type="text"
                        class="search-input"
                        placeholder="Rechercher par artiste, titre, label..."
                        value="<?= htmlspecialchars($search ?? '') ?>"
                        autocomplete="off"
                        aria-label="Rechercher des disques">
                    <ul id="suggestions" class="suggestions" role="listbox" aria-label="Suggestions de recherche"></ul>
                </div>
                <button type="submit" class="search-button">
                    <span>Rechercher</span>
                </button>
            </form>
        </section>

        <?php if (!empty($results)) { ?>
            <section class="results-section">
                <div class="results-header">
                    <div class="results-count">
                        <?= $nb_disques ?> disque<?= $nb_disques > 1 ? 's' : '' ?> trouvé<?= $nb_disques > 1 ? 's' : '' ?>
                    </div>
                    <div class="view-toggle">
                        <button class="view-btn active" data-view="grid">Grille</button>
                        <button class="view-btn" data-view="list">Liste</button>
                    </div>
                </div>

                <div class="results-grid" id="results-container" role="main" aria-live="polite">
                    <?php foreach ($results as $result) { ?>
                        <article class="record-card" tabindex="0" role="article">
                            <div class="record-header">
                                <img
                                    src="<?= htmlspecialchars($result['picture1']) ?>"
                                    alt="Pochette de <?= htmlspecialchars($result['title']) ?> par <?= htmlspecialchars($result['artist']) ?>"
                                    class="record-cover"
                                    loading="lazy" />
                                <div class="record-info">
                                    <h3 class="record-artist"><?= htmlspecialchars($result['artist']) ?></h3>
                                    <div class="record-title"><?= htmlspecialchars($result['title']) ?></div>
                                </div>
                            </div>
                            <div class="record-details">
                                <div class="record-label">
                                    <strong>Label:</strong> <?= htmlspecialchars($result['label']) ?>
                                </div>
                                <div class="record-pressing">
                                    <strong>Pressage:</strong> <?= htmlspecialchars($result['pressing']) ?>
                                </div>
                            </div>
                        </article>
                    <?php } ?>
                </div>

                <nav class="pagination-wrapper" role="navigation" aria-label="Navigation pagination">
                    <ul class="pagination">
                        <li>
                            <a href="index.php?page=<?= max(1, $currentPage - 1) ?>&search=<?= urlencode($search) ?>"
                                class="<?= ($currentPage === 1) ? "disabled" : "" ?>"
                                aria-label="Page précédente">
                                ← Précédent
                            </a>
                        </li>

                        <?php
                        $start = max(1, $currentPage - 2);
                        $end = min($pages, $currentPage + 2);

                        if ($start > 1) {
                            echo '<li><a href="index.php?page=1&search=' . urlencode($search) . '">1</a></li>';
                            if ($start > 2) {
                                echo '<li><span>...</span></li>';
                            }
                        }

                        for ($page = $start; $page <= $end; $page++): ?>
                            <li>
                                <a href="index.php?page=<?= $page ?>&search=<?= urlencode($search) ?>"
                                    class="<?= ($currentPage === $page) ? "active" : "" ?>"
                                    aria-label="Page <?= $page ?>"
                                    <?= ($currentPage === $page) ? 'aria-current="page"' : '' ?>>
                                    <?= $page ?>
                                </a>
                            </li>
                        <?php endfor;

                        if ($end < $pages) {
                            if ($end < $pages - 1) {
                                echo '<li><span>...</span></li>';
                            }
                            echo '<li><a href="index.php?page=' . $pages . '&search=' . urlencode($search) . '">' . $pages . '</a></li>';
                        }
                        ?>

                        <li>

                            <a href="index.php?page=<?= min($pages, $currentPage + 1) ?>&search=<?= urlencode($search) ?>"
                                class="<?= ($currentPage == $pages) ? "disabled" : "" ?>"
                                aria-label="Page suivante">
                                Suivant →
                            </a>
                        </li>
                    </ul>
                </nav>
            </section>
        <?php } else if ($search !== '') { ?>
            <section class="no-results">
                <h2>Aucun résultat trouvé</h2>
                <p>Aucun disque ne correspond à votre recherche "<?= htmlspecialchars($search) ?>"</p>
                <p>Essayez avec d'autres mots-clés ou parcourez notre catalogue complet.</p>
            </section>
        <?php } ?>
    </main>


</body>

</html>

<script defer src="script.js"></script>