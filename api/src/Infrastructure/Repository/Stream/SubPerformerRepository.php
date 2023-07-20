<?php

namespace App\Infrastructure\Repository\Stream;

use App\Domain\Stream\SubPerformer\SubPerformer;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Stream\SubPerformer\SubPerformerRepositoryInterface;
use App\Infrastructure\Repository\BaseRepository;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @extends BaseRepository<SubPerformer>
 */
class SubPerformerRepository extends BaseRepository implements SubPerformerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubPerformer::class);
    }
}
