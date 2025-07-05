@extends('layouts.app')

@section('title', 'Messages Page')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/chat-index.css') }}" />
@endsection

@section('content')
    <section class="heading">
      <h3>Messages</h3>
      <p>Your conservations with artists and clients</p>
    </section>

    <section class="chat">
      <div class="chats-container">
        @forelse($groupedChats as $userId => $chats)
            @php
                $chatUser = \App\Models\User::find($userId);
            @endphp
            <a href="{{ route('chat.show', $chatUser->id) }}" class="chat-card">
                <div class="contact-info">
                    <div class="photo-profile">
                        <img
                            src="{{ $chatUser->profile_picture ? asset('storage/' . $chatUser->profile_picture) : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80' }}"
                            alt="Photo Profile"
                            style="width: 100%; height: 100%; object-fit: cover"
                        />
                    </div>
                    <div class="information">
                        <h3 class="name">{{ $chatUser->name }}</h3>
                        <p class="last-message">
                            {{ $chats->last()->message }}
                        </p>
                    </div>
                </div>
                <div class="right-side">
                    <div class="last-chat">{{ $chats->last()->created_at->diffForHumans() }}</div>
                    <div class="chat-svg">
                        <svg
                            width="19"
                            height="19"
                            viewBox="0 0 19 19"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M9.5 18C14.1944 18 18 14.1944 18 9.5C18 4.80558 14.1944 1 9.5 1C4.80558 1 1 4.80558 1 9.5C1 10.8597 1.31928 12.1449 1.88694 13.2846C2.0378 13.5875 2.08801 13.9337 2.00055 14.2605L1.49428 16.1527C1.27451 16.9741 2.02596 17.7255 2.84735 17.5057L4.73948 16.9995C5.06634 16.912 5.41253 16.9622 5.7154 17.113C6.85511 17.6807 8.14025 18 9.5 18Z"
                                stroke="#57606E"
                            />
                        </svg>
                    </div>
                </div>
            </a>
        @empty  
            <p class="chat-card">No chats available</p>
        @endforelse
      </div>
    </section>
@endsection
