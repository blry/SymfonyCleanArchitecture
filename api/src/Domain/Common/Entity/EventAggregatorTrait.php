<?php

declare(strict_types = 1);

namespace App\Domain\Common\Entity;

use App\Domain\Common\DomainEventInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
trait EventAggregatorTrait
{
    private array $domainEvents = [];

    final public function releaseEvents(): array
    {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function recordEvent(DomainEventInterface $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
