<?php

declare(strict_types=1);

namespace App\Tests\Domain\User;

use App\Domain\Common\DomainException;
use App\Domain\Common\Entity\CreatedAtTrait;
use App\Domain\Common\Entity\EventAggregatorInterface;
use App\Domain\Common\Entity\UpdatedAtTrait;
use App\Domain\Common\ValueObject\Uuid;
use App\Domain\User\Event\UserCreatedEvent;
use App\Domain\User\Event\UserEmailConfirmedEvent;
use App\Domain\User\Event\UserEmailUpdateCancelledEvent;
use App\Domain\User\Event\UserEmailUpdateStartedEvent;
use App\Domain\User\Event\UserPasswordChangedEvent;
use App\Domain\User\User;
use App\Domain\User\ValueObject\ProviderEnum;
use App\Infrastructure\Service\Common\ByteString;
use PHPUnit\Framework\TestCase;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class UserTest extends TestCase
{
    public const USER_NICKNAME = 'TestName';
    public const USER_LOCALE = 'ru';
    public const USER_EMAIL = 'mail@example.com';
    public const USER_NEW_EMAIL = 'new_mail@example.com';
    public const USER_INVALID_EMAIL = 'example@';
    public const USER_ACTIVATION_CODE = '2eQT5zCT9kHpPuXipvX1XLoREURXpwlWPDfaxa8ycVYWDxAYOw';

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User('22', self::USER_NICKNAME, self::USER_LOCALE);
    }

    public function testUses()
    {
        $uses = class_uses(User::class);

        $this->assertTrue(\in_array(CreatedAtTrait::class, $uses));
        $this->assertTrue(\in_array(UpdatedAtTrait::class, $uses));
        $this->assertInstanceOf(EventAggregatorInterface::class, $this->user);
    }

    public function testConstructor()
    {
        $uuid = $this->user->getId();
        /** @var UserCreatedEvent $createdAt */
        $createdAt = $this->user->releaseEvents()[0];

        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertEquals($uuid->getValue(), $createdAt->getUserId());
    }

    public function testNickname()
    {
        $this->assertEquals(self::USER_NICKNAME, $this->user->getNickname());

        $anotherName = 'AnotherTestName';
        $this->user->setNickname($anotherName);

        $this->assertEquals($anotherName, $this->user->getNickname());
    }

    public function testNicknameTooLong()
    {
        $this->expectException(DomainException::class);

        $this->user->setNickname(str_repeat('x', 51));
    }

    public function testNicknameTooShort()
    {
        $this->expectException(DomainException::class);

        $this->user->setNickname('');
    }

    public function testSetEmail()
    {
        $this->user->setEmail(self::USER_EMAIL);
        $this->assertEquals(self::USER_EMAIL, $this->user->getEmail());

        $this->user->setEmail(null);
        $this->assertEquals(null, $this->user->getEmail());
    }

    public function testSetInvalidEmail()
    {
        $this->expectException(DomainException::class);

        $this->user->setEmail(self::USER_INVALID_EMAIL);
    }

    public function testUpdateEmail()
    {
        $this->user->updateEmail(self::USER_NEW_EMAIL, new ByteString());

        $this->assertEquals(self::USER_NEW_EMAIL, $this->user->getUnconfirmedEmail());
        $this->assertEquals(self::USER_ACTIVATION_CODE, $this->user->getActivationCode());
        $this->assertEquals(null, $this->user->getEmail());
        $this->assertInstanceOf(UserEmailUpdateStartedEvent::class, $this->user->releaseEvents()[1]);
    }

    public function testUpdateEmailInvalidEmail()
    {
        $this->expectException(DomainException::class);

        $this->user->updateEmail(self::USER_INVALID_EMAIL, new ByteString());
    }

    public function testUpdateEmailInvalidCode()
    {
        $this->expectException(DomainException::class);

        $this->user->updateEmail(self::USER_NEW_EMAIL, new ByteString());
    }

    public function testUpdateEmailTheSame()
    {
        $this->user->setEmail(self::USER_EMAIL);

        $this->expectException(DomainException::class);

        $this->user->updateEmail(self::USER_EMAIL, new ByteString());
    }

    public function testCancelEmailUpdate()
    {
        $this->user->updateEmail(self::USER_NEW_EMAIL, new ByteString());
        $this->user->cancelEmailUpdate();
        $events = $this->user->releaseEvents();

        $this->assertEquals(null, $this->user->getUnconfirmedEmail());
        $this->assertEquals(null, $this->user->getActivationCode());
        $this->assertInstanceOf(UserEmailUpdateStartedEvent::class, $events[1]);
        $this->assertInstanceOf(UserEmailUpdateCancelledEvent::class, $events[2]);
    }

    public function testCancelEmailUpdateNotStarted()
    {
        $this->expectException(DomainException::class);

        $this->user->cancelEmailUpdate();
    }

    public function testConfirmEmailUpdate()
    {
        $this->user->setEmail(self::USER_EMAIL);
        $this->user->updateEmail(self::USER_NEW_EMAIL, new ByteString());
        $this->user->confirmEmail();
        /** @var UserEmailConfirmedEvent $emailConfirmedEvent */
        $emailConfirmedEvent = $this->user->releaseEvents()[2];

        $this->assertEquals(self::USER_NEW_EMAIL, $this->user->getEmail());
        $this->assertEquals(null, $this->user->getUnconfirmedEmail());
        $this->assertEquals(null, $this->user->getActivationCode());
        $this->assertEquals(self::USER_EMAIL, $emailConfirmedEvent->getOldEmail());
    }

    public function testSetAds()
    {
        $this->assertEquals(false, $this->user->isAds());

        $this->user->setAds(true);
        $this->assertEquals(true, $this->user->isAds());

        $this->user->setAds(false);
        $this->assertEquals(false, $this->user->isAds());
    }

    public function testSetRoles()
    {
        $this->assertEquals([], $this->user->getRoles());

        $roles = ['ROLE_XX', 'ROLE_YY'];
        $this->user->setRoles($roles);
        $this->assertEquals($roles, $this->user->getRoles());

        $this->user->setRoles(array_merge($roles, $roles));
        $this->assertEquals($roles, $this->user->getRoles());
    }

    public function testSetRolesRoleUser()
    {
        $this->expectException(DomainException::class);

        $this->user->setRoles(['ROLE_USER', 'ROLE_YY']);
    }

    public function testSetPassword()
    {
        $this->assertFalse($this->user->getHasPassword());
        $this->assertNull($this->user->getPassword());

        $this->user->setPassword(self::USER_ACTIVATION_CODE);
        $this->assertEquals(self::USER_ACTIVATION_CODE, $this->user->getPassword());
        $this->assertCount(1, $this->user->releaseEvents());

        $this->user->setPassword(null);
        $this->assertInstanceOf(UserPasswordChangedEvent::class, $this->user->releaseEvents()[0]);
    }

    public function testSocial()
    {
        foreach (ProviderEnum::CASES as $key => $provider) {
            $key = (string) $key;

            $this->assertNull($this->user->{'get'.$provider}());
            $this->user->setProviderResourceId($provider, $key);
            $this->assertEquals($key, $this->user->{'get'.$provider}());

            $this->user->setProviderResourceId($provider, null);
            $this->assertNull($this->user->{'get'.$provider}());
        }
    }

    public function testUsername()
    {
        $this->assertEquals($this->user->getId(), $this->user->getUsername());
    }
}
