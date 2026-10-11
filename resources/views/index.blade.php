<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dasmariñas Livelihood Training</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

  <!-- Custom Stylesheet -->
  <link rel="stylesheet" href="{{ asset('stylesheet/landingpage.css') }}">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

  <!-- Tab Icon -->
  <link rel="icon" type="image/png"
    href="{{ asset('images/logo_ledipo.png') }}">

  <style>
    /* =========================================================
       LOGGED-IN PROFILE AVATAR / DROPDOWN
       ========================================================= */
    .landing-profile {
      position: relative;
      display: flex;
      align-items: center;
    }

    .landing-avatar-btn {
      width: 46px;
      height: 46px;
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
      padding: 16px;
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
      font-size: 13px;
      font-weight: 800;
    }

    .landing-user-info {
      min-width: 0;
    }

    .landing-dh-name {
      color: #1f2937;
      font-size: 13px;
      font-weight: 700;
      line-height: 1.35;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .landing-dh-role {
      margin-top: 3px;
      color: #718096;
      font-size: 11px;
      font-weight: 600;
      text-transform: capitalize;
    }

    .landing-dd-items {
      padding: 7px 0;
    }

    .landing-dd-item {
      display: flex;
      align-items: center;
      gap: 11px;
      width: 100%;
      box-sizing: border-box;
      padding: 11px 16px;
      color: #374151;
      background: #ffffff;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.15s ease, color 0.15s ease;
    }

    .landing-dd-item i {
      width: 18px;
      text-align: center;
      font-size: 14px;
    }

    .landing-dd-item:hover {
      background: #f0fdf4;
      color: #025628;
      text-decoration: none;
    }

    .landing-dd-divider {
      height: 1px;
      background: #e5e7eb;
      margin: 5px 12px;
    }

    .landing-dd-logout {
      color: #dc2626;
    }

    .landing-dd-logout:hover {
      background: #fef2f2;
      color: #b91c1c;
    }

    /* =========================================================
       SCHEDULE & REQUIREMENTS SECTION
       ========================================================= */
    .schedule-wrapper-section {
      background: #1a4d2e;
      padding: 30px 20px 20px;
      margin: 0;
    }

    .schedule-table-card {
      background: #1a4d2e;
      border-radius: 16px;
      padding: 20px;
      color: #fff;
      margin: 0 auto;
      max-width: 1100px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      border: 2px solid #276749;
    }

    .schedule-table-card h3 {
      text-align: center;
      color: #f5c842;
      font-size: 16px;
      font-weight: 800;
      margin: 0 0 16px;
      text-transform: uppercase;
    }

    .schedule-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
      background: #143c24;
      border-radius: 8px;
      overflow: hidden;
    }

    .schedule-table th,
    .schedule-table td {
      padding: 10px 12px;
      border: 1px solid rgba(255, 255, 255, 0.15);
      text-align: left;
    }

    .schedule-table th {
      background: #0f2d1e;
      color: #f5c842;
      text-transform: uppercase;
    }

    .requirements-box {
      margin-top: 16px;
      background: #0f2d1e;
      padding: 16px;
      border-radius: 8px;
      font-size: 12.5px;
      line-height: 1.5;
    }

    /* =========================================================
       INSTRUCTORS & MODALS
       ========================================================= */
    .instructors-container {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 20px;
      position: relative;
      margin-top: 20px;
    }

    .instructor-nav-btn {
      background: none;
      border: none;
      font-size: 24px;
      color: #1a4d2e;
      cursor: pointer;
      padding: 10px;
      transition: transform 0.2s;
    }

    .instructor-nav-btn:hover {
      transform: scale(1.2);
    }

    .instructors-track {
      display: flex;
      gap: 32px;
      overflow-x: auto;
      scroll-behavior: smooth;
      scrollbar-width: none;
      padding: 10px;
    }

    .instructors-track::-webkit-scrollbar {
      display: none;
    }

    .instructor-card {
      text-align: center;
      cursor: pointer;
      flex: 0 0 auto;
      max-width: 220px;
    }

    .instructor-card img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #1a4d2e;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s;
      margin: 0 auto;
    }

    .instructor-card img:hover {
      transform: scale(1.05);
    }

    .instructor-modal,
    .video-modal {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.85);
      align-items: center;
      justify-content: center;
    }

    .instructor-modal-content {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      max-width: 450px;
      width: 90%;
      text-align: center;
      position: relative;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .close-modal {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 20px;
      cursor: pointer;
      color: #718096;
    }

    .modal-circle-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #1a4d2e;
      margin: 0 auto 12px auto;
      display: block;
    }

    .modal-cert-list {
      text-align: left;
      padding-left: 20px;
      margin: 10px 0;
      font-size: 13px;
      color: #4a5568;
    }

    @media (max-width: 768px) {
      .landing-profile-dropdown {
        right: -5px;
      }

      .landing-avatar-btn {
        width: 42px;
        height: 42px;
        font-size: 13px;
      }
    }
  </style>
