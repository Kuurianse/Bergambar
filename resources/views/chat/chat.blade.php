@extends('layouts.app')

@section('title', 'Messages Page')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}" />
@endsection

@section('content')
    <section class="heading">
      <h3>Messages</h3>
      <p>Your conservations with artists and clients</p>
    </section>

    <!-- Kontainer Halaman Obrolan -->
    <section class="container">
        <div class="chat-container">
            <!-- Header Obrolan dengan Nama Kontak -->
            <div class="chat-header">
                <button class="back-button" aria-label="Kembali" onclick="history.back()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <div class="profile-pic"></div>
                <div class="contact-name">{{ $artist->name }}</div>
            </div>

            <!-- Area untuk Menampilkan Pesan -->
            <div class="messages-area">
                @foreach ($messages as $message)
                    @if ($message->sender_id == auth()->id())
                        <!-- Pesan Dikirim -->
                        <div class="message sent">
                            <div class="message-bubble">
                                {{ $message->message }}
                            </div>
                            <div class="message-time">{{ $message->created_at->format('H:i A') }}</div>
                        </div>
                    @else
                        <!-- Pesan Diterima -->
                        <div class="message received">
                            <div class="message-bubble">
                                {{ $message->message }}
                            </div>
                            <div class="message-time">{{ $message->created_at->format('H:i A') }}</div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Area untuk Mengetik dan Mengirim Pesan -->
            <div class="chat-input-area">
                <input type="hidden" id="receiverId" value="{{ $artist->id }}">
                <input type="text" id="chatMessage" placeholder="Type a message...">
                <button class="send-button" id="sendMessageBtn" aria-label="Kirim Pesan">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const messagesArea = document.querySelector('.messages-area');
        const receiverId = document.getElementById('receiverId').value;
        const chatMessageInput = document.getElementById('chatMessage');
        const sendMessageBtn = document.getElementById('sendMessageBtn');

        // Scroll to bottom
        messagesArea.scrollTop = messagesArea.scrollHeight;

        const appendMessage = (message, senderName = 'You', messageType = 'sent') => {
            const messageTime = new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            const messageHtml = `
                <div class="message ${messageType}">
                    <div class="message-bubble">
                        ${message.message}
                    </div>
                    <div class="message-time">${messageTime}</div>
                </div>
            `;
            messagesArea.innerHTML += messageHtml;
            messagesArea.scrollTop = messagesArea.scrollHeight;
        };

        if (window.Echo) {
            console.log("Echo is ready!");

            const currentUserId = {{ auth()->id() }};
            window.Echo.private('chat.' + currentUserId)
                .listen('MessageSent', (event) => {
                    console.log('New message received:', event);
                    // Cek apakah pesan yang masuk berasal dari user yang sedang diajak chat
                    if (event.message.sender_id == receiverId) {
                        appendMessage(event.message, event.sender_name, 'received');
                    }
                });
        } else {
            console.error('Laravel Echo belum siap!');
        }

        const sendMessage = () => {
            let message = chatMessageInput.value;
            if (!message.trim()) return;

            axios.post('/send-message', {
                message: message,
                receiver_id: receiverId
            })
            .then(response => {
                appendMessage(response.data, 'You', 'sent');
                chatMessageInput.value = '';
            })
            .catch(error => {
                console.error('Error saat mengirim pesan:', error);
            });
        };

        sendMessageBtn.addEventListener('click', sendMessage);
        chatMessageInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Mencegah form submit jika ada
                sendMessage();
            }
        });
    });
</script>
@endsection
