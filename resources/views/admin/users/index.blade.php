@extends('layouts.admin')
@section('title', 'Users')
@section('heading', 'Users')

@section('content')
<form method="get" class="mb-4 flex gap-2">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search" class="rounded-xl border-slate-200 text-sm">
    <select name="role" class="rounded-xl border-slate-200 text-sm">
        <option value="">All roles</option>
        @foreach ($roles as $role)
            <option value="{{ $role->value }}" @selected(request('role')===$role->value)>{{ $role->value }}</option>
        @endforeach
    </select>
    <button class="rounded-xl bg-brand-800 px-4 py-2 text-sm text-white">Filter</button>
</form>

<div class="space-y-4">
@foreach ($users as $user)
    <form method="post" action="{{ route('admin.users.update', $user) }}" class="grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 md:grid-cols-5">
        @csrf @method('PUT')
        <input name="name" value="{{ $user->name }}" class="rounded-xl border-slate-200 text-sm">
        <input name="email" value="{{ $user->email }}" class="rounded-xl border-slate-200 text-sm">
        <select name="role" class="rounded-xl border-slate-200 text-sm">
            @foreach ($roles as $role)
                <option value="{{ $role->value }}" @selected($user->role===$role)>{{ $role->value }}</option>
            @endforeach
        </select>
        <input type="password" name="password" placeholder="New password" class="rounded-xl border-slate-200 text-sm">
        <div class="flex items-center gap-3">
            <label class="text-sm"><input type="checkbox" name="is_active" value="1" @checked($user->is_active)> Active</label>
            <button class="rounded-xl bg-brand-700 px-3 py-2 text-sm text-white">Save</button>
        </div>
    </form>
@endforeach
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
