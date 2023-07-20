<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Event;

use PHPUnit\Framework\TestCase;
use App\Domain\User\Event\UserCreatedEvent;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class CreatedEventTest extends TestCase
{
    public function testConstructor()
    {
        $userId = 'randomId';
        $event = new UserCreatedEvent($userId);

        $this->assertEquals($userId, $event->getUserId());
    }
}
