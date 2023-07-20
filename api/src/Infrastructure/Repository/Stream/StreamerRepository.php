<?php

namespace App\Infrastructure\Repository\Stream;

use App\Domain\Stream\Streamer;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Stream\StreamerRepositoryInterface;
use App\Infrastructure\Repository\BaseRepository;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @extends BaseRepository<Streamer>
 */
class StreamerRepository extends BaseRepository implements StreamerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Streamer::class);
    }
}
