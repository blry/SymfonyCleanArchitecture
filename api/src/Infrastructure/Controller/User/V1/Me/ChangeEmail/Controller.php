<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\Me\ChangeEmail;

use Nelmio\ApiDocBundle\Annotation\Model;
use App\Application\User\UseCase\Me\ChangeEmail\Input;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use App\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use App\Infrastructure\Controller\SuccessJsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
#[OA\Tag(name: 'user')]
class Controller extends BaseController
{
    /**
     * Change my email
     */
    #[Security(name: 'OAuth2')]
    #[OA\RequestBody(
        description: 'Payload',
        content: new Model(type: ChangeEmailBody::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new Model(type: SuccessJsonResponse::class, groups: ['basic'])
    )]
    #[Route('/user/v1/me/email', name: 'user.v1.me.email', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: 'You must be logged in.')]
    public function __invoke(
        #[MapRequestPayload] ChangeEmailBody $dto,
        #[CurrentUser] UserInterface $user
    ): Response
    {
        $this->dispatchMessage(new Input($user->getUserIdentifier(), $dto->email, $dto->password));

        return new SuccessJsonResponse();
    }
}
