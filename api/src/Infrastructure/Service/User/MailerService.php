<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\User;

use App\Domain\User\Service\MailerServiceInterface;
use App\Domain\User\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class MailerService implements MailerServiceInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function welcome(User $user): void
    {
        $subject = sprintf('Welcome to %s!', $_ENV['BRAND']);
        $template = 'emails/user/signup.html.twig';
        $context = ['user' => $user];

        $this->send($subject, $template, $context, [
            new Address($user->getEmail(), $user->getNickname())
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function signupConfirmationRequest(User $user): void
    {
        $subject = 'Please, confirm your email';
        $template = 'emails/user/signup-confirmation.html.twig';
        $context = [
            'user' => $user,
            'emailConfirmationLink' => sprintf('%s/emailConfirmation/%s', $_ENV['FRONTEND_URL'], $user->getActivationCode()),
        ];

        $this->send($subject, $template, $context, [
            new Address($user->getUnconfirmedEmail(), $user->getNickname())
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function emailConfirmed(User $user, string $oldEmail): void
    {
        $subject = 'Your email was updated';
        $template = 'emails/user/email-changed.html.twig';
        $context = ['user' => $user];

        $this->send($subject, $template, $context, [
            new Address($oldEmail, $user->getNickname()),
            new Address($user->getEmail(), $user->getNickname()),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function emailUpdateStarted(User $user): void
    {
        $subject = 'Please, confirm your email';
        $template = 'emails/user/email-confirmation.html.twig';
        $context = [
            'user' => $user,
            'emailConfirmationLink' => sprintf('%s/emailConfirmation/%s', $_ENV['FRONTEND_URL'], $user->getActivationCode()),
        ];

        $this->send($subject, $template, $context, [
            new Address($user->getUnconfirmedEmail(), $user->getNickname()),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function send($subject, $template, $context, array $addresses): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($_ENV['NOTIFICATION_SENDER'], $_ENV['BRAND']))
            ->to(...$addresses)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context);

        $this->mailer->send($email);
    }
}