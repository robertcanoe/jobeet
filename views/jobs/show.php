<article class="job-detail">
    <header class="job-detail-header">
        <h1><?= e((string) $job['position']); ?></h1>
        <p>
            <?= e((string) $job['company']); ?> - <?= e((string) $job['location']); ?>
        </p>
        <p class="job-meta">
            <span>Categoria: <?= e((string) ($job['category_name'] ?? 'Sin categoria')); ?></span>
            <?php if (!empty($job['type'])): ?>
                <span>Tipo: <?= e((string) $job['type']); ?></span>
            <?php endif; ?>
            <span>Expira: <?= e(date('Y-m-d', strtotime((string) $job['expires_at']))); ?></span>
        </p>
    </header>

    <section class="panel">
        <h2>Descripcion</h2>
        <p><?= nl2br(e((string) $job['description'])); ?></p>
    </section>

    <?php if (!empty($job['how_to_apply'])): ?>
        <section class="panel">
            <h2>Como aplicar</h2>
            <p><?= nl2br(e((string) $job['how_to_apply'])); ?></p>
        </section>
    <?php endif; ?>

    <section class="panel">
        <h2>Contacto</h2>
        <p>Email: <a href="mailto:<?= e((string) $job['email']); ?>"><?= e((string) $job['email']); ?></a></p>

        <?php if (!empty($job['url'])): ?>
            <p>
                Sitio web:
                <a href="<?= e((string) $job['url']); ?>" target="_blank" rel="noopener noreferrer">
                    <?= e((string) $job['url']); ?>
                </a>
            </p>
        <?php endif; ?>
    </section>

    <div class="actions">
        <a class="btn" href="<?= e(url('jobs')); ?>">Volver al listado</a>
        <a class="btn btn-secondary" href="<?= e(url('jobs/' . (int) $job['id'] . '/edit')); ?>">Editar</a>

        <form method="POST" action="<?= e(url('jobs/' . (int) $job['id'] . '/delete')); ?>" onsubmit="return confirm('¿Seguro que deseas eliminar esta oferta?');">
            <?= csrf_field(); ?>
            <button class="btn btn-danger" type="submit">Eliminar</button>
        </form>
    </div>
</article>
