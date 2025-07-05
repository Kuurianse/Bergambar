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

        // Subquery untuk mendapatkan pesan terakhir untuk setiap percakapan
        $latestMessages = Message::select('id')
            ->whereIn('id', function ($query) use ($userId) {
                $query->selectRaw('MAX(id)')
                    ->from('messages')
                    ->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId)
                    ->groupByRaw('IF(sender_id = ?, receiver_id, sender_id)', [$userId]);
            });

        // Mengambil chat yang telah dipaginasi dengan relasi yang dibutuhkan
        $chats = Message::whereIn('id', $latestMessages)
                        ->with(['sender', 'receiver'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10); // Misalnya, 10 chat per halaman

        return view('chat.index', compact('chats'));
    }

    public function show(User $artist)
    {
        $messages = Message::where(function($query) use ($artist) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $artist->id);
        })->orWhere(function($query) use ($artist) {
            $query->where('sender_id', $artist->id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

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

