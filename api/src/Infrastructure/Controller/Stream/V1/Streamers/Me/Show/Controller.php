<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Stream\V1\Streamers\Me\Show;

use App\Domain\Stream\StreamerRepositoryInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use App\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Domain\Stream\Streamer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
#[OA\Tag(name: 'stream')]
class Controller extends BaseController
{
    public function __construct(
        private StreamerRepositoryInterface $streamerRepo
    ) {}

    /**
     * Show streamer
     */
    #[Security(name: 'OAuth2')]
    #[OA\Response(
        response: 200,
        description: 'Streamer model',
        content: new Model(type: Streamer::class, groups: ['basic', 'full'])
    )]
    #[Route('/stream/v1/streamers/me', name: 'stream.v1.streamers.me', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: 'You must be logged in.')]
    public function __invoke(
        #[CurrentUser] UserInterface $user
    ): Response
    {
        $streamer = $this->streamerRepo->findOrFail($user->getUserIdentifier());

        return $this->json($streamer, 200, [], ['groups' => ['basic', 'full']]);
    }
}
