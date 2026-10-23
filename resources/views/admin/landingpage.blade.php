<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Landing Page Editor</title>

  <link rel="stylesheet" href="{{ asset('stylesheet/admin-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('stylesheet/certificates.css') }}">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

      <!-- Tab Icon -->
   <link rel="icon" type="image/png" href="{{ asset('images/logo_ledipo.png') }}">

  <style>
    .lp-section {
      position: relative;
      background: #ffffff;
      border: 1px solid #dcdcdc;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 16px;
    }

    .lp-edit-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      border: 1px solid #dcdcdc;
      background: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #025628;
      font-size: 13px;
    }

    .lp-edit-btn:hover {
      background: #e8f5e9;
    }

    .lp-label {
      font-size: 11px;
      color: #888;
      text-transform: uppercase;
      letter-spacing: .04em;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .lp-title {
      font-size: 18px;
      font-weight: 700;
      color: #1a1a1a;
      margin: 0 0 6px 0;
    }

    .lp-body {
      font-size: 13px;
      color: #555;
      line-height: 1.5;
      white-space: pre-line;
    }

    .lp-with-image {
      display: flex;
      gap: 16px;
      align-items: flex-start;
    }

    .lp-image-preview {
      width: 100px;
      height: 100px;
      border-radius: 8px;
      background: #f0f0f0;
      object-fit: cover;
      flex-shrink: 0;
    }

    .lp-image-placeholder {
      width: 100px;
      height: 100px;
      border-radius: 8px;
      background: #f0f0f0;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #bbb;
      font-size: 24px;
      flex-shrink: 0;
    }

    .lp-carousel-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }

    .lp-carousel-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 14px;
    }

    .lp-slide-card {
      position: relative;
      border: 1px solid #dcdcdc;
      border-radius: 8px;
      overflow: hidden;
      background: #fff;
    }

    .lp-slide-img {
      width: 100%;
      height: 110px;
      object-fit: cover;
      background: #f0f0f0;
      display: block;
    }

    .lp-slide-body {
      padding: 10px;
    }

    .lp-slide-title {
      font-size: 12.5px;
      font-weight: 700;
      color: #1a1a1a;
      margin: 0 0 2px 0;
    }

    .lp-slide-caption {
      font-size: 11px;
      color: #888;
    }

    .lp-slide-actions {
      position: absolute;
      top: 6px;
      right: 6px;
      display: flex;
      gap: 6px;
    }

    .lp-slide-icon-btn {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      border: none;
      background: rgba(255,255,255,0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 11px;
    }

    .lp-slide-icon-btn.edit { color: #025628; }
    .lp-slide-icon-btn.delete { color: #A32D2D; }

    .lp-add-slide-card {
      border: 2px dashed #ccc;
      border-radius: 8px;
      min-height: 168px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      gap: 6px;
      color: #999;
      cursor: pointer;
      font-size: 12px;
      font-weight: 600;
      background: #fafafa;
    }

    .lp-add-slide-card:hover {
      background: #f0faf3;
      border-color: #025628;
      color: #025628;
    }

    .lp-modal-body {
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .lp-field label {
      font-size: 12px;
      font-weight: 600;
      color: #333;
      display: block;
      margin-bottom: 6px;
    }

    .lp-field input[type="text"],
    .lp-field textarea {
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 10px 12px;
      font-size: 13px;
      font-family: inherit;
      box-sizing: border-box;
    }

    .lp-field textarea {
      min-height: 90px;
      resize: vertical;
    }

    .lp-field input[type="file"] {
      width: 100%;
      font-size: 12px;
    }

    .lp-current-image {
      width: 80px;
      height: 80px;
      border-radius: 8px;
      object-fit: cover;
      margin-bottom: 8px;
      display: block;
    }
  </style>
</head>

<body>

  <!-- TOPBAR -->
  <nav class="topbar">
    <div class="topbar-left">
      <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
        <span></span><span></span><span></span>
      </button>
      <a href="{{ route('admin1') }}" class="topbar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="logo" class="topbar-logo">
        <span>LEDIPO</span>
      </a>
    </div>

    <div class="topbar-right">
      <button class="avatar-btn" id="avatarBtn" aria-label="Open profile menu">AD</button>
      <div class="dropdown" id="dropdown">
        <div class="dropdown-header">
          <div class="dh-name">Administrator</div>
          <div class="dh-role">Admin</div>
        </div>
        <div class="dd-divider"></div>
        <a href="#" class="dd-item dd-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fa fa-right-from-bracket dd-icon"></i>
          Log out
        </a>
        <form id="logout-form" action="{{ route('Logout') }}" method="POST" style="display:none;">
          @csrf
        </form>
      </div>
    </div>
  </nav>

  <!-- APP BODY & SIDEBAR -->
  <div class="app-body">
    <div class="sidebar-overlay" id="overlay"></div>

    <aside class="sidebar" id="sidebar">
      <div class="sidebar-section-label">Menu</div>

      <a href="{{ route('admin1') }}?view=overview" class="nav-item">
        <i class="fa fa-gauge-high nav-icon"></i><span>Overview</span>
      </a>

      <div class="sidebar-section-label">Manage</div>

      <a href="{{ route('admin1') }}?view=all-trainees" class="nav-item">
        <i class="fa fa-user-graduate nav-icon"></i><span>Trainees</span>
      </a>
      <a href="{{ route('admin1') }}?view=all-trainers" class="nav-item">
        <i class="fa fa-chalkboard-user nav-icon"></i><span>Trainers</span>
      </a>
      <a href="{{ route('admin1') }}?view=registrations" class="nav-item">
        <i class="fa fa-clipboard-list nav-icon"></i><span>Registrations</span>
      </a>
      <a href="{{ route('admin1') }}?view=courses" class="nav-item">
        <i class="fa fa-book nav-icon"></i><span>Courses</span>
      </a>
      <a href="{{ route('admin1') }}?view=facilities" class="nav-item">
        <i class="fa fa-building nav-icon"></i><span>Facilities</span>
      </a>

      <a href="{{ route('admin1') }}?view=certificate" class="nav-item">
        <i class="fa fa-award nav-icon"></i><span>Certificate</span>
      </a>

      <div class="sidebar-section-label">System</div>

      <a href="{{ route('admin1') }}?view=announcements" class="nav-item">
        <i class="fa fa-bell nav-icon"></i><span>Announcements</span>
      </a>

      <a href="{{ route('admin1') }}?view=settings" class="nav-item">
        <i class="fa fa-gear nav-icon"></i><span>Settings</span>
      </a>
      

      <a href="{{ route('admin.landingpage') }}" class="nav-item active">
        <i class="fa fa-layer-group nav-icon"></i><span>Landing Page</span>
      </a>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-main">
      <nav class="breadcrumb">
        <a href="{{ route('admin1') }}">Home</a> / <span>Landing Page</span>
      </nav>
      <h1 class="page-title">Landing Page Editor</h1>

      <!-- HERO SECTION -->
      <div class="lp-section">
        <button class="lp-edit-btn" onclick="openSectionModal('hero')" aria-label="Edit hero section">
          <i class="fa-solid fa-pencil"></i>
        </button>
        <div class="lp-label">Hero</div>
        <div class="lp-title">{{ $hero->title ?? 'Untitled' }}</div>
        <div class="lp-body">{{ $hero->body ?? '' }}</div>
      </div>

      <!-- ABOUT SECTION -->
      <div class="lp-section">
        <button class="lp-edit-btn" onclick="openSectionModal('about')" aria-label="Edit about section">
          <i class="fa-solid fa-pencil"></i>
        </button>
        <div class="lp-label">About / Our Story</div>
        <div class="lp-with-image">
          @if ($about && $about->image_path)
            <img src="{{ asset($about->image_path) }}" class="lp-image-preview" alt="">
          @else
            <div class="lp-image-placeholder"><i class="fa-solid fa-image"></i></div>
          @endif
          <div>
            <div class="lp-title">{{ $about->title ?? 'Untitled' }}</div>
            <div class="lp-body">{{ $about->body ?? '' }}</div>
          </div>
        </div>
      </div>

      <!-- ANNOUNCEMENT SECTION -->
      <div class="lp-section">
        <button class="lp-edit-btn" onclick="openSectionModal('announcement')" aria-label="Edit announcement block">
          <i class="fa-solid fa-pencil"></i>
        </button>
        <div class="lp-label">Announcement Block</div>
        <div class="lp-with-image">
          @if ($announcement && $announcement->image_path)
            <img src="{{ asset($announcement->image_path) }}" class="lp-image-preview" alt="">
          @else
            <div class="lp-image-placeholder"><i class="fa-solid fa-image"></i></div>
          @endif
          <div>
            <div class="lp-title">{{ $announcement->title ?? 'Untitled' }}</div>
            <div class="lp-body">{{ $announcement->body ?? '' }}</div>
          </div>
        </div>
      </div>

      <!-- CAROUSEL SECTION -->
      <div class="lp-section">
        <div class="lp-carousel-header">
          <div class="lp-label" style="margin-bottom:0;">Community Carousel</div>
          <span style="font-size:11px; background:#e8f5e9; color:#025628; padding:3px 10px; border-radius:12px; font-weight:700;">
            {{ $slides->count() }} slide{{ $slides->count() === 1 ? '' : 's' }}
          </span>
        </div>

        <div class="lp-carousel-grid" id="carouselGrid">
          @foreach ($slides as $slide)
            <div class="lp-slide-card" data-slide-id="{{ $slide->id }}">
              <div class="lp-slide-actions">
                <button class="lp-slide-icon-btn edit" onclick='openSlideModal({{ $slide->toJson() }})' aria-label="Edit slide">
                  <i class="fa-solid fa-pencil"></i>
                </button>
                <button class="lp-slide-icon-btn delete" onclick="deleteSlide({{ $slide->id }})" aria-label="Delete slide">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
              <img src="{{ asset($slide->image_path) }}" class="lp-slide-img" alt="">
              <div class="lp-slide-body">
                <div class="lp-slide-title">{{ $slide->title ?? 'Untitled' }}</div>
                <div class="lp-slide-caption">{{ $slide->caption ?? '' }}</div>
              </div>
            </div>
          @endforeach

          <div class="lp-add-slide-card" onclick="openSlideModal(null)">
            <i class="fa-solid fa-plus" style="font-size:20px;"></i>
            Add Slide
          </div>
        </div>
      </div>

    </main>
  </div>

  <!-- SECTION EDIT MODAL (Hero / About / Announcement) -->
  <div id="sectionModal" class="modal" style="display:none;">
    <div class="modal-content card">
      <div class="modal-header">
        <h3 id="sectionModalTitle"><i class="fa-solid fa-pencil"></i> Edit Section</h3>
        <span class="close-modal" onclick="closeSectionModal()">&times;</span>
      </div>
      <form id="sectionForm" class="lp-modal-body" enctype="multipart/form-data">
        <input type="hidden" id="sectionKey" value="">

        <div id="sectionCurrentImageWrap" style="display:none;">
          <img id="sectionCurrentImage" class="lp-current-image" src="" alt="">
        </div>

        <div class="lp-field">
          <label for="sectionTitle">Title</label>
          <input type="text" id="sectionTitle" maxlength="150">
        </div>

        <div class="lp-field">
          <label for="sectionBody">Text</label>
          <textarea id="sectionBody"></textarea>
        </div>

        <div class="lp-field" id="sectionImageField">
          <label for="sectionImage">Image (optional — leave blank to keep current)</label>
          <input type="file" id="sectionImage" accept="image/*">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeSectionModal()">Cancel</button>
          <button type="submit" class="btn-save-main">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  <!-- CAROUSEL SLIDE MODAL (Add / Edit) -->
  <div id="slideModal" class="modal" style="display:none;">
    <div class="modal-content card">
      <div class="modal-header">
        <h3 id="slideModalTitle"><i class="fa-solid fa-images"></i> Add Slide</h3>
        <span class="close-modal" onclick="closeSlideModal()">&times;</span>
      </div>
      <form id="slideForm" class="lp-modal-body" enctype="multipart/form-data">
        <input type="hidden" id="slideId" value="">

        <div id="slideCurrentImageWrap" style="display:none;">
          <img id="slideCurrentImage" class="lp-current-image" src="" alt="">
        </div>

        <div class="lp-field">
          <label for="slideTitle">Title</label>
          <input type="text" id="slideTitle" maxlength="150" placeholder="e.g. Community Event 1">
        </div>

        <div class="lp-field">
          <label for="slideCaption">Caption</label>
          <input type="text" id="slideCaption" maxlength="150" placeholder="e.g. Dasmariñas City Training Center">
        </div>

        <div class="lp-field">
          <label for="slideImage" id="slideImageLabel">Image</label>
          <input type="file" id="slideImage" accept="image/*">
        </div>

        <div class="modal-footer" style="justify-content: space-between;">
          <button type="button" class="btn-delete-text" id="slideDeleteBtn" style="display:none;" onclick="deleteSlideFromModal()">
            <i class="fa-solid fa-trash"></i> Delete Slide
          </button>
          <div class="action-buttons" style="margin-left:auto; display:flex; gap:8px;">
            <button type="button" class="btn-cancel" onclick="closeSlideModal()">Cancel</button>
            <button type="submit" class="btn-save-main">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Sidebar / topbar toggle (same behavior as admin1)
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const avatarBtn = document.getElementById('avatarBtn');
    const dropdown = document.getElementById('dropdown');

    hamburger.addEventListener('click', function () {
      sidebar.classList.toggle('sidebar-open');
      overlay.classList.toggle('show');
    });
    overlay.addEventListener('click', function () {
      sidebar.classList.remove('sidebar-open');
      overlay.classList.remove('show');
    });
    avatarBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.classList.toggle('open');
    });
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.topbar-right')) {
        dropdown.classList.remove('open');
      }
    });

    // ===== SECTION MODAL (Hero / About / Announcement) =====
    const sectionData = {
      hero: @json($hero),
      about: @json($about),
      announcement: @json($announcement),
    };

    function openSectionModal(key) {
      const data = sectionData[key] || {};
      document.getElementById('sectionKey').value = key;
      document.getElementById('sectionTitle').value = data.title || '';
      document.getElementById('sectionBody').value = data.body || '';

      const titleMap = { hero: 'Edit Hero', about: 'Edit About / Our Story', announcement: 'Edit Announcement' };
      document.getElementById('sectionModalTitle').innerHTML =
        '<i class="fa-solid fa-pencil"></i> ' + (titleMap[key] || 'Edit Section');

      const imageField = document.getElementById('sectionImageField');
      const currentImgWrap = document.getElementById('sectionCurrentImageWrap');
      const currentImg = document.getElementById('sectionCurrentImage');

      if (key === 'hero') {
        imageField.style.display = 'none';
        currentImgWrap.style.display = 'none';
      } else {
        imageField.style.display = 'block';
        if (data.image_path) {
          currentImg.src = '/' + data.image_path;
          currentImgWrap.style.display = 'block';
        } else {
          currentImgWrap.style.display = 'none';
        }
      }

      document.getElementById('sectionImage').value = '';
      document.getElementById('sectionModal').style.display = 'block';
    }

    function closeSectionModal() {
      document.getElementById('sectionModal').style.display = 'none';
    }

    document.getElementById('sectionForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const key = document.getElementById('sectionKey').value;
      const formData = new FormData();
      formData.append('title', document.getElementById('sectionTitle').value);
      formData.append('body', document.getElementById('sectionBody').value);
      formData.append('_method', 'PUT');

      const imageInput = document.getElementById('sectionImage');
      if (imageInput.files[0]) {
        formData.append('image', imageInput.files[0]);
      }

      fetch(`/admin/landingpage/section/${key}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: formData,
      })
        .then(async res => {
          const data = await res.json().catch(() => null);
          if (res.ok && data && data.success) {
            alert(data.message || 'Section updated!');
            location.reload();
          } else {
            alert((data && data.message) || 'Failed to update section.');
          }
        })
        .catch(err => {
          console.error(err);
          alert('An error occurred while saving.');
        });
    });

    // ===== SLIDE MODAL (Add / Edit / Delete) =====
    function openSlideModal(slide) {
      const form = document.getElementById('slideForm');
      form.reset();

      const currentImgWrap = document.getElementById('slideCurrentImageWrap');
      const deleteBtn = document.getElementById('slideDeleteBtn');
      const imageLabel = document.getElementById('slideImageLabel');
      const imageInput = document.getElementById('slideImage');

      if (slide) {
        document.getElementById('slideModalTitle').innerHTML = '<i class="fa-solid fa-pencil"></i> Edit Slide';
        document.getElementById('slideId').value = slide.id;
        document.getElementById('slideTitle').value = slide.title || '';
        document.getElementById('slideCaption').value = slide.caption || '';
        document.getElementById('slideCurrentImage').src = '/' + slide.image_path;
        currentImgWrap.style.display = 'block';
        deleteBtn.style.display = 'inline-flex';
        imageLabel.textContent = 'Replace Image (optional)';
        imageInput.required = false;
      } else {
        document.getElementById('slideModalTitle').innerHTML = '<i class="fa-solid fa-images"></i> Add Slide';
        document.getElementById('slideId').value = '';
        currentImgWrap.style.display = 'none';
        deleteBtn.style.display = 'none';
        imageLabel.textContent = 'Image';
        imageInput.required = true;
      }

      document.getElementById('slideModal').style.display = 'block';
    }

    function closeSlideModal() {
      document.getElementById('slideModal').style.display = 'none';
    }

    document.getElementById('slideForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const id = document.getElementById('slideId').value;
      const isEdit = !!id;

      const formData = new FormData();
      formData.append('title', document.getElementById('slideTitle').value);
      formData.append('caption', document.getElementById('slideCaption').value);

      const imageInput = document.getElementById('slideImage');
      if (imageInput.files[0]) {
        formData.append('image', imageInput.files[0]);
      }

      const url = isEdit ? `/admin/landingpage/carousel/${id}` : '/admin/landingpage/carousel';
      if (isEdit) formData.append('_method', 'PUT');

      fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: formData,
      })
        .then(async res => {
          const data = await res.json().catch(() => null);
          if (res.ok && data && data.success) {
            alert(data.message || 'Slide saved!');
            location.reload();
          } else {
            alert((data && data.message) || 'Failed to save slide.');
          }
        })
        .catch(err => {
          console.error(err);
          alert('An error occurred while saving.');
        });
    });

    function deleteSlide(id) {
      if (!confirm('Delete this slide? This cannot be undone.')) return;

      fetch(`/admin/landingpage/carousel/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      })
        .then(async res => {
          const data = await res.json().catch(() => null);
          if (res.ok && data && data.success) {
            alert(data.message || 'Slide deleted!');
            location.reload();
          } else {
            alert((data && data.message) || 'Failed to delete slide.');
          }
        })
        .catch(err => {
          console.error(err);
          alert('An error occurred while deleting.');
        });
    }

    function deleteSlideFromModal() {
      const id = document.getElementById('slideId').value;
      if (id) deleteSlide(id);
    }

    window.addEventListener('click', function (e) {
      if (e.target.id === 'sectionModal') closeSectionModal();
      if (e.target.id === 'slideModal') closeSlideModal();
    });
  </script>

</body>
</html>