<?php

declare(strict_types=1);

namespace App\Application\User\DomainEventHandler\Notifications;

use App\Application\Common\DomainEventHandler\HandlerInterface;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\User\Event\UserEmailConfirmedEvent;
use App\Domain\User\Service\MailerServiceInterface;
use App\Domain\User\User;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class SendEmailOnEmailConfirmedEventHandler implements HandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerServiceInterface $mailer
    ) {}

    public function __invoke(UserEmailConfirmedEvent $event): void
    {
        $user = $this->em->find(User::class, $event->getUserId());
        if (!$user || !$event->getOldEmail()) {
            return;
        }

        $this->mailer->emailConfirmed($user, $event->getOldEmail());
    }
}
