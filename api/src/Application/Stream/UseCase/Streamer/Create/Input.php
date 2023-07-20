<?php

declare(strict_types=1);

namespace App\Application\Stream\UseCase\Streamer\Create;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Input
{
    public function __construct(
        private string $streamerId
    ) {}

    public function getStreamerId(): string
    {
        return $this->streamerId;
    }
}
