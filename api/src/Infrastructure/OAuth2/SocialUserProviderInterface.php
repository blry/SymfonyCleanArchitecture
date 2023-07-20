<?php

declare(strict_types=1);

namespace App\Infrastructure\OAuth2;

use App\Domain\User\User;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
interface SocialUserProviderInterface
{
    public function getUser(string $provider): ?User;
}
