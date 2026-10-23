<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class AdminNotificationController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->check(), 401);

        $role = strtolower(str_replace([' ', '-'], '_', (string) (auth()->user()->role ?? '')));
        abort_unless(in_array($role, ['admin', 'superadmin', 'super_admin'], true), 403);
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();
        $adminId = (int) auth()->id();

        $this->syncActionableNotifications($adminId);

        $notifications = DB::table('admin_notifications')
            ->where('user_id', $adminId)
            ->orderByRaw('read_at IS NULL DESC')
            ->orderByDesc('created_at')
            ->limit(15)
            ->get()
            ->map(function ($item) {
                $item->created_at_human = $item->created_at
                    ? Carbon::parse($item->created_at)->diffForHumans()
                    : '';
                return $item;
            });

        $unreadCount = DB::table('admin_notifications')
            ->where('user_id', $adminId)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markRead($id)
    {
        $this->authorizeAdmin();

        $updated = DB::table('admin_notifications')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now(), 'updated_at' => now()]);

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'unread_count' => DB::table('admin_notifications')
                ->where('user_id', auth()->id())
                ->whereNull('read_at')
                ->count(),
        ]);
    }

    public function markAllRead()
    {
        $this->authorizeAdmin();

        $updated = DB::table('admin_notifications')
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now(), 'updated_at' => now()]);

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'unread_count' => 0,
        ]);
    }

    /**
     * Reconcile actionable records into notifications.
     * The unique (user_id, event_key) index prevents duplicate notifications
     * when this endpoint is polled or the admin refreshes the dashboard.
     */
    private function syncActionableNotifications(int $adminId): void
    {
        $add = function (string $key, string $type, string $title, string $message, string $url, string $icon) use ($adminId) {
            DB::table('admin_notifications')->insertOrIgnore([
                'user_id' => $adminId,
                'event_key' => $key,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'icon_class' => $icon,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        };

        // Registrations have no review-status column in the supplied schema.
        // Limit initial catch-up to the latest 30 days to avoid importing the entire historical table.
        DB::table('registrations')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('id')
            ->select('id', 'first_name', 'last_name', 'course_name')
            ->get()
            ->each(function ($row) use ($add) {
                $name = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''));
                $course = trim((string) ($row->course_name ?? ''));
                $message = ($name !== '' ? $name : 'A trainee') . ' submitted a registration'
                    . ($course !== '' ? ' for ' . $course : '') . '.';
                $add(
                    'registration:' . $row->id,
                    'registration',
                    'New trainee registration',
                    $message,
                    route('admin1', ['view' => 'registrations']),
                    'fa-solid fa-clipboard-list'
                );
            });

        // New user accounts that are still awaiting approval.
        DB::table('user_tbls')
            ->whereRaw('LOWER(status) = ?', ['pending'])
            ->whereIn(DB::raw('LOWER(role)'), ['student', 'trainer'])
            ->orderBy('id')
            ->select('id', 'firstname', 'lastname', 'role')
            ->get()
            ->each(function ($row) use ($add) {
                $name = trim(($row->firstname ?? '') . ' ' . ($row->lastname ?? ''));
                $role = strtolower((string) $row->role);
                $add(
                    'account-pending:' . $row->id,
                    'account',
                    'Account awaiting approval',
                    ($name !== '' ? $name : 'A new user') . ' has a pending ' . $role . ' account.',
                    route('admin1', ['view' => $role === 'trainer' ? 'all-trainers' : 'all-trainees']),
                    'fa-solid fa-user-clock'
                );
            });

        // Enrollment requests still awaiting admin action.
        DB::table('enrollment_tbls as e')
            ->leftJoin('user_tbls as u', 'u.id', '=', 'e.user_id')
            ->leftJoin('course_tbls as c', 'c.id', '=', 'e.course_id')
            ->whereRaw('LOWER(e.status) = ?', ['pending'])
            ->select('e.id', 'u.firstname', 'u.lastname', 'c.title as course_title')
            ->orderBy('e.id')
            ->get()
            ->each(function ($row) use ($add) {
                $name = trim(($row->firstname ?? '') . ' ' . ($row->lastname ?? ''));
                $course = trim((string) ($row->course_title ?? 'training course'));
                $add(
                    'enrollment-pending:' . $row->id,
                    'enrollment',
                    'Enrollment request pending',
                    ($name !== '' ? $name : 'A trainee') . ' requested enrollment in ' . $course . '.',
                    route('admin1', ['view' => 'all-trainees']),
                    'fa-solid fa-user-plus'
                );
            });

        // Certificate records that are still Pending.
        DB::table('certificates as cert')
            ->leftJoin('user_tbls as u', 'u.id', '=', 'cert.user_id')
            ->leftJoin('course_tbls as c', 'c.id', '=', 'cert.course_id')
            ->whereRaw('LOWER(cert.status) = ?', ['pending'])
            ->select('cert.id', 'u.firstname', 'u.lastname', 'c.title as course_title')
            ->orderBy('cert.id')
            ->get()
            ->each(function ($row) use ($add) {
                $name = trim(($row->firstname ?? '') . ' ' . ($row->lastname ?? ''));
                $course = trim((string) ($row->course_title ?? 'training course'));
                $add(
                    'certificate-pending:' . $row->id,
                    'certificate',
                    'Certificate awaiting processing',
                    ($name !== '' ? $name : 'A trainee') . ' has a pending certificate for ' . $course . '.',
                    route('admin1', ['view' => 'pending-certificates']),
                    'fa-solid fa-award'
                );
            });

        // Upcoming deadlines: only due today through the next seven days.
        if (Schema::hasTable('deadline_tbls')) {
            DB::table('deadline_tbls as d')
                ->leftJoin('course_tbls as c', 'c.id', '=', 'd.course_id')
                ->whereBetween('d.due_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
                ->select('d.id', 'd.title', 'd.due_date', 'c.title as course_title')
                ->orderBy('d.due_date')
                ->get()
                ->each(function ($row) use ($add) {
                    $course = trim((string) ($row->course_title ?? 'all courses'));
                    $due = Carbon::parse($row->due_date)->format('M j, Y');
                    $add(
                        'deadline:' . $row->id . ':' . $row->due_date,
                        'deadline',
                        'Upcoming training deadline',
                        $row->title . ' (' . $course . ') is due ' . $due . '.',
                        route('admin1', ['view' => 'announcements']),
                        'fa-solid fa-calendar-days'
                    );
                });
        }
    }
}
