<?php

declare(strict_types=1);

namespace App\Application\Common\IntegrationEvent;

/**
 * All integration events has to implement this interface.
 * You can use $integrationEventBus in order to dispatch Integration events to these handlers.
 *
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @see IntegrationEventMapper
 */
interface IntegrationEventInterface
{
}
