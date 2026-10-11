<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
        content="{{ csrf_token() }}">

    <title>SuperAdmin Dashboard - LEDIPO</title>

    <!-- Shared LEDIPO Admin Dashboard Styling -->
    <link rel="stylesheet"
        href="{{ asset('stylesheet/admin-dashboard.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>

        /* ==========================================================
           SUPERADMIN DASHBOARD ONLY
        ========================================================== */

        .superadmin-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .superadmin-stat-card {
            background: #fff;
            border: 1px solid #e3e3e3;
            border-radius: 10px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }

        .superadmin-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 9px;
            background: #e8f5e9;
            color: #025628;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 17px;
        }

        .superadmin-stat-info small {
            display: block;
            color: #777;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .superadmin-stat-info strong {
            display: block;
            color: #025628;
            font-size: 22px;
            line-height: 1.1;
        }

        .superadmin-dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
            align-items: start;
        }

        .superadmin-card {
            background: #fff;
            border: 1px solid #e2e2e2;
            border-radius: 10px;
            padding: 18px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }

        .superadmin-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .superadmin-card-header h3 {
            margin: 0;
            color: #004d26;
            font-size: 15px;
            font-weight: 700;
        }

        .superadmin-card-header span {
            color: #888;
            font-size: 11px;
        }

        .distribution-row {
            margin-bottom: 15px;
        }

        .distribution-row:last-child {
            margin-bottom: 0;
        }

        .distribution-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .distribution-label span {
            font-size: 12px;
            color: #444;
        }

        .distribution-label strong {
            font-size: 12px;
            color: #025628;
        }

        .distribution-bar {
            width: 100%;
            height: 7px;
            background: #edf1ee;
            border-radius: 10px;
            overflow: hidden;
        }

        .distribution-fill {
            height: 100%;
            background: #025628;
            border-radius: 10px;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 11px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .activity-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e8f5e9;
            color: #025628;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 12px;
        }

        .activity-content {
            min-width: 0;
        }

        .activity-content strong {
            display: block;
            color: #333;
            font-size: 12px;
            margin-bottom: 2px;
        }

        .activity-content small {
            color: #999;
            font-size: 10px;
        }

        .superadmin-page-card {
            background: #fff;
            border: 1px solid #e2e2e2;
            border-radius: 10px;
            padding: 18px;
        }

        .superadmin-page-card h3 {
            margin: 0 0 8px;
            color: #004d26;
            font-size: 16px;
        }

        .superadmin-page-card p {
            margin: 0;
            color: #777;
            font-size: 13px;
        }

        .superadmin-placeholder {
            margin-top: 18px;
            padding: 35px 20px;
            text-align: center;
            border: 1px dashed #cfd8d2;
            border-radius: 8px;
            background: #fafcfb;
            color: #888;
        }

        .superadmin-placeholder i {
            display: block;
            color: #025628;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .superadmin-placeholder strong {
            display: block;
            color: #555;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .superadmin-placeholder span {
            font-size: 11px;
        }


/* ==========================================================
   ADMIN ACCOUNTS
========================================================== */

/* ---------- Header ---------- */

.admin-accounts-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 18px;
}

.admin-accounts-header h3 {
    margin-bottom: 6px;
}

.admin-accounts-header p {
    margin-bottom: 0;
}


/* ---------- Add Administrator Button ---------- */

.superadmin-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 10px 16px;

    border: none;
    border-radius: 7px;

    background: #025628;
    color: #ffffff;

    font-size: 12px;
    font-weight: 700;

    cursor: pointer;
    white-space: nowrap;

    transition: background 0.2s ease;
}

.superadmin-add-btn:hover {
    background: #01451f;
}


/* ==========================================================
   ADMIN ACCOUNTS TABLE
========================================================== */

.superadmin-table-wrapper {
    margin-top: 18px;
    width: 100%;
    overflow-x: auto;

    border: 1px solid #e2e2e2;
    border-radius: 8px;
}

.superadmin-table {
    width: 100%;
    min-width: 900px;

    background: #ffffff;

    border-collapse: collapse;
}

.superadmin-table thead {
    background: #f7faf8;
}

.superadmin-table th {
    padding: 13px 14px;

    text-align: left;

    color: #555;

    font-size: 11px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: 0.3px;

    border-bottom: 1px solid #e2e2e2;

    white-space: nowrap;
}

.superadmin-table td {
    padding: 14px;

    color: #444;

    font-size: 12px;

    border-bottom: 1px solid #eeeeee;

    vertical-align: middle;
}

.superadmin-table tbody tr:last-child td {
    border-bottom: none;
}

.superadmin-table tbody tr:hover {
    background: #fafcfb;
}


/* ---------- Administrator User ---------- */

.admin-user-cell {
    display: flex;
    align-items: center;
    gap: 10px;

    min-width: 180px;
}

.admin-user-avatar {
    width: 34px;
    height: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 50%;

    background: #e8f5e9;
    color: #025628;

    font-size: 11px;
    font-weight: 800;
}

.admin-user-cell strong {
    color: #333;

    font-size: 12px;
    font-weight: 700;
}


/* ---------- Status ---------- */

.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 5px 10px;

    border-radius: 20px;

    font-size: 10px;
    font-weight: 800;

    white-space: nowrap;
}

.status-badge.active {
    background: #e8f5e9;
    color: #025628;
}

.status-badge.inactive,
.status-badge.disabled {
    background: #f1f1f1;
    color: #777;
}

.status-badge.pending {
    background: #fff8d6;
    color: #856404;
}


/* ---------- Action Buttons ---------- */

.admin-action-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
}

.admin-action-btn {
    width: 32px;
    height: 32px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #dcdcdc;
    border-radius: 6px;

    background: #ffffff;

    cursor: pointer;

    transition: all 0.2s ease;
}

.admin-action-btn i {
    font-size: 12px;
}

.admin-action-btn.edit {
    color: #025628;
}

.admin-action-btn.edit:hover {
    background: #e8f5e9;
    border-color: #b8d9bf;
}

.admin-action-btn.disable {
    color: #777;
}

