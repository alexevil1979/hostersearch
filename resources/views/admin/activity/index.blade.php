@extends('layouts.admin')
@section('title', 'Activity')
@section('heading', 'Activity log')

@section('content')
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
<table class="min-w-full text-sm">
    <thead class="bg-slate-50 text-slate-500"><tr><th class="px-3 py-3 text-left">When</th><th class="px-3 py-3">User</th><th class="px-3 py-3">Action</th><th class="px-3 py-3">IP</th></tr></thead>
    <tbody>
    @foreach ($logs as $log)
        <tr class="border-t">
            <td class="px-3 py-3">{{ $log->created_at }}</td>
            <td class="px-3 py-3">{{ $log->user?->email ?? 'system' }}</td>
            <td class="px-3 py-3">{{ $log->action }}</td>
            <td class="px-3 py-3">{{ $log->ip_address }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-4">{{ $logs->links() }}</div>
@endsection
