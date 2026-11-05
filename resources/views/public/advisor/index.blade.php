@extends('layouts.public')

@section('title', __('Web Hosting Advisor').' — HosterSearch')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="section-title">{{ __('Web Hosting Advisor') }}</h1>
    <p class="mt-3 text-slate-600">{{ __('Answer a few questions and get personalized hosting recommendations.') }}</p>

    <form method="post" action="{{ route('advisor.store') }}" class="mt-8 space-y-6" x-data="{ step: 0, total: {{ $questions->count() }} }">
        @csrf
        @foreach ($questions as $index => $question)
            <div class="card-surface p-6" x-show="step === {{ $index }}" x-cloak>
                <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-brand-600">Step {{ $index + 1 }} / {{ $questions->count() }}</div>
                <h2 class="text-xl font-semibold text-brand-900">{{ app()->getLocale() === 'ru' && $question->question_ru ? $question->question_ru : $question->question }}</h2>
                <div class="mt-5 space-y-2">
                    @foreach ($question->answers as $answer)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 hover:border-brand-300">
                            <input
                                type="{{ $question->type === 'multiple' ? 'checkbox' : 'radio' }}"
                                name="answers[{{ $question->slug }}]{{ $question->type === 'multiple' ? '[]' : '' }}"
                                value="{{ $answer->slug }}"
                                class="rounded border-slate-300 text-brand-700 focus:ring-brand-500"
                                @if($question->type !== 'multiple') required @endif
                            >
                            <span>{{ app()->getLocale() === 'ru' && $answer->label_ru ? $answer->label_ru : $answer->label }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="button" class="btn-secondary" x-show="step > 0" @click="step--">Back</button>
                    <div class="ml-auto flex gap-2">
                        <button type="button" class="btn-primary" x-show="step < total - 1" @click="step++">Next</button>
                        <button type="submit" class="btn-accent" x-show="step === total - 1">{{ __('Get recommendations') }}</button>
                    </div>
                </div>
            </div>
        @endforeach
    </form>
</div>
@endsection
