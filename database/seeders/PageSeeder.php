<?php

namespace Database\Seeders;

use App\Enums\PageSlug;
use App\Services\PageService;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(PageService::class);

        $pages = [
            [
                'slug' => PageSlug::About->value,
                'title' => 'من نحن',
                'content' => '',
            ],
            [
                'slug' => PageSlug::Contact->value,
                'title' => 'اتصل بنا',
                'content' => '',
            ],
        ];

        foreach ($pages as $page) {
            if ($service->findBy('slug', $page['slug'])) {
                continue;
            }

            $service->create($page);
        }
    }
}