.admin-action-btn.disable:hover {
    background: #f5f5f5;
    border-color: #cfcfcf;
}


/* ---------- Empty State ---------- */

.empty-admins {
    padding: 45px 20px !important;

    text-align: center;

    color: #888 !important;
}

.empty-admins i {
    display: block;

    margin-bottom: 10px;

    color: #025628;

    font-size: 28px;
}

.empty-admins strong {
    display: block;

    margin-bottom: 4px;

    color: #555;

    font-size: 13px;
}

.empty-admins span {
    display: block;

    color: #888;

    font-size: 11px;
}


/* ==========================================================
   ADD ADMINISTRATOR MODAL
========================================================== */

/* ---------- Password Visibility ---------- */

.password-input-wrapper {
    position: relative;
    width: 100%;
}

.password-input-wrapper input {
    padding-right: 42px;
}

.password-toggle-btn {
    position: absolute;
    top: 50%;
    right: 10px;

    width: 28px;
    height: 28px;

    display: flex;
    align-items: center;
    justify-content: center;

    transform: translateY(-50%);

    border: none;
    background: transparent;

    color: #777;

    cursor: pointer;
}

.password-toggle-btn:hover {
    color: #025628;
}

.password-toggle-btn i {
    font-size: 13px;
}

.superadmin-modal {
    display: none;

    position: fixed;
    inset: 0;

    align-items: center;
    justify-content: center;

    padding: 20px;

    background: rgba(0, 0, 0, 0.45);

    z-index: 5000;
}

.superadmin-modal.open {
    display: flex;
}

.superadmin-modal-content {
    width: 100%;
    max-width: 650px;

    background: #ffffff;

    border-radius: 12px;

    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.18);

    overflow: hidden;
}


/* ---------- Modal Header ---------- */

.superadmin-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 20px;

    padding: 22px 24px;

    border-bottom: 1px solid #eeeeee;
}

.superadmin-modal-header h3 {
    margin: 0 0 5px;

    color: #025628;

    font-size: 18px;
}

.superadmin-modal-header p {
    margin: 0;

    color: #777;

    font-size: 12px;
}

.superadmin-modal-close {
    border: none;

    background: transparent;
    color: #777;

    font-size: 26px;
    line-height: 1;

    cursor: pointer;
}


/* ---------- Form ---------- */

.superadmin-form-grid {
    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 16px;

    padding: 22px 24px;
}

.superadmin-form-group {
    display: flex;
    flex-direction: column;

    gap: 6px;
}

.superadmin-form-group label {
    color: #444;

    font-size: 12px;
    font-weight: 700;
}

.superadmin-form-group input {
    width: 100%;
    box-sizing: border-box;

    padding: 10px 12px;

    border: 1px solid #dcdcdc;
    border-radius: 7px;

    outline: none;

    font-size: 12px;
    color: #333;
}

.superadmin-form-group input:focus {
    border-color: #025628;

    box-shadow:
        0 0 0 2px rgba(2, 86, 40, 0.08);
}


/* ---------- Modal Actions ---------- */

.superadmin-form-actions {
    display: flex;
    justify-content: flex-end;

    gap: 10px;

    padding: 16px 24px;

    border-top: 1px solid #eeeeee;

    background: #fafafa;
}

.superadmin-cancel-btn,
.superadmin-save-btn {
    padding: 10px 16px;

    border-radius: 7px;

    font-size: 12px;
    font-weight: 700;

    cursor: pointer;
}

.superadmin-cancel-btn {
    border: 1px solid #d8d8d8;

    background: #ffffff;
    color: #555;
}

.superadmin-save-btn {
    border: none;

    background: #025628;
    color: #ffffff;
}

.superadmin-save-btn:hover {
    background: #01451f;
}


/* ==========================================================
   SUPERADMIN NOTIFICATIONS
========================================================== */

.notification-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    flex-shrink: 0;
    margin-right: 10px;
}

.notification-btn {
    position: relative;
    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: none;
    background: transparent;

    color: #ffffff;
    font-size: 19px;

    border-radius: 50%;
    cursor: pointer;

    transition: all 0.2s ease;
}

.notification-btn:hover {
    background: rgba(255, 255, 255, 0.12);
}

.notification-badge {
    position: absolute;

    top: 1px;
    right: 0;

    min-width: 17px;
    height: 17px;

    padding: 0 4px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 20px;

    background: #f4d900;
    color: #025628;

    border: 2px solid #025628;

    font-size: 9px;
    font-weight: 800;

    line-height: 1;
}


/* ==========================================================
   NOTIFICATION DROPDOWN
========================================================== */

.notification-dropdown {
    position: absolute;

    top: calc(100% + 12px);
    right: 0;

    width: 360px;
    max-width: calc(100vw - 24px);

    background: #ffffff;

    border-radius: 14px;

    box-shadow:
        0 14px 35px rgba(0, 0, 0, 0.18),
        0 3px 10px rgba(0, 0, 0, 0.08);

    border: 1px solid #e7e7e7;

    overflow: hidden;

    opacity: 0;
    visibility: hidden;

    transform: translateY(-8px);

    transition:
        opacity 0.2s ease,
        transform 0.2s ease,
        visibility 0.2s ease;

    z-index: 3000;
}

.notification-dropdown.open {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}


/* Header */

.notification-header {
    padding: 17px 18px;

    display: flex;
    align-items: center;
    justify-content: space-between;
}

.notification-header h3 {
    margin: 0;

    font-size: 16px;
    font-weight: 800;

    color: #025628;
}

.notification-header span {
    display: block;

    margin-top: 3px;

    font-size: 11px;
    color: #888;
}

.notification-header-icon {
    color: #025628;
    font-size: 18px;
}

.notification-divider {
    height: 1px;
    background: #eeeeee;
}


/* Notification List */

.notification-list {
    max-height: 380px;
    overflow-y: auto;
}

.notification-item {
    display: flex;

    align-items: flex-start;

    gap: 12px;

    padding: 13px 16px;

    border-bottom: 1px solid #eeeeee;

    cursor: pointer;

    transition: background 0.2s ease;
}

