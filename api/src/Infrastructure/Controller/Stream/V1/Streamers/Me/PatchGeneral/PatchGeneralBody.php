<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Stream\V1\Streamers\Me\PatchGeneral;

use App\Domain\Stream\ValueObject\CountryEnum;
use App\Domain\Stream\ValueObject\LanguageEnum;
use App\Domain\Stream\ValueObject\StreamerCategoryEnum;
use App\Domain\Stream\ValueObject\StreamerTagEnum;
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
