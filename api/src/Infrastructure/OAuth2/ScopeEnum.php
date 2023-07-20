<?php

declare(strict_types=1);

namespace App\Infrastructure\OAuth2;

use App\Domain\Common\ValueObject\Enum;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class ScopeEnum extends Enum
{
    public const PUBLIC = 'public';
    public const OFFLINE_ACCESS = 'offline_access';

    public const AVAILABLE = [
        self::PUBLIC,
        self::OFFLINE_ACCESS,
    ];
    public const DEFAULT = [
        self::PUBLIC
    ];

    public const CASES = self::AVAILABLE;
}
