<?php

declare(strict_types=1);

namespace App\Models;

class CategoryModel extends DBAbstractModel
{
    public function getAllWithActiveJobCount(): array
    {
        $sql = "
            SELECT
                c.id,
                c.name,
                COUNT(j.id) AS active_jobs
            FROM categories c
            LEFT JOIN jobs j
                ON j.category_id = c.id
                AND j.public = 1
                AND j.activated = 1
                AND j.expires_at >= NOW()
            GROUP BY c.id, c.name
            ORDER BY c.name ASC
        ";

        return $this->get_results_from_query($sql);
    }

    public function findById(int $categoryId): array
    {
        $sql = 'SELECT id, name FROM categories WHERE id = :id LIMIT 1';

        return $this->get_results_from_query($sql, [':id' => $categoryId], true);
    }

    public function exists(int $categoryId): bool
    {
        return $this->findById($categoryId) !== [];
    }
}
