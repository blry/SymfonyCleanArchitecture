<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Stream\V1\Streamers\Me\UploadAvatar;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class UploadAvatarFiles
{
    /**
     * Streamer avatar
     * @var \stdClass|null
     */
    #[OA\Property(description: 'Avatar', type: 'string', format: 'binary')]
    #[Assert\NotBlank]
    #[Assert\Image(
        minWidth: 500,
        maxWidth: 1600,
        maxHeight: 1600,
        minHeight: 500,
        maxRatio: 1.2,
        minRatio: 0.8
    )]
    public UploadedFile|null $avatar;
}
