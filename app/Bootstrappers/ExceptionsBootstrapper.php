<?php

declare(strict_types=1);

namespace App\Bootstrappers;

use Illuminate\Foundation\Configuration\Exceptions;

final class ExceptionsBootstrapper
{
    /**
     * @param Exceptions $exceptions
     * @return void
     */
    public function __invoke(Exceptions $exceptions): void {}
}
