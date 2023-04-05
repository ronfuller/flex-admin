<?php

namespace Psi\FlexAdmin\Fields\Enums;

enum SearchType: string
{
    case FULL = 'full';
    case PARTIAL = 'partial';
    case EXACT = 'exact';
}
