<?php

declare(strict_types=1);

namespace App\Application\Common;

use App\Application\Common\IntegrationEvent\IntegrationEventInterface;
use App\Domain\Common\DomainEventInterface;

/**
 * If a class implements IntegrationEventMapper, the mapDomainEvent method will be invoked
 * to map domain events to integration events in @DomainEventSubscriber.
 *
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @see DomainEventSubscriber
 */
interface IntegrationEventMapperInterface
{
    /**
     * This method is invoked to map Domain events to Integration events.
     */
    public function mapDomainEvent(DomainEventInterface $event): IntegrationEventInterface|null;
}
