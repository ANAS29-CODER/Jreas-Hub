<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Donor = 'donor';
    case Student = 'student';
}
