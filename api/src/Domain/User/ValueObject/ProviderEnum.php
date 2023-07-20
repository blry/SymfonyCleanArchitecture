<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Domain\Common\ValueObject\Enum;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class ProviderEnum extends Enum
{
    public const TELEGRAM = 'telegram';
    // public const FACEBOOK = 'facebook';

    public const CASES = [
        self::TELEGRAM,
        // self::FACEBOOK,
    ];
}
