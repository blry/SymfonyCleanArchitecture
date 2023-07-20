<?php

declare(strict_types=1);

namespace App\Domain\Common\Service;

use App\Domain\Common\DomainException;

/**
 * Todo: Use deferred validations for Domain entities in order to unlink Domain service from framework-related implementation.
 *
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class Assert extends \Webmozart\Assert\Assert
{
    /**
     * @param string $message
     *
     * @throws DomainException
     */
    protected static function reportInvalidArgument($message)
    {
        throw new DomainException('domain_error', $message);
    }
}
