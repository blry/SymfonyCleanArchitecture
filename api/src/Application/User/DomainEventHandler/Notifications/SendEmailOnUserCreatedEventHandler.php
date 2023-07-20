<?php

declare(strict_types=1);

namespace App\Application\User\DomainEventHandler\Notifications;

use App\Application\Common\DomainEventHandler\HandlerInterface;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\User\Event\UserCreatedEvent;
use App\Domain\User\Service\MailerServiceInterface;
use App\Domain\User\User;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class SendEmailOnUserCreatedEventHandler implements HandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerServiceInterface $mailer,
    ) {}

    public function __invoke(UserCreatedEvent $event): void
    {
        $user = $this->em->find(User::class, $event->getUserId());
        if (!$user || (!$user->getEmail() && !$user->getUnconfirmedEmail())) {
            return;
        }

        if ($user->getEmail()) {
            // If user's email is already confirmed
            $this->mailer->welcome($user);
        } else {
            $this->mailer->signupConfirmationRequest($user);
        }
    }
}
