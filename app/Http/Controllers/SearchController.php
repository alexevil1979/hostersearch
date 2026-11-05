<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Page;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));

        $companies = collect();
        $reviews = collect();
        $pages = collect();

        if (strlen($q) >= 2) {
            try {
                $companies = Company::query()->approved()->with('features')->search($q)->limit(20)->get();
            } catch (\Throwable) {
                $companies = Company::query()
                    ->approved()
                    ->with('features')
                    ->where(function ($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%")
                            ->orWhere('name_ru', 'like', "%{$q}%")
                            ->orWhere('short_description', 'like', "%{$q}%");
                    })
                    ->limit(20)
                    ->get();
            }

            try {
                $reviews = Review::query()->approved()->search($q)->with('company')->limit(10)->get();
            } catch (\Throwable) {
                $reviews = Review::query()
                    ->approved()
                    ->where(function ($query) use ($q) {
                        $query->where('title', 'like', "%{$q}%")
                            ->orWhere('body', 'like', "%{$q}%");
                    })
                    ->with('company')
                    ->limit(10)
                    ->get();
            }

            $pages = Page::query()
                ->where('is_published', true)
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('title_ru', 'like', "%{$q}%")
                        ->orWhere('body', 'like', "%{$q}%");
                })
                ->limit(10)
                ->get();
        }

        return view('public.search', compact('q', 'companies', 'reviews', 'pages'));
    }
}
