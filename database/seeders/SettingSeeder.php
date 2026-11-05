<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'HosterSearch', 'type' => 'string'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Find the best web hosting for your next project', 'type' => 'string'],
            ['group' => 'general', 'key' => 'support_email', 'value' => 'hello@hostersearch.test', 'type' => 'string'],
            ['group' => 'seo', 'key' => 'seo_title_suffix', 'value' => ' | HosterSearch', 'type' => 'string'],
            ['group' => 'seo', 'key' => 'seo_default_description', 'value' => 'Compare web hosting providers, read expert and user reviews, find exclusive coupons.', 'type' => 'string'],
            ['group' => 'i18n', 'key' => 'default_locale', 'value' => 'en', 'type' => 'string'],
            ['group' => 'i18n', 'key' => 'supported_locales', 'value' => json_encode(['en', 'ru']), 'type' => 'json'],
            ['group' => 'i18n', 'key' => 'default_currency', 'value' => 'USD', 'type' => 'string'],
            ['group' => 'i18n', 'key' => 'supported_currencies', 'value' => json_encode(['USD', 'EUR']), 'type' => 'json'],
            ['group' => 'i18n', 'key' => 'usd_to_eur_rate', 'value' => '0.92', 'type' => 'string'],
            ['group' => 'features', 'key' => 'reviews_require_auth', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'features', 'key' => 'claim_enabled', 'value' => '1', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