.notification-item:hover {
    background: #f7fbf8;
}

.notification-item.notification-unread {
    background: #f7fbf8;
}

.notification-item-icon {
    width: 32px;
    height: 32px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #e8f5e9;

    color: #025628;

    font-size: 13px;
}

.notification-item-content {
    flex: 1;
    min-width: 0;
}

.notification-item-title {
    color: #222;

    font-size: 12px;
    font-weight: 700;

    margin-bottom: 3px;
}

.notification-item.notification-unread
.notification-item-title {
    font-weight: 800;
}

.notification-item-message {
    color: #666;

    font-size: 11px;

    line-height: 1.4;

    margin-bottom: 5px;
}

.notification-item-meta {
    color: #aaa;

    font-size: 10px;

    display: flex;
    align-items: center;

    gap: 4px;
}


/* Empty State */

.notification-empty {
    padding: 30px 20px;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    text-align: center;
}

.notification-empty-icon {
    width: 45px;
    height: 45px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #f1f5f2;
    color: #777;

    font-size: 18px;

    margin-bottom: 10px;
}

.notification-empty strong {
    font-size: 13px;
    color: #333;
}

.notification-empty span {
    margin-top: 4px;

    font-size: 11px;
    color: #888;
}


/* Footer */

.notification-footer {
    padding: 12px 16px;

    border-top: 1px solid #eeeeee;

    background: #fafafa;

    text-align: center;
}

.notification-footer a {
    display: inline-flex;

    align-items: center;

    gap: 7px;

    color: #025628;

    font-size: 12px;
    font-weight: 800;

    text-decoration: none;
}

.notification-footer a:hover {
    text-decoration: underline;
}

.notification-footer i {
    font-size: 10px;
}


/* Keep dropdown attached to notification area */

.topbar-right {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: nowrap;
}

.topbar-right .avatar-btn {
    flex-shrink: 0;
}


/* Mobile */

