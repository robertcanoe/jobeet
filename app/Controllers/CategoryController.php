<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\CategoryService;

class CategoryController extends BaseController
{
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    public function showAction(string $id): void
    {
        $payload = $this->categoryService->getCategoryWithJobs((int) $id);

        $this->renderHTML('categories/show', [
            'title' => 'Categoria: ' . $payload['category']['name'],
            'category' => $payload['category'],
            'jobs' => $payload['jobs'],
        ]);
    }
}
