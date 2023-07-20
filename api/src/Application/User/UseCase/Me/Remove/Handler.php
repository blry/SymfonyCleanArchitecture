<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\Remove;

use App\Application\Common\UseCase\HandlerInterface;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\User\UserRepositoryInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Handler implements HandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(Input $input): void
    {
        $user = $this->userRepository->findOrFail($input->getUserId());
        $this->em->remove($user);

        $this->em->flush();
    }
}
