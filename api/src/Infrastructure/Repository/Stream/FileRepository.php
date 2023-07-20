<?php

namespace App\Infrastructure\Repository\Stream;

use App\Domain\Stream\File\File;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Stream\File\FileRepositoryInterface;
use App\Infrastructure\Repository\BaseRepository;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @extends BaseRepository<File>
 */
class FileRepository extends BaseRepository implements FileRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }
}