@media (max-width: 600px) {

    .notification-dropdown {
        position: fixed;

        top: 70px;
        right: 10px;
        left: 10px;

        width: auto;
        max-width: none;
    }

}


        /* ==========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 1000px) {

            .superadmin-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .superadmin-dashboard-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 600px) {

            .superadmin-stats {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>


<body>


<!-- ==========================================================
     TOPBAR
========================================================== -->

<nav class="topbar">

    <div class="topbar-left">

        <button
            class="hamburger"
            id="hamburger"
            aria-label="Toggle sidebar">

            <span></span>
            <span></span>
            <span></span>

        </button>


        <a
            href="{{ route('superadmin.dashboard') }}"
            class="topbar-brand">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="LEDIPO Logo"
                class="topbar-logo">

            <span>LEDIPO</span>

        </a>

    </div>


    <div class="topbar-right">


        <!-- ==================================================
             NOTIFICATION
        =================================================== -->

        <div
            class="notification-wrapper"
            id="notificationWrapper">

            <button
                type="button"
                class="notification-btn"
                id="notificationBtn"
                aria-label="Open notifications"
                aria-expanded="false">

                <i class="fa-solid fa-bell"></i>


                @php

                    $notificationAllAnnouncements =
                        \App\Models\Announcement::where(
                            'is_active',
                            1
                        )
                        ->latest()
                        ->get();

                    $notificationReadIds =
                        \DB::table('announcement_reads')
                        ->where(
                            'user_id',
                            \Auth::id()
                        )
                        ->whereIn(
                            'announcement_id',
                            $notificationAllAnnouncements->pluck('id')
                        )
                        ->pluck('announcement_id')
                        ->toArray();

                    $notificationLatest =
                        $notificationAllAnnouncements->take(5);

                    $notificationUnread =
                        $notificationAllAnnouncements
                        ->whereNotIn(
                            'id',
                            $notificationReadIds
                        );

                    $notificationAnnouncements =
                        $notificationLatest
                        ->concat($notificationUnread)
                        ->unique('id')
                        ->sortByDesc('created_at')
                        ->values();

                    $notificationCount =
                        $notificationUnread->count();

                @endphp


                @if($notificationCount > 0)

                    <span class="notification-badge">
                        {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                    </span>

                @endif

            </button>


            <!-- Notification Dropdown -->

            <div
                class="notification-dropdown"
                id="notificationDropdown">

                <div class="notification-header">

                    <div>

                        <h3>Notifications</h3>

                        <span>
                            {{ $notificationCount }}
                            unread notification{{ $notificationCount != 1 ? 's' : '' }}
                        </span>

                    </div>

                    <i class="fa-solid fa-bell notification-header-icon"></i>

                </div>


                <div class="notification-divider"></div>


                <div class="notification-list">

                    @forelse(
                        $notificationAnnouncements
                        as $announcement
                    )

                        <div
                            class="notification-item {{ in_array($announcement->id, $notificationReadIds, true) ? '' : 'notification-unread' }}"
                            data-announcement-id="{{ $announcement->id }}"
                            data-read="{{ in_array($announcement->id, $notificationReadIds, true) ? '1' : '0' }}"
                            role="button"
                            tabindex="0">

                            <div
                                class="notification-item-icon
                                notification-type-{{ strtolower($announcement->type) }}">

                                @if($announcement->type === 'urgent')

                                    <i class="fa-solid fa-circle-exclamation"></i>

                                @elseif($announcement->type === 'notice')

                                    <i class="fa-solid fa-bullhorn"></i>

                                @else

                                    <i class="fa-solid fa-bell"></i>

                                @endif

                            </div>


                            <div class="notification-item-content">

                                <div class="notification-item-title">
                                    {{ $announcement->title }}
                                </div>

                                <div class="notification-item-message">
                                    {{ $announcement->message }}
                                </div>

                                <div class="notification-item-meta">

                                    <i class="fa-regular fa-clock"></i>

                                    {{ $announcement->created_at->diffForHumans() }}

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="notification-empty">

                            <div class="notification-empty-icon">

                                <i class="fa-solid fa-bell-slash"></i>

                            </div>

                            <strong>No notifications</strong>

                            <span>
                                You're all caught up.
                            </span>

                        </div>

                    @endforelse

                </div>


                <div class="notification-footer">

                    <a
                        href="{{ route('admin1') }}?view=announcements">

                        View All Announcements

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>

            </div>

        </div>


        <!-- ==================================================
             PROFILE
        =================================================== -->

        @php

            $superAdminUser = \Auth::user();

            $superAdminName =
                trim(
                    ($superAdminUser->firstname ?? '') .
                    ' ' .
                    ($superAdminUser->lastname ?? '')
                );

            $superAdminName =
                $superAdminName !== ''
                    ? $superAdminName
                    : 'Super Admin';

            $nameParts =
                preg_split(
                    '/\s+/',
                    $superAdminName
                );

            $superAdminInitials =
                strtoupper(
                    substr($nameParts[0] ?? 'S', 0, 1) .
                    substr(
                        $nameParts[count($nameParts) - 1] ?? 'A',
                        0,
                        1
                    )
                );

        @endphp


        <button
            class="avatar-btn"
            id="avatarBtn"
            aria-label="Open profile menu">

            {{ $superAdminInitials }}

        </button>


        <div
            class="dropdown"
            id="dropdown">

            <div class="dropdown-header">

                <div class="dh-name">
                    {{ $superAdminName }}
                </div>

                <div class="dh-role">
                    SuperAdmin
                </div>

            </div>


            <div class="dd-divider"></div>


            <a
                href="#"
                class="dd-item"
                onclick="showView('profile'); return false;">

                <i class="fa fa-user dd-icon"></i>

                My Profile

            </a>


            <a
                href="#"
                class="dd-item dd-logout"
                onclick="
                    event.preventDefault();
                    openLogoutModal();
                ">

                <i class="fa fa-right-from-bracket dd-icon"></i>

                Log out

            </a>


            <form
                id="logout-form"
                action="{{ route('Logout') }}"
                method="POST"
                style="display:none;">

                @csrf

            </form>

        </div>

    </div>

</nav>



<!-- ==========================================================
     LOGOUT MODAL
========================================================== -->

<div
    id="logoutModal"
    class="modal"
    style="display:none;">

    <div class="modal-content">

        <div class="modal-header">

            <h3>
                <i class="fa-solid fa-right-from-bracket"></i>
                Confirm Logout
            </h3>

            <span
                class="close-modal"
                onclick="closeLogoutModal()">

                &times;

            </span>

        </div>


        <div
            style="
                padding:24px;
                text-align:center;
            ">

            <p
                style="
                    margin:0;
                    font-size:14px;
                    color:#333;
                ">

                Are you sure you want to log out?

            </p>

        </div>


        <div
            class="modal-actions-centered"
            style="
                display:flex;
                justify-content:center;
                gap:10px;
                padding-bottom:18px;
            ">

            <button
                onclick="confirmLogout()"
                class="btn-modal-yes">

                Yes

            </button>


            <button
                onclick="closeLogoutModal()"
                class="btn-modal-no">

                Cancel

            </button>

        </div>

    </div>

</div>



<!-- ==========================================================
     APPLICATION BODY
========================================================== -->

<div class="app-body">


    <!-- Mobile Overlay -->

    <div
        class="sidebar-overlay"
        id="overlay">
    </div>


    <!-- ======================================================
         SIDEBAR
    ======================================================= -->

    <aside
        class="sidebar"
        id="sidebar">


        <div class="sidebar-section-label">
            Menu
        </div>


        <a
            href="#dashboard"
            class="nav-item active"
            data-view="dashboard"
            onclick="
                showView('dashboard');
                return false;
            ">

            <i class="fa fa-house nav-icon"></i>

            <span>Dashboard</span>

        </a>



        <div class="sidebar-section-label">
            User Management
        </div>


        <a
            href="#admin-accounts"
            class="nav-item"
            data-view="admin-accounts"
            onclick="
                showView('admin-accounts');
                return false;
            ">

            <i class="fa fa-user-shield nav-icon"></i>

            <span>Admin Accounts</span>

        </a>


        <a
            href="#trainer-accounts"
            class="nav-item"
            data-view="trainer-accounts"
            onclick="
                showView('trainer-accounts');
                return false;
            ">

            <i class="fa fa-chalkboard-user nav-icon"></i>

            <span>Trainer Accounts</span>

        </a>


        <a
            href="#student-accounts"
            class="nav-item"
            data-view="student-accounts"
            onclick="
                showView('student-accounts');
                return false;
            ">

            <i class="fa fa-users nav-icon"></i>

            <span>Student Accounts</span>

        </a>



        <div class="sidebar-section-label">
            Access & Permissions
        </div>


        <a
            href="#roles-permissions"
            class="nav-item"
            data-view="roles-permissions"
            onclick="
                showView('roles-permissions');
                return false;
            ">

            <i class="fa fa-key nav-icon"></i>

            <span>Roles &amp; Permissions</span>

        </a>



        <div class="sidebar-section-label">
            System Management
        </div>


        <a
            href="#system-settings"
            class="nav-item"
            data-view="system-settings"
            onclick="
                showView('system-settings');
                return false;
            ">

            <i class="fa fa-gear nav-icon"></i>

            <span>System Settings</span>

        </a>


        <a
            href="#system-configuration"
            class="nav-item"
            data-view="system-configuration"
            onclick="
                showView('system-configuration');
                return false;
            ">

            <i class="fa fa-sliders nav-icon"></i>

            <span>System Configuration</span>

        </a>



        <div class="sidebar-section-label">
            Audit &amp; Logs
        </div>


        <a
            href="#user-activity"
            class="nav-item"
            data-view="user-activity"
            onclick="
                showView('user-activity');
                return false;
            ">

            <i class="fa fa-user-clock nav-icon"></i>

            <span>User Activity Logs</span>

        </a>


        <a
            href="#system-logs"
            class="nav-item"
            data-view="system-logs"
            onclick="
                showView('system-logs');
                return false;
            ">

            <i class="fa fa-file-lines nav-icon"></i>

            <span>System Logs</span>

        </a>



        <div class="sidebar-section-label">
            Account
        </div>


        <a
            href="#profile"
            class="nav-item"
            data-view="profile"
            onclick="
                showView('profile');
                return false;
            ">

            <i class="fa fa-user nav-icon"></i>

            <span>My Profile</span>

        </a>


        <a
            href="#logout"
            class="nav-item"
            onclick="
                openLogoutModal();
                return false;
            ">

            <i class="fa fa-right-from-bracket nav-icon"></i>

            <span>Logout</span>

        </a>


    </aside>



    <!-- ======================================================
         MAIN CONTENT
    ======================================================= -->

    <main class="admin-main">


        <!-- Breadcrumb -->

        <nav class="breadcrumb">

            <a
                href="#dashboard"
                onclick="
                    showView('dashboard');
                    return false;
                ">

                Home

            </a>

            /

            <span id="breadcrumb-current">
                SuperAdmin Dashboard
            </span>

        </nav>



        <!-- ==================================================
             DASHBOARD VIEW
        =================================================== -->

        <div
            id="view-dashboard"
            class="superadmin-view">


            <h1
                class="page-title"
                id="main-title">

                SuperAdmin Dashboard

            </h1>


            <p
                style="
                    color:#777;
                    font-size:13px;
                    margin:-10px 0 20px;
                ">

                System-wide overview and administrative controls.

            </p>



            <!-- ==============================================
                 STATISTICS
            =============================================== -->

            <div class="superadmin-stats">


                <!-- Total Admins -->

                <div class="superadmin-stat-card">

                    <div class="superadmin-stat-icon">

                        <i class="fa-solid fa-user-shield"></i>

                    </div>

                    <div class="superadmin-stat-info">

                        <small>Total Admins</small>

                        <strong>3</strong>

                    </div>

                </div>


                <!-- Total Trainers -->

                <div class="superadmin-stat-card">

                    <div class="superadmin-stat-icon">

                        <i class="fa-solid fa-chalkboard-user"></i>

                    </div>

                    <div class="superadmin-stat-info">

                        <small>Total Trainers</small>

                        <strong>8</strong>

                    </div>

                </div>


                <!-- Total Students -->

                <div class="superadmin-stat-card">

                    <div class="superadmin-stat-icon">

                        <i class="fa-solid fa-users"></i>

                    </div>

                    <div class="superadmin-stat-info">

                        <small>Total Students</small>

                        <strong>245</strong>

                    </div>

                </div>


                <!-- Total Users -->

                <div class="superadmin-stat-card">

                    <div class="superadmin-stat-icon">

                        <i class="fa-solid fa-user-group"></i>

                    </div>

                    <div class="superadmin-stat-info">

                        <small>Total Users</small>

                        <strong>256</strong>

                    </div>

                </div>


            </div>



            <!-- ==============================================
                 DASHBOARD GRID
            =============================================== -->

            <div class="superadmin-dashboard-grid">


                <!-- USER DISTRIBUTION -->

                <div class="superadmin-card">

                    <div class="superadmin-card-header">

                        <h3>
                            <i
                                class="fa-solid fa-chart-simple"
                                style="margin-right:7px;">
                            </i>

                            User Distribution

                        </h3>

                        <span>
                            Current overview
                        </span>

                    </div>


                    <!-- Admin -->

                    <div class="distribution-row">

                        <div class="distribution-label">

                            <span>Administrators</span>

                            <strong>3</strong>

                        </div>

                        <div class="distribution-bar">

                            <div
                                class="distribution-fill"
                                style="width:12%;">
                            </div>

                        </div>

                    </div>


                    <!-- Trainers -->

                    <div class="distribution-row">

                        <div class="distribution-label">

                            <span>Trainers</span>

                            <strong>8</strong>

                        </div>

                        <div class="distribution-bar">

                            <div
                                class="distribution-fill"
                                style="width:18%;">
                            </div>

                        </div>

                    </div>


                    <!-- Students -->

                    <div class="distribution-row">

                        <div class="distribution-label">

                            <span>Students / Trainees</span>

                            <strong>245</strong>

                        </div>

                        <div class="distribution-bar">

                            <div
                                class="distribution-fill"
                                style="width:95%;">
                            </div>

                        </div>

                    </div>

                </div>



                <!-- RECENT ACTIVITY -->

                <div class="superadmin-card">

                    <div class="superadmin-card-header">

                        <h3>
                            <i
                                class="fa-solid fa-clock-rotate-left"
                                style="margin-right:7px;">
                            </i>

                            Recent Activity

                        </h3>

                    </div>


                    <div class="activity-item">

                        <div class="activity-icon">

                            <i class="fa-solid fa-user-plus"></i>

                        </div>

                        <div class="activity-content">

                            <strong>
                                New administrator account
                            </strong>

                            <small>
                                Recently added
                            </small>

                        </div>

                    </div>


                    <div class="activity-item">

                        <div class="activity-icon">

                            <i class="fa-solid fa-key"></i>

                        </div>

                        <div class="activity-content">

                            <strong>
                                Permission settings updated
                            </strong>

                            <small>
                                System access
                            </small>

                        </div>

                    </div>


                    <div class="activity-item">

                        <div class="activity-icon">

                            <i class="fa-solid fa-right-to-bracket"></i>

                        </div>

                        <div class="activity-content">

                            <strong>
                                Administrator logged in
                            </strong>

                            <small>
                                Recent system activity
                            </small>

                        </div>

                    </div>


                </div>

            </div>


        </div>



        <!-- ==========================================================
     ADMIN ACCOUNTS
========================================================== -->

<div
    id="view-admin-accounts"
    class="superadmin-view"
    style="display:none;"
>

    <h1 class="page-title">
        Admin Accounts
    </h1>


    <!-- ADMIN ACCOUNTS CARD -->

    <div class="superadmin-page-card">

        <!-- HEADER -->

        <div class="admin-accounts-header">

            <div>

                <h3>
                    Manage Administrator Accounts
                </h3>

                <p>
                    Create, update, disable, and manage administrator
                    accounts from this section.
                </p>

            </div>


            <button
                type="button"
                class="superadmin-add-btn"
                onclick="openAddAdminModal()"
            >

                <i class="fa-solid fa-plus"></i>

                Add Administrator

            </button>

        </div>


        <!-- ADMINISTRATOR TABLE -->

        <div class="superadmin-table-wrapper">

            <table class="superadmin-table">

                <thead>

                    <tr>

                        <th>
                            Administrator
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Username
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Date Created
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($admins as $admin)

                        <tr>

                            <!-- Administrator -->

                            <td>

                                <div class="admin-user-cell">

                                    <div class="admin-user-avatar">

                                        {{ strtoupper(substr($admin->firstname, 0, 1)) }}{{ strtoupper(substr($admin->lastname, 0, 1)) }}

                                    </div>


                                    <div>

                                        <strong>
                                            {{ $admin->firstname }}
                                            {{ $admin->lastname }}
                                        </strong>

                                    </div>

                                </div>

                            </td>


                            <!-- Email -->

                            <td>
                                {{ $admin->email }}
                            </td>


                            <!-- Username -->

                            <td>
                                {{ $admin->username }}
                            </td>


                            <!-- Status -->

                            <td>

                                @php
                                    $status = strtolower(
                                        $admin->status ?? 'inactive'
                                    );
                                @endphp

                                <span
                                    class="status-badge {{ $status }}"
                                >
                                    {{ ucfirst($status) }}
                                </span>

                            </td>


                            <!-- Date Created -->

                            <td>

                                {{ $admin->created_at?->format('M d, Y') ?? 'N/A' }}

                            </td>


                            <!-- Actions -->

                            <td>

                                <div class="admin-action-buttons">

                                    <button
                                        type="button"
                                        class="admin-action-btn edit"
                                        title="Edit Administrator"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                    </button>


                                    <button
                                        type="button"
                                        class="admin-action-btn disable"
                                        title="Disable Administrator"
                                    >

                                        <i class="fa-solid fa-user-slash"></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="empty-admins"
                            >

                                <i class="fa-solid fa-user-shield"></i>

                                <strong>
                                    No administrator accounts found.
                                </strong>

                                <span>
                                    There are currently no users with the
                                    Admin role.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- ==========================================================
     ADD ADMINISTRATOR MODAL
========================================================== -->

<div
    id="addAdminModal"
    class="superadmin-modal"
>

    <div class="superadmin-modal-content">


        <!-- MODAL HEADER -->

        <div class="superadmin-modal-header">

            <div>

                <h3>
                    Add Administrator
                </h3>

                <p>
                    Create a new administrator account.
                </p>

            </div>


            <button
                type="button"
                class="superadmin-modal-close"
                onclick="closeAddAdminModal()"
            >

                &times;

            </button>

        </div>


        <!-- FORM -->

        <form
            method="POST"
            action="{{ route('superadmin.admin.store') }}"
        >

            @csrf


            <div class="superadmin-form-grid">

                <!-- First Name -->

                <div class="superadmin-form-group">

                    <label for="admin_firstname">
                        First Name
                    </label>

                    <input
                        type="text"
                        id="admin_firstname"
                        name="firstname"
                        required
                    >

                </div>


                <!-- Last Name -->

                <div class="superadmin-form-group">

                    <label for="admin_lastname">
                        Last Name
                    </label>

                    <input
                        type="text"
                        id="admin_lastname"
                        name="lastname"
                        required
                    >

                </div>


                <!-- Email -->

                <div class="superadmin-form-group">

                    <label for="admin_email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="admin_email"
                        name="email"
                        required
                    >

                </div>


                <!-- Username -->

                <div class="superadmin-form-group">

                    <label for="admin_username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="admin_username"
                        name="username"
                        required
                    >

                </div>


                <!-- Password -->

<div class="superadmin-form-group">

    <label for="admin_password">
        Password
    </label>

    <div class="password-input-wrapper">

        <input
            type="password"
            id="admin_password"
            name="password"
            required
        >

        <button
            type="button"
            class="password-toggle-btn"
            onclick="togglePasswordVisibility('admin_password', 'adminPasswordIcon')"
            title="Show password"
        >
            <i
                id="adminPasswordIcon"
                class="fa-solid fa-eye"
            ></i>
        </button>

    </div>

</div>


                <!-- Confirm Password -->

<div class="superadmin-form-group">

    <label for="admin_password_confirmation">
        Confirm Password
    </label>

    <div class="password-input-wrapper">

        <input
            type="password"
            id="admin_password_confirmation"
            name="password_confirmation"
            required
        >

        <button
            type="button"
            class="password-toggle-btn"
            onclick="togglePasswordVisibility('admin_password_confirmation', 'adminConfirmPasswordIcon')"
            title="Show password"
        >
            <i
                id="adminConfirmPasswordIcon"
                class="fa-solid fa-eye"
            ></i>
        </button>

    </div>

</div>


            <!-- FORM ACTIONS -->

            <div class="superadmin-form-actions">

                <button
                    type="button"
                    class="superadmin-cancel-btn"
                    onclick="closeAddAdminModal()"
                >

                    Cancel

                </button>


                <button
                    type="submit"
                    class="superadmin-save-btn"
                >

                    <i class="fa-solid fa-user-plus"></i>

                    Create Administrator

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ==================================================
             TRAINER ACCOUNTS
        =================================================== -->

        <div
            id="view-trainer-accounts"
            class="superadmin-view"
            style="display:none;">

            <h1 class="page-title">
                Trainer Accounts
            </h1>

            <div class="superadmin-page-card">

                <h3>
                    Trainer Account Management
                </h3>

                <p>
                    View and manage trainer accounts across the system.
                </p>

                <div class="superadmin-placeholder">

                    <i class="fa-solid fa-chalkboard-user"></i>

                    <strong>
                        Trainer Accounts
                    </strong>

                    <span>
                        Trainer management will be added here.
                    </span>

                </div>

            </div>

        </div>



        <!-- ==================================================
             STUDENT ACCOUNTS
        =================================================== -->

        <div
            id="view-student-accounts"
            class="superadmin-view"
            style="display:none;">

            <h1 class="page-title">
                Student Accounts
            </h1>

            <div class="superadmin-page-card">

                <h3>
                    Student / Trainee Accounts
                </h3>

                <p>
                    View and manage registered trainees and student accounts.
                </p>

                <div class="superadmin-placeholder">

                    <i class="fa-solid fa-users"></i>

                    <strong>
                        Student Accounts
                    </strong>

                    <span>
                        Student account management will be added here.
                    </span>

                </div>

            </div>

        </div>



        <!-- ==================================================
             ROLES & PERMISSIONS
        =================================================== -->

        <div
            id="view-roles-permissions"
            class="superadmin-view"
            style="display:none;">

            <h1 class="page-title">
                Roles &amp; Permissions
            </h1>

            <div class="superadmin-page-card">

                <h3>
                    Access &amp; Permissions
                </h3>

                <p>
                    Manage system roles and determine which functions
                    each role can access.
                </p>

                <div class="superadmin-placeholder">

                    <i class="fa-solid fa-key"></i>

                    <strong>
                        Role &amp; Permission Management
                    </strong>

                    <span>
                        Permission controls will be added here.
                    </span>

                </div>

            </div>

        </div>



        <!-- ==================================================
             SYSTEM SETTINGS
        =================================================== -->

        <div
            id="view-system-settings"
            class="superadmin-view"
            style="display:none;">

            <h1 class="page-title">
                System Settings
            </h1>

            <div class="superadmin-page-card">

                <h3>
                    System Settings
                </h3>

                <p>
                    Manage system-wide settings and administrative preferences.
                </p>

                <div class="superadmin-placeholder">

                    <i class="fa-solid fa-gear"></i>

                    <strong>
                        System Settings
                    </strong>

                    <span>
                        System settings will be added here.
                    </span>

                </div>

            </div>

        </div>



        <!-- ==================================================
             SYSTEM CONFIGURATION
        =================================================== -->

        <div
            id="view-system-configuration"
            class="superadmin-view"
            style="display:none;">

            <h1 class="page-title">
                System Configuration
            </h1>

            <div class="superadmin-page-card">

                <h3>
                    System Configuration
                </h3>

                <p>
                    Configure system-level options and application behavior.
                </p>

                <div class="superadmin-placeholder">

                    <i class="fa-solid fa-sliders"></i>

                    <strong>
                        System Configuration
                    </strong>

                    <span>
                        Configuration controls will be added here.
                    </span>

                </div>

            </div>

        </div>



        <!-- ==================================================
             USER ACTIVITY LOGS
        =================================================== -->

        <div
            id="view-user-activity"
            class="superadmin-view"
            style="display:none;">

            <h1 class="page-title">
                User Activity Logs
            </h1>

            <div class="superadmin-page-card">

                <h3>
                    User Activity
                </h3>

                <p>
                    Review important user actions and account activity.
                </p>

                <div class="superadmin-placeholder">

                    <i class="fa-solid fa-user-clock"></i>

                    <strong>
                        User Activity Logs
                    </strong>

                    <span>
                        Activity logging will be added here.
                    </span>

                </div>

            </div>

        </div>



        <!-- ==================================================
             SYSTEM LOGS
        =================================================== -->

        <div
            id="view-system-logs"
            class="superadmin-view"
            style="display:none;">

            <h1 class="page-title">
                System Logs
            </h1>

            <div class="superadmin-page-card">

                <h3>
                    System Logs
                </h3>

                <p>
                    Review system-level events and administrative changes.
                </p>

                <div class="superadmin-placeholder">

                    <i class="fa-solid fa-file-lines"></i>

                    <strong>
                        System Logs
                    </strong>

                    <span>
                        System log records will be added here.
                    </span>

                </div>

            </div>

        </div>



        <!-- ==================================================
             PROFILE
        =================================================== -->

        <div
            id="view-profile"
            class="superadmin-view"
            style="display:none;">

            <h1 class="page-title">
                My Profile
            </h1>

            <div class="superadmin-page-card">

                <h3>
                    SuperAdmin Profile
                </h3>

                <p>
                    View your current SuperAdmin account information.
                </p>

                <div
                    style="
                        margin-top:18px;
                        display:grid;
                        grid-template-columns:
                            repeat(auto-fit,minmax(200px,1fr));
                        gap:12px;
                    ">

                    <div
                        style="
                            border:1px solid #e5e5e5;
                            border-radius:8px;
                            padding:14px;
                        ">

                        <small
                            style="
                                display:block;
                                color:#888;
                                font-size:11px;
                                margin-bottom:4px;
                            ">

                            Name

                        </small>

                        <strong
                            style="
                                color:#333;
                                font-size:13px;
                            ">

                            {{ $superAdminName }}

                        </strong>

                    </div>


                    <div
                        style="
                            border:1px solid #e5e5e5;
                            border-radius:8px;
                            padding:14px;
                        ">

                        <small
                            style="
                                display:block;
                                color:#888;
                                font-size:11px;
                                margin-bottom:4px;
                            ">

                            Email

                        </small>

                        <strong
                            style="
                                color:#333;
                                font-size:13px;
                            ">

                            {{ $superAdminUser->email }}

                        </strong>

                    </div>


                    <div
                        style="
                            border:1px solid #e5e5e5;
                            border-radius:8px;
                            padding:14px;
                        ">

                        <small
                            style="
                                display:block;
                                color:#888;
                                font-size:11px;
                                margin-bottom:4px;
                            ">

                            Role

                        </small>

                        <strong
                            style="
                                color:#025628;
                                font-size:13px;
                            ">

                            SuperAdmin

                        </strong>

                    </div>

                </div>

            </div>

        </div>


    </main>

</div>



<!-- ==========================================================
     JAVASCRIPT
========================================================== -->

<script>

/* ==========================================================
   ADMINISTRATOR MODAL
========================================================== */

