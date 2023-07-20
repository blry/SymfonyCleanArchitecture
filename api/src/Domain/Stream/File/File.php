<?php

declare(strict_types=1);

namespace App\Domain\Stream\File;

use App\Domain\Common\Entity\CreatedAtTrait;
use App\Domain\Common\Service\Assert;
use App\Domain\Stream\Streamer;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as AssertConstraints;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
#[ORM\Entity(repositoryClass: FileRepositoryInterface::class)]
#[ORM\Table(name: 'stream_files')]
#[ORM\HasLifecycleCallbacks]
class File
{
    use CreatedAtTrait;

    #[Groups(['basic'])]
    #[OA\Property(type: 'string', format: 'number', description: 'The unique identifier of the model.')]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'bigint', options: ['unsigned' => true])]
    #[ORM\Id]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Streamer::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    private ?Streamer $streamer;

    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    #[AssertConstraints\Choice(FileTypeEnum::CASES)]
    #[ORM\Column(type: 'string', length: 20)]
    private string $type;


    #[Groups(['basic'])]
    #[AssertConstraints\NotBlank]
    #[ORM\Column(type: 'string', length: 20)]
    private string $path;

    public function __construct(string $id, ?Streamer $streamer, string $type, string $path)
    {
        Assert::numeric($id);
        $this->id = $id;
        $this->streamer = $streamer;
        $this->setType($type);
        $this->setPath($path);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStreamer(): ?Streamer
    {
        return $this->streamer;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        FileTypeEnum::from($type);

        $this->type = $type;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        Assert::maxLength($path, 20, 'The path length should not be more than 20 characters.');
        Assert::true(file_exists($path), 'File does not exist');

        $this->path = $path;

        return $this;
    }
}
