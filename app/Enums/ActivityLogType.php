<?php

namespace App\Enums;

enum ActivityLogType: string
{
    case Course = 'course';
    case Workshop = 'workshop';
    case Initiative = 'initiative';
    case Training = 'training';
    case Event = 'event';
}
