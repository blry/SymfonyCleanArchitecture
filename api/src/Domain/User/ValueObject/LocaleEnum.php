<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Domain\Common\ValueObject\Enum;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class LocaleEnum extends Enum
{
    public const RUSSIAN = 'ru';
    public const ENGLISH = 'en';

    public const CASES = [
        self::RUSSIAN,
        self::ENGLISH,
    ];
}