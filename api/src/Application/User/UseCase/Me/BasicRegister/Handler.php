<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\BasicRegister;

use App\Application\Common\UseCase\HandlerInterface;
use App\Domain\Common\DomainException;
use App\Domain\Common\Service\ByteStringInterface;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\Common\Service\IdGeneratorInterface;
use App\Domain\User\Service\PasswordHasherServiceInterface;
use App\Domain\User\User;
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
        private IdGeneratorInterface $idGenerator,
        private ByteStringInterface $byteString
    ) {}

    public function __invoke(Input $input): Output
    {
        if (!$this->userRepo->isNicknameAvailable($input->getNickname())) {
            throw new DomainException('user-unavailable-nickname', 'Nickname is already in use');
        }

        if (!$this->userRepo->isEmailAvailable($input->getEmail(), true)) {
            throw new DomainException('user-unavailable-email', 'Email is already in use');
        }

        $user = new User($this->idGenerator->id(), $input->getNickname(), $input->getLocale());
        $user->updateEmail($input->getEmail(), $this->byteString);
        $user->setPassword($this->passwordHasher->hashPassword($user, $input->getPassword()));
        $user->setAds($input->getAds());

        $this->em->persist($user);
        $this->em->flush();

        return new Output($user->getUserIdentifier());
    }
}
