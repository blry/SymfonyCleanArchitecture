<?php

declare(strict_types=1);

namespace App\Domain\Common\Service;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @template-covariant T of object
 */
interface RepositoryInterface extends ObjectRepository, ServiceEntityRepositoryInterface
{
    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return object|null The object.
     * @psalm-return T|null
     */
    public function find($id, $lockMode = null, $lockVersion = null);

    /**
     * @psalm-return T
     */
    public function findOrFail($id, $message = null, $lockMode = null, $lockVersion = null): object;

    /**
     * @psalm-return T
     */
    public function findOrFailBy(array $criteria, $message = null): object;
}
