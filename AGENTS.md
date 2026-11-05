# HosterSearch — карта для агента

Агрегатор отзывов о хостинге (HostAdvice-style): компании, планы, купоны, сравнение, advisor-quiz, admin, partner claim.

**Стек:** Laravel 12 · PHP 8.2+ · MySQL/MariaDB · Tailwind/Vite · Apache

## Карта (читать по задаче)

| Путь | Зачем |
|------|--------|
| `README.md` | setup, demo-аккаунты, Apache |
| `routes/web.php`, `routes/auth.php` | маршруты |
| `app/Http/Controllers/` | публичка; `Admin/`, `Partner/`, `Auth/` |
| `app/Models/` | домен (Company, Plan, Review, Coupon, …) |
| `app/Enums/` | статусы/роли |
| `database/migrations/`, `database/seeders/` | схема и сиды |
| `resources/views/`, `resources/css/` | UI |
| `apache-vhost.conf.example` | vhost |

## Правила

- Не коммитить `.env`, дампы `*.sql`, секреты.
- Не читать/править `vendor/`, `node_modules/` без явной нужды.
- Перед широким поиском по репо — сначала таблица выше.
- Локальный запуск и demo-пароли — только в `README.md`.
