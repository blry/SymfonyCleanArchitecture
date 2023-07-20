<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Event;

use PHPUnit\Framework\TestCase;
use App\Domain\User\Event\UserEmailUpdateStartedEvent;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class EmailUpdateStartedEventTest extends TestCase
{
    public function testConstructor()
    {
        $userId = 'randomId';
        $event = new UserEmailUpdateStartedEvent($userId);

        $this->assertEquals($userId, $event->getUserId());
    }
}
