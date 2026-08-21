<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationChatController extends Controller
{
    /**
     * Fetch all messages for an application.
     */
    public function fetchMessages(Application $application)
    {
        $userId = Auth::id();

        // Mark incoming messages as read
        ApplicationMessage::where('application_id', $application->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = ApplicationMessage::with('sender')
            ->where('application_id', $application->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($userId) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->name ?? 'User',
                    'message' => $msg->message,
                    'is_me' => $msg->sender_id === $userId,
                    'time' => $msg->created_at->format('H:i'),
                    'date' => $msg->created_at->format('d M Y'),
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send a new message.
     */
    public function sendMessage(Request $request, Application $application)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Receiver is candidate if sender is HR/Admin, or HR if sender is candidate
        $receiverId = ($user->id === $application->user_id)
            ? ($application->job->user_id ?? 1)
            : $application->user_id;

        $msg = ApplicationMessage::create([
            'application_id' => $application->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $user->name,
                'message' => $msg->message,
                'is_me' => true,
                'time' => $msg->created_at->format('H:i'),
                'date' => $msg->created_at->format('d M Y'),
            ]
        ]);
    }
}
