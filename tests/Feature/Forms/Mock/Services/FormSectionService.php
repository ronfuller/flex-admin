<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Tests\Feature\Forms\Mock\Services;

use Psi\FlexAdmin\Services\FormSectionService as ServicesFormSectionService;
use Psi\FlexAdmin\Tests\Feature\Forms\Mock\Enums\FormSection;

class FormSectionService implements ServicesFormSectionService
{
    public function build(string $from, mixed $data): array
    {
        return FormSection::from(
            value: $from
        )->section(
            $data
        )->toArray();
    }
}
