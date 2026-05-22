@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
    main { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: calc(100vh - 60px); padding: 32px 24px; }
    .dashboard-wrap { width: 100%; max-width: 540px; }
    .search-bar { display: flex; align-items: center; background: var(--surface); border: 1px solid var(--border); border-radius: 999px; overflow: hidden; height: 52px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .search-bar input { flex: 1; border: none; outline: none; padding: 0 20px; font-family: 'DM Sans', sans-serif; font-size: 15px; background: transparent; color: var(--text); font-style: italic; }
    .search-bar button { height: 52px; padding: 0 28px; background: var(--muted); color: #fff; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500; border-radius: 0 999px 999px 0; transition: background .15s; }
    .search-bar button:hover { background: var(--accent); }
</style>
@endpush

@section('content')
<div class="dashboard-wrap">
    @if(session('success'))
        <div class="flash success" style="margin-bottom:24px">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash error" style="margin-bottom:24px">{{ session('error') }}</div>
    @endif

    <h1 style="font-family:'DM Serif Display',serif;font-size:32px;line-height:1.2;margin-bottom:4px">
        Halo, <strong>{{ $namaUser }}</strong>
    </h1>
    <p style="font-family:'DM Serif Display',serif;font-size:24px;color:var(--text);margin-bottom:28px">
        Mau baca apa hari ini?
    </p>

    <form action="{{ route('dashboard.search') }}" method="GET" class="search-bar">
        <input type="text" name="keyword" placeholder='"Laut Bercerita"'>
        <button type="submit">Cari</button>
    </form>
</div>
@endsection
