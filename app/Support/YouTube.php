<?php

namespace App\Support;

class YouTube
{
    /**
     * Extract the 11-character video id from any common YouTube link
     * (watch?v=, youtu.be/, /embed/, /shorts/, /live/), or null if it isn't one.
     */
    public static function id(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $pattern = '~^(?:https?://)?(?:www\.|m\.)?(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})(?:[?&#/].*)?$~';

        return preg_match($pattern, trim($url), $matches) ? $matches[1] : null;
    }

    public static function embedUrl(string $id): string
    {
        return "https://www.youtube-nocookie.com/embed/{$id}?autoplay=1&rel=0";
    }

    public static function thumbnailUrl(string $id): string
    {
        return "https://i.ytimg.com/vi/{$id}/hqdefault.jpg";
    }
}
