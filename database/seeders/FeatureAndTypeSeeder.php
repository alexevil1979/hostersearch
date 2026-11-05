<?php

namespace Database\Seeders;

use App\Models\CompanyType;
use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureAndTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['slug' => 'shared', 'name' => 'Shared Hosting', 'name_ru' => 'Shared-хостинг', 'sort_order' => 1],
            ['slug' => 'vps', 'name' => 'VPS', 'name_ru' => 'VPS', 'sort_order' => 2],
            ['slug' => 'cloud', 'name' => 'Cloud', 'name_ru' => 'Cloud', 'sort_order' => 3],
            ['slug' => 'dedicated', 'name' => 'Dedicated', 'name_ru' => 'Dedicated', 'sort_order' => 4],
            ['slug' => 'wordpress', 'name' => 'WordPress', 'name_ru' => 'WordPress', 'sort_order' => 5],
            ['slug' => 'reseller', 'name' => 'Reseller', 'name_ru' => 'Reseller', 'sort_order' => 6],
        ];

        foreach ($types as $type) {
            CompanyType::query()->updateOrCreate(['slug' => $type['slug']], $type);
        }

        $features = [
            ['slug' => 'free-ssl', 'name' => 'Free SSL', 'name_ru' => 'Бесплатный SSL', 'group' => 'security', 'icon' => 'lock'],
            ['slug' => 'free-domain', 'name' => 'Free Domain', 'name_ru' => 'Бесплатный домен', 'group' => 'hosting', 'icon' => 'globe'],
            ['slug' => 'ssd-storage', 'name' => 'SSD Storage', 'name_ru' => 'SSD-хранилище', 'group' => 'hosting', 'icon' => 'hard-drive'],
            ['slug' => 'cpanel', 'name' => 'cPanel', 'name_ru' => 'cPanel', 'group' => 'panel', 'icon' => 'layout'],
            ['slug' => 'hpanel', 'name' => 'hPanel', 'name_ru' => 'hPanel', 'group' => 'panel', 'icon' => 'layout'],
            ['slug' => 'money-back', 'name' => 'Money-back Guarantee', 'name_ru' => 'Гарантия возврата', 'group' => 'support', 'icon' => 'shield'],
            ['slug' => 'daily-backups', 'name' => 'Daily Backups', 'name_ru' => 'Ежедневные бэкапы', 'group' => 'security', 'icon' => 'save'],
            ['slug' => 'cdn', 'name' => 'CDN Included', 'name_ru' => 'CDN включён', 'group' => 'performance', 'icon' => 'zap'],
            ['slug' => 'email-hosting', 'name' => 'Email Hosting', 'name_ru' => 'Почтовый хостинг', 'group' => 'hosting', 'icon' => 'mail'],
            ['slug' => '24-7-support', 'name' => '24/7 Support', 'name_ru' => 'Поддержка 24/7', 'group' => 'support', 'icon' => 'headphones'],
            ['slug' => 'wordpress-optimized', 'name' => 'WordPress Optimized', 'name_ru' => 'Оптимизация под WP', 'group' => 'hosting', 'icon' => 'wordpress'],
            ['slug' => 'unlimited-bandwidth', 'name' => 'Unmetered Bandwidth', 'name_ru' => 'Безлимитный трафик', 'group' => 'hosting', 'icon' => 'activity'],
        ];

        foreach ($features as $i => $feature) {
            Feature::query()->updateOrCreate(
                ['slug' => $feature['slug']],
                array_merge($feature, ['sort_order' => $i + 1])
            );
        }
    }
}
