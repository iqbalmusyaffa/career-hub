<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class NotificationApiController extends Controller
{
    #[OA\Get(
        path: "/notifications",
        summary: "Daftar Notifikasi User",
        description: "Mengambil daftar notifikasi in-app bell pengguna.",
        security: [["bearerAuth" => []]],
        tags: ["Notifications & Salary"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar notifikasi",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Notifikasi berhasil diambil.",
                        "data" => [
                            "unread_count" => 1,
                            "notifications" => [
                                [
                                    "id" => 1,
                                    "user_id" => 1,
                                    "title" => "Undangan Interview",
                                    "message" => "Selamat! Anda diundang untuk mengikuti wawancara.",
                                    "is_read" => false,
                                    "created_at" => "2026-08-21T22:00:00.000000Z"
                                ]
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $notifications = UserNotification::where('user_id', $request->user()->id)
            ->latest()
            ->take(30)
            ->get();

        $unreadCount = UserNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return $this->successResponse([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ], 'Notifikasi berhasil diambil.');
    }

    #[OA\Post(
        path: "/notifications/{id}/read",
        summary: "Tandai Notifikasi Dibaca",
        description: "Tandai notifikasi tertentu sebagai telah dibaca.",
        security: [["bearerAuth" => []]],
        tags: ["Notifications & Salary"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Notifikasi ditandai dibaca",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Notifikasi ditandai sudah dibaca.",
                        "data" => null,
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function markAsRead(Request $request, $id)
    {
        $notif = UserNotification::where('user_id', $request->user()->id)->find($id);

        if ($notif) {
            $notif->update(['is_read' => true]);
        }

        return $this->successResponse(null, 'Notifikasi ditandai sudah dibaca.');
    }

    #[OA\Post(
        path: "/notifications/read-all",
        summary: "Tandai Semua Notifikasi Dibaca",
        description: "Menandai seluruh notifikasi user sebagai telah dibaca.",
        security: [["bearerAuth" => []]],
        tags: ["Notifications & Salary"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Seluruh notifikasi dibaca",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Semua notifikasi ditandai sudah dibaca.",
                        "data" => null,
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function markAllAsRead(Request $request)
    {
        UserNotification::where('user_id', $request->user()->id)->update(['is_read' => true]);

        return $this->successResponse(null, 'Semua notifikasi ditandai sudah dibaca.');
    }
}
