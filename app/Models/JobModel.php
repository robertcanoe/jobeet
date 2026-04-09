<?php

declare(strict_types=1);

namespace App\Models;

class JobModel extends DBAbstractModel
{
    public function listActive(int $limit = 20, int $offset = 0): array
    {
        $safeLimit = max(1, $limit);
        $safeOffset = max(0, $offset);

        $sql = "
            SELECT
                j.id,
                j.type,
                j.company,
                j.logo,
                j.url,
                j.position,
                j.location,
                j.description,
                j.how_to_apply,
                j.token,
                j.public,
                j.activated,
                j.email,
                j.expires_at,
                j.created_at,
                j.updated_at,
                j.category_id,
                c.name AS category_name
            FROM jobs j
            INNER JOIN categories c ON c.id = j.category_id
            WHERE j.public = 1
              AND j.activated = 1
              AND j.expires_at >= NOW()
            ORDER BY j.created_at DESC
            LIMIT {$safeLimit} OFFSET {$safeOffset}
        ";

        return $this->get_results_from_query($sql);
    }

    public function findActiveById(int $jobId): array
    {
        $sql = "
            SELECT
                j.id,
                j.type,
                j.company,
                j.logo,
                j.url,
                j.position,
                j.location,
                j.description,
                j.how_to_apply,
                j.token,
                j.public,
                j.activated,
                j.email,
                j.expires_at,
                j.created_at,
                j.updated_at,
                j.category_id,
                c.name AS category_name
            FROM jobs j
            INNER JOIN categories c ON c.id = j.category_id
            WHERE j.id = :id
              AND j.public = 1
              AND j.activated = 1
              AND j.expires_at >= NOW()
            LIMIT 1
        ";

        return $this->get_results_from_query($sql, [':id' => $jobId], true);
    }

    public function findByCategoryId(int $categoryId): array
    {
        $sql = "
            SELECT
                j.id,
                j.type,
                j.company,
                j.logo,
                j.url,
                j.position,
                j.location,
                j.description,
                j.how_to_apply,
                j.token,
                j.public,
                j.activated,
                j.email,
                j.expires_at,
                j.created_at,
                j.updated_at,
                j.category_id,
                c.name AS category_name
            FROM jobs j
            INNER JOIN categories c ON c.id = j.category_id
            WHERE j.category_id = :category_id
              AND j.public = 1
              AND j.activated = 1
              AND j.expires_at >= NOW()
            ORDER BY j.created_at DESC
        ";

        return $this->get_results_from_query($sql, [':category_id' => $categoryId]);
    }

    public function findById(int $jobId): array
    {
        $sql = 'SELECT * FROM jobs WHERE id = :id LIMIT 1';

        return $this->get_results_from_query($sql, [':id' => $jobId], true);
    }

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO jobs (
                type,
                company,
                logo,
                url,
                position,
                location,
                description,
                how_to_apply,
                token,
                public,
                activated,
                email,
                expires_at,
                category_id
            ) VALUES (
                :type,
                :company,
                :logo,
                :url,
                :position,
                :location,
                :description,
                :how_to_apply,
                :token,
                :public,
                :activated,
                :email,
                :expires_at,
                :category_id
            )
        ";

        $this->execute_single_query($sql, [
            ':type' => $data['type'] ?: null,
            ':company' => $data['company'],
            ':logo' => $data['logo'] ?: null,
            ':url' => $data['url'] ?: null,
            ':position' => $data['position'],
            ':location' => $data['location'],
            ':description' => $data['description'],
            ':how_to_apply' => $data['how_to_apply'] ?: null,
            ':token' => $data['token'],
            ':public' => (int) $data['public'],
            ':activated' => (int) $data['activated'],
            ':email' => $data['email'],
            ':expires_at' => $data['expires_at'],
            ':category_id' => (int) $data['category_id'],
        ]);

        return $this->lastInsertId();
    }

    public function update(int $jobId, array $data): bool
    {
        $sql = "
            UPDATE jobs
            SET
                type = :type,
                company = :company,
                logo = :logo,
                url = :url,
                position = :position,
                location = :location,
                description = :description,
                how_to_apply = :how_to_apply,
                public = :public,
                activated = :activated,
                email = :email,
                expires_at = :expires_at,
                category_id = :category_id,
                updated_at = NOW()
            WHERE id = :id
        ";

        $affectedRows = $this->execute_single_query($sql, [
            ':id' => $jobId,
            ':type' => $data['type'] ?: null,
            ':company' => $data['company'],
            ':logo' => $data['logo'] ?: null,
            ':url' => $data['url'] ?: null,
            ':position' => $data['position'],
            ':location' => $data['location'],
            ':description' => $data['description'],
            ':how_to_apply' => $data['how_to_apply'] ?: null,
            ':public' => (int) $data['public'],
            ':activated' => (int) $data['activated'],
            ':email' => $data['email'],
            ':expires_at' => $data['expires_at'],
            ':category_id' => (int) $data['category_id'],
        ]);

        return $affectedRows > 0;
    }

    public function delete(int $jobId): bool
    {
        $sql = 'DELETE FROM jobs WHERE id = :id';

        return $this->execute_single_query($sql, [':id' => $jobId]) > 0;
    }
}
