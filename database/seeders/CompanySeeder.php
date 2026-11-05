<?php

namespace Database\Seeders;

use App\Enums\CompanyStatus;
use App\Enums\ReviewStatus;
use App\Enums\ReviewType;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Coupon;
use App\Models\Feature;
use App\Models\Plan;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $features = Feature::query()->pluck('id', 'slug');
        $types = CompanyType::query()->pluck('id', 'slug');
        $categories = Category::query()->pluck('id', 'slug');
        $partner = User::query()->where('role', UserRole::Partner)->first();
        $reviewers = User::query()->where('role', UserRole::User)->where('email', '!=', 'user@hostersearch.test')->get();

        $companies = $this->companyData();

        foreach ($companies as $index => $data) {
            $company = Company::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'name_ru' => $data['name_ru'] ?? $data['name'],
                    'website' => $data['website'],
                    'affiliate_url' => $data['website'].'?ref=hostersearch',
                    'founded' => $data['founded'],
                    'headquarters' => $data['headquarters'],
                    'short_description' => $data['short'],
                    'short_description_ru' => $data['short_ru'],
                    'description' => $data['description'],
                    'description_ru' => $data['description_ru'],
                    'expert_review' => $data['expert'],
                    'expert_review_ru' => $data['expert_ru'],
                    'pros' => $data['pros'],
                    'pros_ru' => $data['pros_ru'],
                    'cons' => $data['cons'],
                    'cons_ru' => $data['cons_ru'],
                    'rating_breakdown' => $data['ratings'],
                    'overall_score' => $data['score'],
                    'review_count' => 0,
                    'price_from' => $data['price_from'],
                    'price_currency' => 'USD',
                    'status' => $index === 0 && $partner
                        ? CompanyStatus::Claimed
                        : CompanyStatus::Approved,
                    'claimed_by_user_id' => $index === 0 ? $partner?->id : null,
                    'claimed_at' => $index === 0 ? now() : null,
                    'is_featured' => $index < 6,
                    'is_recommended' => $index < 8,
                    'view_count' => random_int(500, 20000),
                    'click_count' => random_int(50, 2000),
                    'meta_title' => $data['name'].' Reviews 2026 — Pricing, Pros & Cons',
                    'meta_description' => $data['short'],
                    'sort_order' => $index + 1,
                ]
            );

            $company->locations()->delete();
            foreach ($data['locations'] as $loc) {
                $company->locations()->create($loc);
            }

            $company->types()->sync(
                collect($data['types'])->map(fn ($slug) => $types[$slug])->filter()->all()
            );

            $company->features()->sync(
                collect($data['features'])->mapWithKeys(fn ($slug) => [$features[$slug] => ['value' => '1']])->all()
            );

            $categorySync = [];
            foreach ($data['categories'] as $i => $slug) {
                if (! isset($categories[$slug])) {
                    continue;
                }
                $categorySync[$categories[$slug]] = [
                    'is_primary' => $i === 0,
                    'rank_in_category' => $index + 1,
                ];
            }
            $company->categories()->sync($categorySync);

            $company->plans()->forceDelete();
            foreach ($data['plans'] as $pIndex => $planData) {
                Plan::query()->create([
                    'company_id' => $company->id,
                    'slug' => Str::slug($planData['name']),
                    'name' => $planData['name'],
                    'name_ru' => $planData['name_ru'] ?? $planData['name'],
                    'price' => $planData['price'],
                    'price_renewal' => $planData['renewal'] ?? round($planData['price'] * 2.5, 2),
                    'currency' => 'USD',
                    'billing_cycle' => 'monthly',
                    'space_gb' => $planData['space'],
                    'cpu' => $planData['cpu'] ?? null,
                    'ram' => $planData['ram'] ?? null,
                    'sites' => $planData['sites'],
                    'bandwidth' => $planData['bandwidth'],
                    'os' => $planData['os'] ?? 'Linux',
                    'panel' => $planData['panel'] ?? 'cPanel',
                    'score' => $planData['score'] ?? $data['score'],
                    'is_featured' => $pIndex === 1,
                    'is_active' => true,
                    'affiliate_url' => $company->affiliate_url,
                    'sort_order' => $pIndex + 1,
                ]);
            }

            $company->coupons()->forceDelete();
            if (! empty($data['coupon'])) {
                Coupon::query()->create([
                    'company_id' => $company->id,
                    'code' => $data['coupon']['code'],
                    'title' => $data['coupon']['title'],
                    'title_ru' => $data['coupon']['title_ru'],
                    'description' => $data['coupon']['description'],
                    'discount' => $data['coupon']['discount'],
                    'affiliate_url' => $company->affiliate_url,
                    'expires_at' => now()->addMonths(3),
                    'exclusive' => $index < 5,
                    'is_active' => true,
                    'clicks' => random_int(10, 500),
                    'sort_order' => $index + 1,
                ]);
            }

            $company->reviews()->forceDelete();
            // Expert review
            Review::query()->create([
                'company_id' => $company->id,
                'user_id' => null,
                'type' => ReviewType::Expert,
                'author_name' => 'HosterSearch Editorial',
                'rating_overall' => $data['score'],
                'ratings_json' => $data['ratings'],
                'title' => $data['name'].' Expert Review',
                'body' => $data['expert'],
                'pros' => $data['pros'],
                'cons' => $data['cons'],
                'status' => ReviewStatus::Approved,
                'is_featured' => true,
                'is_verified' => true,
                'locale' => 'en',
            ]);

            // User reviews
            $reviewCount = random_int(3, 6);
            for ($r = 0; $r < $reviewCount; $r++) {
                $reviewer = $reviewers->random();
                $rating = round(max(3.0, min(5.0, $data['score'] + (random_int(-8, 8) / 10))), 1);
                Review::query()->create([
                    'company_id' => $company->id,
                    'user_id' => $reviewer->id,
                    'type' => ReviewType::User,
                    'author_name' => $reviewer->name,
                    'rating_overall' => $rating,
                    'ratings_json' => [
                        'pricing' => max(1, min(5, $rating + random_int(-5, 5) / 10)),
                        'features' => max(1, min(5, $rating + random_int(-5, 5) / 10)),
                        'performance' => max(1, min(5, $rating + random_int(-5, 5) / 10)),
                        'ease' => max(1, min(5, $rating + random_int(-5, 5) / 10)),
                        'support' => max(1, min(5, $rating + random_int(-5, 5) / 10)),
                    ],
                    'title' => $this->reviewTitles()[array_rand($this->reviewTitles())],
                    'body' => $this->reviewBodies()[array_rand($this->reviewBodies())],
                    'pros' => ['Good uptime', 'Easy setup'],
                    'cons' => ['Renewal pricing'],
                    'status' => ReviewStatus::Approved,
                    'is_featured' => $r === 0,
                    'is_verified' => true,
                    'helpful_count' => random_int(0, 40),
                    'locale' => 'en',
                ]);
            }

            $company->update([
                'review_count' => $company->reviews()->where('status', ReviewStatus::Approved)->count(),
            ]);
        }
    }

    private function companyData(): array
    {
        return [
            $this->c('hostinger', 'Hostinger', 'https://www.hostinger.com', 2003, 'Lithuania', 4.8, 2.99,
                ['shared', 'vps', 'cloud', 'wordpress'], ['web-hosting', 'vps', 'wordpress-hosting', 'cloud-hosting'],
                ['free-ssl', 'ssd-storage', 'hpanel', 'money-back', 'cdn', '24-7-support', 'wordpress-optimized'],
                [['country_code' => 'LT', 'country_name' => 'Lithuania', 'city' => 'Kaunas', 'is_headquarters' => true], ['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Boston']],
                [['name' => 'Premium', 'price' => 2.99, 'space' => 100, 'sites' => 100, 'bandwidth' => 'Unmetered', 'panel' => 'hPanel'], ['name' => 'Business', 'price' => 3.99, 'space' => 200, 'sites' => 100, 'bandwidth' => 'Unmetered', 'panel' => 'hPanel'], ['name' => 'Cloud Startup', 'price' => 9.99, 'space' => 200, 'sites' => 300, 'bandwidth' => 'Unmetered', 'cpu' => '2 vCPU', 'ram' => '3 GB', 'panel' => 'hPanel']],
                ['code' => 'HOSTERSEARCH', 'title' => 'Up to 79% Off Hostinger', 'title_ru' => 'Скидка до 79% на Hostinger', 'description' => 'Exclusive discount on all shared and cloud plans.', 'discount' => '79% OFF']),
            $this->c('ionos', 'IONOS', 'https://www.ionos.com', 1988, 'Germany', 4.5, 1.00,
                ['shared', 'vps', 'dedicated'], ['web-hosting', 'vps', 'dedicated-servers'],
                ['free-ssl', 'free-domain', 'ssd-storage', 'cpanel', 'money-back', 'email-hosting', '24-7-support'],
                [['country_code' => 'DE', 'country_name' => 'Germany', 'city' => 'Montabaur', 'is_headquarters' => true], ['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Philadelphia']],
                [['name' => 'Essential', 'price' => 1.00, 'space' => 10, 'sites' => 1, 'bandwidth' => 'Unmetered'], ['name' => 'Expert', 'price' => 4.00, 'space' => null, 'sites' => null, 'bandwidth' => 'Unmetered'], ['name' => 'VPS Linux L', 'price' => 12.00, 'space' => 240, 'sites' => null, 'bandwidth' => 'Unmetered', 'cpu' => '4 vCPU', 'ram' => '8 GB']],
                ['code' => 'IONOS24', 'title' => 'IONOS from $1/mo', 'title_ru' => 'IONOS от $1/мес', 'description' => 'Starter hosting with free domain.', 'discount' => 'From $1']),
            $this->c('cloudways', 'Cloudways', 'https://www.cloudways.com', 2012, 'Malta', 4.7, 11.00,
                ['cloud', 'wordpress'], ['cloud-hosting', 'wordpress-hosting', 'web-hosting'],
                ['free-ssl', 'ssd-storage', 'daily-backups', 'cdn', '24-7-support', 'wordpress-optimized'],
                [['country_code' => 'MT', 'country_name' => 'Malta', 'city' => 'St. Julians', 'is_headquarters' => true]],
                [['name' => 'DigitalOcean 1GB', 'price' => 11.00, 'space' => 25, 'sites' => null, 'bandwidth' => '1 TB', 'cpu' => '1 vCPU', 'ram' => '1 GB', 'panel' => 'Cloudways'], ['name' => 'DigitalOcean 2GB', 'price' => 24.00, 'space' => 50, 'sites' => null, 'bandwidth' => '2 TB', 'cpu' => '1 vCPU', 'ram' => '2 GB', 'panel' => 'Cloudways'], ['name' => 'Vultr HF 2GB', 'price' => 32.00, 'space' => 64, 'sites' => null, 'bandwidth' => '3 TB', 'cpu' => '1 vCPU', 'ram' => '2 GB', 'panel' => 'Cloudways']],
                ['code' => 'CWSAVE', 'title' => 'Cloudways Promo Credits', 'title_ru' => 'Бонусы Cloudways', 'description' => 'Get free hosting credits on signup.', 'discount' => '$100 credits']),
            $this->c('namecheap', 'Namecheap', 'https://www.namecheap.com', 2000, 'USA', 4.4, 1.98,
                ['shared', 'vps', 'wordpress'], ['web-hosting', 'domains', 'vps', 'wordpress-hosting'],
                ['free-ssl', 'ssd-storage', 'cpanel', 'money-back', 'email-hosting', '24-7-support'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Phoenix', 'is_headquarters' => true]],
                [['name' => 'Stellar', 'price' => 1.98, 'space' => 20, 'sites' => 3, 'bandwidth' => 'Unmetered'], ['name' => 'Stellar Plus', 'price' => 2.88, 'space' => 40, 'sites' => null, 'bandwidth' => 'Unmetered'], ['name' => 'VPS 1', 'price' => 6.88, 'space' => 20, 'sites' => null, 'bandwidth' => '1 TB', 'cpu' => '1 core', 'ram' => '1 GB']],
                ['code' => 'NCHOST', 'title' => 'Namecheap Hosting Deal', 'title_ru' => 'Скидка Namecheap', 'description' => 'Save on Stellar shared hosting.', 'discount' => '70% OFF']),
            $this->c('bluehost', 'Bluehost', 'https://www.bluehost.com', 2003, 'USA', 4.3, 2.95,
                ['shared', 'wordpress', 'vps'], ['web-hosting', 'wordpress-hosting', 'vps'],
                ['free-ssl', 'free-domain', 'ssd-storage', 'cpanel', 'money-back', 'wordpress-optimized', '24-7-support'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Provo', 'is_headquarters' => true]],
                [['name' => 'Basic', 'price' => 2.95, 'space' => 10, 'sites' => 1, 'bandwidth' => 'Unmetered'], ['name' => 'Choice Plus', 'price' => 5.45, 'space' => 50, 'sites' => null, 'bandwidth' => 'Unmetered'], ['name' => 'Online Store', 'price' => 9.95, 'space' => 50, 'sites' => null, 'bandwidth' => 'Unmetered']],
                ['code' => 'BHDEAL', 'title' => 'Bluehost WordPress Offer', 'title_ru' => 'Оффер Bluehost WP', 'description' => 'Recommended for WordPress beginners.', 'discount' => 'Up to 68% OFF']),
            $this->c('siteground', 'SiteGround', 'https://www.siteground.com', 2004, 'Bulgaria', 4.6, 2.99,
                ['shared', 'wordpress', 'cloud'], ['web-hosting', 'wordpress-hosting', 'cloud-hosting'],
                ['free-ssl', 'ssd-storage', 'daily-backups', 'cdn', 'money-back', 'wordpress-optimized', '24-7-support'],
                [['country_code' => 'BG', 'country_name' => 'Bulgaria', 'city' => 'Sofia', 'is_headquarters' => true], ['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Iowa']],
                [['name' => 'StartUp', 'price' => 2.99, 'space' => 10, 'sites' => 1, 'bandwidth' => '~10k visits', 'panel' => 'Site Tools'], ['name' => 'GrowBig', 'price' => 4.99, 'space' => 20, 'sites' => null, 'bandwidth' => '~100k visits', 'panel' => 'Site Tools'], ['name' => 'GoGeek', 'price' => 7.99, 'space' => 40, 'sites' => null, 'bandwidth' => '~400k visits', 'panel' => 'Site Tools']],
                ['code' => 'SGSAVE', 'title' => 'SiteGround Launch Discount', 'title_ru' => 'Скидка SiteGround', 'description' => 'Discounted intro pricing on shared plans.', 'discount' => 'Up to 83% OFF']),
            $this->c('a2hosting', 'A2 Hosting', 'https://www.a2hosting.com', 2001, 'USA', 4.5, 2.99,
                ['shared', 'vps', 'dedicated', 'wordpress'], ['web-hosting', 'vps', 'dedicated-servers', 'wordpress-hosting'],
                ['free-ssl', 'ssd-storage', 'cpanel', 'money-back', 'cdn', '24-7-support', 'wordpress-optimized'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Ann Arbor', 'is_headquarters' => true]],
                [['name' => 'Startup', 'price' => 2.99, 'space' => 100, 'sites' => 1, 'bandwidth' => 'Unmetered'], ['name' => 'Drive', 'price' => 5.99, 'space' => null, 'sites' => null, 'bandwidth' => 'Unmetered'], ['name' => 'Turbo Max', 'price' => 12.99, 'space' => null, 'sites' => null, 'bandwidth' => 'Unmetered']],
                ['code' => 'A2TURBO', 'title' => 'A2 Turbo Boost Deal', 'title_ru' => 'Скидка A2 Turbo', 'description' => 'Faster Turbo servers at intro rates.', 'discount' => '69% OFF']),
            $this->c('dreamhost', 'DreamHost', 'https://www.dreamhost.com', 1997, 'USA', 4.2, 2.59,
                ['shared', 'wordpress', 'dedicated'], ['web-hosting', 'wordpress-hosting', 'dedicated-servers'],
                ['free-ssl', 'ssd-storage', 'money-back', 'email-hosting', 'wordpress-optimized', 'unlimited-bandwidth'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Brea', 'is_headquarters' => true]],
                [['name' => 'Shared Starter', 'price' => 2.59, 'space' => 50, 'sites' => 1, 'bandwidth' => 'Unmetered', 'panel' => 'DreamHost'], ['name' => 'Shared Unlimited', 'price' => 3.95, 'space' => null, 'sites' => null, 'bandwidth' => 'Unmetered', 'panel' => 'DreamHost'], ['name' => 'DreamPress', 'price' => 16.95, 'space' => 30, 'sites' => 1, 'bandwidth' => 'Unmetered', 'panel' => 'DreamHost']],
                ['code' => 'DHPROMO', 'title' => 'DreamHost Shared Sale', 'title_ru' => 'Акция DreamHost', 'description' => 'Low intro price on shared hosting.', 'discount' => '67% OFF']),
            $this->c('hostgator', 'HostGator', 'https://www.hostgator.com', 2002, 'USA', 4.1, 2.75,
                ['shared', 'vps', 'dedicated', 'reseller'], ['web-hosting', 'vps', 'dedicated-servers'],
                ['free-ssl', 'free-domain', 'ssd-storage', 'cpanel', 'money-back', 'email-hosting', '24-7-support'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Houston', 'is_headquarters' => true]],
                [['name' => 'Hatchling', 'price' => 2.75, 'space' => null, 'sites' => 1, 'bandwidth' => 'Unmetered'], ['name' => 'Baby', 'price' => 3.50, 'space' => null, 'sites' => null, 'bandwidth' => 'Unmetered'], ['name' => 'Business', 'price' => 5.25, 'space' => null, 'sites' => null, 'bandwidth' => 'Unmetered']],
                ['code' => 'HGATOR', 'title' => 'HostGator Intro Pricing', 'title_ru' => 'Цены HostGator', 'description' => 'Save on Hatchling and Baby plans.', 'discount' => '60% OFF']),
            $this->c('inmotion', 'InMotion Hosting', 'https://www.inmotionhosting.com', 2001, 'USA', 4.4, 2.49,
                ['shared', 'vps', 'dedicated', 'wordpress'], ['web-hosting', 'vps', 'dedicated-servers', 'wordpress-hosting'],
                ['free-ssl', 'ssd-storage', 'cpanel', 'money-back', 'daily-backups', '24-7-support', 'wordpress-optimized'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Los Angeles', 'is_headquarters' => true]],
                [['name' => 'Core', 'price' => 2.49, 'space' => 100, 'sites' => 2, 'bandwidth' => 'Unmetered'], ['name' => 'Launch', 'price' => 4.99, 'space' => null, 'sites' => null, 'bandwidth' => 'Unmetered'], ['name' => 'Power', 'price' => 8.99, 'space' => null, 'sites' => null, 'bandwidth' => 'Unmetered']],
                ['code' => 'IMHSAVE', 'title' => 'InMotion Shared Discount', 'title_ru' => 'Скидка InMotion', 'description' => 'Business-class shared hosting deals.', 'discount' => 'Up to 71% OFF']),
            $this->c('liquidweb', 'Liquid Web', 'https://www.liquidweb.com', 1997, 'USA', 4.6, 15.00,
                ['vps', 'dedicated', 'cloud'], ['vps', 'dedicated-servers', 'cloud-hosting'],
                ['free-ssl', 'ssd-storage', 'cpanel', 'daily-backups', '24-7-support', 'cdn'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Lansing', 'is_headquarters' => true]],
                [['name' => 'VPS 2GB', 'price' => 15.00, 'space' => 40, 'sites' => null, 'bandwidth' => '10 TB', 'cpu' => '2 vCPU', 'ram' => '2 GB'], ['name' => 'VPS 4GB', 'price' => 30.00, 'space' => 100, 'sites' => null, 'bandwidth' => '10 TB', 'cpu' => '4 vCPU', 'ram' => '4 GB'], ['name' => 'Cloud VPS', 'price' => 59.00, 'space' => 100, 'sites' => null, 'bandwidth' => '5 TB', 'cpu' => '4 vCPU', 'ram' => '4 GB']],
                ['code' => 'LWCLOUD', 'title' => 'Liquid Web Cloud Offer', 'title_ru' => 'Оффер Liquid Web', 'description' => 'Managed VPS with hero support.', 'discount' => 'Special pricing']),
            $this->c('digitalocean', 'DigitalOcean', 'https://www.digitalocean.com', 2011, 'USA', 4.7, 4.00,
                ['cloud', 'vps'], ['cloud-hosting', 'vps', 'ai-tools'],
                ['ssd-storage', 'cdn', 'unlimited-bandwidth'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'New York', 'is_headquarters' => true]],
                [['name' => 'Basic Droplet', 'price' => 4.00, 'space' => 10, 'sites' => null, 'bandwidth' => '500 GB', 'cpu' => '1 vCPU', 'ram' => '512 MB', 'panel' => 'Control Panel'], ['name' => 'Regular 1GB', 'price' => 6.00, 'space' => 25, 'sites' => null, 'bandwidth' => '1 TB', 'cpu' => '1 vCPU', 'ram' => '1 GB', 'panel' => 'Control Panel'], ['name' => 'CPU-Optimized', 'price' => 42.00, 'space' => 25, 'sites' => null, 'bandwidth' => '4 TB', 'cpu' => '2 vCPU', 'ram' => '4 GB', 'panel' => 'Control Panel']],
                ['code' => 'DOCREDITS', 'title' => 'DigitalOcean Free Credits', 'title_ru' => 'Кредиты DigitalOcean', 'description' => 'New account hosting credits.', 'discount' => '$200 credits']),
            $this->c('vultr', 'Vultr', 'https://www.vultr.com', 2014, 'USA', 4.6, 2.50,
                ['cloud', 'vps', 'dedicated'], ['cloud-hosting', 'vps', 'dedicated-servers'],
                ['ssd-storage', 'cdn'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Florida', 'is_headquarters' => true]],
                [['name' => 'Cloud Compute', 'price' => 2.50, 'space' => 10, 'sites' => null, 'bandwidth' => '500 GB', 'cpu' => '1 vCPU', 'ram' => '512 MB', 'panel' => 'Vultr'], ['name' => 'High Frequency', 'price' => 6.00, 'space' => 16, 'sites' => null, 'bandwidth' => '512 GB', 'cpu' => '1 vCPU', 'ram' => '1 GB', 'panel' => 'Vultr'], ['name' => 'Optimized Cloud', 'price' => 28.00, 'space' => 30, 'sites' => null, 'bandwidth' => '2 TB', 'cpu' => '1 vCPU', 'ram' => '2 GB', 'panel' => 'Vultr']],
                ['code' => 'VULTRNEW', 'title' => 'Vultr New User Bonus', 'title_ru' => 'Бонус Vultr', 'description' => 'Credits for new deployments.', 'discount' => '$100 credits']),
            $this->c('godaddy', 'GoDaddy', 'https://www.godaddy.com', 1997, 'USA', 3.9, 5.99,
                ['shared', 'wordpress'], ['web-hosting', 'domains', 'wordpress-hosting', 'website-builders'],
                ['free-ssl', 'free-domain', 'cpanel', 'email-hosting', '24-7-support'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Tempe', 'is_headquarters' => true]],
                [['name' => 'Web Hosting Economy', 'price' => 5.99, 'space' => 10, 'sites' => 1, 'bandwidth' => 'Unmetered'], ['name' => 'Web Hosting Deluxe', 'price' => 7.99, 'space' => null, 'sites' => null, 'bandwidth' => 'Unmetered'], ['name' => 'WordPress Basic', 'price' => 9.99, 'space' => 20, 'sites' => 1, 'bandwidth' => 'Unmetered']],
                ['code' => 'GDHOST', 'title' => 'GoDaddy Hosting Sale', 'title_ru' => 'Распродажа GoDaddy', 'description' => 'Domain + hosting bundles.', 'discount' => 'Up to 60% OFF']),
            $this->c('scaleway', 'Scaleway', 'https://www.scaleway.com', 1999, 'France', 4.3, 3.60,
                ['cloud', 'dedicated', 'vps'], ['cloud-hosting', 'dedicated-servers', 'vps', 'ai-tools'],
                ['ssd-storage', 'cdn'],
                [['country_code' => 'FR', 'country_name' => 'France', 'city' => 'Paris', 'is_headquarters' => true]],
                [['name' => 'PLAY2-PICO', 'price' => 3.60, 'space' => 10, 'sites' => null, 'bandwidth' => '200 Mbps', 'cpu' => '1 vCPU', 'ram' => '1 GB', 'panel' => 'Scaleway'], ['name' => 'PRO2-XXS', 'price' => 14.80, 'space' => 40, 'sites' => null, 'bandwidth' => '500 Mbps', 'cpu' => '2 vCPU', 'ram' => '8 GB', 'panel' => 'Scaleway'], ['name' => 'GPU Starter', 'price' => 79.00, 'space' => 100, 'sites' => null, 'bandwidth' => '1 Gbps', 'cpu' => '8 vCPU', 'ram' => '32 GB', 'panel' => 'Scaleway']],
                ['code' => 'SCALEFR', 'title' => 'Scaleway EU Cloud Deal', 'title_ru' => 'Облако Scaleway EU', 'description' => 'European cloud with GDPR focus.', 'discount' => 'Promotional rates']),
            $this->c('hetzner', 'Hetzner', 'https://www.hetzner.com', 1997, 'Germany', 4.8, 4.49,
                ['cloud', 'dedicated', 'vps'], ['cloud-hosting', 'dedicated-servers', 'vps'],
                ['ssd-storage', 'unlimited-bandwidth'],
                [['country_code' => 'DE', 'country_name' => 'Germany', 'city' => 'Gunzenhausen', 'is_headquarters' => true], ['country_code' => 'FI', 'country_name' => 'Finland', 'city' => 'Helsinki']],
                [['name' => 'CX22', 'price' => 4.49, 'space' => 40, 'sites' => null, 'bandwidth' => '20 TB', 'cpu' => '2 vCPU', 'ram' => '4 GB', 'panel' => 'Hetzner Cloud'], ['name' => 'CX32', 'price' => 7.59, 'space' => 80, 'sites' => null, 'bandwidth' => '20 TB', 'cpu' => '4 vCPU', 'ram' => '8 GB', 'panel' => 'Hetzner Cloud'], ['name' => 'CPX31', 'price' => 14.49, 'space' => 160, 'sites' => null, 'bandwidth' => '20 TB', 'cpu' => '4 vCPU', 'ram' => '8 GB', 'panel' => 'Hetzner Cloud']],
                ['code' => 'HETZNER', 'title' => 'Hetzner Cloud Value', 'title_ru' => 'Выгода Hetzner Cloud', 'description' => 'Best price-performance in EU.', 'discount' => 'Best value']),
            $this->c('kinsta', 'Kinsta', 'https://kinsta.com', 2013, 'USA', 4.7, 35.00,
                ['wordpress', 'cloud'], ['wordpress-hosting', 'cloud-hosting'],
                ['free-ssl', 'ssd-storage', 'daily-backups', 'cdn', '24-7-support', 'wordpress-optimized'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Los Angeles', 'is_headquarters' => true]],
                [['name' => 'Starter', 'price' => 35.00, 'space' => 10, 'sites' => 1, 'bandwidth' => '25k visits', 'panel' => 'MyKinsta'], ['name' => 'Pro', 'price' => 70.00, 'space' => 20, 'sites' => 2, 'bandwidth' => '50k visits', 'panel' => 'MyKinsta'], ['name' => 'Business 1', 'price' => 115.00, 'space' => 30, 'sites' => 3, 'bandwidth' => '100k visits', 'panel' => 'MyKinsta']],
                ['code' => 'KINSTA20', 'title' => 'Kinsta Managed WP', 'title_ru' => 'Kinsta Managed WP', 'description' => 'Premium managed WordPress hosting.', 'discount' => '20% OFF first months']),
            $this->c('wpengine', 'WP Engine', 'https://wpengine.com', 2010, 'USA', 4.5, 20.00,
                ['wordpress', 'cloud'], ['wordpress-hosting', 'cloud-hosting'],
                ['free-ssl', 'ssd-storage', 'daily-backups', 'cdn', '24-7-support', 'wordpress-optimized'],
                [['country_code' => 'US', 'country_name' => 'United States', 'city' => 'Austin', 'is_headquarters' => true]],
                [['name' => 'Startup', 'price' => 20.00, 'space' => 10, 'sites' => 1, 'bandwidth' => '50k visits', 'panel' => 'User Portal'], ['name' => 'Professional', 'price' => 48.00, 'space' => 25, 'sites' => 3, 'bandwidth' => '125k visits', 'panel' => 'User Portal'], ['name' => 'Growth', 'price' => 97.00, 'space' => 50, 'sites' => 10, 'bandwidth' => '300k visits', 'panel' => 'User Portal']],
                ['code' => 'WPEENG', 'title' => 'WP Engine Growth Offer', 'title_ru' => 'Оффер WP Engine', 'description' => 'Agency-grade WordPress hosting.', 'discount' => '4 months free']),
        ];
    }

    private function c(
        string $slug,
        string $name,
        string $website,
        int $founded,
        string $hq,
        float $score,
        float $priceFrom,
        array $types,
        array $categories,
        array $features,
        array $locations,
        array $plans,
        array $coupon
    ): array {
        return [
            'slug' => $slug,
            'name' => $name,
            'name_ru' => $name,
            'website' => $website,
            'founded' => $founded,
            'headquarters' => $hq,
            'score' => $score,
            'price_from' => $priceFrom,
            'types' => $types,
            'categories' => $categories,
            'features' => $features,
            'locations' => array_map(function ($loc) {
                return array_merge([
                    'is_datacenter' => true,
                    'is_headquarters' => false,
                    'city' => null,
                ], $loc);
            }, $locations),
            'plans' => $plans,
            'coupon' => $coupon,
            'short' => "{$name} offers reliable hosting with competitive pricing starting at \${$priceFrom}/mo.",
            'short_ru' => "{$name} — надёжный хостинг с конкурентными ценами от \${$priceFrom}/мес.",
            'description' => "{$name} is a well-known hosting provider founded in {$founded} and headquartered in {$hq}. It serves beginners and professionals with a broad product lineup, transparent pricing, and global infrastructure.",
            'description_ru' => "{$name} — известный хостинг-провайдер, основанный в {$founded} году, штаб-квартира — {$hq}. Подходит новичкам и профессионалам.",
            'expert' => "Our editorial team tested {$name} across performance, support, features, and value. Overall score: {$score}/5. Introductory pricing is attractive; evaluate renewal rates before committing to long terms. WordPress tooling, uptime, and support quality are strong relative to peers in this tier.",
            'expert_ru' => "Редакция протестировала {$name} по производительности, поддержке, функциям и цене. Общий балл: {$score}/5. Обратите внимание на цены продления.",
            'pros' => ['Competitive intro pricing', 'Solid uptime', 'Beginner-friendly tools'],
            'pros_ru' => ['Выгодные стартовые цены', 'Стабильный аптайм', 'Удобно для новичков'],
            'cons' => ['Higher renewal rates', 'Some features gated on higher plans'],
            'cons_ru' => ['Выше цена при продлении', 'Часть функций на старших тарифах'],
            'ratings' => [
                'pricing' => max(3.5, min(5, $score - 0.2)),
                'features' => max(3.5, min(5, $score)),
                'performance' => max(3.5, min(5, $score + 0.1)),
                'ease' => max(3.5, min(5, $score)),
                'support' => max(3.5, min(5, $score - 0.1)),
            ],
        ];
    }

    private function reviewTitles(): array
    {
        return [
            'Great value for money',
            'Solid performance so far',
            'Easy migration experience',
            'Support helped me quickly',
            'Good for WordPress sites',
            'Reliable uptime this year',
        ];
    }

    private function reviewBodies(): array
    {
        return [
            'I moved my portfolio site here and the migration wizard made it painless. Pages load faster than my previous host.',
            'Customer support answered within minutes and walked me through DNS changes. Would recommend for beginners.',
            'Pricing is excellent on the first term. Just remember to budget for renewal before the discount ends.',
            'Uptime has been excellent for my small business site. Staging tools are a nice bonus.',
            'The control panel is cleaner than cPanel alternatives I used before. Backups restore worked as expected.',
            'Good mix of features for the price. SSL, email, and CDN setup took less than an hour.',
        ];
    }
}
