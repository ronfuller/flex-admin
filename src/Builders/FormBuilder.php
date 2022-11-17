<?php

declare(strict_types=1);
namespace Psi\FlexAdmin\Builders;

use Psi\FlexAdmin\Services\FormSectionService;
use Psi\FlexAdmin\Concerns\Makeable;

class FormBuilder
{
    use Makeable;

    final public function __construct(protected FormSectionService $service)
    {
    }

    public function build(array $sections): array
    {
        return collect($sections)
            ->map(fn ($section) => $this->service->build(
                from: $section['slug'],
                data: $section['data']
            ))->all();
    }
}
