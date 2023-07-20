<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\PatchGeneral;

use App\Application\Common\UseCase\HandlerInterface;
use App\Domain\Common\DomainException;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\User\UserRepositoryInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Handler implements HandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepositoryInterface $userRepo
    ) {}

    public function __invoke(Input $input)
    {
        $user = $this->userRepo->findOrFail($input->getUserId());

        $newNickname = $input->getNickname();
        if ($newNickname) {
            if ($user->getNickname() !== $newNickname && !$this->userRepo->isNicknameAvailable($newNickname)) {
                throw new DomainException('user-unavailable-nickname', 'Nickname is already in use');
            }

            $user->setNickname($newNickname);
        }

        if ($input->getLocale()) {
            $user->setLocale($input->getLocale());
        }

        if ($input->getAds() !== null) {
            $user->setAds($input->getAds());
        }

        $this->em->flush();
    }
}
