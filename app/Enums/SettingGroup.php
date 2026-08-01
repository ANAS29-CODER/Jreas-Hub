<?php

namespace App\Enums;

enum SettingGroup: string
{
    case General = 'general';
    case Social = 'social';
    case Contact = 'contact';
    case Seo = 'seo';
}
