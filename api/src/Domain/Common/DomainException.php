<?php

declare(strict_types=1);

namespace App\Domain\Common;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class DomainException extends \RuntimeException
{
    public const CODE = 1000;

    public function __construct(
        private string $errorCode,
        string $message,
        \Throwable $previous = null
    ) {
        parent::__construct($message, static::CODE, $previous);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
