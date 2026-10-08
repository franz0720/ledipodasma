<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Dasmariñas Livelihood Training</title>

  <link
    href="https\://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('stylesheet/landingpage.css') }}">

  <link rel="stylesheet"
    href="https\://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <link rel="preconnect" href="https\://fonts.googleapis.com">

  <link rel="preconnect" href="https\://fonts.gstatic.com" crossorigin>

  <link
    href="https\://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

  *<!-- bootstrap link -->*

  <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

  *<!-- font awesome cdn link -->*

  <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

  *<!-- Tab Icon -->*

  <link rel="icon" type="image/png"
    href="{{ asset('images/logo_ledipo.png') }}">

  <style>
    /* =========================================================
       LOGGED-IN PROFILE AVATAR / DROPDOWN
       Matches the student homepage profile-circle design
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

  <nav class="main-nav">

    <div class="nav-logo">

      <a href="index.php" class="logo-link">

        <img src="images/logo.png" alt="Logo" class="logo">

      </a> <span>LEDIPO</span>

    </div>

    <ul class="nav-links m-0 p-0">

      <li><a href="#home" class="nav-link active">Home</a></li>

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

  *<!-- HERO -->*

  <section id="home" class="hero">

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

          <h3 class="stat-num">{{ $totalStudents }}+</h3>

          <span class="stat-label">Courses</span>

        </div>

        <div class="stat-divid  er"></div>

        <div class="stat">

          <h3 class="stat-num">{{ $totalCourses }}+</h3>

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

  *<!-- ABOUT SECTION -->*

  <section id="about" class="about-section">

    {{-- Our Story --}}

    <div class="about-story">

      <div>

        <h2>{{ $aboutSection->title ?? 'Our Story' }}</h2>

        <p>{!! nl2br(e($aboutSection->body ?? '')) !!}</p>

      </div>

      <div>

        <img
          src="{{ $aboutSection->image_path ? asset($aboutSection->image_path) : asset('images/ledipostory.png') }}"
          alt="About Us">

      </div>

    </div>

    {{-- CAROUSEL --}}

    <div id="communityCarousel" class="carousel slide"
      data-bs-ride="carousel" style="padding: 0 50px;">

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

  </section>

  </div>

  *<!-- ABOUT SECTION -->*

  <section id="about" class="about-section">

    {{-- Our Story --}}

    <div class="about-story">

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

    </div>

  </section>

  </div>

  {{-- Stats --}}

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

  </div>

  </section>

  *<!-- COURSES SECTION -->*

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

  <section id="contact" class="contact-section">

    <div class="contact-page-wrapper">

      *<!-- {{-- BREADCRUMB --}}*

*&#xA0;       <nav class="breadcrumb">*

*&#xA0;           <a href="{{ route('index') }}">Home</a>*

*&#xA0;           <span class="separator">/</span>*

*&#xA0;           <span>Contact</span>*

*&#xA0;       </nav> -->*

      {{-- SUCCESS MESSAGE --}}

      @if (session('success'))
        <div class="alertSuccess">

          <i class="fas fa-check-circle"></i> {{ session('success') }}

        </div>
      @endif

      {{-- CONTACT SECTION --}}

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
                  placeholder="Your Name \*" class="contact-form-input"
                  required>

              </div>

              <div>

                <input type="email" name="email"
                  placeholder="Your Email \*" class="contact-form-input"
                  required>

              </div>

              <div>

                <input type="tel" name="phone"
                  placeholder="Your Phone \*" class="contact-form-input"
                  maxlength="11" pattern="[0-9{11}"
                  title="please enter an 11-digit phone number starting with 09"
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

  *<!-- footer -->*

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

  <script>
    * // Smooth scroll for navigation links*

    document.querySelectorAll('.nav-link').forEach(link => {

      link.addEventListener('click', function(e) {

        const href = this.getAttribute('href');



        * // Only handle internal links (starting with #)*

        if (href.startsWith('#')) {

          e.preventDefault();

          const targetId = href.substring(1);

          const targetSection = document.getElementById(targetId);



          if (targetSection) {

            targetSection.scrollIntoView({

              behavior: 'smooth',

              block: 'start'

            });



            * // Update active class*

            document.querySelectorAll('.nav-link').forEach(l => l

              .classList.remove('active'));

            this.classList.add('active');

          }

        }

      });

    });



    * // Update active nav link on scroll*

    window\.addEventListener('scroll', function() {

      const sections = document.querySelectorAll('section[id]');

      const navLinks = document.querySelectorAll('.nav-link');



      let current = '';

      sections.forEach(section => {

        const sectionTop = section.offsetTop;

        const sectionHeight = section.clientHeight;

        if (window\.pageYOffset >= sectionTop - 100) {

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



    * // Hamburger menu toggle*

    const hamburger = document.getElementById('hamburger');

    const navLinks = document.querySelector('.nav-links');



    if (hamburger) {

      hamburger.addEventListener('click', function() {

        navLinks.classList.toggle('active');

      });

    }



    window\.addEventListener('scroll', function() {

      const navbar = document.querySelector('.navbar');

      if (window\.scrollY > 50) {

        navbar.classList.add('scrolled');

      } else {

        navbar.classList.remove('scrolled');

      }

    });
  </script>

  <script
    src="https\://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js">
  </script>

  <script>
    // =========================================================
    // Landing-page profile dropdown
    // =========================================================
    document.addEventListener('DOMContentLoaded', function() {
      const avatarBtn = document.getElementById('landingAvatarBtn');
      const dropdown = document.getElementById('landingProfileDropdown');

      if (!avatarBtn || !dropdown) {
        return;
      }

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

