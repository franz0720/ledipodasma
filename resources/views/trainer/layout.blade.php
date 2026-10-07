<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'LEDIPO') — Trainer</title>

  <link rel="stylesheet" href="{{ asset('stylesheet/trainer.css') }}">
  <link rel="stylesheet" href="{{ asset('stylesheet/layout.css') }}">
  @yield('css')

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
    rel="stylesheet">
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">

    <!-- Tab Icon -->
   <link rel="icon" type="image/png" href="{{ asset('images/logo_ledipo.png') }}">

  <style>
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
      background: white;
      padding: 20px;
      width: 300px;
      margin: 15% auto;
      text-align: center;
      border-radius: 10px;
    }

    .modal-actions-centered button {
      margin: 10px;
      padding: 8px 15px;
    }

    /* ==========================================================
   TRAINER NOTIFICATION BELL
   ========================================================== */

.notification-wrapper {
  position: relative;
  display: flex;
  align-items: center;
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
  font-size: 18px;

  border-radius: 50%;
  cursor: pointer;

  transition: all 0.2s ease;
}

.notification-btn:hover {
  background: rgba(255, 255, 255, 0.12);
}

.notification-badge {
  position: absolute;

  top: 0;
  right: -1px;

  min-width: 17px;
  height: 17px;

  padding: 0 4px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 20px;

  background: #f5c842;
  color: #1a4d2e;

  border: 2px solid #1a4d2e;

  font-size: 9px;
  font-weight: 800;
}

.topbar-right {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: nowrap;
    gap: 0;
}

.notification-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    flex-shrink: 0;
    margin-right: 10px;
}

.avatar-btn {
    flex-shrink: 0;
}

/* ==========================================================
   TRAINER PROFILE DROPDOWN - KEEP ANCHORED TO BT
   ========================================================== */

.topbar-right {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  flex-wrap: nowrap;
}

.topbar-right .dropdown {
  position: absolute !important;
  top: calc(100% + 10px) !important;
  right: 0 !important;
  left: auto !important;
  margin: 0 !important;
  z-index: 3000 !important;
}

.topbar-right .avatar-btn {
  flex-shrink: 0;
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

  z-index: 2000;
}

.notification-dropdown.open {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

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

  color: #1a4d2e;
}

.notification-header span {
  display: block;

  margin-top: 3px;

  font-size: 11px;
  color: #777;
}

.notification-header-icon {
  font-size: 18px;
  color: #1a4d2e;
}

.notification-divider {
  height: 1px;
  background: #eeeeee;
}

/* ==========================================================
   NOTIFICATION ITEMS
   ========================================================== */

.notification-list {
  max-height: 350px;
  overflow-y: auto;
}

.notification-item {
  display: flex;
  gap: 12px;

  padding: 14px 16px;

  border-bottom: 1px solid #f0f0f0;

  transition: background 0.2s ease;
}

.notification-item:hover {
  background: #f8faf8;
}

/* ==========================================================
   READ / UNREAD VISUAL STATE
   ========================================================== */

/* Unread notification */
.notification-item.notification-unread {
  background: #f3faf6;
  box-shadow: inset 4px 0 0 #025628;
}

/* Unread title */
.notification-item.notification-unread .notification-item-title {
  font-weight: 900;
  color: #025628;
}

/* Read notification */
.notification-item:not(.notification-unread) .notification-item-title {
  font-weight: 500;
  color: #555;
}

.notification-item:not(.notification-unread) .notification-item-message {
  color: #888;
}

.notification-item:not(.notification-unread) .notification-item-icon {
  opacity: 0.65;
}

.notification-item:last-child {
  border-bottom: none;
}

.notification-item-icon {
  flex: 0 0 36px;

  width: 36px;
  height: 36px;

  border-radius: 50%;

  display: flex;
  align-items: center;
  justify-content: center;

  background: #e6f4eb;
  color: #1a4d2e;

  font-size: 14px;
}

.notification-type-urgent {
  background: #fff0f0;
  color: #c62828;
}

.notification-type-reminder {
  background: #fff8df;
  color: #8a6d00;
}

.notification-item-content {
  min-width: 0;
  flex: 1;
}

