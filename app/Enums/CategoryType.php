<?php

namespace App\Enums;

enum CategoryType: string
{
    case Course = 'course';
    case Workshop = 'workshop';
    case Initiative = 'initiative';
    case Training = 'training';
}
