<article class="record-card" tabindex="0" role="article">
    <header class="record-header">
        <img
            src="<?= htmlspecialchars($result['picture1']) ?>"
            alt="Pochette de <?= htmlspecialchars($result['title']) ?> par <?= htmlspecialchars($result['artist']) ?>"
            class="record-cover"
            loading="lazy" />
        <div class="record-info">
            <h3 class="record-artist"><?= htmlspecialchars($result['artist']) ?></h3>
            <div class="record-title"><?= htmlspecialchars($result['title']) ?></div>
        </div>
    </header>
    <div class="record-details">
        <div class="record-label">
            <strong>Label:</strong> <?= htmlspecialchars($result['label']) ?>
        </div>
        <div class="record-pressing">
            <strong>Pressage:</strong> <?= htmlspecialchars($result['pressing']) ?>
        </div>
    </div>
</article>