<?php

declare(strict_types=1);

namespace App\Application\Stream\UseCase\Streamer\Create;

use App\Application\Common\UseCase\HandlerInterface;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\Stream\Streamer;
use App\Domain\Stream\ValueObject\StreamerStatusEnum;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Handler implements HandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ManagerRegistry $registry
    ) {}

    public function __invoke(Input $input)
    {
        $streamer = new Streamer($input->getStreamerId(), StreamerStatusEnum::PENDING_USER_ACTIVATION);
        $this->em->persist($streamer);

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            if (!$this->em->isOpen()) {
                $this->registry->resetManager();
            }
        }
    }
}
