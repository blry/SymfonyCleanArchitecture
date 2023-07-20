<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Event;

use PHPUnit\Framework\TestCase;
use App\Domain\User\Event\UserEmailConfirmedEvent;
use App\Domain\User\Event\UserEmailUpdateCancelledEvent;
use App\Domain\User\Event\UserEmailUpdateStartedEvent;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class EmailConfirmedEventTest extends TestCase
{
    public function testConstructor()
    {
        $userId = 'randomId';
        $oldEmail = 'mail@example.com';
        $event = new UserEmailConfirmedEvent($userId, $oldEmail);

        $this->assertEquals($userId, $event->getUserId());
        $this->assertEquals($oldEmail, $event->getOldEmail());
    }

    public function testNullOldEmail()
    {
        $event = new UserEmailConfirmedEvent('randomId', null);

        $this->assertEquals(null, $event->getOldEmail());
    }
}
