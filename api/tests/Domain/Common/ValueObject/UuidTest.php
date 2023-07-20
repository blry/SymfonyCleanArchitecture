<?php

namespace App\Tests\Domain\Common\ValueObject;

use PHPUnit\Framework\TestCase;
use App\Domain\Common\DomainException;
use App\Domain\Common\ValueObject\Uuid;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class UuidTest extends TestCase
{
    public const VALID_UUID1 = 'f3c04469-b84b-44e7-8c28-114d6eb21198';
    public const VALID_UUID2 = '7311d0dd-e27f-4eb7-9847-878f53ead268';
    public const INVALID_UUID = 'invalid-e27f-4eb7-9847-878f53ead268';

    public function testConstructor()
    {
        $uuid = new Uuid(self::VALID_UUID1);

        $this->assertEquals(self::VALID_UUID1, $uuid->getValue());
    }

    public function testConstructorInvalidValue()
    {
        $this->expectException(DomainException::class);

        new Uuid(self::INVALID_UUID);
    }

    public function testEquals()
    {
        $uuid1 = new Uuid(self::VALID_UUID1);
        $uuid2 = new Uuid(self::VALID_UUID2);
        $uuid3 = new Uuid(self::VALID_UUID2);

        $this->assertFalse($uuid1->equals($uuid2));
        $this->assertTrue($uuid2->equals($uuid3));
    }

    public function testV4()
    {
        $uuid = Uuid::v4();

        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertFalse($uuid->equals(Uuid::v4()));
    }

    public function testV5Valid()
    {
        $name = 'test';
        $uuid1 = Uuid::v5(self::VALID_UUID1, $name);
        $uuid2 = Uuid::v5(new Uuid(self::VALID_UUID1), $name);

        $this->assertInstanceOf(Uuid::class, $uuid1);
        $this->assertTrue($uuid1->equals($uuid2));
    }

    public function testV5InvalidNamespace()
    {
        $this->expectException(DomainException::class);
        Uuid::v5(self::INVALID_UUID, 'test');
    }

    public function testToString()
    {
        $uuid = Uuid::v4();
        $this->assertEquals($uuid->getValue(), (string) $uuid);
    }

    public function testToJsonSerialize()
    {
        $uuid = Uuid::v4();
        $this->assertEquals('"' . $uuid->getValue() . '"', json_encode($uuid));
    }
}
