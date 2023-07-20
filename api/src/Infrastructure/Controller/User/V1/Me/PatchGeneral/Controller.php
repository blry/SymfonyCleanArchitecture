<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\Me\PatchGeneral;

use Nelmio\ApiDocBundle\Annotation\Model;
use App\Application\User\UseCase\Me\PatchGeneral\Input;
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
     * Patch general profile details
     */
    #[Security(name: 'OAuth2')]
    #[OA\RequestBody(
        description: 'Payload',
        content: new Model(type: PatchGeneralBody::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new Model(type: SuccessJsonResponse::class, groups: ['basic'])
    )]
    #[Route('/user/v1/me/general', name: 'user.v1.me.general', methods: ['PATCH'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: 'You must be logged in.')]
    public function __invoke(
        #[MapRequestPayload] PatchGeneralBody $dto,
        #[CurrentUser] UserInterface $user
    ): Response
    {
        $this->dispatchMessage(new Input($user->getUserIdentifier(), $dto->nickname, $dto->locale, $dto->ads));

        return new SuccessJsonResponse();
    }
}