function togglePasswordVisibility(inputId, iconId) {

    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === 'password') {

        input.type = 'text';

        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');

    } else {

        input.type = 'password';

        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');

    }
}

function openAddAdminModal() {

    document
        .getElementById('addAdminModal')
        .classList.add('open');

}


function closeAddAdminModal() {

    document
        .getElementById('addAdminModal')
        .classList.remove('open');

}


/* ==========================================================
   PROFILE DROPDOWN
========================================================== */

const avatarBtn =
    document.getElementById('avatarBtn');

const dropdown =
    document.getElementById('dropdown');

const notificationBtn =
    document.getElementById('notificationBtn');

const notificationDropdown =
    document.getElementById('notificationDropdown');


avatarBtn.addEventListener('click', function(event) {

    event.stopPropagation();

    dropdown.classList.toggle('open');

    notificationDropdown.classList.remove('open');

});


notificationBtn.addEventListener('click', function(event) {

    event.stopPropagation();

    notificationDropdown.classList.toggle('open');

    dropdown.classList.remove('open');

});


document.addEventListener('click', function(event) {

    if (!event.target.closest('.topbar-right')) {

        dropdown.classList.remove('open');

        notificationDropdown.classList.remove('open');

    }

});



/* ==========================================================
   LOGOUT
========================================================== */

