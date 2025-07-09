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