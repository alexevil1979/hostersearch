<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::query()->orderBy('slug')->paginate(30);

        return view('admin.pages.index', compact('pages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        Page::query()->create([
            ...$data,
            'slug' => $data['slug'] ?: Str::slug($data['title']),
            'is_published' => $request->boolean('is_published', true),
        ]);

        return back()->with('status', 'Page created.');
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $data = $this->validated($request);
        $page->update([
            ...$data,
            'slug' => $data['slug'] ?: Str::slug($data['title']),
            'is_published' => $request->boolean('is_published'),
        ]);

        return back()->with('status', 'Page updated.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return back()->with('status', 'Page deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'title_ru' => ['nullable', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:180'],
            'body' => ['nullable', 'string'],
            'body_ru' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
