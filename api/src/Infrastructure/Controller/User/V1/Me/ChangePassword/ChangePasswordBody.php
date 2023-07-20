<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\Me\ChangePassword;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class ChangePasswordBody
{
    #[OA\Property(type: 'string', format: 'password', maxLength: 180)]
    #[Assert\Type('string')]
    public $oldPassword;

    #[OA\Property(type: 'string', maxLength: 180)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public $newPassword;
}
