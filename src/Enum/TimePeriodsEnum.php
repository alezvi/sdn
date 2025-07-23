<?php

namespace App\Enum;

enum TimePeriodsEnum : string
{
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
    case YEAR = 'year';
}
