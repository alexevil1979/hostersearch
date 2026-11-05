<?php

namespace App\Http\Controllers;

use App\Models\AdvisorQuestion;
use App\Models\AdvisorResult;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvisorController extends Controller
{
    public function index(): View
    {
        $questions = AdvisorQuestion::query()
            ->where('is_active', true)
            ->with('answers')
            ->orderBy('sort_order')
            ->get();

        return view('public.advisor.index', compact('questions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $questions = AdvisorQuestion::query()
            ->where('is_active', true)
            ->with('answers')
            ->orderBy('sort_order')
            ->get();

        $answersInput = $request->input('answers', []);
        $weights = [];

        foreach ($questions as $question) {
            $selected = $answersInput[$question->slug] ?? null;
            if (! $selected) {
                continue;
            }

            $selected = is_array($selected) ? $selected : [$selected];
            foreach ($question->answers as $answer) {
                if (! in_array($answer->slug, $selected, true)) {
                    continue;
                }
                foreach (($answer->weights ?? []) as $key => $value) {
                    if (is_numeric($value)) {
                        $weights[$key] = ($weights[$key] ?? 0) + (float) $value;
                    } else {
                        $weights[$key] = $value;
                    }
                }
            }
        }

        $companies = Company::query()
            ->approved()
            ->with(['types', 'features'])
            ->get();

        $scored = $companies->map(function (Company $company) use ($weights) {
            $score = (float) $company->overall_score;

            $typeSlugs = $company->types->pluck('slug')->all();
            $featureSlugs = $company->features->pluck('slug')->all();

            foreach ($weights as $key => $value) {
                if (! is_numeric($value)) {
                    if ($key === 'location' && $value === 'EU') {
                        $eu = $company->locations()->whereIn('country_code', ['DE', 'FR', 'LT', 'BG', 'FI', 'MT'])->exists();
                        if ($eu) {
                            $score += 1.5;
                        }
                    }
                    if ($key === 'location' && $value === 'US') {
                        if ($company->locations()->where('country_code', 'US')->exists()) {
                            $score += 1.5;
                        }
                    }
                    continue;
                }

                if (in_array($key, $typeSlugs, true)) {
                    $score += $value;
                }
                if (in_array($key, $featureSlugs, true)) {
                    $score += $value;
                }
                if ($key === 'price_max' && $company->price_from !== null && $company->price_from <= $value) {
                    $score += 1.2;
                }
            }

            return ['company' => $company, 'score' => $score];
        })
            ->sortByDesc('score')
            ->take(5)
            ->values();

        $result = AdvisorResult::query()->create([
            'session_id' => $request->session()->getId(),
            'user_id' => $request->user()?->id,
            'answers' => $answersInput,
            'recommended_company_ids' => $scored->pluck('company.id')->all(),
            'scores' => $scored->mapWithKeys(fn ($row) => [$row['company']->id => $row['score']])->all(),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('advisor.result', $result);
    }

    public function result(AdvisorResult $result): View
    {
        $ids = $result->recommended_company_ids ?? [];
        $companies = Company::query()
            ->approved()
            ->whereIn('id', $ids)
            ->with(['features', 'types'])
            ->get()
            ->sortBy(fn ($c) => array_search($c->id, $ids, true))
            ->values();

        $scores = $result->scores ?? [];

        return view('public.advisor.result', compact('result', 'companies', 'scores'));
    }
}