function openLogoutModal() {

    dropdown.classList.remove('open');

    document.getElementById('logoutModal').style.display = 'flex';

}


function closeLogoutModal() {

    document.getElementById('logoutModal').style.display = 'none';

}


function confirmLogout() {

    document
        .getElementById('logout-form')
        .submit();

}


document
    .getElementById('logoutModal')
    .addEventListener('click', function(event) {

        if (event.target === this) {

            closeLogoutModal();

        }

    });



/* ==========================================================
   SIDEBAR
========================================================== */

const hamburger =
    document.getElementById('hamburger');

const sidebar =
    document.getElementById('sidebar');

const overlay =
    document.getElementById('overlay');


hamburger.addEventListener('click', function() {

    sidebar.classList.toggle('sidebar-open');

    overlay.classList.toggle('show');

});


overlay.addEventListener('click', function() {

    sidebar.classList.remove('sidebar-open');

    overlay.classList.remove('show');

});



/* ==========================================================
   VIEW NAVIGATION
========================================================== */

const viewTitles = {

    'dashboard':
        'SuperAdmin Dashboard',

    'admin-accounts':
        'Admin Accounts',

    'trainer-accounts':
        'Trainer Accounts',

    'student-accounts':
        'Student Accounts',

    'roles-permissions':
        'Roles & Permissions',

    'system-settings':
        'System Settings',

    'system-configuration':
        'System Configuration',

    'user-activity':
        'User Activity Logs',

    'system-logs':
        'System Logs',

    'profile':
        'My Profile'

};


