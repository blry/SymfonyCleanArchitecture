<?php

declare(strict_types=1);

namespace App\Domain\Stream;

use App\Domain\Common\Service\Assert;
use App\Domain\Stream\ValueObject\StreamerStatusEnum;
use App\Domain\Stream\File\File;
use App\Domain\Stream\File\FileTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as AssertConstraints;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
#[ORM\Entity(repositoryClass: StreamerRepositoryInterface::class)]
#[ORM\Table(name: 'stream_streamers')]
class Streamer
{
    #[Groups(['basic'])]
    #[OA\Property(type: 'string', format: 'number', description: 'The unique identifier of the model.')]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'bigint', options: ['unsigned' => true])]
    #[ORM\Id]
    private string $id;

    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    #[AssertConstraints\Choice(StreamerStatusEnum::CASES)]
    #[ORM\Column(type: 'string', length: 20)]
    private string $status;

    /**
     * Describe why streamer was (e.g.) declined or banned.
     */
    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $statusReason = null;

    /**
     * Desirable nickname in streaming services
     */
    #[Groups(['full'])]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $nickname = null;

    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    #[ORM\OneToOne(targetEntity: File::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?File $avatar = null;


    public function __construct(string $id, string $status)
    {
        Assert::numeric($id);
        $this->id = $id;
        $this->setStatus($status);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        StreamerStatusEnum::from($status);

        $this->status = $status;

        return $this;
    }

    public function getStatusReason(): ?string
    {
        return $this->statusReason;
    }

    public function setStatusReason(?string $statusReason): self
    {
        $this->statusReason = $statusReason;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        Assert::maxLength($nickname, 20, 'The nickname length should not be more than 20 characters.');

        $this->nickname = $nickname;

        return $this;
    }

    public function getAvatar(): ?File
    {
        return $this->avatar;
    }

    public function setAvatar(?File $avatar): self
    {
        if ($avatar) {
            Assert::eq($avatar->getType(), FileTypeEnum::AVATAR);
        }

        $this->avatar = $avatar;

        return $this;
    }
}
