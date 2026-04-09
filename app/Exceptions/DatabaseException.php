<?php

declare(strict_types=1);

namespace App\Exceptions;

use PDOException;
use RuntimeException;

class DatabaseException extends RuntimeException
{
    public static function fromPDOException(PDOException $exception, string $context = ''): self
    {
        $message = $context !== ''
            ? $context . ': ' . $exception->getMessage()
            : $exception->getMessage();

        return new self($message, (int) $exception->getCode(), $exception);
    }

    public function log(): void
    {
        error_log('[DatabaseException] ' . $this->getMessage());
    }
}
