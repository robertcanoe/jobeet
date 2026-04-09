<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\HttpException;
use App\Models\CategoryModel;
use App\Models\JobModel;
use DateTimeImmutable;

class JobService
{
    private JobModel $jobModel;

    private CategoryModel $categoryModel;

    public function __construct()
    {
        $this->jobModel = new JobModel();
        $this->categoryModel = new CategoryModel();
    }

    public function listJobs(): array
    {
        $perPage = (int) \env('APP_PER_PAGE', 20);

        return $this->jobModel->listActive($perPage);
    }

    public function getJobDetail(int $jobId): array
    {
        if ($jobId <= 0) {
            throw new HttpException('Oferta invalida.', 404);
        }

        $job = $this->jobModel->findActiveById($jobId);
        if ($job === []) {
            throw new HttpException('Oferta no encontrada o expirada.', 404);
        }

        return $job;
    }

    public function getEditableJob(int $jobId): array
    {
        if ($jobId <= 0) {
            throw new HttpException('Oferta invalida.', 404);
        }

        $job = $this->jobModel->findById($jobId);
        if ($job === []) {
            throw new HttpException('Oferta no encontrada.', 404);
        }

        return $job;
    }

    public function createJob(array $formData): int
    {
        $this->assertCategoryExists((int) $formData['category_id']);

        $payload = [
            'type' => $formData['type'],
            'company' => $formData['company'],
            'logo' => $formData['logo'],
            'url' => $formData['url'],
            'position' => $formData['position'],
            'location' => $formData['location'],
            'description' => $formData['description'],
            'how_to_apply' => $formData['how_to_apply'],
            'token' => $this->generateToken(),
            'public' => (int) $formData['public'],
            'activated' => (int) $formData['activated'],
            'email' => $formData['email'],
            'expires_at' => $this->normalizeExpiresAt((string) $formData['expires_at']),
            'category_id' => (int) $formData['category_id'],
        ];

        return $this->jobModel->create($payload);
    }

    public function updateJob(int $jobId, array $formData): void
    {
        $this->getEditableJob($jobId);
        $this->assertCategoryExists((int) $formData['category_id']);

        $payload = [
            'type' => $formData['type'],
            'company' => $formData['company'],
            'logo' => $formData['logo'],
            'url' => $formData['url'],
            'position' => $formData['position'],
            'location' => $formData['location'],
            'description' => $formData['description'],
            'how_to_apply' => $formData['how_to_apply'],
            'public' => (int) $formData['public'],
            'activated' => (int) $formData['activated'],
            'email' => $formData['email'],
            'expires_at' => $this->normalizeExpiresAt((string) $formData['expires_at']),
            'category_id' => (int) $formData['category_id'],
        ];

        $this->jobModel->update($jobId, $payload);
    }

    public function deleteJob(int $jobId): void
    {
        $this->getEditableJob($jobId);
        $this->jobModel->delete($jobId);
    }

    private function assertCategoryExists(int $categoryId): void
    {
        if ($categoryId <= 0 || !$this->categoryModel->exists($categoryId)) {
            throw new HttpException('La categoria seleccionada no existe.', 422);
        }
    }

    private function normalizeExpiresAt(string $date): string
    {
        $date = trim($date);
        $dateObj = DateTimeImmutable::createFromFormat('Y-m-d', $date);

        if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
            throw new HttpException('La fecha de expiracion no tiene formato valido.', 422);
        }

        return $dateObj->setTime(23, 59, 59)->format('Y-m-d H:i:s');
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(16));
    }
}
