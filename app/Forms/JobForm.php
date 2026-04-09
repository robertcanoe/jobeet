<?php

declare(strict_types=1);

namespace App\Forms;

class JobForm
{
    public static function validateAndSanitize(array $payload, bool $isUpdate = false): array
    {
        $sanitizer = new JobSanitizer();
        $validator = new JobValidator();

        $data = $sanitizer->sanitize($payload);
        $errors = $validator->validate($data, $isUpdate);

        return [
            'is_valid' => $errors === [],
            'errors' => $errors,
            'data' => $data,
            'form' => $data,
        ];
    }
}
