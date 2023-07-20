<?php

declare(strict_types=1);

namespace App\Application\Common\IntegrationEventHandler;

use App\Application\Common\IntegrationEvent\IntegrationEventInterface;
use App\Domain\Common\DomainEventInterface;

/**
 * All integration event handlers has to implement this interface.
 * You can use $integrationEventBus in order to dispatch Integration events to these handlers.
 *
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @see DomainEventSubscriber
 */
interface HandlerInterface
{
}
