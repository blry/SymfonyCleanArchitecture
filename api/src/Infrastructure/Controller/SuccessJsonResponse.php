<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class SuccessJsonResponse extends JsonResponse
{
    /**
     * @var bool
     *
     * @Groups({"basic"})
     *
     * @Assert\NotBlank
     */
    public bool $success = true;

    public function __construct()
    {
        parent::__construct(['success' => $this->success]);
    }
}