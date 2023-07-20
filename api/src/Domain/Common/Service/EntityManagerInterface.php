<?php

declare(strict_types=1);

namespace App\Domain\Common\Service;

use Doctrine\ORM\EntityManagerInterface as DoctrineEntityManagerInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
interface EntityManagerInterface extends DoctrineEntityManagerInterface
{
}
