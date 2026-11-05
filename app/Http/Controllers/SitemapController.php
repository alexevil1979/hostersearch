<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Comparison;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('coupons.index'), 'priority' => '0.7'],
            ['loc' => route('comparisons.index'), 'priority' => '0.7'],
            ['loc' => route('advisor.index'), 'priority' => '0.7'],
            ['loc' => route('claim.create'), 'priority' => '0.5'],
        ]);

        Category::query()->where('is_active', true)->each(function (Category $category) use ($urls) {
            $urls->push(['loc' => route('categories.show', $category), 'priority' => '0.8']);
        });

        Company::query()->approved()->each(function (Company $company) use ($urls) {
            $urls->push([
                'loc' => route('companies.show', $company),
                'priority' => '0.9',
                'lastmod' => optional($company->updated_at)->toAtomString(),
            ]);
        });

        Comparison::query()->where('is_published', true)->each(function (Comparison $comparison) use ($urls) {
            $urls->push(['loc' => route('comparisons.show', $comparison->slug), 'priority' => '0.6']);
        });

        Page::query()->where('is_published', true)->each(function (Page $page) use ($urls) {
            $urls->push(['loc' => route('pages.show', $page->slug), 'priority' => '0.5']);
        });

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
