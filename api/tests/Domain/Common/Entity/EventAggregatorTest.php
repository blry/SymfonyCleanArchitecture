<?php

namespace App\Tests\Domain\Common\Entity;

use PHPUnit\Framework\TestCase;
use App\Domain\Common\DomainEventInterface;
use App\Domain\Common\Entity\EventAggregatorInterface;
use App\Domain\Common\Entity\EventAggregatorTrait;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class EventAggregatorTest extends TestCase
{
    public function testRecordEvent()
    {
        $event0 = new class() implements DomainEventInterface {};
        $event1 = new class() implements DomainEventInterface {};

        $entity = new class($event0, $event1) implements EventAggregatorInterface {
            use EventAggregatorTrait;

            public function __construct(DomainEventInterface ...$domainEvents)
            {
                foreach ($domainEvents as $event) {
                    $this->recordEvent($event);
                }
            }
        };

        $events = $entity->releaseEvents();

        $this->assertEquals($events[0], $event0);
        $this->assertEquals($events[1], $event1);
        $this->assertCount(2, $events);
        $this->assertEmpty($entity->releaseEvents());
    }
}
