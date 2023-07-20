<?php

namespace App\Tests\Domain\Common;

use PHPUnit\Framework\TestCase;
use App\Domain\Common\DomainException;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class DomainExceptionTest extends TestCase
{
    public function testErrorCode()
    {
        $e = new DomainException($errorCode = 'testError', $msg = 'message');

        $this->assertEquals($errorCode, $e->getErrorCode());
        $this->assertEquals($msg, $e->getMessage());
    }
}
