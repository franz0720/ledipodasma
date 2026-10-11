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
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:wght@500;600;700;800&display=swap"
    rel="stylesheet">

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">

  <!-- Tab Icon -->
  <link rel="icon" type="image/png"
    href="{{ asset('images/logo_ledipo.png') }}">

  <style>
    /* Modal Styles */
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 10000;
    }

    .modal-content {
      background: white;
      padding: 24px;
      width: 320px;
      margin: 15% auto;
      text-align: center;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .modal-actions-centered {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 16px;
    }

    .modal-actions-centered button {
      padding: 7px 18px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 6px;
      cursor: pointer;
      border: none;
      transition: opacity 0.2s;
    }

    .btn-modal-yes {
      background: #dc2626;
      color: #ffffff;
    }

    .btn-modal-yes:hover {
      background: #b91c1c;
    }

    .btn-modal-no {
      background: #e5e7eb;
      color: #374151;
    }

    .btn-modal-no:hover {
      background: #d1d5db;
    }

    /* ===== Topbar Profile & Dropdown (Integrated Design) ===== */
    .landing-profile {
      position: relative;
      display: flex;
      align-items: center;
    }

    .landing-avatar-btn {
      width: 44px;
      height: 44px;
      padding: 0;
      border: 2px solid #ffffff;
      border-radius: 50%;
      background: #fff200;
      color: #006b35;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      font-weight: 800;
      line-height: 1;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .landing-avatar-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 14px rgba(0, 0, 0, 0.16);
    }

    .landing-avatar-btn:focus-visible {
      outline: 3px solid rgba(212, 209, 32, 0.45);
      outline-offset: 2px;
    }

    .landing-profile-dropdown {
      position: absolute;
      top: calc(100% + 12px);
      right: 0;
      width: 250px;
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.16);
      overflow: hidden;
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
      transform: translateY(-8px);
      transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
      z-index: 9999;
    }

    .landing-profile-dropdown.open {
      opacity: 1;
      visibility: visible;
      pointer-events: auto;
      transform: translateY(0);
    }

    .landing-dropdown-header {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 16px;
      background: #f8faf9;
      border-bottom: 1px solid #eef1ef;
    }

    .landing-dd-avatar {
      width: 42px;
      height: 42px;
      min-width: 42px;
      border-radius: 50%;
      background: #fff200;
      color: #006b35;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 800;
    }

    .landing-dd-info {
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .dh-name {
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 700;
      color: #143c24;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      line-height: 1.25;
    }

    .dh-role {
      font-size: 11px;
      font-weight: 600;
      color: #006b35;
      background: #e8f5ed;
      display: inline-block;
      align-self: flex-start;
      padding: 2px 8px;
      border-radius: 10px;
      margin-top: 3px;
      text-transform: capitalize;
    }

    /* Dropdown Items */
    .dd-items {
      padding: 8px;
    }

    .dd-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 12px;
      border-radius: 8px;
      color: #374151;
      font-size: 12.5px;
      font-weight: 500;
      text-decoration: none;
      transition: background-color 0.15s ease, color 0.15s ease, transform 0.15s ease;
    }

    .dd-item:hover {
      background-color: #f3f6f4;
      color: #006b35;
      transform: translateX(2px);
    }

    .dd-icon {
      font-size: 13px;
      width: 16px;
      color: #6b7280;
      transition: color 0.15s ease;
    }

    .dd-item:hover .dd-icon {
      color: #006b35;
    }

    .dd-divider {
      height: 1px;
      background-color: #f1f3f2;
      margin: 6px 4px;
    }

    .dd-item.dd-logout {
      color: #dc2626;
    }

    .dd-item.dd-logout .dd-icon {
      color: #dc2626;
    }

    .dd-item.dd-logout:hover {
      background-color: #fef2f2;
      color: #b91c1c;
    }

    .dd-item.dd-logout:hover .dd-icon {
      color: #b91c1c;
    }
  </style>
</head>

<body>

  {{-- ===== TOPBAR ===== --}}
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

    {{-- Redesigned Profile & Dropdown --}}
    <div class="topbar-right landing-profile">
      <button class="landing-avatar-btn" id="avatarBtn"
        aria-label="Open profile menu">
        {{ strtoupper(substr(Auth::user()->firstname ?? 'T', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? '', 0, 1)) }}
      </button>

      <div class="landing-profile-dropdown" id="dropdown">
        <div class="landing-dropdown-header">
          <div class="landing-dd-avatar">
            {{ strtoupper(substr(Auth::user()->firstname ?? 'T', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? '', 0, 1)) }}
          </div>
          <div class="landing-dd-info">
            <div class="dh-name">
              {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
            </div>
            <div class="dh-role">{{ ucfirst(Auth::user()->role) }}</div>
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

  {{-- ===== LOGOUT MODAL ===== --}}
  <div id="logoutModal" class="modal" style="display:none;">
    <div class="modal-content">
      <p style="margin: 0; font-weight: 600; color: #1f2937;">Are you sure you
        want to log out?</p>

      <div class="modal-actions-centered">
        <button onclick="confirmLogout()" class="btn-modal-yes">Yes, Log
          out</button>
        <button onclick="closeLogoutModal()"
          class="btn-modal-no">Cancel</button>
      </div>
    </div>
  </div>

  <div class="app-body">

    <div class="sidebar-overlay" id="overlay"></div>

    {{-- ===== SIDEBAR ===== --}}
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
    });

    document.addEventListener('click', function(e) {
      if (!e.target.closest('.landing-profile')) {
        dropdown.classList.remove('open');
      }
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
