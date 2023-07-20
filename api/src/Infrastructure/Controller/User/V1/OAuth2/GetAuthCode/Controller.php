<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User\V1\OAuth2\GetAuthCode;

use League\Bundle\OAuth2ServerBundle\Converter\UserConverterInterface;
use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEvent;
use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEventFactory;
use League\Bundle\OAuth2ServerBundle\OAuth2Events;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestEvent;
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
        private EventDispatcherInterface $eventDispatcher,
        private AuthorizationRequestResolveEventFactory $eventFactory,
        private UserConverterInterface $userConverter
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
    #[Route('authorize', name: 'authorize', methods: ['GET', 'POST'])]
    public function __invoke(ServerRequestInterface $serverRequest, ResponseFactoryInterface $responseFactory): ResponseInterface
    {
        $authRequest = $this->server->validateAuthorizationRequest($serverRequest);

        /** @var AuthorizationRequestResolveEvent $event */
        $event = $this->eventDispatcher->dispatch(
            $this->eventFactory->fromAuthorizationRequest($authRequest),
            OAuth2Events::AUTHORIZATION_REQUEST_RESOLVE
        );

        $authRequest->setUser($this->userConverter->toLeague($event->getUser()));

        if ($event->getAuthorizationResolution()) {
            return $event->getResponse();
        }

        $authRequest->setAuthorizationApproved($event->getAuthorizationResolution());
        $serverResponse = $responseFactory->createResponse();

        return $this->server->completeAuthorizationRequest($authRequest, $serverResponse);
    }
}
