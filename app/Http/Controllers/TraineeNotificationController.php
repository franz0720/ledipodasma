<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TraineeNotificationController extends Controller
{
    /**
     * Mark an announcement notification as read.
     */
    public function markRead(Request $request, $announcement)
    {
        $userId = auth()->id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in first.',
            ], 401);
        }

        $exists = DB::table('announcements')
            ->where('id', $announcement)
            ->where('is_active', 1)
            ->exists();

        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => 'Announcement not found.',
            ], 404);
        }

        DB::table('announcement_reads')->updateOrInsert(
            [
                'announcement_id' => $announcement,
                'user_id' => $userId,
            ],
            [
                'read_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Announcement marked as read.',
        ]);
    }

    /**
     * Mark a student comment notification as read.
     *
     * Assumes comment_notifications has:
     * id, user_id, read_at
     */
    public function markCommentRead(Request $request, $notification)
    {
        $userId = auth()->id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in first.',
            ], 401);
        }

        // Avoid querying a table that has not been created yet.
        if (!Schema::hasTable('comment_notifications')) {
            return response()->json([
                'success' => false,
                'message' => 'Comment notifications have not been configured.',
            ], 500);
        }

        // Only allow the logged-in student to update their own notification.
        $notificationRecord = DB::table('comment_notifications')
            ->where('id', $notification)
            ->where('user_id', $userId)
            ->first();

        if (!$notificationRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Comment notification not found.',
            ], 404);
        }

        DB::table('comment_notifications')
            ->where('id', $notification)
            ->where('user_id', $userId)
            ->update([
                'read_at' => $notificationRecord->read_at ?? now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment notification marked as read.',
        ]);
    }

    public function markTrainerRead(Request $request, $trainerAnnouncement)
{
    $userId = auth()->id();

    if (!$userId) {
        return response()->json([
            'success' => false,
            'message' => 'Please log in first.',
        ], 401);
    }

    if (!\Illuminate\Support\Facades\Schema::hasTable(
        'trainer_announcement_reads'
    )) {
        return response()->json([
            'success' => false,
            'message' => 'Trainer notification read tracking is not configured.',
        ], 500);
    }

    $exists = DB::table('trainer_announcements')
        ->where('id', $trainerAnnouncement)
        ->exists();

    if (!$exists) {
        return response()->json([
            'success' => false,
            'message' => 'Trainer announcement not found.',
        ], 404);
    }

    DB::table('trainer_announcement_reads')->updateOrInsert(
        [
            'user_id' => $userId,
            'trainer_announcement_id' => $trainerAnnouncement,
        ],
        [
            'read_at' => now(),
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'Trainer notification marked as read.',
    ]);
}
}
