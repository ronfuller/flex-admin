<?php

namespace Psi\FlexAdmin\Tests\Models\Traits;

trait HasDateRange
{
    protected $dateRanges = [
        'Last 4 hours (new)',
        'Last 7 days',
        'Last 14 days',
        'Last 30 days',
        'Last 60 days',
        'Last 90 days',
        'This Month',
        'This Quarter',
        'This Year',
        'Last Month',
        'Last Quarter',
        'Last Year',
    ];

    public function getDateRanges(): array
    {
        return collect($this->dateRanges)->map(fn ($range) => ['label' => $range, 'value' => $range])->all();
    }
}
