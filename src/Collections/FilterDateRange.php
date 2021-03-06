<?php

namespace Psi\FlexAdmin\Collections;

trait FilterDateRange
{
    protected function getStartDateTime(string $date_range)
    {
        // TODO: Implement smarter date range to parse words, numeric
        switch ($date_range) {
            case 'Last 4 hours (new)':
                return now()->subHours(4);
            case 'Last 7 days':
                return now()->subDays(7);
            case 'Last 14 days':
                return now()->subDays(14);
            case 'Last 30 days':
                return now()->subDays(30);
            case 'Last 60 days':
                return now()->subDays(60);
            case 'Last 90 days':
                return now()->subDays(90);
            case 'This Month':
                return now()->firstOfMonth();
            case 'Last Month':
                return now()->subMonth()->firstOfMonth();
            case 'This Quarter':
                return now()->firstOfQuarter();
            case 'Last Quarter':
                return now()->subQuarter()->firstOfQuarter();
            case 'This Year':
                return now()->firstOfYear();
            case 'Last Year':
                return now()->subYear()->firstOfYear();
            default:
                throw new \Exception("Error in date range filter. Unknown parameter {$date_range}");
        }
    }

    protected function getEndDateTime(string $date_range)
    {
        switch ($date_range) {
            case 'Last 4 hours (new)':
            case 'Last 7 days':
            case 'Last 14 days':
            case 'Last 30 days':
            case 'Last 60 days':
            case 'Last 90 days':
            case 'This Month':
            case 'This Quarter':
            case 'This Year':
                return now();
            case 'Last Month':
                return now()->subMonth()->lastOfMonth();
            case 'Last Quarter':
                return now()->subQuarter()->lastOfQuarter();
            case 'Last Year':
                return now()->subYear()->lastOfYear();
            default:
                throw new \Exception("Error in date range filter. Unknown parameter {$date_range}");
        }
    }
}
