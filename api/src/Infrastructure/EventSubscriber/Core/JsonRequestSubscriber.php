<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber\Core;

use App\Domain\Common\DomainException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * If the request has Content-Type which equals to JSON, decodes request content to array.
 *
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class JsonRequestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    /**
     * @param RequestEvent $event
     *
     * @throws DomainException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $this->determineFormat($request);

        if ('json' === $request->getContentTypeFormat()) {
            $request->request->replace();
            $this->transformRequest($request);
        }
    }

    private function determineFormat(Request $request): void
    {
        $accepts = $request->getAcceptableContentTypes();
        if (array_intersect(['application/json', 'text/plain'], $accepts)) {
            $request->attributes->set('_format', 'json');
            $request->setRequestFormat('json');
        } else {
            $request->setRequestFormat('html');
        }
    }

    /**
     * @throws DomainException
     */
    private function transformRequest(Request $request): void
    {
        $content = $request->getContent();
        if (empty($content)) {
            return;
        }

        $data = json_decode($content, true, 256);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new DomainException('core.http_invalid_json', json_last_error_msg());
        }

        if (is_array($data)) {
            $request->request->replace($data);
        }
    }
}
