<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\OAuth2\GetToken;

use App\Domain\User\ValueObject\ProviderEnum;
use App\Infrastructure\OAuth2\ScopeEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class GetTokenBody
{
    #[OA\Property(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Choice(["password", "social", "client_credentials", "code", "refresh_token", "token"])]
    public $grant_type;

    #[OA\Property(type: 'string', example: 'd92b93967a5b7082a42dd6ec6d798235')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(exactly: 32)]
    public $client_id;

    #[OA\Property(type: 'string', format: 'password')]
    #[Assert\Type('string')]
    #[Assert\Length(min: 100, max: 128)]
    public $client_secret;

    #[OA\Property(type: 'array', items: new OA\Items(type: 'string', enum: ScopeEnum::AVAILABLE), nullable: true)]
    //#[Assert\Choice(ScopeEnum::AVAILABLE, multiple: true)]
    //#[Assert\Unique]
    public $scope;

    #[OA\Property(description: 'Only for password grant', type: 'string', format: 'email')]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3)]
    public $username;

    #[OA\Property(description: 'Only for password grant', type: 'string', format: 'password')]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3)]
    public $password;

    #[OA\Property(description: 'Only for social grant', type: 'string')]
    #[Assert\Choice(ProviderEnum::CASES)]
    public $provider;

    #[OA\Property(description: 'Only for social grant', type: 'string')]
    #[Assert\Type('string')]
    #[Assert\Length(min: 10)]
    public $code;

    #[OA\Property(description: 'Only for refresh_token grant', type: 'string')]
    #[Assert\Type('string')]
    #[Assert\Length(min: 100)]
    public $refresh_token;
}
