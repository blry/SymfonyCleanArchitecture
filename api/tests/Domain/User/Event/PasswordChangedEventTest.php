<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Event;

use PHPUnit\Framework\TestCase;
use App\Domain\User\Event\UserPasswordChangedEvent;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class PasswordChangedEventTest extends TestCase
{
    public function testConstructor()
    {
        $userId = 'randomId';
        $event = new UserPasswordChangedEvent($userId);

        $this->assertEquals($userId, $event->getUserId());
    }
}
