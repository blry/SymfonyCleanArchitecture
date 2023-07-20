<?php

namespace App\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
