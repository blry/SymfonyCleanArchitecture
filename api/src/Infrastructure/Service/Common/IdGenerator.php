<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Common;

use App\Domain\Common\Service\IdGeneratorInterface;
use Godruoyi\Snowflake\Snowflake;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class IdGenerator extends Snowflake implements IdGeneratorInterface
{
}
