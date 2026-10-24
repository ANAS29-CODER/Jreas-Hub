<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Services\CategoryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $service = app(CategoryService::class);

        $categories = [
            CategoryType::Course->value => ['البرمجة', 'التصميم'],
            CategoryType::Workshop->value => ['ريادة الأعمال', 'التسويق الرقمي'],
            CategoryType::Initiative->value => ['المسؤولية المجتمعية', 'التطوع'],
            CategoryType::Training->value => ['التدريب التعاوني', 'التدريب الصيفي'],
        ];

        foreach ($categories as $type => $names) {
            foreach ($names as $name) {
                $slug = Str::slug($name);

                if ($service->findBy('slug', $slug)) {
                    continue;
                }

                $service->create([
                    'name' => $name,
                    'slug' => $slug,
                    'type' => $type,
                ]);
            }
        }
    }
}
