<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Collections;

trait FlexLogging
{
    protected function flexLog(string $message, array $context)
    {
        if (config('flex-admin.logging')) {
            logger(message: $message, context: $context);
        }
    }
}
