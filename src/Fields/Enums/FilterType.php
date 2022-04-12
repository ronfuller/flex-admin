<?php

namespace Psi\FlexAdmin\Fields\Enums;

enum FilterType: string
{
    case VALUE = "value";
    case RANGE = "range";
    case DATE_RANGE = "date-range";
    case LTE = "lte";
    case GTE = "gte";
    case BETWEEN = "between";
}
