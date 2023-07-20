<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber\Core;

use App\Application\Common\IntegrationEventMapperInterface;
use App\Domain\Common\DomainEventInterface;
use App\Domain\Common\Entity\EventAggregatorTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Domain\Common\Entity\EventAggregatorInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Subscribed to entities which implement @EventAggregatorInterface in order to release
 * and map Domain events to Integration events.
 *
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @see EventAggregatorInterface
 * @see EventAggregatorTrait
 * @see IntegrationEventMapperInterface
 */
readonly class DomainEventSubscriber implements EventSubscriber
{
    /**
     * @var IntegrationEventMapperInterface[]
     */
    private iterable $integrationEventMappers;

    public function __construct(
        private MessageBusInterface $domainEventBus,
        private MessageBusInterface $integrationEventBus,
        #[TaggedIterator('app.application.integration_event_mapper')] iterable $integrationEventMappers
    ) {
        $this->integrationEventMappers = $integrationEventMappers;
    }

    /**
     * Doctrine\Common\EventSubscriber it can only return the event names; you cannot define a custom method names
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->releaseEventsIfAggregator($args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->releaseEventsIfAggregator($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->releaseEventsIfAggregator($args);
    }

    public function releaseEventsIfAggregator(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof EventAggregatorInterface) {
            foreach ($object->releaseEvents() as $event) {
                $this->mapAndDispatchDomainEvent($event);
            }
        }
    }

    private function mapAndDispatchDomainEvent(DomainEventInterface $event): void
    {
        $this->domainEventBus->dispatch($event);

        foreach ($this->integrationEventMappers as $eventMapper) {
            $integrationEvent = $eventMapper->mapDomainEvent($event);
            if ($integrationEvent) {
                $this->integrationEventBus->dispatch($integrationEvent);
                break;
            }
        }
    }
}
