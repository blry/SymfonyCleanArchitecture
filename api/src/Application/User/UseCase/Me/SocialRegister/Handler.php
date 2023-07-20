<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\SocialRegister;

use App\Application\Common\UseCase\HandlerInterface;
use App\Domain\Common\DomainException;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\Common\Service\IdGeneratorInterface;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\LocaleEnum;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Handler implements HandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepositoryInterface $userRepo,
        private IdGeneratorInterface $idGenerator
    ) {}

    public function __invoke(Input $input): Output
    {
        $user = $this->userRepo->findOneByProvider($input->getProvider(), $input->getProviderResourceId());
        if ($user) {
            throw new DomainException('user-already-exists', sprintf('You have already been registered!'));
        }

        $id = $this->idGenerator->id();
        $nickname = $input->getNickname() && $this->userRepo->isNicknameAvailable($input->getNickname()) ?
            $input->getNickname() : $id;

        $user = new User($id, $nickname, LocaleEnum::RUSSIAN);
        $user->setProviderResourceId($input->getProvider(), $input->getProviderResourceId());

        $this->em->persist($user);
        $this->em->flush();

        return new Output($user->getUserIdentifier());
    }
}
