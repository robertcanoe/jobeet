<section class="panel panel-error">
    <h1>Error 404</h1>
    <p><?= e($message ?? 'No se encontro el recurso solicitado.'); ?></p>
    <a class="btn" href="<?= e(url('jobs')); ?>">Volver al listado</a>
</section>
