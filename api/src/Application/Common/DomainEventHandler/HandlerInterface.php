<?php

declare(strict_types=1);

namespace App\Application\Common\DomainEventHandler;

/**
 * All domain event handlers has to implement this interface.
 * You can use $domainEventBus in order to dispatch Domain events to these handlers.
 *
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @see DomainEventSubscriber
 *
 */
interface HandlerInterface
{
}
