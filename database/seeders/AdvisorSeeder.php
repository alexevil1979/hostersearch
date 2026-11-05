<?php

namespace Database\Seeders;

use App\Models\AdvisorAnswer;
use App\Models\AdvisorQuestion;
use Illuminate\Database\Seeder;

class AdvisorSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'slug' => 'site-type',
                'question' => 'What type of website are you building?',
                'question_ru' => 'Какой сайт вы создаёте?',
                'type' => 'single',
                'answers' => [
                    ['slug' => 'blog', 'label' => 'Blog / Personal', 'label_ru' => 'Блог / Личный', 'weights' => ['shared' => 3, 'wordpress' => 2]],
                    ['slug' => 'business', 'label' => 'Business / Brochure', 'label_ru' => 'Бизнес-сайт', 'weights' => ['shared' => 2, 'wordpress' => 2, 'cloud' => 1]],
                    ['slug' => 'ecommerce', 'label' => 'Online Store', 'label_ru' => 'Интернет-магазин', 'weights' => ['cloud' => 3, 'wordpress' => 2, 'vps' => 2]],
                    ['slug' => 'app', 'label' => 'Web App / SaaS', 'label_ru' => 'Веб-приложение / SaaS', 'weights' => ['vps' => 3, 'cloud' => 3, 'dedicated' => 2]],
                ],
            ],
            [
                'slug' => 'budget',
                'question' => 'What is your monthly budget?',
                'question_ru' => 'Какой у вас месячный бюджет?',
                'type' => 'single',
                'answers' => [
                    ['slug' => 'under-5', 'label' => 'Under $5', 'label_ru' => 'До $5', 'weights' => ['shared' => 3, 'price_max' => 5]],
                    ['slug' => '5-15', 'label' => '$5 – $15', 'label_ru' => '$5 – $15', 'weights' => ['shared' => 2, 'vps' => 2, 'price_max' => 15]],
                    ['slug' => '15-50', 'label' => '$15 – $50', 'label_ru' => '$15 – $50', 'weights' => ['cloud' => 3, 'vps' => 2, 'wordpress' => 2, 'price_max' => 50]],
                    ['slug' => '50-plus', 'label' => '$50+', 'label_ru' => '$50+', 'weights' => ['dedicated' => 3, 'cloud' => 2, 'wordpress' => 2, 'price_max' => 999]],
                ],
            ],
            [
                'slug' => 'location',
                'question' => 'Where are most of your visitors?',
                'question_ru' => 'Где находится основная аудитория?',
                'type' => 'single',
                'answers' => [
                    ['slug' => 'us', 'label' => 'United States', 'label_ru' => 'США', 'weights' => ['location' => 'US']],
                    ['slug' => 'eu', 'label' => 'Europe', 'label_ru' => 'Европа', 'weights' => ['location' => 'EU']],
                    ['slug' => 'global', 'label' => 'Global / Mixed', 'label_ru' => 'По всему миру', 'weights' => ['cdn' => 3]],
                ],
            ],
            [
                'slug' => 'features',
                'question' => 'Which features matter most?',
                'question_ru' => 'Какие функции важнее всего?',
                'type' => 'multiple',
                'answers' => [
                    ['slug' => 'wordpress', 'label' => 'WordPress tools', 'label_ru' => 'Инструменты WordPress', 'weights' => ['wordpress-optimized' => 3, 'wordpress' => 3]],
                    ['slug' => 'support', 'label' => '24/7 support', 'label_ru' => 'Поддержка 24/7', 'weights' => ['24-7-support' => 3]],
                    ['slug' => 'performance', 'label' => 'Top performance', 'label_ru' => 'Максимальная скорость', 'weights' => ['cdn' => 2, 'ssd-storage' => 2, 'cloud' => 2]],
                    ['slug' => 'backups', 'label' => 'Daily backups', 'label_ru' => 'Ежедневные бэкапы', 'weights' => ['daily-backups' => 3]],
                ],
            ],
            [
                'slug' => 'experience',
                'question' => 'How experienced are you with hosting?',
                'question_ru' => 'Какой у вас опыт с хостингом?',
                'type' => 'single',
                'answers' => [
                    ['slug' => 'beginner', 'label' => 'Beginner', 'label_ru' => 'Новичок', 'weights' => ['shared' => 3, 'wordpress' => 2]],
                    ['slug' => 'intermediate', 'label' => 'Intermediate', 'label_ru' => 'Средний', 'weights' => ['vps' => 2, 'cloud' => 2]],
                    ['slug' => 'expert', 'label' => 'Developer / Expert', 'label_ru' => 'Разработчик / Эксперт', 'weights' => ['vps' => 3, 'dedicated' => 2, 'cloud' => 3]],
                ],
            ],
        ];

        foreach ($questions as $qIndex => $q) {
            $question = AdvisorQuestion::query()->updateOrCreate(
                ['slug' => $q['slug']],
                [
                    'question' => $q['question'],
                    'question_ru' => $q['question_ru'],
                    'type' => $q['type'],
                    'sort_order' => $qIndex + 1,
                    'is_active' => true,
                ]
            );

            foreach ($q['answers'] as $aIndex => $a) {
                AdvisorAnswer::query()->updateOrCreate(
                    [
                        'advisor_question_id' => $question->id,
                        'slug' => $a['slug'],
                    ],
                    [
                        'label' => $a['label'],
                        'label_ru' => $a['label_ru'],
                        'weights' => $a['weights'],
                        'sort_order' => $aIndex + 1,
                    ]
                );
            }
        }
    }
}
