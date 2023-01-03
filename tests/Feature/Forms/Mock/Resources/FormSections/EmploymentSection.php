<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Tests\Feature\Forms\Mock\Resources\FormSections;

use Psi\FlexAdmin\Concerns\Makeable;
use Psi\FlexAdmin\Resources\FormSections\ResourceFormSection;

class EmploymentSection implements ResourceFormSection
{
    use Makeable;

    public function __construct(protected array $data)
    {
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
