<?php
$expiresAt = '';
if (!empty($job['expires_at'])) {
    $expiresAt = date('Y-m-d', strtotime((string) $job['expires_at']));
}
?>
<article class="job-card">
    <header>
        <h3>
            <a href="<?= e(url('jobs/' . (int) $job['id'])); ?>"><?= e((string) $job['position']); ?></a>
        </h3>
        <p class="job-company"><?= e((string) $job['company']); ?></p>
    </header>

    <p class="job-location"><?= e((string) $job['location']); ?></p>

    <p class="job-meta">
        <span><?= e((string) ($job['category_name'] ?? 'Sin categoria')); ?></span>
        <?php if (!empty($job['type'])): ?>
            <span><?= e((string) $job['type']); ?></span>
        <?php endif; ?>
        <?php if ($expiresAt !== ''): ?>
            <span>Expira: <?= e($expiresAt); ?></span>
        <?php endif; ?>
    </p>
</article>
