<?php

declare(strict_types=1);

namespace App\Domain\Common\Entity;

/**
 * If Entity implements EventAggregatorInterface, releaseEvents method will be invoked by DomainEventSubscriber.
 * which will dispatch all Domain events and map them to Integration events if they are implemented.
 *
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @see EventAggregatorTrait
 * @see DomainEventSubscriber
 */
interface EventAggregatorInterface
{
    public function releaseEvents(): array;
}
