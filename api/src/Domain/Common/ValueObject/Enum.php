<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use App\Domain\Common\DomainException;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
abstract class Enum
{
    public const CASES = [];

    private function __construct(
        private $value
    ) {}

    public function getValue() {
        return $this->value;
    }

    public static function tryFrom($value): static|null
    {
        return in_array($value, static::CASES, true) ? new static($value) : null;
    }

    /**
     * @throw DomainException
     */
    public static function from($value): static
    {
        if (in_array($value, static::CASES, true)) {
            return new static($value);
        }

        throw new DomainException('domain_error', sprintf(
            'Value %s is not supported. Please use one of %s',
            $value,
            implode(', ', static::CASES)
        ));
    }

    public static function cases(): array
    {
        return static::CASES;
    }
}