</head>

<body>

  <!-- NAVIGATION -->
  <nav class="main-nav">
    <div class="nav-logo">
      <a href="{{ Route::has('index') ? route('index') : url('/') }}"
        class="logo-link">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
      </a>
      <span>LEDIPO</span>
    </div>

    <ul class="nav-links m-0 p-0">
      <li><a href="#home" class="nav-link active">Home</a></li>
      <li><a href="#schedule" class="nav-link">Schedule</a></li>
      <li><a href="#about" class="nav-link">About</a></li>
      <li><a href="#courses" class="nav-link">Courses</a></li>
      <li><a href="#contact" class="nav-link">Contact</a></li>
    </ul>

    <div class="nav-actions">
      @auth
        {{-- LOGGED-IN USER PROFILE --}}
        <div class="landing-profile">
          <button type="button" class="landing-avatar-btn" id="landingAvatarBtn"
            aria-label="Open profile menu" aria-expanded="false">
            {{ strtoupper(substr(Auth::user()->firstname ?? 'M', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? 'B', 0, 1)) }}
          </button>

          {{-- PROFILE DROPDOWN --}}
          <div class="landing-profile-dropdown" id="landingProfileDropdown">
            <div class="landing-dropdown-header">
              <div class="landing-dd-avatar">
                {{ strtoupper(substr(Auth::user()->firstname ?? 'M', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? 'B', 0, 1)) }}
              </div>
              <div class="landing-user-info">
                <div class="landing-dh-name">
                  {{ Auth::user()->firstname ?? 'Student' }}
                  {{ Auth::user()->lastname ?? '' }}
                </div>
                <div class="landing-dh-role">
                  {{ ucfirst(Auth::user()->role ?? 'Student') }}
                </div>
              </div>
            </div>

            <div class="landing-dd-items">
              <a href="{{ Route::has('student.profile') ? route('student.profile') : route('homepage') }}"
                class="landing-dd-item">
                <i class="fa fa-user"></i>
                <span>Profile</span>
              </a>

              <a href="{{ route('homepage') }}" class="landing-dd-item">
                <i class="fa fa-house"></i>
                <span>Dashboard</span>
              </a>

              <div class="landing-dd-divider"></div>

              <a href="#" class="landing-dd-item landing-dd-logout"
                onclick="event.preventDefault(); document.getElementById('landing-logout-form').submit();">
                <i class="fa fa-right-from-bracket"></i>
                <span>Log out</span>
              </a>
            </div>
          </div>
        </div>

        {{-- LOGOUT FORM --}}
        <form id="landing-logout-form"
          action="{{ Route::has('Logout') ? route('Logout') : '#' }}"
          method="POST" style="display:none;">
          @csrf
        </form>
      @else
        {{-- GUEST --}}
        <a href="{{ route('Login') }}" class="btn-signin">Login</a>
        <a href="{{ route('SignupPage') }}" class="btn-signup">Sign Up</a>
      @endauth
    </div>

    <button class="hamburger" id="hamburger">
      <i class="fas fa-bars"></i>
    </button>
  </nav>

  <!-- HERO -->
  <section id="home" class="hero"
    style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('{{ asset('images/landingpage.jpg') }}') center/cover no-repeat; margin-bottom: 0;">
    <div class="hero-overlay"></div>

    <div class="hero-content">
      <div class="hero-badge">
        <i class="fas fa-award"></i>
        Dasmariñas City Livelihood Program
      </div>

      @php
        $heroTitle = $heroSection->title ?? 'Start Your Training Journey';
        $heroWords = explode(' ', $heroTitle);
        $heroLastWord = array_pop($heroWords);
        $heroRest = implode(' ', $heroWords);
      @endphp

      <h1>{{ $heroRest }} <span>{{ $heroLastWord }}</span></h1>
      <p>{{ $heroSection->body ?? '' }}</p>

      <div class="hero-actions">
        <a href="{{ route('SignupPage') }}" class="btn-primary">
          Get Started <i class="fas fa-arrow-right"></i>
        </a>
        <a href="{{ route('Login') }}" class="btn-secondary">
          Login
        </a>
      </div>

      <div class="hero-stats">
        <div class="stat">
          <h3 class="stat-num">{{ $totalCourses }}+</h3>
          <span class="stat-label">Courses</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat">
          <h3 class="stat-num">{{ $totalStudents }}+</h3>
          <span class="stat-label">Students</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat">
          <span class="stat-num">Free</span>
          <span class="stat-label">Registration</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SCHEDULE & REQUIREMENTS SECTION -->
  <div id="schedule" class="schedule-wrapper-section">
    <div class="schedule-table-card">
      <h3>(2026) 3rd QUARTER FREE LIVELIHOOD AT THE DASMARIÑAS CITY TRAINING
        CENTER-MAIN</h3>
      <p
        style="font-size: 11.5px; text-align: center; margin-bottom: 14px; opacity: 0.9;">
        Monday to Thursday (Except Holidays) 8:00 AM - 5:00 PM<br>
        Training Venue: Dasmariñas City Training Center-Main, B-077 LS Brgy. San
        Andres 1, City of Dasmariñas, Cavite
      </p>

      <table class="schedule-table">
        <thead>
          <tr>
            <th>Title of Program</th>
            <th>Duration</th>
            <th>Date of Trainings</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Candle Making</strong><br><small>CBTP for CANDLE
                MAKERS</small></td>
            <td>40 Hours (5 Days)</td>
            <td>2nd Batch: September 01-Sept 08, 2026</td>
          </tr>
          <tr>
            <td><strong>Hilot Wellness Massage</strong><br><small>CBTP for
                MASSAGE THERAPIST (BASIC)</small></td>
            <td>40 Hours (5 Days)</td>
            <td>2nd Batch: September 14-21, 2026</td>
          </tr>
          <tr>
            <td><strong>Hilot Wellness Massage</strong><br><small>CBTP for
                MASSAGE THERAPIST (HILOT WELLNESS)</small></td>
            <td>80 Hours (10 Days)</td>
            <td>2nd Batch: September 22-Oct 7, 2026</td>
          </tr>
          <tr>
            <td><strong>Basic Sewing</strong><br><small>CBTP for SEWERS</small>
            </td>
            <td>40 Hours (5 Days)</td>
            <td>2nd Batch: September 21-28, 2026</td>
          </tr>
          <tr>
            <td><strong>Dress Making</strong><br><small>CBTP for PATTERN
                MAKERS</small></td>
            <td>40 Hours (5 Days)</td>
            <td>2nd Batch: September 29-Oct 14, 2026</td>
          </tr>
          <tr>
            <td><strong>Fashion Bayong Bag</strong><br><small>CBTP for FASHION
                BAYONG BAG MAKERS</small></td>
            <td>40 Hours (5 Days)</td>
            <td>October 05 - 12, 2026</td>
          </tr>
        </tbody>
      </table>

      <div class="requirements-box">
        <strong
          style="color: #f5c842; display: block; margin-bottom: 6px;">REQUIREMENTS
          FOR ADMISSION:</strong>
        • Open to all residents of the City of Dasmariñas. Limited slots
        available – First Come, First Served!<br>
        • Residence Certificate (Barangay-issued) &middot; Voter's ID or Voter's
        Certification<br>
        • Two (2) pieces 2x2 ID photos &middot; One (1) valid government-issued
        ID &middot; Must be 18 years old and above.<br>
        <strong>Additional Requirement (Dressmaking):</strong> With knowledge in
        basic sewing.<br>
        <span
          style="font-size: 11px; opacity: 0.8; display: block; margin-top: 8px;">For
          inquiries: Livelihood and Microenterprise Development Office (LEDIPO)
          · 0917 311 2871</span>
      </div>
    </div>
  </div>

  <!-- ABOUT SECTION -->
  <section id="about" class="about-section">
    {{-- Our Story Video Section --}}
    <div class="about-story"
      style="margin-top: 10px; margin-bottom: 0; align-items: flex-start;">
      <div>
        <h2>{{ $aboutSection->title ?? 'OUR STORY' }}</h2>
        <p>{!! nl2br(e($aboutSection->body ?? '')) !!}</p>
      </div>

      <div class="story-video-wrapper"
        style="position: relative; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); cursor: pointer; max-height: 320px;"
        onclick="openVideoModal()">
        <video id="storyVideo" muted autoplay loop playsinline
          style="width: 100%; height: 100%; object-fit: cover; display: block;">
          <source src="{{ asset('videos/ourstory.mp4') }}" type="video/mp4">
          Your browser does not support the video tag.
        </video>

        <!-- Video Pause & Mute Controls Overlay -->
        <div class="video-controls-overlay" onclick="event.stopPropagation();"
          style="position: absolute; bottom: 15px; right: 15px; display: flex; gap: 10px; background: rgba(0,0,0,0.6); padding: 8px 12px; border-radius: 8px; backdrop-filter: blur(4px);">
          <button type="button" id="playPauseBtn"
            onclick="togglePlayPause(event)"
            style="background: none; border: none; color: #fff; font-size: 16px; cursor: pointer;">
            <i class="fas fa-pause" id="playPauseIcon"></i>
          </button>
          <button type="button" id="muteUnmuteBtn"
            onclick="toggleMuteUnmute(event)"
            style="background: none; border: none; color: #fff; font-size: 16px; cursor: pointer;">
            <i class="fas fa-volume-mute" id="muteIcon"></i>
          </button>
        </div>

        <div
          style="position: absolute; top: 15px; left: 15px; background: rgba(0,0,0,0.6); color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 11px; pointer-events: none;">
          <i class="fas fa-expand"></i> Click to enlarge
        </div>
      </div>
    </div>

    {{-- Partner Grid Cards --}}
    <div
      style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; max-width: 1100px; margin: 20px auto; padding: 0 20px;">
      <div
        style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; text-align: center;">
        <img src="{{ asset('images/dti.jpg') }}" alt="DTI Partnership"
          style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
        <h4
          style="font-size: 14px; font-weight: 700; color: #1a4d2e; margin-bottom: 6px;">
          Department of Trade and Industry (DTI) Cavite:</h4>
        <p style="font-size: 12px; color: #718096; line-height: 1.5;">
          Collaborates directly to strengthen MSMEs through the One Town, One
          Product (OTOP) framework, offering consumer protection and product
          innovation support.</p>
      </div>
      <div
        style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; text-align: center;">
        <img src="{{ asset('images/cdcci.jpg') }}" alt="CDCCI Business Expo"
          style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
        <h4
          style="font-size: 14px; font-weight: 700; color: #1a4d2e; margin-bottom: 6px;">
          City of Dasmariñas Chamber of Commerce & Industry, Inc. (CDCCI):</h4>
        <p style="font-size: 12px; color: #718096; line-height: 1.5;">Partners
          on local economic initiatives, joint technical meetings, and
          delegations to major national gatherings like the Philippine Chamber
          of Commerce and Industry (PCCI) business conferences.</p>
      </div>
      <div
        style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; text-align: center;">
        <img src="{{ asset('images/bazaar.jpg') }}" alt="Trade Fairs"
          style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
        <h4
          style="font-size: 14px; font-weight: 700; color: #1a4d2e; margin-bottom: 6px;">
          Dasmariñas Business Expo & Food Bazaar:</h4>
        <p style="font-size: 12px; color: #718096; line-height: 1.5;">Alongside
          these partnerships, LEDIPO spearheads major initiatives such as
          provincial trade fairs at hubs like SM City Dasmariñas, and free
          skills development tracks at the Dasmariñas City Training Center.</p>
      </div>
    </div>

    {{-- CAROUSEL --}}
    <div id="communityCarousel" class="carousel slide"
      data-bs-ride="carousel" style="padding: 0 50px; margin-top: 20px;">

      {{-- INDICATORS --}}
      <div class="carousel-indicators">
        @foreach ($carouselSlides as $index => $slide)
          <button type="button" data-bs-target="#communityCarousel"
            data-bs-slide-to="{{ $index }}"
            class="{{ $index === 0 ? 'active' : '' }}"></button>
        @endforeach
      </div>

      {{-- SLIDES --}}
      <div class="carousel-inner"
        style="border-radius: 12px; overflow: hidden;">
        @forelse ($carouselSlides as $index => $slide)
          <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
            <img src="{{ asset($slide->image_path) }}" class="d-block w-100"
              alt="{{ $slide->title ?: 'Community Photo' }}"
              style="height: 450px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block"
              style="background: rgba(0,0,0,0.4); border-radius: 8px; padding: 10px 20px;">
              <h5>{{ $slide->title }}</h5>
              <p>{{ $slide->caption }}</p>
            </div>
          </div>
        @empty
          <div class="carousel-item active">
            <div
              style="height: 450px; display:flex; align-items:center; justify-content:center; background:#eee; color:#999;">
              No carousel slides yet.
            </div>
          </div>
        @endforelse
      </div>

      {{-- CONTROLS --}}
      <button class="carousel-control-prev" type="button"
        data-bs-target="#communityCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button"
        data-bs-target="#communityCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>

    {{-- ANNOUNCEMENT SECTION (From Original Template) --}}
    @if (isset($announcementSection))
      <div class="about-story" style="margin-top: 30px;">
        <div>
          <h2>{{ $announcementSection->title ?? 'CONGRATULATIONS' }}</h2>
          <p>{!! nl2br(e($announcementSection->body ?? '')) !!}</p>
        </div>
        <div>
          <img
            src="{{ $announcementSection->image_path ? asset($announcementSection->image_path) : asset('images/8.jpg') }}"
            alt="Announcement">
        </div>
      </div>
    @endif
  </section>

  <!-- TRAINING JOURNEY SECTION -->
  <section class="about-section" style="padding-top: 0;">
    <div
      style="max-width: 1100px; margin: 0 auto; padding: 0 20px; text-align: center;">
      <h2 style="color: #1a4d2e; font-weight: 800; margin-bottom: 16px;">
        Training Journey</h2>
      <p
        style="font-size: 13.5px; color: #4a5568; max-width: 900px; margin: 0 auto 24px; line-height: 1.6;">
        To ensure our community programs translate into real-world employment
        and entrepreneurship, LEDIPO structures its training tracks in strict
        alignment with national competency standards, incorporating recognized
        frameworks such as TESDA (Technical Education and Skills Development
        Authority). When you enroll and complete a course through our platform,
        you gain:
      </p>
      <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; text-align: left;">
        <div>
          <img src="{{ asset('images/tj1.jpg') }}" alt="Cooking Class"
            style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); margin-bottom: 12px;">
          <p
            style="font-size: 12.5px; color: #2d3748; line-height: 1.5; margin: 0;">
            <strong style="color: #1a4d2e;">Industry-Aligned
              Knowledge:</strong> Practical, hands-on skills designed to meet
            current market demands, whether you want to enter the workforce or
            launch a local micro-enterprise.
          </p>
        </div>
        <div>
          <img src="{{ asset('images/tj2.png') }}"
            alt="Certificate of Completion"
            style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); margin-bottom: 12px;">
          <p
            style="font-size: 12.5px; color: #2d3748; line-height: 1.5; margin: 0;">
            <strong style="color: #1a4d2e;">Official Credentials:</strong>
            Verified digital and printable certificates of completion featuring
            official city and national markers to validate your new skills to
            employers.
          </p>
        </div>
        <div>
          <img src="{{ asset('images/tj3.png') }}"
            alt="Community Presentation"
            style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); margin-bottom: 12px;">
          <p
            style="font-size: 12.5px; color: #2d3748; line-height: 1.5; margin: 0;">
            <strong style="color: #1a4d2e;">Direct Support Pathways:</strong> A
            transparent, streamlined journey from online registration all the
            way to barangay-level practical assessments and graduation.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- MEET OUR INSTRUCTORS SECTION WITH ARROWS & MODAL -->
  <section class="about-section"
    style="text-align: center; background: #fafbfc; padding: 40px 20px;">
    <h2 style="color: #1a4d2e; font-weight: 800; margin-bottom: 24px;">MEET OUR
      INSTRUCTORS</h2>

    <div class="instructors-container">
      <button type="button" class="instructor-nav-btn"
        onclick="scrollInstructors(-1)"><i
          class="fas fa-arrow-left"></i></button>

      <div class="instructors-track" id="instructorTrack">
        <div class="instructor-card"
          onclick="openInstructorModal('Wobi Hufancia', '{{ asset('images/instructor.png') }}', 'Specialist in Livelihood Skills & Pastry Arts', ['TM1 Certified Trainer', 'TESDA Accredited Assessor', 'NCII Pastry Production Holder'], 'Over 5 years of community training experience in Dasmariñas City.')">
          <img src="{{ asset('images/instructor.png') }}"
            alt="Wobi Hufancia">
          <p
            style="font-weight: 700; margin-top: 10px; color: #2d3748; margin-bottom: 2px;">
            Wobi Hufancia</p>
          <span style="font-size: 11.5px; color: #718096; display: block;">TM1
            Certified Trainer · Livelihood Specialist</span>
        </div>
        <div class="instructor-card"
          onclick="openInstructorModal('Nico Cainto', '{{ asset('images/instructor.png') }}', 'Senior Technical Instructor · ICT & Web Development Lead', ['TESDA TM1 Certified Instructor', 'Microsoft Office Specialist Master', 'NCII Computer Systems Servicing'], 'Expert in digital literacy programs and community tech integration across barangays.')">
          <img src="{{ asset('images/instructor.png') }}" alt="Nico Cainto">
          <p
            style="font-weight: 700; margin-top: 10px; color: #2d3748; margin-bottom: 2px;">
            Nico Cainto</p>
          <span
            style="font-size: 11.5px; color: #718096; display: block;">Senior
            Technical Instructor · ICT Lead</span>
        </div>
        <div class="instructor-card"
          onclick="openInstructorModal('Bea Binene', '{{ asset('images/instructor.png') }}', 'TESDA Accredited Assessor · Garments & Sewing Track Head', ['TM1 Certified Trainer', 'NCII Dressmaking and Tailoring Holder', 'Pattern Making Certificate'], 'Passionate about empowering local entrepreneurs through garment creation courses and workshops.')">
          <img src="{{ asset('images/instructor.png') }}" alt="Bea Binene">
          <p
            style="font-weight: 700; margin-top: 10px; color: #2d3748; margin-bottom: 2px;">
            Bea Binene</p>
          <span
            style="font-size: 11.5px; color: #718096; display: block;">TESDA
            Accredited Assessor · Garments Head</span>
        </div>
      </div>

      <button type="button" class="instructor-nav-btn"
        onclick="scrollInstructors(1)"><i
          class="fas fa-arrow-right"></i></button>
    </div>

    <!-- MISSION & VISION -->
    <div
      style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; max-width: 1000px; margin: 30px auto 0; text-align: left;">
      <div
        style="background: #fef08a; border-radius: 12px; padding: 24px; color: #1a4d2e;">
        <h3 style="font-weight: 800; margin-bottom: 12px;">OUR MISSION</h3>
        <p style="font-size: 13.5px; color: #1e293b; line-height: 1.6;">To
          empower Dasmariñas residents through accessible, industry-aligned
          livelihood and technical training programs, fostering self-reliance,
          employability, and sustainable economic growth across all barangays.
        </p>
      </div>
      <div
        style="background: #fef08a; border-radius: 12px; padding: 24px; color: #1a4d2e;">
        <h3 style="font-weight: 800; margin-bottom: 12px;">OUR VISION</h3>
        <p style="font-size: 13.5px; color: #1e293b; line-height: 1.6;">A
          digitally empowered and economically resilient City of Dasmariñas
          where every individual has equal access to quality skill-building
          opportunities, paving the way for thriving local enterprises and an
          improved quality of life.</p>
      </div>
    </div>
  </section>

  <!-- Instructor Details Modal -->
  <div class="instructor-modal" id="instructorModal">
    <div class="instructor-modal-content">
      <span class="close-modal"
        onclick="closeInstructorModal()">&times;</span>
      <img id="modalImg" src="" alt="Instructor Photo"
        class="modal-circle-img">
      <h3 id="modalName"
        style="color: #1a4d2e; font-weight: 800; margin-bottom: 5px;"></h3>
      <p id="modalRole"
        style="font-weight: 600; color: #276749; font-size: 13px; margin-bottom: 12px;">
      </p>

      <div style="text-align: left; margin-bottom: 12px;">
        <strong
          style="font-size: 13px; color: #1a4d2e; display: block; margin-bottom: 4px;">Certificates
          & Credentials:</strong>
        <ul id="modalCertList" class="modal-cert-list"></ul>
      </div>

      <div style="text-align: left;">
        <strong
          style="font-size: 13px; color: #1a4d2e; display: block; margin-bottom: 4px;">Experience
          & Background:</strong>
        <p id="modalBio"
          style="font-size: 12.5px; color: #4a5568; line-height: 1.5; margin: 0;">
        </p>
      </div>
    </div>
  </div>

  <!-- Video Enlarged Modal -->
  <div id="videoModal" class="video-modal">
    <div
      style="position: relative; width: 90%; max-width: 800px; background: #000; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
      <span onclick="closeVideoModal()"
        style="position: absolute; top: 15px; right: 20px; font-size: 28px; color: #fff; cursor: pointer; z-index: 10; background: rgba(0,0,0,0.5); width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">&times;</span>
      <video id="modalVideo" controls autoplay playsinline
        style="width: 100%; max-height: 80vh; display: block;">
        <source src="{{ asset('videos/ourstory.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>
  </div>

  {{-- STATS CARDS --}}
  <div class="about-stats">
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-user-graduate"></i>
      </div>
      <h3>{{ $totalStudents }}+</h3>
      <p>Students active on our site</p>
    </div>

    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-book"></i>
      </div>
      <h3>{{ $totalCourses }}+</h3>
      <p>Courses available on our site</p>
    </div>

    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-chalkboard-teacher"></i>
      </div>
      <h3>{{ $totalTrainers }}+</h3>
      <p>Trainers active on our site</p>
    </div>
  </div>

  <!-- COURSES SECTION -->
  <section id="courses" class="courses-section">
    <div class="courses-wrapper">
      {{-- Header --}}
      <div class="courses-header">
        <h2>Available Courses</h2>
        <p>Browse our free government-accredited livelihood training programs.
        </p>
      </div>

      {{-- Courses Grid --}}
      <div class="courses-grid">
        @forelse($courses as $course)
          <div class="course-card">
            <div class="course-thumbnail">
              <i class="fas fa-book"></i>
              <span class="course-sector-badge">{{ $course->sector }}</span>
            </div>

            <div class="course-body">
              <h4>{{ $course->title }}</h4>
              <p>{{ Str::limit($course->description, 100) }}</p>

              <div class="course-meta">
                <p><i class="fas fa-clock"></i> {{ $course->schedule }}</p>
                <p><i class="fas fa-calendar"></i> {{ $course->duration }}</p>
                <p><i class="fas fa-location-dot"></i>
                  {{ Str::limit($course->location, 35) }}</p>
                <p><i class="fas fa-users"></i> {{ $course->available_slots }}
                  slots available</p>
              </div>

              @if ($course->available_slots > 0)
                <a href="{{ route('landing.course.detail', $course->id) }}"
                  class="btn-view-course">
                  View Course
                </a>
              @else
                <a href="{{ route('landing.course.detail', $course->id) }}"
                  class="btn-view-course" style="opacity:0.6;">
                  View Course <span style="font-size:11px;">(Full)</span>
                </a>
              @endif
            </div>
          </div>
        @empty
          <p class="courses-empty">No courses available at the moment.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- CONTACT SECTION -->
  <section id="contact" class="contact-section">
    <div class="contact-page-wrapper">
      {{-- SUCCESS MESSAGE --}}
      @if (session('success'))
        <div class="alertSuccess">
          <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
      @endif

      {{-- CONTACT CONTAINER --}}
      <div class="contact-container">
        {{-- LEFT - CONTACT INFO --}}
        <div class="contact-info-list">
          {{-- Call Us --}}
          <div class="contact-info-item">
            <div class="contact-icon-wrapper">
              <i class="fas fa-phone"></i>
            </div>
            <div>
              <h4 class="contact-info-title">Call To Us</h4>
              <p class="contact-info-subtitle">We are available 24/7, 7 days a
                week.</p>
              <p class="contact-info-detail">Phone: +88015-88888-9999</p>
            </div>
          </div>

          <hr class="contact-divider">

          {{-- Write To Us --}}
          <div class="contact-info-item">
            <div class="contact-icon-wrapper">
              <i class="fas fa-envelope"></i>
            </div>
            <div>
              <h4 class="contact-info-title">Write To Us</h4>
              <p class="contact-info-subtitle">Fill out our form and we will
                contact you within 24 hours.</p>
              <p class="contact-info-detail">Emails: Regals@gmail.com</p>
              <p class="contact-info-detail">Emails: support@ledipo.gov.ph</p>
            </div>
          </div>

          <hr class="contact-divider">

          {{-- Address --}}
          <div class="contact-info-item">
            <div class="contact-icon-wrapper">
              <i class="fas fa-location-dot"></i>
            </div>
            <div>
              <h4 class="contact-info-title">Visit Us</h4>
              <p class="contact-info-subtitle">Come visit us at our office.</p>
              <p class="contact-info-detail">Barangay Burol Main, City of
                Dasmariñas, Cavite, Philippines.</p>
            </div>
          </div>
        </div>

        {{-- RIGHT - CONTACT FORM --}}
        <div>
          <form action="{{ route('contact.send') }}" method="POST">
            @csrf
            <div class="contact-form-grid">
              <div>
                <input type="text" name="name"
                  placeholder="Your Name *" class="contact-form-input"
                  required>
              </div>

              <div>
                <input type="email" name="email"
                  placeholder="Your Email *" class="contact-form-input"
                  required>
              </div>

              <div>
                <input type="tel" name="phone"
                  placeholder="Your Phone *" class="contact-form-input"
                  maxlength="11" pattern="[0-9]{11}"
                  title="Please enter an 11-digit phone number starting with 09"
                  required>
              </div>
            </div>

            <div class="contact-form-textarea-wrapper">
              <textarea name="message" placeholder="Your Message"
                class="contact-form-textarea" required></textarea>
            </div>

            <div class="contact-form-submit-wrapper">
              <button type="submit" class="btn-submit-contact">
                Send Message
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-col">
        <h3>Support</h3>
        <p>Barangay Burol Main, City of Dasmariñas, Cavite, Philippines.</p>
        <p><a href="mailto:Regals@gmail.com">Regals@gmail.com</a></p>
        <p>+88015-88888-9999</p>
      </div>

      <div class="footer-col">
        <h3>Account</h3>
        <ul>
          <li><a href="#">My Account</a></li>
          <li><a href="#">Login / Register</a></li>
          <li><a href="#">Likes</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h3>Quick Link</h3>
        <ul>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms Of Use</a></li>
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; Copyright Rimel 2022. All right reserved</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js">
  </script>

  <script>
    // =========================================================
    // Story Video Intersection Observer & Modal Controls
    // =========================================================
    document.addEventListener("DOMContentLoaded", function() {
      const video = document.getElementById('storyVideo');

      if (video && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              video.play().catch(error => console.log(
                "Autoplay prevented:", error));
            } else {
              video.pause();
            }
          });
        }, {
          threshold: 0.5
        });

        observer.observe(video);
      }
    });

    function togglePlayPause(e) {
      e.stopPropagation();
      const video = document.getElementById('storyVideo');
      const icon = document.getElementById('playPauseIcon');
      if (video.paused) {
        video.play();
        icon.className = "fas fa-pause";
      } else {
        video.pause();
        icon.className = "fas fa-play";
      }
    }

    function toggleMuteUnmute(e) {
      e.stopPropagation();
      const video = document.getElementById('storyVideo');
      const icon = document.getElementById('muteIcon');
      video.muted = !video.muted;
      if (video.muted) {
        icon.className = "fas fa-volume-mute";
      } else {
        icon.className = "fas fa-volume-up";
      }
    }

    function openVideoModal() {
      const modal = document.getElementById('videoModal');
      const modalVideo = document.getElementById('modalVideo');
      const mainVideo = document.getElementById('storyVideo');

      modal.style.display = 'flex';
      mainVideo.pause();
      modalVideo.currentTime = mainVideo.currentTime;
      modalVideo.play();
    }

    function closeVideoModal() {
      const modal = document.getElementById('videoModal');
      const modalVideo = document.getElementById('modalVideo');
      const mainVideo = document.getElementById('storyVideo');

      modal.style.display = 'none';
      modalVideo.pause();
      mainVideo.play();
    }

    // =========================================================
    // Instructors Carousel & Modal
    // =========================================================
    function scrollInstructors(direction) {
      const track = document.getElementById('instructorTrack');
      track.scrollBy({
        left: direction * 250,
        behavior: 'smooth'
      });
    }

    function openInstructorModal(name, imgSrc, role, certificates, bio) {
      document.getElementById('modalName').innerText = name;
      document.getElementById('modalImg').src = imgSrc;
      document.getElementById('modalRole').innerText = role;
      document.getElementById('modalBio').innerText = bio;

      const certList = document.getElementById('modalCertList');
      certList.innerHTML = '';
      certificates.forEach(cert => {
        const li = document.createElement('li');
        li.innerText = cert;
        certList.appendChild(li);
      });

      document.getElementById('instructorModal').style.display = 'flex';
    }

    function closeInstructorModal() {
      document.getElementById('instructorModal').style.display = 'none';
    }

    window.addEventListener('click', function(event) {
      const instructorModal = document.getElementById('instructorModal');
      const videoModal = document.getElementById('videoModal');
      if (event.target === instructorModal) {
        instructorModal.style.display = 'none';
      }
      if (event.target === videoModal) {
        closeVideoModal();
      }
    });

    // =========================================================
    // Smooth Scroll & Active Nav Tracking
    // =========================================================
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href && href.startsWith('#')) {
          e.preventDefault();
          const targetSection = document.getElementById(href.substring(
          1));
          if (targetSection) {
            targetSection.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
            document.querySelectorAll('.nav-link').forEach(l => l
              .classList.remove('active'));
            this.classList.add('active');
          }
        }
      });
    });

    window.addEventListener('scroll', function() {
      const sections = document.querySelectorAll(
        'section[id], div[id="schedule"]');
      const navLinks = document.querySelectorAll('.nav-link');
      let current = '';

      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        if (window.pageYOffset >= sectionTop - 120) {
          current = section.getAttribute('id');
        }
      });

      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
          link.classList.add('active');
        }
      });
    });

    // Hamburger Menu Toggle
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.querySelector('.nav-links');
    if (hamburger && navLinks) {
      hamburger.addEventListener('click', function() {
        navLinks.classList.toggle('active');
      });
    }

    // =========================================================
    // Profile Dropdown
    // =========================================================
    document.addEventListener('DOMContentLoaded', function() {
      const avatarBtn = document.getElementById('landingAvatarBtn');
      const dropdown = document.getElementById('landingProfileDropdown');

      if (!avatarBtn || !dropdown) return;

      avatarBtn.addEventListener('click', function(event) {
        event.stopPropagation();
        const isOpen = dropdown.classList.toggle('open');
        avatarBtn.setAttribute('aria-expanded', isOpen ? 'true' :
        'false');
      });

      document.addEventListener('click', function(event) {
        if (!event.target.closest('.landing-profile')) {
          dropdown.classList.remove('open');
          avatarBtn.setAttribute('aria-expanded', 'false');
        }
      });

      document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
          dropdown.classList.remove('open');
          avatarBtn.setAttribute('aria-expanded', 'false');
        }
      });
    });
  </script>
</body>

</html>
