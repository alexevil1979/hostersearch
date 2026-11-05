@extends('layouts.public')

@section('title', t_field($page, 'title').' — HosterSearch')
@section('meta_description', $page->meta_description)

@section('content')
<article class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="section-title">{{ t_field($page, 'title') }}</h1>
    <div class="prose-host mt-8 space-y-4 text-slate-700 leading-relaxed">
        {!! t_field($page, 'body') !!}
    </div>
</article>
@endsection
