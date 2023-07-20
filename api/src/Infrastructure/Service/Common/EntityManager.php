<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Common;

use App\Domain\Common\Service\EntityManagerInterface;
use Doctrine\ORM\Decorator\EntityManagerDecorator;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class EntityManager extends EntityManagerDecorator implements EntityManagerInterface
{
}
