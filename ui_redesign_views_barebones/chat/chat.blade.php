@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Chat with [Artist Name Placeholder]</div>

                <div class="card-body">
                    <div class="chat-box mb-3" style="height: 350px; overflow-y: scroll; border: 1px solid #eee; padding: 15px; background-color: #f9f9f9; border-radius: 5px;">
                        <!-- Example Message (Receiver) -->
                        <div class="message-item mb-2">
                            <div class="d-flex justify-content-start">
                                <div style="background-color: #e9ecef; color: #333; padding: 10px 15px; border-radius: 15px 15px 15px 0;">
                                    <strong>[Artist Name Placeholder]:</strong> Halo! Ada yang bisa saya bantu?
                                </div>
                            </div>
                            <small class="text-muted d-block text-start mt-1">10:00 AM</small>
                        </div>

                        <!-- Example Message (Sender - You) -->
                        <div class="message-item mb-2">
                            <div class="d-flex justify-content-end">
                                <div style="background-color: #007bff; color: white; padding: 10px 15px; border-radius: 15px 15px 0 15px;">
                                    <strong>You:</strong> Ya, saya tertarik dengan komisi Anda.
                                </div>
                            </div>
                            <small class="text-muted d-block text-end mt-1">10:01 AM</small>
                        </div>

                        <!-- Example Message (Receiver) -->
                        <div class="message-item mb-2">
                            <div class="d-flex justify-content-start">
                                <div style="background-color: #e9ecef; color: #333; padding: 10px 15px; border-radius: 15px 15px 15px 0;">
                                    <strong>[Artist Name Placeholder]:</strong> Tentu, komisi yang mana ya? Bisa berikan detail lebih lanjut?
                                </div>
                            </div>
                            <small class="text-muted d-block text-start mt-1">10:02 AM</small>
                        </div>
                         <!-- Example Message (Sender - You) with longer text -->
                        <div class="message-item mb-2">
                            <div class="d-flex justify-content-end">
                                <div style="background-color: #007bff; color: white; padding: 10px 15px; border-radius: 15px 15px 0 15px; max-width: 70%;">
                                    <strong>You:</strong> Saya tertarik dengan komisi ilustrasi karakter yang Anda post kemarin. Saya ingin diskusi mengenai pose dan latar belakangnya. Apakah ada waktu untuk diskusi lebih lanjut? Ini adalah contoh referensi yang saya punya.
                                </div>
                            </div>
                            <small class="text-muted d-block text-end mt-1">10:05 AM</small>
                        </div>
                    </div>

                    <input type="hidden" id="receiverIdPlaceholder" value="artist_id_placeholder">
                    <input type="hidden" id="currentUserIdPlaceholder" value="current_user_id_placeholder">


                    <div class="input-group mt-3">
                        <input type="text" id="chatMessageInput" class="form-control" placeholder="Type your message here..." aria-label="Type your message here">
                        <button id="sendMessageButton" class="btn btn-primary" type="button">
                            <i class="fas fa-paper-plane"></i> Send
                        </button>
                    </div>
                    <small class="text-muted">Press Enter to send message (optional feature).</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Barebones script placeholder. Actual JS for Echo and Axios would be more complex. -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const chatBox = document.querySelector('.chat-box');
    const messageInput = document.getElementById('chatMessageInput');
    const sendMessageBtn = document.getElementById('sendMessageButton');
    // const receiverId = document.getElementById('receiverIdPlaceholder').value;
    // const currentUserId = document.getElementById('currentUserIdPlaceholder').value; // Example

    function appendMessage(senderName, message, isSender) {
        const messageItem = document.createElement('div');
        messageItem.classList.add('message-item', 'mb-2');

        const messageBubbleContainer = document.createElement('div');
        messageBubbleContainer.classList.add('d-flex', isSender ? 'justify-content-end' : 'justify-content-start');
        
        const messageBubble = document.createElement('div');
        messageBubble.style.padding = '10px 15px';
        messageBubble.style.maxWidth = '70%';
        if (isSender) {
            messageBubble.style.backgroundColor = '#007bff';
            messageBubble.style.color = 'white';
            messageBubble.style.borderRadius = '15px 15px 0 15px';
        } else {
            messageBubble.style.backgroundColor = '#e9ecef';
            messageBubble.style.color = '#333';
            messageBubble.style.borderRadius = '15px 15px 15px 0';
        }
        messageBubble.innerHTML = `<strong>${senderName}:</strong> ${message}`;
        
        const timestamp = document.createElement('small');
        timestamp.classList.add('text-muted', 'd-block', 'mt-1', isSender ? 'text-end' : 'text-start');
        timestamp.textContent = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        messageBubbleContainer.appendChild(messageBubble);
        messageItem.appendChild(messageBubbleContainer);
        messageItem.appendChild(timestamp);
        chatBox.appendChild(messageItem);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    sendMessageBtn.addEventListener('click', function() {
        const message = messageInput.value.trim();
        if (message) {
            appendMessage('You', message, true); // Simulate sending message
            messageInput.value = '';
            
            // Simulate receiving a reply after a short delay
            setTimeout(() => {
                appendMessage('[Artist Name Placeholder]', 'Okay, I see your message!', false);
            }, 1000);
        }
    });

    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessageBtn.click();
        }
    });

    // Placeholder for Echo listener
    // if (window.Echo) {
    //     window.Echo.private('chat.' + receiverId)
    //         .listen('MessageSent', (event) => {
    //             if (event.message.sender_id.toString() !== currentUserId) {
    //                 appendMessage(event.sender_name, event.message.message, false);
    //             }
    //         });
    // } else {
    //     console.error('Laravel Echo not configured (barebones).');
    // }
    console.log("Barebones chat script loaded. AJAX and Echo functionalities are placeholders.");
});
</script>
@endsection