<?php

namespace App\Tests\Domain\Common\Service;

use PHPUnit\Framework\TestCase;
use App\Domain\Common\DomainException;
use App\Domain\Common\Service\Assert;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class AssertTest extends TestCase
{
    public const INVALID_UUID = 'invalid-e27f-4eb7-9847-878f53ead268';

    public function testDomainException()
    {
        $this->expectException(DomainException::class);

        Assert::uuid(self::INVALID_UUID);
    }
}
