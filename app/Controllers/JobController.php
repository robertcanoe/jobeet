<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\HttpException;
use App\Forms\JobForm;
use App\Services\CategoryService;
use App\Services\JobService;

class JobController extends BaseController
{
    private JobService $jobService;

    private CategoryService $categoryService;

    public function __construct()
    {
        $this->jobService = new JobService();
        $this->categoryService = new CategoryService();
    }

    public function listAction(): void
    {
        $this->renderHTML('jobs/index', [
            'title' => 'Ofertas de empleo',
            'jobs' => $this->jobService->listJobs(),
            'categories' => $this->categoryService->listCategories(),
        ]);
    }

    public function showAction(string $id): void
    {
        $job = $this->jobService->getJobDetail((int) $id);

        $this->renderHTML('jobs/show', [
            'title' => $job['position'] . ' - ' . $job['company'],
            'job' => $job,
        ]);
    }

    public function createAction(): void
    {
        $this->renderHTML('jobs/form', [
            'title' => 'Publicar nueva oferta',
            'isEdit' => false,
            'jobId' => null,
            'errors' => [],
            'form' => $this->defaultFormData(),
            'categories' => $this->categoryService->listCategories(),
        ]);
    }

    public function storeAction(): void
    {
        $this->requirePostMethod();
        $this->ensureValidCsrfToken();

        $result = JobForm::validateAndSanitize($_POST, false);
        if (!$result['is_valid']) {
            $this->renderFormWithState(false, null, $result['form'], $result['errors']);

            return;
        }

        try {
            $jobId = $this->jobService->createJob($result['data']);
            $this->redirect(\url('jobs/' . $jobId), ['success' => 'Oferta creada correctamente.']);
        } catch (HttpException $exception) {
            if ($exception->getStatusCode() >= 500) {
                throw $exception;
            }

            $errors = $result['errors'];
            $errors['general'] = $exception->getMessage();
            $this->renderFormWithState(false, null, $result['form'], $errors);
        }
    }

    public function editAction(string $id): void
    {
        $jobId = (int) $id;
        $job = $this->jobService->getEditableJob($jobId);

        if (isset($job['expires_at'])) {
            $job['expires_at'] = substr((string) $job['expires_at'], 0, 10);
        }

        $this->renderFormWithState(true, $jobId, $job, []);
    }

    public function updateAction(string $id): void
    {
        $this->requirePostMethod();
        $this->ensureValidCsrfToken();

        $jobId = (int) $id;
        $result = JobForm::validateAndSanitize($_POST, true);
        if (!$result['is_valid']) {
            $this->renderFormWithState(true, $jobId, $result['form'], $result['errors']);

            return;
        }

        try {
            $this->jobService->updateJob($jobId, $result['data']);
            $this->redirect(\url('jobs/' . $jobId), ['success' => 'Oferta actualizada correctamente.']);
        } catch (HttpException $exception) {
            if ($exception->getStatusCode() >= 500) {
                throw $exception;
            }

            $errors = $result['errors'];
            $errors['general'] = $exception->getMessage();
            $this->renderFormWithState(true, $jobId, $result['form'], $errors);
        }
    }

    public function deleteAction(string $id): void
    {
        $this->requirePostMethod();
        $this->ensureValidCsrfToken();

        $jobId = (int) $id;
        $this->jobService->deleteJob($jobId);

        $this->redirect(\url('jobs'), ['success' => 'Oferta eliminada correctamente.']);
    }

    private function renderFormWithState(bool $isEdit, ?int $jobId, array $form, array $errors): void
    {
        $title = $isEdit ? 'Editar oferta' : 'Publicar nueva oferta';

        $this->renderHTML('jobs/form', [
            'title' => $title,
            'isEdit' => $isEdit,
            'jobId' => $jobId,
            'errors' => $errors,
            'form' => array_merge($this->defaultFormData(), $form),
            'categories' => $this->categoryService->listCategories(),
        ]);
    }

    private function defaultFormData(): array
    {
        return [
            'category_id' => 0,
            'type' => '',
            'company' => '',
            'logo' => '',
            'url' => '',
            'position' => '',
            'location' => '',
            'description' => '',
            'how_to_apply' => '',
            'email' => '',
            'expires_at' => '',
            'public' => 1,
            'activated' => 1,
        ];
    }
}
