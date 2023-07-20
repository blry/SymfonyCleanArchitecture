<?php

declare(strict_types = 1);

namespace App\Domain\Common\ValueObject;

use App\Domain\Common\Service\Assert;
use App\Domain\Common\DomainException;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class Uuid implements \JsonSerializable
{
    /**
     * @Groups({"basic"})
     */
    private $value;

    /**
     * @throws DomainException
     */
    public function __construct(string $value)
    {
        Assert::uuid($value);

        $this->value = $value;
    }

    public static function v4(): self
    {
        return new static(uuid_create());
    }

    public static function v5($namespace, string $name): self
    {
        $uuid = uuid_generate_sha1($namespace, $name);

        Assert::uuid($uuid);

        return new static($uuid);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public function __toString()
    {
        return $this->value;
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}
