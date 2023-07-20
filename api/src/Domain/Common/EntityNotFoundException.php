<?php

declare(strict_types=1);

namespace App\Domain\Common;

use Symfony\Component\Messenger\Exception\UnrecoverableExceptionInterface;
use Doctrine\ORM\EntityNotFoundException as DoctrineEntityNotFoundException;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class EntityNotFoundException extends DoctrineEntityNotFoundException implements UnrecoverableExceptionInterface
{
}
