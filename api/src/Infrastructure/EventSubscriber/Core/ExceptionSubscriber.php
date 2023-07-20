<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber\Core;

use Doctrine\ORM\EntityNotFoundException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Log\LoggerInterface;
use App\Domain\Common\DomainException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use League\Bundle\OAuth2ServerBundle\Security\Exception\InsufficientScopesException;
use League\Bundle\OAuth2ServerBundle\Security\Exception\Oauth2AuthenticationFailedException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private bool $debug,
        private LoggerInterface $logger
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [['onKernelException', 250]],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if ($e instanceof HandlerFailedException || $e->getPrevious() instanceof ValidationFailedException) {
            $e = $e->getPrevious() ?? $e;
        }

        if ($this->debug && $event->getRequest()->getPreferredFormat('json') === 'html') {
            throw $e;
        }

        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $data = ['error' => 'core.http_' . $statusCode, 'message' => Response::$statusTexts[$statusCode]];
        } elseif ($e instanceof ValidationFailedException) {
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data = [
                'error' => 'core.http_' . $statusCode,
                'message' => Response::$statusTexts[$statusCode],
                'details' => $this->transformViolations($e->getViolations()),
            ];
        } elseif ($e instanceof AccessDeniedException) {
            $statusCode = $e->getCode();
            $data = ['error' => 'core.access_denied', 'message' => $e->getMessage()];
        } elseif ($e instanceof DomainException) {
            $statusCode = Response::HTTP_BAD_REQUEST;
            $data = ['error' => $e->getErrorCode(), 'message' => $e->getMessage()];
        } elseif ($e instanceof EntityNotFoundException) {
            $statusCode = Response::HTTP_NOT_FOUND;
            $data = ['error' => 'core.entity_not_found', 'message' => $e->getMessage()];
        } elseif ($e instanceof OAuthServerException) {
            $statusCode = $e->getHttpStatusCode();
            $data = [
                'error' => 'core.oauth2_'  . $e->getErrorType(),
                'message' => $e->getMessage(),
                'details' => $e->getHint() ?? false,
            ];
        } elseif ($e instanceof Oauth2AuthenticationFailedException ||
            $e instanceof InsufficientScopesException ||
            $e instanceof IdentityProviderException
        ) {
            $statusCode = $e->getCode();
            $errors = [
                Response::HTTP_UNAUTHORIZED => 'core.oauth2_invalid_token',
                Response::HTTP_FORBIDDEN => 'oauth2_insufficient_scopes',
            ];
            $data = [
                'error' => $errors[$statusCode] ?? 'core.oauth2_' . $statusCode,
                'message' => $e->getMessage(),
                'details' => false,
            ];
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $data = [
                'error' => 'core.internal',
                'message' => $e->getMessage()
            ];

            if (!$this->debug) {
                $this->logger->error(get_class($e) . "\n{$e->getMessage()}\n" . $e->getTraceAsString());
            }
        }

        if (!isset($data['details'])) {
            $data['class'] = get_class($e);
            $data['trace'] = $e->getTraceAsString();
        }

        $event->setResponse(new JsonResponse($data, $statusCode));
    }

    private function transformViolations(ConstraintViolationListInterface $errors): array {
        $data = [];

        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $propertyPath = $error->getPropertyPath();
            if (!isset($data[$propertyPath])) {
                $data[$propertyPath] = [];
            }

            $data[$propertyPath][] = ['code' => $error->getCode(), 'message' => $error->getMessage()];
        }

        return $data;
    }
}
