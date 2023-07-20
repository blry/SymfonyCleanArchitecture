<?php

declare(strict_types=1);

namespace App\Domain\Common\Service;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
interface IdGeneratorInterface
{

    /**
     * Get snowflake id.
     *
     * @return string
     */
    public function id();
}
