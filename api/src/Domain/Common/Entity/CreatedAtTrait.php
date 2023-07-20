<?php

declare(strict_types=1);

namespace App\Domain\Common\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
trait CreatedAtTrait
{
    #[Groups(['basic'])]
    #[Assert\NotBlank]
    #[ORM\Column(type: 'datetime_immutable')]
    protected ?\DateTimeImmutable $createdAt;

    #[ORM\PrePersist]
    public function getCreatedAt(): \DateTimeImmutable
    {
        if (!isset($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
        }

        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
