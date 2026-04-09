<section class="hero">
    <h1>Ofertas de empleo activas</h1>
    <p>Encuentra oportunidades publicadas por empresas verificadas.</p>
    <a class="btn" href="<?= e(url('jobs/create')); ?>">Publicar nueva oferta</a>
</section>

<section class="filters">
    <h2>Filtrar por categoria</h2>
    <div class="filter-list">
        <a class="filter-link" href="<?= e(url('jobs')); ?>">Todas</a>
        <?php foreach ($categories as $category): ?>
            <a class="filter-link" href="<?= e(url('categories/' . (int) $category['id'] . '/jobs')); ?>">
                <?= e((string) $category['name']); ?>
                (<?= (int) ($category['active_jobs'] ?? 0); ?>)
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="jobs-grid">
    <?php if ($jobs === []): ?>
        <div class="panel">
            <p>No hay ofertas activas en este momento.</p>
        </div>
    <?php else: ?>
        <?php foreach ($jobs as $job): ?>
            <?php require VIEW_PATH . '/jobs/partials/job_card.php'; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
