<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\Me\ConfirmEmail;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class ConfirmEmailBody
{
    #[OA\Property(type: 'string', maxLength: 180)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public $activationCode;
}
