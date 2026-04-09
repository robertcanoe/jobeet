<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\DatabaseException;
use PDO;
use PDOException;

abstract class DBAbstractModel
{
    private static ?PDO $connection = null;

    protected function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            (string) \env('DB_HOST', '127.0.0.1'),
            (string) \env('DB_PORT', '3306'),
            (string) \env('DB_NAME', ''),
            (string) \env('DB_CHARSET', 'utf8mb4')
        );

        try {
            self::$connection = new PDO(
                $dsn,
                (string) \env('DB_USER', ''),
                (string) \env('DB_PASS', ''),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            $dbException = DatabaseException::fromPDOException(
                $exception,
                'No se pudo establecer conexion PDO con la base de datos'
            );
            $dbException->log();
            throw $dbException;
        }

        return self::$connection;
    }

    protected function execute_single_query(string $sql, array $params = []): int
    {
        try {
            $statement = $this->getConnection()->prepare($sql);
            $statement->execute($params);

            return $statement->rowCount();
        } catch (PDOException $exception) {
            $dbException = DatabaseException::fromPDOException($exception, 'Fallo execute_single_query');
            $dbException->log();
            throw $dbException;
        }
    }

    protected function get_results_from_query(string $sql, array $params = [], bool $single = false): array
    {
        try {
            $statement = $this->getConnection()->prepare($sql);
            $statement->execute($params);

            if ($single) {
                $row = $statement->fetch();

                return is_array($row) ? $row : [];
            }

            $rows = $statement->fetchAll();

            return is_array($rows) ? $rows : [];
        } catch (PDOException $exception) {
            $dbException = DatabaseException::fromPDOException($exception, 'Fallo get_results_from_query');
            $dbException->log();
            throw $dbException;
        }
    }

    protected function beginTransaction(): bool
    {
        return $this->getConnection()->beginTransaction();
    }

    protected function commit(): bool
    {
        return $this->getConnection()->commit();
    }

    protected function rollback(): bool
    {
        return $this->getConnection()->rollBack();
    }

    protected function lastInsertId(): int
    {
        return (int) $this->getConnection()->lastInsertId();
    }
}
