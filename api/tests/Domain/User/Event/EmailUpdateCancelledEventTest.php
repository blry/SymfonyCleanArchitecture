<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Event;

use PHPUnit\Framework\TestCase;
use App\Domain\User\Event\UserEmailUpdateCancelledEvent;
use App\Domain\User\Event\UserEmailUpdateStartedEvent;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class EmailUpdateCancelledEventTest extends TestCase
{
    public function testConstructor()
    {
        $userId = 'randomId';
        $cancelledEmail = 'mail@example.com';
        $event = new UserEmailUpdateCancelledEvent($userId, $cancelledEmail);

        $this->assertEquals($userId, $event->getUserId());
        $this->assertEquals($cancelledEmail, $event->getCancelledEmail());
    }
}
