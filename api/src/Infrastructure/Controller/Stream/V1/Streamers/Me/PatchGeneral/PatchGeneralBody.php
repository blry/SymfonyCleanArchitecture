<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Stream\V1\Streamers\Me\PatchGeneral;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class PatchGeneralBody
{
    /**
     * Desirable nickname in streaming services
     */
     #[OA\Property(type: 'string', maxLength: 20, example: 'supernickname')]
     #[Assert\Type('string')]
    public $nickname;
}
