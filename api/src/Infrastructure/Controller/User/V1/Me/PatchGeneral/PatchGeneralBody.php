<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\Me\PatchGeneral;

use App\Domain\User\ValueObject\LocaleEnum;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class PatchGeneralBody
{
    #[OA\Property(type: 'string', maxLength: 50, minLength: 1, example: 'supernickname')]
    #[Assert\Type('string')]
    public $nickname;

    #[OA\Property(type: 'string')]
    #[Assert\Choice(LocaleEnum::CASES)]
    public $locale;

    #[OA\Property(type: 'boolean')]
    #[Assert\Type('boolean')]
    public $ads;
}
