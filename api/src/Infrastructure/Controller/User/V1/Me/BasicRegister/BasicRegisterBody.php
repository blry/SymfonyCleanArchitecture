<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\Me\BasicRegister;

use App\Domain\User\ValueObject\LocaleEnum;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class BasicRegisterBody
{
    #[OA\Property(type: 'string', maxLength: 50, minLength: 1, example: 'supernickname')]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public $nickname;

    #[OA\Property(type: 'string', format: 'password', maxLength: 180)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public $password;

    #[OA\Property(type: 'string', format: 'email', maxLength: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    public $email;

    #[OA\Property(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Choice(LocaleEnum::CASES)]
    public $locale;

    #[OA\Property(type: 'boolean')]
    #[Assert\Type('boolean')]
    #[Assert\NotBlank]
    public $ads;
}
