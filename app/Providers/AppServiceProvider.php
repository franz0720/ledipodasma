<?php

namespace App\Providers;

use App\Models\Attendance;
use App\Observers\AttendanceObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Preserve the existing attendance observer.
        Attendance::observe(AttendanceObserver::class);

        // Share trainer notifications with the trainer layout.
        View::composer('*', function ($view) {

            // Only load notifications for authenticated users
            // viewing trainer-related pages.
            if (
                !Auth::check() ||
                !request()->is(
                    'trainer*',
                    'teacher*',
                    'assessment*',
                    'certificates*',
                    'reports*',
                    'settings*'
                )
            ) {
                return;
            }

            $userId = Auth::id();

            // Load the latest notifications, with unread items first.
            $notifications = DB::table('trainer_notifications')
                ->where('user_id', $userId)
                ->orderByRaw('CASE WHEN read_at IS NULL THEN 0 ELSE 1 END')
                ->orderByDesc('created_at')
                ->limit(30)
                ->get();

            // Count all unread notifications, not just the latest 30.
            $unreadCount = DB::table('trainer_notifications')
                ->where('user_id', $userId)
                ->whereNull('read_at')
                ->count();

            // Pass data to the existing notification dropdown in the Blade layout.
            $view->with([
                'trainerNotifications' => $notifications,
                'trainerUnreadCount' => $unreadCount,
            ]);
        });
    }
}
