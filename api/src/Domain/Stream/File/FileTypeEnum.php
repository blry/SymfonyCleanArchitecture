<?php

declare(strict_types=1);

namespace App\Domain\Stream\File;

use App\Domain\Common\ValueObject\Enum;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class FileTypeEnum extends Enum
{
    public const DOCS_FRONT = 'docs_front';
    public const FACE = 'face';
    public const DOCS_FRONT_WITH_FACE = 'docs_front_with_face';
    public const AVATAR = 'avatar';
    public const PHOTO = 'photo';
    public const VIDEO = 'video';

    public const CASES = [
        self::DOCS_FRONT,
        self::FACE,
        self::DOCS_FRONT_WITH_FACE,
        self::AVATAR,
        self::PHOTO,
        self::VIDEO,
    ];
}