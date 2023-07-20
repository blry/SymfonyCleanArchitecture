<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Stream\V1\Streamers\Me\PatchGeneral;

use App\Infrastructure\Controller\BaseController;
use App\Infrastructure\Controller\SuccessJsonResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Application\Stream\UseCase\Streamer\Me\PatchGeneral\Input;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
#[OA\Tag(name: 'stream')]
class Controller extends BaseController
{
    /**
     * Patch general streamer details
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
    #[Route('/stream/v1/streamers/me/general', name: 'stream.v1.streamers.me.general', methods: ['PATCH'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: 'You must be logged in.')]
    public function __invoke(
        #[MapRequestPayload] PatchGeneralBody $dto,
        #[CurrentUser] UserInterface $user
    ): Response
    {
        $this->dispatchMessage(new Input(
            $user->getUserIdentifier(),
            $dto->nickname,
        ));

        return new SuccessJsonResponse();
    }
}
