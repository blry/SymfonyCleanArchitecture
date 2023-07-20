<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\ChangeEmail;

use App\Application\Common\UseCase\HandlerInterface;
use App\Domain\Common\DomainException;
use App\Domain\Common\Service\ByteStringInterface;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\User\Service\PasswordHasherServiceInterface;
use App\Domain\User\UserRepositoryInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Handler implements HandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepositoryInterface $userRepo,
        private PasswordHasherServiceInterface $passwordHasher,
        private ByteStringInterface $byteString
    ) {}

    public function __invoke(Input $input)
    {
        if (!$this->userRepo->isEmailAvailable($input->getEmail())) {
            throw new DomainException('user-unavailable-email', 'Email is already in use');
        }

        $user = $this->userRepo->findOrFail($input->getUserId());
        if (!$this->passwordHasher->isPasswordValid($user, $input->getPassword())) {
            throw new DomainException('user-wrong-password', 'Wrong user password');
        }

        $user->updateEmail($input->getEmail(), $this->byteString);

        $this->em->flush();
    }
}
