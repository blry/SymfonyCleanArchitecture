<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\LocaleEnum;
use App\Domain\User\ValueObject\ProviderEnum;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Common\Service\Assert;
use App\Domain\Common\Service\ByteStringInterface;
use App\Domain\Common\Entity\CreatedAtTrait;
use App\Domain\Common\Entity\UpdatedAtTrait;
use App\Domain\Common\Entity\EventAggregatorInterface;
use App\Domain\Common\Entity\EventAggregatorTrait;
use App\Domain\User\Event\UserEmailUpdateCancelledEvent;
use App\Domain\User\Event\UserEmailUpdateStartedEvent;
use App\Domain\User\Event\UserEmailConfirmedEvent;
use App\Domain\User\Event\UserPasswordChangedEvent;
use App\Domain\User\Event\UserRemovedEvent;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Attributes as OA;
use App\Domain\User\Event\UserCreatedEvent;
use App\Domain\User\Event\UserActivatedEvent;
use Symfony\Component\Validator\Constraints as AssertConstraints;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
#[ORM\Entity(repositoryClass: UserRepositoryInterface::class)]
#[ORM\Table(name: 'user_users')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, EventAggregatorInterface, PasswordAuthenticatedUserInterface
{
    use EventAggregatorTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    public const ACTIVATION_TIMEOUT_DAYS = 4;
    public const ACTIVATION_CODE_LENGTH = 50;

    #[Groups(['basic'])]
    #[OA\Property(type: 'string', format: 'number', description: 'The unique identifier of the model.')]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'bigint', options: ['unsigned' => true])]
    #[ORM\Id]
    private string $id;

    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $nickname;

    #[Groups(['basic'])]
    #[OA\Property(type: 'string', format: 'email', nullable: true)]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'string', length: 180, nullable: true, unique: true)]
    private ?string $email = null;

    #[Groups(['full'])]
    #[OA\Property(type: 'string', format: 'email', nullable: true)]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'string', length: 180, nullable: true)]
    private ?string $unconfirmedEmail = null;

    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'string', length: 50, nullable: true, unique: true)]
    private ?string $activationCode = null;

    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $activationStartedAt = null;

    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'string', length: 180, nullable: true, unique: true)]
    private ?string $telegram = null;

    /**
     * @var string[]
     */
    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $passwordHash = null;

    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    #[AssertConstraints\Choice(LocaleEnum::CASES)]
    #[ORM\Column(type: 'string', length: 2)]
    private string $locale;

    /**
     * User agreed to receive ads
     */
    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'boolean', nullable: false, options: ["default" => false])]
    private bool $ads = false;

    public function __construct(string $id, string $nickname, string $locale)
    {
        $this->setId($id);
        $this->setNickname($nickname);
        $this->setLocale($locale);

        $this->recordEvent(new UserCreatedEvent($id));
    }

    public function getId(): string
    {
        return $this->id;
    }

    private function setId(string $id): self
    {
        Assert::numeric($id);
        $this->id = $id;

        return $this;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        Assert::lengthBetween($nickname, 1, 50);
        Assert::regex($nickname, '/^[0-9A-Za-z_\-\(\)]+$/');

        $this->nickname = $nickname;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        LocaleEnum::from($locale);

        $this->locale = $locale;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        if (null !== $email) {
            Assert::email($email);
        }


        if (!$this->isActive()) {
            $this->recordEvent(new UserActivatedEvent($this->getUserIdentifier()));
        }

        $this->email = $email;

        return $this;
    }

    public function getUnconfirmedEmail(): ?string
    {
        return $this->unconfirmedEmail;
    }

    public function getActivationCode(): ?string
    {
        return $this->activationCode;
    }

    public function updateEmail(string $newEmail, ByteStringInterface $byteString): self
    {
        Assert::email($newEmail);
        Assert::notEq($newEmail, $this->email, 'You already use this email');

        $this->unconfirmedEmail = $newEmail;
        $this->activationCode = $byteString::fromRandom(self::ACTIVATION_CODE_LENGTH)->toString();
        $this->activationStartedAt = new \DateTimeImmutable();

        if (null !== $this->email) {
            $this->recordEvent(new UserEmailUpdateStartedEvent($this->getUserIdentifier()));
        }

        return $this;
    }

    public function cancelEmailUpdate(): self
    {
        Assert::notNull($this->activationCode, 'Email change process is not started');

        $this->recordEvent(new UserEmailUpdateCancelledEvent($this->getUserIdentifier(), (string) $this->unconfirmedEmail));
        $this->unconfirmedEmail = null;
        $this->activationCode = null;
        $this->activationStartedAt = null;

        return $this;
    }

    public function confirmEmail(): self
    {
        Assert::notNull($this->activationCode, 'Email change process is not started');
        Assert::lessThan(
            $this->activationStartedAt->diff(new \DateTimeImmutable())->d,
            self::ACTIVATION_TIMEOUT_DAYS,
            'Activation code is expired'
        );

        $this->recordEvent(new UserEmailConfirmedEvent($this->getUserIdentifier(), $this->email));

        if (!$this->isActive()) {
            $this->recordEvent(new UserActivatedEvent($this->getUserIdentifier()));
        }

        $this->email = $this->unconfirmedEmail;
        $this->unconfirmedEmail = null;
        $this->activationCode = null;
        $this->activationStartedAt = null;

        return $this;
    }

    public function isAds(): bool
    {
        return $this->ads;
    }

    public function setAds(bool $ads): self
    {
        $this->ads = $ads;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        if ($this->isActive()) {
            $roles[] = 'ROLE_ACTIVE_USER';
        }

        return $roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = array_unique($roles);

        return $this;
    }

    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    public function getHasPassword(): bool
    {
        return !!$this->passwordHash;
    }

    public function getPassword(): ?string
    {
        return $this->passwordHash;
    }

    public function setPassword(?string $passwordHash): self
    {
        if (null !== $this->passwordHash) {
            $this->recordEvent(new UserPasswordChangedEvent($this->id));
        }

        $this->passwordHash = $passwordHash;

        return $this;
    }

    public function setProviderResourceId(string $provider, ?string $resourceId): self
    {
        // TODO: Use an array for providers, so we won't need a separate column for every provider
        ProviderEnum::from($provider);

        if (!$this->isActive()) {
            $this->recordEvent(new UserActivatedEvent($this->getUserIdentifier()));
        }

        $this->$provider = $resourceId;

        return $this;
    }

    public function getTelegram(): ?string
    {
        return $this->telegram;
    }

    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    public function isActive(): bool
    {
        if ($this->email) {
            return true;
        }

        foreach (ProviderEnum::CASES as $provider) {
            if ($this->$provider) {
                return true;
            }
        }

        return false;
    }

    #[ORM\PreRemove]
    public function preRemove(): void
    {
        $this->recordEvent(new UserRemovedEvent($this->id));
    }

    /**
     * An identifier that represents this user.
     *
     * @see UserInterface
     */
    #[Pure] public function getUsername(): string
    {
        return $this->id;
    }

    #[Pure] public function getUserIdentifier(): string
    {
        return $this->id;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }
}
