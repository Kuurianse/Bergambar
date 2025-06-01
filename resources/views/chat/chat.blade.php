@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Chat with {{ $artist->name }}</div>

                <div class="card-body">
                    <div class="chat-box" style="height: 300px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px;">
                        @foreach ($messages as $message)
                            @if ($message->sender_id === auth()->id())
                                <p><strong>You:</strong> {{ $message->message }}</p>
                            @else
                                <p><strong>{{ $message->sender->name }}:</strong> {{ $message->message }}</p>
                            @endif
                        @endforeach
                    </div>

                    <input type="hidden" id="receiverId" value="{{ $artist->id }}">

                    <div class="input-group mt-3">
                        <input type="text" id="chatMessage" class="form-control" placeholder="Type your message...">
                        <button id="sendMessageBtn" class="btn btn-primary">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log(window.Echo); // Memeriksa apakah Laravel Echo sudah siap

        // Cek apakah Echo terinisialisasi
        if (window.Echo) {
            console.log("Echo is ready!");

            // Menentukan receiverId
            const receiverId = document.getElementById('receiverId').value;
            console.log('Listening to channel: chat.' + receiverId);

            // Mendengarkan channel 'chat.{receiverId}'
            window.Echo.channel('chat.' + receiverId)
                .listen('MessageSent', (event) => {
                    console.log('New message received:', event);
                    let messageBox = document.querySelector('.chat-box');
                    // Cek apakah pengirim adalah pengguna yang sedang login
                    if (event.message.sender_id === {{ auth()->id() }}) {
                        messageBox.innerHTML += `<p><strong>You:</strong> ${event.message.message}</p>`;
                    } else {
                        messageBox.innerHTML += `<p><strong>${event.message.sender_name}:</strong> ${event.message.message}</p>`;
                    }
                    messageBox.scrollTop = messageBox.scrollHeight; // Scroll ke bawah saat pesan baru diterima
                });

            // Event listener untuk tombol send
            document.getElementById('sendMessageBtn').addEventListener('click', function() {
                let message = document.getElementById('chatMessage').value;
                let receiverId = document.getElementById('receiverId').value;

                console.log('Tombol Send di-klik');  
                console.log('Message:', message);  
                console.log('Receiver ID:', receiverId);  

                axios.post('/send-message', {
                    message: message,
                    receiver_id: receiverId
                })
                .then(response => {
                    console.log('Response dari server:', response.data);
                    document.querySelector('.chat-box').innerHTML += `<p><strong>You:</strong> ${response.data.message}</p>`;
                    document.getElementById('chatMessage').value = ''; // Kosongkan input setelah kirim
                })
                .catch(error => {
                    console.error('Error saat mengirim pesan:', error.response);  // Menangkap error dari Axios
                });
            });
        } else {
            console.error('Laravel Echo belum siap!');
        }
    });
</script>
@endsection
