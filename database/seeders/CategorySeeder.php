<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'web-hosting',
                'name' => 'Web Hosting',
                'name_ru' => 'Веб-хостинг',
                'type' => CategoryType::Hosting,
                'description' => 'Compare the best shared and cloud web hosting providers.',
                'description_ru' => 'Сравните лучшие провайдеры shared и cloud веб-хостинга.',
                'icon' => 'server',
                'sort_order' => 1,
            ],
            [
                'slug' => 'vps',
                'name' => 'VPS Hosting',
                'name_ru' => 'VPS-хостинг',
                'type' => CategoryType::Hosting,
                'description' => 'Virtual private servers with dedicated resources.',
                'description_ru' => 'Виртуальные частные серверы с выделенными ресурсами.',
                'icon' => 'cpu',
                'sort_order' => 2,
            ],
            [
                'slug' => 'wordpress-hosting',
                'name' => 'WordPress Hosting',
                'name_ru' => 'Хостинг для WordPress',
                'type' => CategoryType::Hosting,
                'description' => 'Optimized hosting for WordPress sites.',
                'description_ru' => 'Оптимизированный хостинг для сайтов на WordPress.',
                'icon' => 'wordpress',
                'sort_order' => 3,
            ],
            [
                'slug' => 'cloud-hosting',
                'name' => 'Cloud Hosting',
                'name_ru' => 'Облачный хостинг',
                'type' => CategoryType::Hosting,
                'description' => 'Scalable cloud infrastructure for growing projects.',
                'description_ru' => 'Масштабируемая облачная инфраструктура для растущих проектов.',
                'icon' => 'cloud',
                'sort_order' => 4,
            ],
            [
                'slug' => 'dedicated-servers',
                'name' => 'Dedicated Servers',
                'name_ru' => 'Выделенные серверы',
                'type' => CategoryType::Hosting,
                'description' => 'Full physical servers for maximum performance.',
                'description_ru' => 'Физические серверы для максимальной производительности.',
                'icon' => 'hard-drive',
                'sort_order' => 5,
            ],
            [
                'slug' => 'domains',
                'name' => 'Domain Registrars',
                'name_ru' => 'Регистраторы доменов',
                'type' => CategoryType::Domain,
                'description' => 'Find and register domain names.',
                'description_ru' => 'Поиск и регистрация доменных имён.',
                'icon' => 'globe',
                'sort_order' => 10,
            ],
            [
                'slug' => 'website-builders',
                'name' => 'Website Builders',
                'name_ru' => 'Конструкторы сайтов',
                'type' => CategoryType::Builder,
                'description' => 'No-code website builders and page creators.',
                'description_ru' => 'Конструкторы сайтов без кода.',
                'icon' => 'layout',
                'sort_order' => 20,
            ],
            [
                'slug' => 'ai-tools',
                'name' => 'AI Tools',
                'name_ru' => 'AI-инструменты',
                'type' => CategoryType::Ai,
                'description' => 'AI-powered hosting and site generation tools.',
                'description_ru' => 'AI-инструменты для хостинга и генерации сайтов.',
                'icon' => 'sparkles',
                'sort_order' => 30,
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, [
                    'is_active' => true,
                    'meta_title' => 'Best '.$category['name'].' 2026',
                    'meta_description' => $category['description'],
                ])
            );
        }
    }
}
