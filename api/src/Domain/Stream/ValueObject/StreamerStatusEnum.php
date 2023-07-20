<?php

declare(strict_types=1);

namespace App\Domain\Stream\ValueObject;

use App\Domain\Common\ValueObject\Enum;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class StreamerStatusEnum extends Enum
{
    public const PENDING_USER_ACTIVATION = 'user_inactive';
    public const PENDING_INFO            = 'pending_info';
    public const PENDING_REVIEW          = 'pending_review';
    public const DECLINED                = 'declined';
    public const ACCEPTED                = 'accepted';
    public const BANNED                  = 'banned';

    public const CASES = [
        self::PENDING_USER_ACTIVATION,
        self::PENDING_INFO,
        self::PENDING_REVIEW,
        self::DECLINED,
        self::ACCEPTED,
        self::BANNED,
    ];
}