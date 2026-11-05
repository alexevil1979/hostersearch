<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about',
                'title' => 'About HosterSearch',
                'title_ru' => 'О HosterSearch',
                'body' => '<p>HosterSearch is an independent hosting review aggregator. We test providers, collect verified user feedback, and help you pick the right plan with confidence.</p>',
                'body_ru' => '<p>HosterSearch — независимый агрегатор обзоров хостинга. Мы тестируем провайдеров, собираем отзывы и помогаем выбрать подходящий тариф.</p>',
            ],
            [
                'slug' => 'methodology',
                'title' => 'Review Methodology',
                'title_ru' => 'Методология обзоров',
                'body' => '<p>Our scores combine lab benchmarks (TTFB, uptime samples), feature depth, pricing fairness, and moderated user reviews. Expert reviews are written by our editorial team and updated at least annually.</p><ul><li>Performance &amp; uptime</li><li>Features &amp; ease of use</li><li>Support quality</li><li>Pricing &amp; renewals</li><li>Verified user sentiment</li></ul>',
                'body_ru' => '<p>Наши оценки сочетают лабораторные тесты, глубину функций, справедливость цен и модерируемые отзывы пользователей.</p>',
            ],
            [
                'slug' => 'faq',
                'title' => 'Frequently Asked Questions',
                'title_ru' => 'Частые вопросы',
                'body' => '<h2>How do you make money?</h2><p>We may earn commissions when you click Visit Site and purchase through our links. Rankings are not sold.</p><h2>Can I claim my company?</h2><p>Yes. Use the Claim page on any company profile. After verification you get a partner dashboard.</p>',
                'body_ru' => '<h2>Как вы зарабатываете?</h2><p>Мы можем получать комиссию по партнёрским ссылкам. Места в рейтинге не продаются.</p>',
            ],
            [
                'slug' => 'claim',
                'title' => 'Claim Your Hosting Company',
                'title_ru' => 'Заявите права на компанию',
                'body' => '<p>Are you an official representative? Claim your listing to reply to reviews, update plans, and access analytics.</p>',
                'body_ru' => '<p>Вы официальный представитель? Заявите листинг, чтобы отвечать на отзывы, обновлять тарифы и смотреть аналитику.</p>',
            ],
            [
                'slug' => 'who-is-hosting',
                'title' => 'Who Is Hosting This Website?',
                'title_ru' => 'Кто хостит этот сайт?',
                'body' => '<p>Enter a domain to discover hosting clues from public DNS and IP ownership data. Results are informational and may vary.</p>',
                'body_ru' => '<p>Введите домен, чтобы узнать подсказки о хостинге по публичным DNS и данным об IP.</p>',
            ],
            [
                'slug' => 'privacy',
                'title' => 'Privacy Policy',
                'title_ru' => 'Политика конфиденциальности',
                'body' => '<p>We store account data, review content, and anonymized click analytics needed to operate the service.</p>',
                'body_ru' => '<p>Мы храним данные аккаунта, отзывы и обезличенную аналитику кликов, необходимые для работы сервиса.</p>',
            ],
            [
                'slug' => 'terms',
                'title' => 'Terms of Use',
                'title_ru' => 'Условия использования',
                'body' => '<p>By using HosterSearch you agree not to submit spam reviews or abuse claim workflows.</p>',
                'body_ru' => '<p>Используя HosterSearch, вы соглашаетесь не публиковать спам-отзывы и не злоупотреблять процедурой claim.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(
                ['slug' => $page['slug']],
                array_merge($page, [
                    'meta_title' => $page['title'].' | HosterSearch',
                    'meta_description' => strip_tags($page['body']),
                    'is_published' => true,
                    'template' => 'default',
                ])
            );
        }
    }
}
