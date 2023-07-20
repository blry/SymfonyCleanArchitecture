<?php

declare(strict_types=1);

namespace App\Application\Stream\IntegrationEventHandler\User;

use App\Application\Common\IntegrationEvent\User\UserActivatedIntegrationEvent;
use App\Application\Common\IntegrationEventHandler\HandlerInterface;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\Stream\StreamerRepositoryInterface;
use App\Domain\Stream\ValueObject\StreamerStatusEnum;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class ActivateStreamerOnUserActivatedEventHandler implements HandlerInterface
{
    public function __construct(
        private StreamerRepositoryInterface $streamerRepo,
        private EntityManagerInterface $em,
    ) {}

    public function __invoke(UserActivatedIntegrationEvent $event): void
    {
        $streamer = $this->streamerRepo->findOrFail($event->getUserId());

        if ($streamer->getStatus() === StreamerStatusEnum::PENDING_USER_ACTIVATION) {
            $streamer->setStatus(StreamerStatusEnum::PENDING_INFO);
        }

        $this->em->flush();
    }
}
