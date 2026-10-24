<?php

namespace Tests\Unit;

use App\Support\YouTube;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class YouTubeTest extends TestCase
{
    public static function links(): array
    {
        return [
            'watch' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'watch with extra params' => ['https://youtube.com/watch?feature=share&v=dQw4w9WgXcQ&t=30', 'dQw4w9WgXcQ'],
            'short link' => ['https://youtu.be/dQw4w9WgXcQ?si=xyz', 'dQw4w9WgXcQ'],
            'mobile' => ['https://m.youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'embed' => ['https://www.youtube.com/embed/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'shorts' => ['https://www.youtube.com/shorts/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'not youtube' => ['https://vimeo.com/123456', null],
            'lookalike domain' => ['https://notyoutube.com/watch?v=dQw4w9WgXcQ', null],
            'empty' => ['', null],
        ];
    }

    #[DataProvider('links')]
    public function test_it_extracts_the_video_id(string $url, ?string $expected): void
    {
        $this->assertSame($expected, YouTube::id($url));
    }
}
