<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrainerNotificationController extends Controller
{
    /**
     * Mark one notification as read.
     */
    public function markRead($notification)
    {
        $userId = Auth::id();

        $updated = DB::table('trainer_notifications')
            ->where('id', $notification)
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
                'updated_at' => now(),
            ]);

        abort_unless(
            $updated || DB::table('trainer_notifications')
                ->where('id', $notification)
                ->where('user_id', $userId)
                ->exists(),
            404
        );

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications belonging to the current trainer as read.
     */
    public function markAllRead()
    {
        DB::table('trainer_notifications')
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
