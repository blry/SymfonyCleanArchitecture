<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\Me\ChangeEmail;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class ChangeEmailBody
{
    #[OA\Property(type: 'string', format: 'email', maxLength: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    public $email;

    #[OA\Property(type: 'string', format: 'password', maxLength: 180)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public $password;
}
