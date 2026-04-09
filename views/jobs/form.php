<?php
$actionUrl = $isEdit
    ? url('jobs/' . (int) $jobId . '/update')
    : url('jobs/store');

$submitLabel = $isEdit ? 'Actualizar oferta' : 'Publicar oferta';

$types = [
    '' => 'Selecciona tipo',
    'full-time' => 'Full time',
    'part-time' => 'Part time',
    'freelance' => 'Freelance',
    'internship' => 'Internship',
    'temporary' => 'Temporary',
];
?>
<section class="panel">
    <h1><?= e($isEdit ? 'Editar oferta' : 'Nueva oferta de empleo'); ?></h1>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-error"><?= e((string) $errors['general']); ?></div>
    <?php endif; ?>

    <form class="job-form" method="POST" action="<?= e($actionUrl); ?>" novalidate>
        <?= csrf_field(); ?>

        <label>
            Categoria
            <select name="category_id" required>
                <option value="">Selecciona categoria</option>
                <?php foreach ($categories as $category): ?>
                    <option
                        value="<?= (int) $category['id']; ?>"
                        <?= (int) old($form, 'category_id') === (int) $category['id'] ? 'selected' : ''; ?>
                    >
                        <?= e((string) $category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['category_id'])): ?>
                <small class="field-error"><?= e((string) $errors['category_id']); ?></small>
            <?php endif; ?>
        </label>

        <label>
            Tipo
            <select name="type">
                <?php foreach ($types as $value => $label): ?>
                    <option value="<?= e($value); ?>" <?= (string) old($form, 'type') === $value ? 'selected' : ''; ?>>
                        <?= e($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['type'])): ?>
                <small class="field-error"><?= e((string) $errors['type']); ?></small>
            <?php endif; ?>
        </label>

        <label>
            Empresa
            <input type="text" name="company" value="<?= e((string) old($form, 'company')); ?>" required>
            <?php if (!empty($errors['company'])): ?>
                <small class="field-error"><?= e((string) $errors['company']); ?></small>
            <?php endif; ?>
        </label>

        <label>
            URL empresa
            <input type="url" name="url" value="<?= e((string) old($form, 'url')); ?>">
            <?php if (!empty($errors['url'])): ?>
                <small class="field-error"><?= e((string) $errors['url']); ?></small>
            <?php endif; ?>
        </label>

        <label>
            Logo (URL o ruta)
            <input type="text" name="logo" value="<?= e((string) old($form, 'logo')); ?>">
            <?php if (!empty($errors['logo'])): ?>
                <small class="field-error"><?= e((string) $errors['logo']); ?></small>
            <?php endif; ?>
        </label>

        <label>
            Posicion
            <input type="text" name="position" value="<?= e((string) old($form, 'position')); ?>" required>
            <?php if (!empty($errors['position'])): ?>
                <small class="field-error"><?= e((string) $errors['position']); ?></small>
            <?php endif; ?>
        </label>

        <label>
            Ubicacion
            <input type="text" name="location" value="<?= e((string) old($form, 'location')); ?>" required>
            <?php if (!empty($errors['location'])): ?>
                <small class="field-error"><?= e((string) $errors['location']); ?></small>
            <?php endif; ?>
        </label>

        <label>
            Descripcion
            <textarea name="description" rows="5" required><?= e((string) old($form, 'description')); ?></textarea>
            <?php if (!empty($errors['description'])): ?>
                <small class="field-error"><?= e((string) $errors['description']); ?></small>
            <?php endif; ?>
        </label>

        <label>
            Como aplicar
            <textarea name="how_to_apply" rows="4"><?= e((string) old($form, 'how_to_apply')); ?></textarea>
            <?php if (!empty($errors['how_to_apply'])): ?>
                <small class="field-error"><?= e((string) $errors['how_to_apply']); ?></small>
            <?php endif; ?>
        </label>

        <label>
            Email de contacto
            <input type="email" name="email" value="<?= e((string) old($form, 'email')); ?>" required>
            <?php if (!empty($errors['email'])): ?>
                <small class="field-error"><?= e((string) $errors['email']); ?></small>
            <?php endif; ?>
        </label>

        <label>
            Fecha de expiracion
            <input type="date" name="expires_at" value="<?= e((string) old($form, 'expires_at')); ?>" required>
            <?php if (!empty($errors['expires_at'])): ?>
                <small class="field-error"><?= e((string) $errors['expires_at']); ?></small>
            <?php endif; ?>
        </label>

        <label class="checkbox-row">
            <input type="checkbox" name="public" value="1" <?= (int) old($form, 'public', 1) === 1 ? 'checked' : ''; ?>>
            Oferta publica
        </label>

        <label class="checkbox-row">
            <input type="checkbox" name="activated" value="1" <?= (int) old($form, 'activated', 1) === 1 ? 'checked' : ''; ?>>
            Oferta activada
        </label>

        <div class="actions">
            <button class="btn" type="submit"><?= e($submitLabel); ?></button>
            <a class="btn btn-secondary" href="<?= e(url('jobs')); ?>">Cancelar</a>
        </div>
    </form>
</section>
