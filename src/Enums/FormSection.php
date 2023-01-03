<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Enums;

use Psi\FlexAdmin\Resources\FormSections\ResourceFormSection;

interface FormSection
{
    public function section(...$args): ResourceFormSection;

    public function fake(int $count, ...$args): array;
}