function showView(view) {

    /* Hide all views */

    document
        .querySelectorAll('.superadmin-view')
        .forEach(function(element) {

            element.style.display = 'none';

        });


    /* Show selected view */

    const selectedView =
        document.getElementById(
            'view-' + view
        );

    if (selectedView) {

        selectedView.style.display = 'block';

    }


    /* Update page title */

    const title =
        viewTitles[view] ||
        'SuperAdmin Dashboard';

    document
        .getElementById('main-title');

    const dashboardTitle =
        document.querySelector(
            '#view-' + view + ' .page-title'
        );

    if (dashboardTitle) {

        dashboardTitle.textContent = title;

    }


    /* Update breadcrumb */

    const breadcrumb =
        document.getElementById(
            'breadcrumb-current'
        );

    if (breadcrumb) {

        breadcrumb.textContent = title;

    }


    /* Update active sidebar item */

    document
        .querySelectorAll(
            '.sidebar .nav-item'
        )
        .forEach(function(item) {

            item.classList.remove('active');

        });


    const activeItem =
        document.querySelector(
            '.sidebar .nav-item[data-view="' +
            view +
            '"]'
        );

    if (activeItem) {

        activeItem.classList.add('active');

    }


    /* Close mobile sidebar */

    if (window.innerWidth <= 768) {

        sidebar.classList.remove('sidebar-open');

        overlay.classList.remove('show');

    }

}



