<?php

declare(strict_types=1);

namespace App\Infrastructure\OAuth2\Client\Telegram;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class TelegramResourceOwner implements ResourceOwnerInterface
{
    private string $id;
    private string $firstName;
    private string $username;
    private ?string $lastName = null;
    private ?string $email = null;

    public function __construct(string $id, string $username, string $firstName)
    {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getName(): string
    {
        return $this->firstName . ($this->lastName ? ' ' . $this->lastName : '');
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
        ];
    }
}
