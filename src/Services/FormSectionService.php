<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Services;

interface FormSectionService
{
    public function build(
        string $from,
        mixed $data
    ): array;
}
