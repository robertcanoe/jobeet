<?php

declare(strict_types=1);

namespace App\Forms;

class JobSanitizer
{
    public function sanitize(array $input): array
    {
        $categoryId = filter_var(
            $input['category_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        return [
            'category_id' => $categoryId !== false ? (int) $categoryId : 0,
            'type' => $this->cleanText((string) ($input['type'] ?? '')),
            'company' => $this->cleanText((string) ($input['company'] ?? '')),
            'logo' => trim((string) ($input['logo'] ?? '')),
            'url' => trim((string) ($input['url'] ?? '')),
            'position' => $this->cleanText((string) ($input['position'] ?? '')),
            'location' => $this->cleanText((string) ($input['location'] ?? '')),
            'description' => trim(strip_tags((string) ($input['description'] ?? ''))),
            'how_to_apply' => trim(strip_tags((string) ($input['how_to_apply'] ?? ''))),
            'email' => trim((string) filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL)),
            'expires_at' => trim((string) ($input['expires_at'] ?? '')),
            'public' => isset($input['public']) ? 1 : 0,
            'activated' => isset($input['activated']) ? 1 : 0,
        ];
    }

    private function cleanText(string $value): string
    {
        return trim(strip_tags($value));
    }
}
