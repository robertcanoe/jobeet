<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Jobeet'); ?></title>
    <link rel="stylesheet" href="<?= e(url('assets/css/app.css')); ?>">
</head>
<body>
    <?php require VIEW_PATH . '/includes/nav.php'; ?>

    <main class="container">
        <?php $flash = query_flash(); ?>

        <?php if (!empty($flash['success'])): ?>
            <div class="alert alert-success"><?= e((string) $flash['success']); ?></div>
        <?php endif; ?>

        <?php if (!empty($flash['error'])): ?>
            <div class="alert alert-error"><?= e((string) $flash['error']); ?></div>
        <?php endif; ?>

        <?= $content; ?>
    </main>

    <?php require VIEW_PATH . '/includes/footer.php'; ?>
</body>
</html>
