<?php

declare(strict_types=1);

namespace App\Models;

class AffiliateModel extends DBAbstractModel
{
    public function findByToken(string $token): array
    {
        $sql = 'SELECT * FROM affiliates WHERE token = :token LIMIT 1';

        return $this->get_results_from_query($sql, [':token' => $token], true);
    }

    public function findActiveByCategoryId(int $categoryId): array
    {
        $sql = "
            SELECT
                a.id,
                a.url,
                a.email,
                a.token,
                a.active,
                a.created_at,
                ac.category_id
            FROM affiliates a
            INNER JOIN affiliates_categories ac ON ac.affiliate_id = a.id
            WHERE ac.category_id = :category_id
              AND a.active = 1
            ORDER BY a.created_at DESC
        ";

        return $this->get_results_from_query($sql, [':category_id' => $categoryId]);
    }
}
