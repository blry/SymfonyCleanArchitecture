<?php

declare(strict_types=1);

namespace App\Domain\Common\Service;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
interface ByteStringInterface extends \Stringable
{
    public static function fromRandom(int $length = 16, string $alphabet = null): self;

    /**
     * @throw DomainException
     */
    public function toString(): string;
}
