<section class="panel panel-error">
    <h1>Error 500</h1>
    <p><?= e($message ?? 'Ocurrio un error interno del servidor.'); ?></p>
    <a class="btn" href="<?= e(url('jobs')); ?>">Volver al inicio</a>
</section>
