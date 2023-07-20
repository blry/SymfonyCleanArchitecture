<?php

declare(strict_types=1);

namespace App\Application\Stream\IntegrationEventHandler\User;

use App\Application\Common\IntegrationEvent\User\UserRemovedIntegrationEvent;
use App\Application\Common\IntegrationEventHandler\HandlerInterface;
use App\Application\Stream\UseCase\Streamer\Remove\Input;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class RemoveStreamerOnUserRemovedEventHandler implements HandlerInterface
{
    public function __construct(private MessageBusInterface $bus) {}

    public function __invoke(UserRemovedIntegrationEvent $event): void
    {
        $this->bus->dispatch(new Input($event->getUserId()));
    }
}
