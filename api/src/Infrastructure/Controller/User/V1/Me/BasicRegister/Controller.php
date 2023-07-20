<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\Me\BasicRegister;

use Nelmio\ApiDocBundle\Annotation\Model;
use App\Application\User\UseCase\Me\BasicRegister\Input;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use App\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use App\Infrastructure\Controller\SuccessJsonResponse;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
#[OA\Tag(name: 'user')]
class Controller extends BaseController
{
    /**
     * General register
     */
    #[Security(name: 'OAuth2')]
    #[OA\RequestBody(
        description: 'Payload',
        content: new Model(type: BasicRegisterBody::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new Model(type: SuccessJsonResponse::class, groups: ['basic'])
    )]
    #[Route('/user/v1/register', name: 'user.v1.register', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] BasicRegisterBody $dto): Response
    {
        $this->dispatchMessage(new Input($dto->nickname, $dto->password, $dto->email, $dto->locale, $dto->ads));

        return new SuccessJsonResponse();
    }
}
