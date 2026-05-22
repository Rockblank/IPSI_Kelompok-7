@extends('layouts.app')

@section('title', 'Kotak Masuk Notifikasi')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🔔 Kotak Masuk Notifikasi</h2>
        <span class="badge bg-secondary">Total: {{ $notifications->count() }}</span>
    </div>

    @if($notifications->isEmpty())
        <div class="alert alert-info text-center py-4" role="alert">
            <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
            Belum ada notifikasi atau pengingat saat ini.
        </div>
    @else
        <div class="list-group shadow-sm">
            @foreach($notifications as $notif)
                <div class="list-group-item list-group-item-action p-3 @if(!$notif->is_read) border-start border-primary border-4 bg-light @endif">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <p class="mb-1 text-dark {{ !$notif->is_read ? 'fw-bold' : '' }}">
                            {{ $notif->message }}
                        </p>
                        <small class="text-muted text-end" style="min-width: 120px;">
                            {{ \Carbon\Carbon::parse($notif->sent_at)->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection