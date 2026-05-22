@extends('layouts.app')
@section('title', 'Notifikasi')

@push('styles')
<style>
    .notif-icon {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .notif-icon svg { width: 18px; height: 18px; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .notif-icon.type-available { background: var(--badge-available); }
    .notif-icon.type-available svg { stroke: var(--badge-available-text); }
    .notif-icon.type-overdue   { background: var(--badge-empty); }
    .notif-icon.type-overdue   svg { stroke: var(--badge-empty-text); }
    .notif-icon.type-default   { background: #F0EFEB; }
    .notif-icon.type-default   svg { stroke: var(--muted); }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Notifikasi</h1>
    <span style="font-size:13px;color:var(--muted)">{{ $notifications->count() }} pesan</span>
</div>

@if($notifications->isEmpty())
    <div class="card" style="text-align:center;padding:60px 24px;color:var(--muted)">
        <div style="display:flex;justify-content:center;margin-bottom:14px">
            <div style="width:48px;height:48px;display:flex;align-items:center;justify-content:center">
                <svg width="32" height="32" viewBox="0 0 24 24" style="stroke:var(--muted);fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </div>
        </div>
        <p>Belum ada notifikasi.</p>
    </div>
@else
    <div style="display:flex;flex-direction:column;gap:8px">
        @foreach($notifications as $notif)
        @php
            $msg = $notif->message;
            if (str_contains($msg, 'tersedia') && !str_contains($msg, 'tidak')) {
                $iconType = 'type-available';
                $iconPath = '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>';
            } elseif (str_contains($msg, 'terlambat') || str_contains($msg, 'denda')) {
                $iconType = 'type-overdue';
                $iconPath = '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>';
            } else {
                $iconType = 'type-default';
                $iconPath = '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>';
            }
        @endphp
        <div class="card" style="padding:16px 20px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;{{ !$notif->is_read ? 'border-left:3px solid var(--accent);' : '' }}">
            <div style="display:flex;align-items:flex-start;gap:12px;flex:1">
                <div class="notif-icon {{ $iconType }}">
                    <svg viewBox="0 0 24 24">{!! $iconPath !!}</svg>
                </div>
                <p style="font-size:14px;line-height:1.6;font-weight:{{ !$notif->is_read ? '500' : '400' }};margin-top:8px">
                    {{ $notif->message }}
                </p>
            </div>
            <span style="font-size:12px;color:var(--muted);flex-shrink:0;white-space:nowrap;margin-top:10px">
                {{ \Carbon\Carbon::parse($notif->sent_at)->diffForHumans() }}
            </span>
        </div>
        @endforeach
    </div>
@endif
@endsection
