<?php

namespace App\Enums;

enum MediaFileType: string
{
    case Image = 'image';
    case Video = 'video';
    case Pdf = 'pdf';
}
