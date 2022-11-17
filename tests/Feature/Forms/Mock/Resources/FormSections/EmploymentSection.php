<?php

declare(strict_types=1);
namespace Psi\FlexAdmin\Tests\Feature\Forms\Mock\Resources\FormSections;

use Psi\FlexAdmin\Resources\FormSections\ResourceFormSection;
use Psi\FlexAdmin\Concerns\Makeable;

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
