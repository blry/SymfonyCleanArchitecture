<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\Me\Show;

use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use App\Infrastructure\Controller\BaseController;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Domain\User\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
#[OA\Tag(name: 'user')]
class Controller extends BaseController
{
    /**
     * My profile
     */
    #[Security(name: 'OAuth2')]
    #[OA\Response(
        response: 200,
        description: 'User model',
        content: new Model(type: User::class, groups: ['basic', 'full'])
    )]
    #[Route('/user/v1/me', name: 'user.v1.me', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: 'You must be logged in.')]
    public function __invoke(
        #[CurrentUser] UserInterface $user
    ): Response
    {
        return $this->json($user, 200, [], ['groups' => ['basic', 'full']]);
    }
}
