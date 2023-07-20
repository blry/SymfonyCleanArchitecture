<?php

declare(strict_types=1);

namespace App\Application\User\DomainEventHandler\Notifications;

use App\Application\Common\DomainEventHandler\HandlerInterface;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\User\Event\UserEmailUpdateStartedEvent;
use App\Domain\User\Service\MailerServiceInterface;
use App\Domain\User\User;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class SendEmailOnUserEmailUpdateStartedEventHandler implements HandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerServiceInterface $mailer
    ) {}

    /**
     * @throws \Throwable
     */
    public function __invoke(UserEmailUpdateStartedEvent $event): void
    {
        $user = $this->em->find(User::class, $event->getUserId());
        if (!$user) {
            return;
        }

        $this->mailer->emailUpdateStarted($user);
    }
}
