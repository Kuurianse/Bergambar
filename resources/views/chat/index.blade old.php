@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 1.5%">
    <h2>Recent Messages</h2>
    
    <div class="list-group">
        @forelse($groupedChats as $userId => $chats)
            @php
                $chatUser = \App\Models\User::find($userId);
            @endphp
            <a href="{{ route('chat.show', $chatUser->id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $chatUser->name }}</strong>
                        <p class="mb-0">
                            {{ $chats->last()->message }} <!-- Tampilkan pesan terakhir -->
                        </p>
                    </div>
                    <small class="text-muted">{{ $chats->last()->created_at->diffForHumans() }}</small>
                </div>
            </a>
        @empty
            <p>No chats available</p>
        @endforelse
    </div>
</div>
@endsection
