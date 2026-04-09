<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AffiliateModel;

class AffiliateService
{
    private AffiliateModel $affiliateModel;

    public function __construct()
    {
        $this->affiliateModel = new AffiliateModel();
    }

    public function getAffiliateByToken(string $token): array
    {
        return $this->affiliateModel->findByToken($token);
    }

    public function getActiveAffiliatesByCategory(int $categoryId): array
    {
        return $this->affiliateModel->findActiveByCategoryId($categoryId);
    }
}
