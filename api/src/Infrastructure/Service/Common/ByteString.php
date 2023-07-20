<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Common;

use App\Domain\Common\DomainException;
use App\Domain\Common\Service\ByteStringInterface;
use Symfony\Component\String\ByteString as SymfonyByteString;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class ByteString extends SymfonyByteString implements ByteStringInterface
{
    public static function fromRandom(int $length = 16, string $alphabet = null): self
    {
        return parent::fromRandom($length, $alphabet);
    }

    public function toString(): string
    {
        if ($this->string === '') {
            throw new DomainException('domain_error', 'Empty string');
        }

        return $this->string;
    }
}