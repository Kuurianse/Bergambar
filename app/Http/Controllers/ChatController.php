<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    
    public function index()
    {
        $userId = Auth::id();

        // Mengambil semua pesan yang berhubungan dengan user yang sedang login
        $chats = Message::where('sender_id', $userId)
                        ->orWhere('receiver_id', $userId)
                        ->with(['sender', 'receiver'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Mengelompokkan pesan berdasarkan pengguna yang di-chat
        $groupedChats = $chats->groupBy(function($message) use ($userId) {
            return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
        });

        return view('chat.index', compact('groupedChats'));
    }

    public function show(User $artist)
    {
        $messages = Message::where(function($query) use ($artist) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $artist->id);
        })->orWhere(function($query) use ($artist) {
            $query->where('sender_id', $artist->id)
                ->where('receiver_id', auth()->id());
        })->get();

        return view('chat.chat', ['artist' => $artist, 'messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        // Validasi data input
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'required|exists:users,id',
        ]);

        // Buat pesan baru di database
        $message = Message::create([
            'sender_id' => auth()->id(), // ID pengirim
            'receiver_id' => $request->receiver_id, // ID penerima
            'message' => $request->message, // Isi pesan
        ]);

        // Memancarkan event agar pesan dikirim real-time
        broadcast(new MessageSent($message, auth()->user()->name))->toOthers();

        return response()->json($message); // Mengembalikan response JSON
    }
}

