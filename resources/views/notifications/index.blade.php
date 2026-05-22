@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')
<div class="page-header">
    <h1>Notifikasi</h1>
    <span style="font-size:13px;color:var(--muted)">{{ $notifications->count() }} pesan</span>
</div>

@if($notifications->isEmpty())
    <div class="card" style="text-align:center;padding:60px 24px;color:var(--muted)">
        <div style="font-size:40px;margin-bottom:12px">🔔</div>
        <p>Belum ada notifikasi saat ini.</p>
    </div>
@else
    <div style="display:flex;flex-direction:column;gap:8px">
        @foreach($notifications as $notif)
        <div class="card" style="padding:16px 20px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;
            {{ !$notif->is_read ? 'border-left: 3px solid var(--accent);' : '' }}">
            <div style="display:flex;align-items:flex-start;gap:12px;flex:1">
                <span style="font-size:18px;flex-shrink:0">🔔</span>
                <p style="font-size:14px;line-height:1.5;font-weight:{{ !$notif->is_read ? '500' : '400' }}">
                    {{ $notif->message }}
                </p>
            </div>
            <span style="font-size:12px;color:var(--muted);flex-shrink:0;white-space:nowrap">
                {{ \Carbon\Carbon::parse($notif->sent_at)->diffForHumans() }}
            </span>
        </div>
        @endforeach
    </div>
@endif
@endsection