/* ==========================================================
   NOTIFICATION READ STATE
========================================================== */

function markNotificationRead(item) {

    if (!item) return;

    if (item.dataset.read === '1') return;

    const announcementId =
        item.dataset.announcementId;

    if (!announcementId) return;


    fetch(
        `{{ url('/notifications/announcements') }}/${announcementId}/read`,
        {
            method: 'POST',

            headers: {

                'X-CSRF-TOKEN':
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .getAttribute('content'),

                'Accept':
                    'application/json',

                'Content-Type':
                    'application/json'

            },

            body: JSON.stringify({})

        }
    )
    .then(function(response) {

        if (!response.ok) {

            throw new Error(
                'Unable to mark notification as read.'
            );

        }

        return response.json();

    })
    .then(function(data) {

        if (!data || !data.success) return;

        item.dataset.read = '1';

        item.classList.remove(
            'notification-unread'
        );


        const badge =
            document.querySelector(
                '.notification-badge'
            );

        if (badge) {

            const currentCount =
                parseInt(
                    badge.textContent
                        .replace('+', '')
                        .trim(),
                    10
                ) || 0;

            const nextCount =
                Math.max(
                    currentCount - 1,
                    0
                );

            if (nextCount === 0) {

                badge.remove();

            } else {

                badge.textContent =
                    nextCount > 99
                        ? '99+'
                        : String(nextCount);

            }

        }

    })
    .catch(function(error) {

        console.error(
            'Notification read error:',
            error
        );

    });

}


document
    .querySelectorAll(
        '.notification-item[data-announcement-id]'
    )
    .forEach(function(item) {

        item.addEventListener(
            'click',
            function() {

                markNotificationRead(this);

            }
        );


        item.addEventListener(
            'keydown',
            function(event) {

                if (
                    event.key === 'Enter' ||
                    event.key === ' '
                ) {

                    event.preventDefault();

                    markNotificationRead(this);

                }

            }
        );

    });



/* ==========================================================
   INITIAL VIEW
========================================================== */

document.addEventListener(
    'DOMContentLoaded',
    function() {

        showView('dashboard');

    }
);

</script>


</body>

</html>