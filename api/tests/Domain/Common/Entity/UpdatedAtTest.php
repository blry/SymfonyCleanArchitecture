<?php

namespace App\Tests\Domain\Common\Entity;

use PHPUnit\Framework\TestCase;
use App\Domain\Common\Entity\UpdatedAtTrait;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class UpdatedAtTest extends TestCase
{
    private object $entity;

    public function setUp(): void
    {
        parent::setUp();

        $this->entity = new class {
            use UpdatedAtTrait;
        };
    }

    public function testUpdatedAt()
    {
        $this->assertEquals(null, $this->entity->getUpdatedAt());

        $date = new \DateTimeImmutable();
        $this->entity->setUpdatedAt($date);

        $this->assertEquals($date, $this->entity->getUpdatedAt());
    }

    public function testFillUpdatedAt()
    {
        $this->entity->fillUpdatedAt();
        $date = $this->entity->getUpdatedAt();

        $this->assertEquals($date, $this->entity->getUpdatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $date);
    }
}
