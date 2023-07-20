<?php

declare(strict_types=1);

namespace App\Application\Stream\UseCase\Streamer\Remove;

use App\Application\Common\UseCase\HandlerInterface;
use App\Domain\Common\Service\EntityManagerInterface;
use App\Domain\Stream\StreamerRepositoryInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Handler implements HandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private StreamerRepositoryInterface $streamerRepo
    ) {}

    public function __invoke(Input $input)
    {
        $streamer = $this->streamerRepo->findOrFail($input->getStreamerId());
        $this->em->remove($streamer);

        $this->em->flush();
    }
}
