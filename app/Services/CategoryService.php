<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\HttpException;
use App\Models\CategoryModel;
use App\Models\JobModel;

class CategoryService
{
    private CategoryModel $categoryModel;

    private JobModel $jobModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->jobModel = new JobModel();
    }

    public function listCategories(): array
    {
        return $this->categoryModel->getAllWithActiveJobCount();
    }

    public function getCategoryWithJobs(int $categoryId): array
    {
        if ($categoryId <= 0) {
            throw new HttpException('Categoria invalida.', 404);
        }

        $category = $this->categoryModel->findById($categoryId);
        if ($category === []) {
            throw new HttpException('Categoria no encontrada.', 404);
        }

        return [
            'category' => $category,
            'jobs' => $this->jobModel->findByCategoryId($categoryId),
        ];
    }
}
