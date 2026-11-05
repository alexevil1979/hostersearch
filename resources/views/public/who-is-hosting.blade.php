@extends('layouts.public')

@section('title', 'Who Is Hosting — HosterSearch')
@section('meta_description', 'Find which company hosts a website using public DNS records.')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="section-title">Who is hosting this website?</h1>
    <p class="mt-3 text-slate-600">Enter a domain to inspect public DNS (A/NS/MX/PTR) and get a hosting hint. Results are informational.</p>

    <form method="post" action="{{ route('who-is-hosting.lookup') }}" class="card-surface mt-8 flex flex-col gap-3 p-6 sm:flex-row">
        @csrf
        <input type="text" name="domain" value="{{ old('domain', $domain) }}" required placeholder="example.com"
               class="w-full flex-1 rounded-xl border-slate-200 px-4 py-3">
        <button class="btn-primary !py-3">Lookup</button>
    </form>

    @if ($result)
        <div class="mt-8 space-y-4">
            <div class="card-surface p-6">
                <div class="text-sm uppercase tracking-wide text-slate-500">Guess</div>
                <div class="mt-1 text-2xl font-semibold text-brand-900">{{ $result['guess']['name'] }}</div>
                <div class="mt-1 text-xs text-slate-400">Confidence: {{ $result['guess']['confidence'] }}</div>
                @if ($matched)
                    <a href="{{ route('companies.show', $matched) }}" class="btn-secondary mt-4 inline-flex !py-2">View {{ $matched->name }} review</a>
                @endif
            </div>

            <div class="card-surface grid gap-4 p-6 sm:grid-cols-2">
                <div>
                    <div class="font-semibold text-brand-900">A records</div>
                    <ul class="mt-2 space-y-1 font-mono text-sm text-slate-600">
                        @forelse ($result['ips'] as $ip)
                            <li>{{ $ip }}@if(!empty($result['ptr'][$ip])) <span class="text-slate-400">→ {{ $result['ptr'][$ip] }}</span>@endif</li>
                        @empty
                            <li>—</li>
                        @endforelse
                    </ul>
                </div>
                <div>
                    <div class="font-semibold text-brand-900">Nameservers</div>
                    <ul class="mt-2 space-y-1 font-mono text-sm text-slate-600">
                        @forelse ($result['ns'] as $ns)
                            <li>{{ $ns }}</li>
                        @empty
                            <li>—</li>
                        @endforelse
                    </ul>
                </div>
                <div>
                    <div class="font-semibold text-brand-900">MX</div>
                    <ul class="mt-2 space-y-1 font-mono text-sm text-slate-600">
                        @forelse ($result['mx'] as $mx)
                            <li>{{ $mx }}</li>
                        @empty
                            <li>—</li>
                        @endforelse
                    </ul>
                </div>
                <div>
                    <div class="font-semibold text-brand-900">CNAME / AAAA</div>
                    <ul class="mt-2 space-y-1 font-mono text-sm text-slate-600">
                        @foreach ($result['cname'] as $c)<li>CNAME {{ $c }}</li>@endforeach
                        @foreach ($result['ipv6'] as $ip6)<li>AAAA {{ $ip6 }}</li>@endforeach
                        @if (!$result['cname'] && !$result['ipv6'])<li>—</li>@endif
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
