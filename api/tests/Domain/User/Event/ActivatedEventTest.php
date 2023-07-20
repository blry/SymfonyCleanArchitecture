<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Event;

use PHPUnit\Framework\TestCase;
use App\Domain\User\Event\UserActivatedEvent;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class ActivatedEventTest extends TestCase
{
    public function testConstructor()
    {
        $userId = 'randomId';
        $event = new UserActivatedEvent($userId);

        $this->assertEquals($userId, $event->getUserId());
    }
}
