<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\OAuth2\GetToken;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestEvent;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
#[OA\Tag(name: 'user')]
#[Route('/user/v1/oauth2/', name: 'user.oauth2.')]
final class Controller
{
    public function __construct(
        private AuthorizationServer $server,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->server->getEmitter()->addListener(
            '*',
            function (RequestEvent $event) use ($eventDispatcher) {
                $eventDispatcher->dispatch($event, $event->getName());
            }
        );
    }

    /**
     * @throws OAuthServerException
     */
    #[OA\RequestBody(
        description: 'Payload',
        content: new Model(type: GetTokenBody::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Responses with an access_token and optionally refresh_token'
    )]
    #[Route('token', name: 'token', methods: ['POST'])]
    public function __invoke(
        ServerRequestInterface $serverRequest,
        ResponseFactoryInterface $responseFactory
    ): ResponseInterface {
        $serverResponse = $responseFactory->createResponse();

        return $this->server->respondToAccessTokenRequest($serverRequest, $serverResponse);
    }
}
