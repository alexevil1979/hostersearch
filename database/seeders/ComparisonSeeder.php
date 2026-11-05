<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Comparison;
use Illuminate\Database\Seeder;

class ComparisonSeeder extends Seeder
{
    public function run(): void
    {
        $pairs = [
            ['hostinger', 'ionos', 'Hostinger vs IONOS'],
            ['cloudways', 'kinsta', 'Cloudways vs Kinsta'],
            ['bluehost', 'siteground', 'Bluehost vs SiteGround'],
            ['namecheap', 'hostinger', 'Namecheap vs Hostinger'],
            ['hetzner', 'digitalocean', 'Hetzner vs DigitalOcean'],
            ['a2hosting', 'inmotion', 'A2 Hosting vs InMotion'],
        ];

        foreach ($pairs as $i => [$slug1, $slug2, $title]) {
            $c1 = Company::query()->where('slug', $slug1)->first();
            $c2 = Company::query()->where('slug', $slug2)->first();
            if (! $c1 || ! $c2) {
                continue;
            }

            $comparison = Comparison::query()->updateOrCreate(
                ['slug' => "{$slug1}-vs-{$slug2}"],
                [
                    'title' => $title,
                    'title_ru' => str_replace(' vs ', ' против ', $title),
                    'description' => "Side-by-side comparison of {$c1->name} and {$c2->name}: pricing, features, scores and plans.",
                    'description_ru' => "Сравнение {$c1->name} и {$c2->name}: цены, функции, оценки и тарифы.",
                    'meta_title' => "{$title} (2026 Comparison)",
                    'meta_description' => "Compare {$c1->name} and {$c2->name} hosting plans, ratings and coupons.",
                    'is_popular' => $i < 4,
                    'is_published' => true,
                    'view_count' => random_int(100, 5000),
                ]
            );

            $comparison->companies()->sync([
                $c1->id => ['position' => 1],
                $c2->id => ['position' => 2],
            ]);
        }
    }
}
