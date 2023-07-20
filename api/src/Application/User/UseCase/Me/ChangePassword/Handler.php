<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\ChangePassword;

use App\Application\Common\UseCase\HandlerInterface;
use App\Domain\Common\Service\Assert;
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
        private UserRepositoryInterface $userRepository,
        private PasswordHasherServiceInterface $passwordHasher
    ) {}

    public function __invoke(Input $input)
    {
        $user = $this->userRepository->findOrFail($input->getUserId());

        Assert::true(
            (!$input->getCurrentPassword() && !$user->getPassword()) ||
                $this->passwordHasher->isPasswordValid($user, $input->getCurrentPassword()),
            'Invalid old password'
        );

        $user->setPassword($this->passwordHasher->hashPassword($user, $input->getNewPassword()));

        $this->em->flush();
    }
}
