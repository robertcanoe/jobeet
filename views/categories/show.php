<section class="hero">
    <h1>Categoria: <?= e((string) $category['name']); ?></h1>
    <p>Ofertas activas filtradas por categoria.</p>
    <a class="btn btn-secondary" href="<?= e(url('jobs')); ?>">Ver todas las ofertas</a>
</section>

<section class="jobs-grid">
    <?php if ($jobs === []): ?>
        <div class="panel">
            <p>No hay ofertas activas para esta categoria.</p>
        </div>
    <?php else: ?>
        <?php foreach ($jobs as $job): ?>
            <?php require VIEW_PATH . '/jobs/partials/job_card.php'; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
