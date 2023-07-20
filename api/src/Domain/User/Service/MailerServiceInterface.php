<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\User;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
interface MailerServiceInterface
{
    /**
     * @throws \Throwable
     */
    public function welcome(User $user): void;

    /**
     * @throws \Throwable
     */
    public function signupConfirmationRequest(User $user): void;

    /**
     * @throws \Throwable
     */
    public function emailConfirmed(User $user, string $oldEmail): void;

    /**
     * @throws \Throwable
     */
    public function emailUpdateStarted(User $user): void;
}