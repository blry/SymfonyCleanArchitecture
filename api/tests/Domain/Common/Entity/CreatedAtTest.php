<?php

namespace App\Tests\Domain\Common\Entity;

use PHPUnit\Framework\TestCase;
use App\Domain\Common\Entity\CreatedAtTrait;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class CreatedAtTest extends TestCase
{
    private object $entity;

    public function setUp(): void
    {
        parent::setUp();

        $this->entity = new class {
            use CreatedAtTrait;
        };
    }

    public function testGetCreatedAt()
    {
        $date = $this->entity->getCreatedAt();

        $this->assertEquals($date, $this->entity->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $date);
    }

    public function testSetCreatedAt()
    {
        $date = new \DateTimeImmutable();
        $this->entity->setCreatedAt($date);

        $this->assertEquals($date, $this->entity->getCreatedAt());
    }
}
