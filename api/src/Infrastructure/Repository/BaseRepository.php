<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Common\EntityNotFoundException;
use App\Domain\Common\Service\RepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @template-covariant T of object
 * @template-extends ServiceEntityRepository<T>
 * @template-implements RepositoryInterface<T>
 */
abstract class BaseRepository extends ServiceEntityRepository implements RepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     * @throws \Doctrine\ORM\EntityNotFoundException
     *
     * @psalm-return T
     */
    public function findOrFail($id, $message = null, $lockMode = null, $lockVersion = null): object
    {
        $entity = $this->find($id, $lockMode, $lockVersion);
        if (null === $entity) {
            if (null === $message) {
                throw EntityNotFoundException::fromClassNameAndIdentifier($this->getClassName(), [$id]);
            }
            throw new EntityNotFoundException($message);
        }

        return $entity;
    }

    /**
     * @throws EntityNotFoundException
     * @throws \Doctrine\ORM\EntityNotFoundException
     *
     * @psalm-return T
     */
    public function findOrFailBy(array $criteria, $message = null): object
    {
        $entity = $this->findOneBy($criteria);
        if (null === $entity) {
            if (null === $message) {
                throw EntityNotFoundException::fromClassNameAndIdentifier($this->getClassName(), $criteria);
            }
            throw new EntityNotFoundException($message);
        }

        return $entity;
    }
}