.notification-item-title {
  font-size: 13px;
  font-weight: 500;
  color: #222;

  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.notification-item-message {
  margin-top: 3px;

  font-size: 12px;
  line-height: 1.45;

  color: #666;
}

.notification-item-meta {
  display: flex;
  align-items: center;
  gap: 5px;

  margin-top: 7px;

  font-size: 10px;
  color: #8a8a8a;
}

/* ==========================================================
   EMPTY STATE
   ========================================================== */

.notification-empty {
  padding: 35px 20px;

  display: flex;
  flex-direction: column;
  align-items: center;

  text-align: center;
}

.notification-empty-icon {
  width: 48px;
  height: 48px;

  margin-bottom: 10px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: #f1f5f2;
  color: #777;

  font-size: 18px;
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

/* ==========================================================
   FOOTER
   ========================================================== */

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

  color: #1a4d2e;

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

/* ==========================================================
   MOBILE
   ========================================================== */

@media (max-width: 600px) {

  .notification-dropdown {
    position: fixed;

    top: 70px;
    right: 10px;
    left: 10px;

    width: auto;
    max-width: none;
  }

  .notification-item.notification-unread {
    background: #f7fbf8;
  }

  .notification-item.notification-unread .notification-item-title {
      font-weight: 800;
  }

}

  </style>
</head>

<body>

  {{-- ===== TOPBAR (unchanged) ===== --}}
  <nav class="topbar">
    <div class="topbar-left">
      <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <a href="{{ route('teacher') }}" class="topbar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="logo"
          class="topbar-logo">
        <span>LEDIPO</span>
      </a>
    </div>

<div class="topbar-right">

  <!-- Notification Bell -->
  <div class="notification-wrapper" id="notificationWrapper">

    <button
      type="button"
      class="notification-btn"
      id="notificationBtn"
      aria-label="Open notifications"
      aria-expanded="false">

      <i class="fa-solid fa-bell"></i>

      @php
          // Trainer receives General and Trainer announcements.
          $notificationAllAnnouncements = \App\Models\Announcement::where('is_active', 1)
              ->whereIn('audience', ['general', 'trainer'])
              ->latest()
              ->get();

          // Read announcements for the currently logged-in Trainer.
          $notificationReadIds = \DB::table('announcement_reads')
              ->where('user_id', \Auth::id())
              ->whereIn('announcement_id', $notificationAllAnnouncements->pluck('id'))
              ->pluck('announcement_id')
              ->toArray();

          /*
          |--------------------------------------------------------------------------
          | Trainer Notification Dropdown
          |--------------------------------------------------------------------------
          | Show the latest 5 announcements PLUS any older unread
          | announcements so unread notifications are never hidden.
          |--------------------------------------------------------------------------
          */

          $notificationLatest = $notificationAllAnnouncements->take(5);

          $notificationUnread = $notificationAllAnnouncements
              ->whereNotIn('id', $notificationReadIds);

          $notificationAnnouncements = $notificationLatest
              ->concat($notificationUnread)
              ->unique('id')
              ->sortByDesc('created_at')
              ->values();

          $notificationCount = $notificationUnread->count();
      @endphp

      @if($notificationCount > 0)
        <span class="notification-badge">
        {{ $notificationCount > 99 ? '99+' : $notificationCount }}
        </span>
      @endif

    </button>

    <!-- Notification Dropdown -->
    <div class="notification-dropdown" id="notificationDropdown">

      <div class="notification-header">
        <div>
          <h3>Notifications</h3>
          <span>
          {{ $notificationCount }} unread notification{{ $notificationCount != 1 ? 's' : '' }}
          </span>
        </div>

        <i class="fa-solid fa-bell notification-header-icon"></i>
      </div>

      <div class="notification-divider"></div>

      <div class="notification-list">

        @forelse($notificationAnnouncements as $announcement)

          <div
          class="notification-item {{ in_array($announcement->id, $notificationReadIds, true) ? '' : 'notification-unread' }}"
          data-announcement-id="{{ $announcement->id }}"
          role="button"
          tabindex="0"
          >

            <div class="notification-item-icon
              notification-type-{{ strtolower($announcement->type) }}">

              @if(strtolower($announcement->type) === 'urgent')
                <i class="fa-solid fa-triangle-exclamation"></i>
              @elseif(strtolower($announcement->type) === 'reminder')
                <i class="fa-solid fa-clock"></i>
              @else
                <i class="fa-solid fa-bell"></i>
              @endif

            </div>

            <div class="notification-item-content">

              <div class="notification-item-title">
                {{ $announcement->title }}
              </div>

              <div class="notification-item-message">
                {{ \Illuminate\Support\Str::limit($announcement->message, 75) }}
              </div>

              <div class="notification-item-meta">
                <span>{{ ucfirst($announcement->type) }}</span>
                <span>•</span>
                <span>{{ $announcement->created_at?->diffForHumans() }}</span>
              </div>

            </div>

          </div>

        @empty

          <div class="notification-empty">
            <div class="notification-empty-icon">
              <i class="fa-regular fa-bell-slash"></i>
            </div>

            <strong>No notifications</strong>

            <span>You're all caught up.</span>
          </div>

        @endforelse

      </div>

      <div class="notification-footer">
        <a href="{{ route('teacher') }}">
          View All Announcements
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

    </div>

  </div>

  <!-- Trainer Profile -->
  <button class="avatar-btn" id="avatarBtn" aria-label="Open profile menu">
    {{ strtoupper(substr(Auth::user()->firstname ?? 'T', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? '', 0, 1)) }}
  </button>

  <div class="dropdown" id="dropdown">

    <div class="dropdown-header">
      <div class="dd-avatar">
        {{ strtoupper(substr(Auth::user()->firstname ?? 'T', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? '', 0, 1)) }}
      </div>

      <div>
        <div class="dh-name">
          {{ Auth::user()->firstname }}
          {{ Auth::user()->lastname }}
        </div>

        <div class="dh-role">
          {{ ucfirst(Auth::user()->role) }}
        </div>
      </div>
    </div>

    <div class="dd-items">

      <a href="{{ route('trainer.profile') }}" class="dd-item">
        <i class="fa fa-user dd-icon"></i>
        Profile
      </a>

      <div class="dd-divider"></div>

      <a href="#" class="dd-item dd-logout"
        onclick="event.preventDefault(); openLogoutModal();">
        <i class="fa fa-right-from-bracket dd-icon"></i>
        Log out
      </a>

    </div>

    <form id="logout-form" action="{{ route('Logout') }}" method="POST"
      style="display:none;">
      @csrf
    </form>

  </div>

</div>
  </nav>
  <div id="logoutModal" class="modal" style="display:none;">
    <div class="modal-content">
      <p>Are you sure you want to log out?</p>

      <div class="modal-actions-centered">
        <button onclick="confirmLogout()" class="btn-modal-yes">Yes</button>
        <button onclick="closeLogoutModal()"
          class="btn-modal-no">Cancel</button>
      </div>
    </div>
  </div>
  <div class="app-body">

    <div class="sidebar-overlay" id="overlay"></div>

    {{-- ===== SIDEBAR (added new nav items) ===== --}}
    <aside class="sidebar" id="sidebar">

      <div class="sidebar-section-label">Menu</div>

      <a href="{{ route('teacher') }}"
        class="nav-item {{ request()->routeIs('teacher') ? 'active' : '' }}">
        <i class="fa fa-table-cells nav-icon"></i>
        <span>Dashboard</span>
      </a>

      <a href="{{ route('trainer.courses') }}"
        class="nav-item {{ request()->routeIs('trainer.courses*') ? 'active' : '' }}">
        <i class="fa fa-book-open nav-icon"></i>
        <span>My Courses</span>
      </a>

      <a href="{{ route('trainer.students') }}"
        class="nav-item {{ request()->routeIs('trainer.students*') ? 'active' : '' }}">
        <i class="fa fa-users nav-icon"></i>
        <span>Students</span>
      </a>

      <a href="{{ route('trainer.attendance') }}"
        class="nav-item {{ request()->routeIs('trainer.attendance*') ? 'active' : '' }}">
        <i class="fa fa-clipboard-list nav-icon"></i>
        <span>Attendance</span>
      </a>

      <a href="{{ route('trainer.schedule') }}"
        class="nav-item {{ request()->routeIs('trainer.schedule*') ? 'active' : '' }}">
        <i class="fa fa-calendar-alt nav-icon"></i>
        <span>Schedule</span>
      </a>

      <div class="sidebar-section-label">Manage</div>

      <a href="{{ route('assessment') }}"
        class="nav-item {{ request()->routeIs('assessment*') ? 'active' : '' }}">
        <i class="fa fa-clipboard-check nav-icon"></i>
        <span>Assessment</span>
      </a>

    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="main-content">
      @yield('content')
    </main>

  </div>

 <script>
document.addEventListener('DOMContentLoaded', function () {

    const notificationDropdown =
        document.getElementById('notificationDropdown');

    if (!notificationDropdown) return;

    function markNotificationRead(item) {

        if (!item) return;

        // Already read
        if (item.dataset.read === '1') return;

        const announcementId = item.dataset.announcementId;

        if (!announcementId) {
            console.error(
                'Trainer notification is missing data-announcement-id.'
            );
            return;
        }

        fetch(`{{ url('/notifications/announcements') }}/${announcementId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => {

            if (!response.ok) {
                throw new Error(
                    `Notification read request failed: ${response.status}`
                );
            }

            return response.json();
        })
        .then(data => {

            if (!data || !data.success) {
                throw new Error(
                    'Server did not confirm the notification as read.'
                );
            }

            // Mark this notification as read locally
            item.dataset.read = '1';
            item.classList.remove('notification-unread');

            // Update badge
            const notificationBadge =
                document.querySelector('.notification-badge');

            if (notificationBadge) {

                const currentCount = parseInt(
                    notificationBadge.textContent
                        .replace('+', '')
                        .trim(),
                    10
                ) || 0;

                const nextCount = Math.max(currentCount - 1, 0);

                if (nextCount === 0) {
                    notificationBadge.remove();
                } else {
                    notificationBadge.textContent =
                        nextCount > 99 ? '99+' : String(nextCount);
                }
            }

            // Update "X unread notifications" text
            const unreadItems =
                notificationDropdown.querySelectorAll(
                    '.notification-item.notification-unread'
                ).length;

            const headerUnread =
                document.querySelector(
                    '.notification-header > div > span'
                );

            if (headerUnread) {
                headerUnread.textContent =
                    `${unreadItems} unread notification${unreadItems !== 1 ? 's' : ''}`;
            }

        })
        .catch(error => {
            console.error(
                'Trainer notification read error:',
                error
            );
        });
    }


    // Attach click + keyboard handlers
    notificationDropdown
        .querySelectorAll(
            '.notification-item[data-announcement-id]'
        )
        .forEach(item => {

            item.addEventListener('click', function () {
                markNotificationRead(this);
            });

            item.addEventListener('keydown', function (event) {

                if (event.key === 'Enter' || event.key === ' ') {

                    event.preventDefault();

                    markNotificationRead(this);
                }
            });
        });

});
</script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

  <script>
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const avatarBtn = document.getElementById('avatarBtn');
    const dropdown = document.getElementById('dropdown');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');

    hamburger.addEventListener('click', function() {
      sidebar.classList.toggle('sidebar-open');
      overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', function() {
      sidebar.classList.remove('sidebar-open');
      overlay.classList.remove('show');
    });

  avatarBtn.addEventListener('click', function(e) {
      e.stopPropagation();

      dropdown.classList.toggle('open');

      // Close notification dropdown
      notificationDropdown.classList.remove('open');
      notificationBtn.setAttribute('aria-expanded', 'false');
    });

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.topbar-right')) {
      dropdown.classList.remove('open');
      notificationDropdown.classList.remove('open');

      notificationBtn.setAttribute('aria-expanded', 'false');
    }
  });
  notificationBtn.addEventListener('click', function(e) {
  e.stopPropagation();

  notificationDropdown.classList.toggle('open');

  notificationBtn.setAttribute(
    'aria-expanded',
    notificationDropdown.classList.contains('open') ? 'true' : 'false'
  );

  // Close profile dropdown
  dropdown.classList.remove('open');
  });

  </script>
  <script>
    function openLogoutModal() {
      document.getElementById('logoutModal').style.display = 'block';
    }

    function closeLogoutModal() {
      document.getElementById('logoutModal').style.display = 'none';
    }

    function confirmLogout() {
      document.getElementById('logout-form').submit();
    }
  </script>
  @yield('scripts')

</body>

</html>
