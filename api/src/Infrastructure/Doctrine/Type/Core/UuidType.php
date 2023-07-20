<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type\Core;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use App\Domain\Common\ValueObject\Uuid;

/**
 * Doctine Uuid type
 *
 * @author Alexandru Sterpu <alexander.sterpU@gmail.com>
 */
class UuidType extends GuidType
{
    public const NAME = 'uuid';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Uuid ? (string) $value : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        return !empty($value) ? new Uuid($value) : null;
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}
