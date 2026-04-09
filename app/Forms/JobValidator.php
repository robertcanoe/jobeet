<?php

declare(strict_types=1);

namespace App\Forms;

use DateTimeImmutable;

class JobValidator
{
    private const ALLOWED_TYPES = [
        'full-time',
        'part-time',
        'freelance',
        'internship',
        'temporary',
    ];

    public function validate(array $data, bool $isUpdate = false): array
    {
        $errors = [];

        if ($data['category_id'] <= 0) {
            $errors['category_id'] = 'Debes seleccionar una categoria valida.';
        }

        if ($data['company'] === '') {
            $errors['company'] = 'La empresa es obligatoria.';
        }

        if ($data['position'] === '') {
            $errors['position'] = 'La posicion es obligatoria.';
        }

        if ($data['location'] === '') {
            $errors['location'] = 'La ubicacion es obligatoria.';
        }

        if ($data['description'] === '') {
            $errors['description'] = 'La descripcion es obligatoria.';
        } elseif (mb_strlen($data['description']) < 20) {
            $errors['description'] = 'La descripcion debe tener al menos 20 caracteres.';
        }

        if ($data['email'] === '') {
            $errors['email'] = 'El correo de contacto es obligatorio.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El correo de contacto no es valido.';
        }

        if ($data['url'] !== '' && !filter_var($data['url'], FILTER_VALIDATE_URL)) {
            $errors['url'] = 'La URL de la empresa no es valida.';
        }

        if ($data['logo'] !== '' && !filter_var($data['logo'], FILTER_VALIDATE_URL) && !preg_match('/^[a-zA-Z0-9._\/-]+$/', $data['logo'])) {
            $errors['logo'] = 'El logo debe ser una URL valida o una ruta segura.';
        }

        if ($data['type'] !== '' && !in_array($data['type'], self::ALLOWED_TYPES, true)) {
            $errors['type'] = 'El tipo de oferta no es valido.';
        }

        if ($data['expires_at'] === '') {
            $errors['expires_at'] = 'La fecha de expiracion es obligatoria.';
        } else {
            $date = DateTimeImmutable::createFromFormat('Y-m-d', $data['expires_at']);
            if (!$date || $date->format('Y-m-d') !== $data['expires_at']) {
                $errors['expires_at'] = 'La fecha debe tener formato YYYY-MM-DD.';
            }
        }

        if (!$isUpdate && $data['how_to_apply'] === '') {
            $errors['how_to_apply'] = 'Indica como aplicar a la oferta.';
        }

        return $errors;
    }
}
