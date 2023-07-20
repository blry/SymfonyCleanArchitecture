<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Stream\V1\Streamers\Me\UploadAvatar;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use App\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Infrastructure\Controller\SuccessJsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
#[OA\Tag(name: 'stream')]
class Controller extends BaseController
{
    /**
     * Upload avatar
     */
    #[Security(name: 'OAuth2')]
    #[OA\RequestBody(
        new OA\MediaType(
            'multipart/form-data',
            new OA\Schema(new Model(type: UploadAvatarFiles::class))
        ),
        description: 'Payload'
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new Model(type: SuccessJsonResponse::class, groups: ['basic'])
    )]
    #[Route('/stream/v1/streamers/me/avatar', name: 'stream.v1.streamers.me.avatar', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: 'You must be logged in.')]
    public function __invoke(
        Request $request,
        #[CurrentUser] UserInterface $user
    ): Response
    {
        /** @var UploadAvatarFiles $dto */
        $dto = $this->denormalize($request->files->all(), UploadAvatarFiles::class);
        $this->validate($dto);

        if ($dto->avatar) {
            $dto->avatar->move($dto->avatar->getPath()); // disable file remove if async
        }

        return new SuccessJsonResponse();
    }
}
