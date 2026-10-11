@extends('student.layout')

@section('title', 'Announcements')

@section('css')
  <style>
    /* ── Main Container ────────────────────────────────────────── */
    .announcements-wrap {
      padding: 32px 40px;
      font-family: 'Open Sans', system-ui, -apple-system, sans-serif;
      max-width: 1000px;
    }

    .page-header-title {
      font-size: 24px;
      font-weight: 800;
      color: #025628;
      margin-bottom: 6px;
    }

    .page-header-sub {
      font-size: 13px;
      color: #718096;
      margin-bottom: 24px;
    }

    /* ── Filter / Search Bar ───────────────────────────────────── */
    .announcement-filter-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }

    .search-box-wrap {
      position: relative;
      flex: 1;
      min-width: 260px;
    }

    .search-box-wrap input {
      width: 100%;
      padding: 10px 16px 10px 38px;
      border: 1px solid #E2E8F0;
      border-radius: 12px;
      font-size: 13px;
      outline: none;
      transition: border-color 0.2s;
    }

    .search-box-wrap input:focus {
      border-color: #025628;
    }

    .search-box-wrap i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #A0AEC0;
      font-size: 14px;
    }

    .filter-pills {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .filter-pill {
      background: #EDF2F7;
      color: #4A5568;
      border: none;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
    }

    .filter-pill.active,
    .filter-pill:hover {
      background: #025628;
      color: #ffffff;
    }

    /* ── Announcement Cards Stack ──────────────────────────────── */
    .announcements-feed {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .announcement-card {
      background: #ffffff;
      border: 1px solid #E2E8F0;
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
      transition: transform 0.2s, box-shadow 0.2s;
      position: relative;
      overflow: hidden;
    }

    .announcement-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .announcement-card.type-urgent {
      border-left: 5px solid #E53E3E;
    }

    .announcement-card.type-notice {
      border-left: 5px solid #3182CE;
    }

    .announcement-card.type-reminder {
      border-left: 5px solid #D69E2E;
    }

    .announcement-card.inactive-item {
      opacity: 0.65;
      background: #F7FAFC;
    }

    .badge-row {
      display: flex;
      gap: 6px;
      align-items: center;
      margin-bottom: 12px;
    }

    .category-badge {
      display: inline-block;
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 3px 10px;
      border-radius: 10px;
    }

    .badge-urgent {
      background: #FED7D7;
      color: #9B2C2C;
    }

    .badge-notice {
      background: #EBF8FF;
      color: #2B6CB0;
    }

    .badge-reminder {
      background: #FEFCBF;
      color: #744210;
    }

    .badge-status {
      background: #EDF2F7;
      color: #718096;
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      padding: 3px 8px;
      border-radius: 10px;
    }

    .author-meta-row {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 14px;
    }

    .author-avatar {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: #025628;
      color: #ffffff;
      font-weight: 800;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-transform: uppercase;
    }

    .author-avatar.type-urgent {
      background: #C53030;
    }

    .author-avatar.type-notice {
      background: #2B6CB0;
    }

    .author-avatar.type-reminder {
      background: #B7791F;
    }

    .author-info .name {
      font-size: 13.5px;
      font-weight: 700;
      color: #1A202C;
    }

    .author-info .role-time {
      font-size: 11px;
      color: #718096;
    }

    .announcement-title {
      font-size: 17px;
      font-weight: 800;
      color: #025628;
      margin-bottom: 10px;
      line-height: 1.35;
    }

    .announcement-body {
      font-size: 13px;
      color: #4A5568;
      line-height: 1.65;
      margin-bottom: 16px;
      white-space: pre-line;
    }

    .announcement-footer {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      border-top: 1px solid #EDF2F7;
      padding-top: 14px;
      font-size: 12px;
      color: #718096;
    }

    .footer-actions {
      display: flex;
      gap: 16px;
    }

    .action-btn {
      background: transparent;
      border: none;
      color: #718096;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: color 0.2s;
    }

    .action-btn:hover {
      color: #025628;
    }

    .empty-state {
      text-align: center;
      padding: 48px 16px;
      color: #A0AEC0;
    }

    .empty-state i {
      font-size: 36px;
      margin-bottom: 12px;
      display: block;
    }

    @media (max-width: 640px) {
      .announcements-wrap {
        padding: 20px 16px;
      }

      .announcement-filter-bar {
        flex-direction: column;
        align-items: stretch;
      }

      .filter-pills {
        overflow-x: auto;
        padding-bottom: 4px;
      }
    }
  </style>
@endsection


@section('content')
<div class="announcements-wrap">

    <div>
        <h1 class="page-header-title">Announcements & Updates</h1>
        <p class="page-header-sub">
            Official announcements and notices from the administration.
        </p>
    </div>

    {{-- Search & Filter Controls --}}
    <div class="announcement-filter-bar">
        <div class="search-box-wrap">
            <i class="fa fa-search"></i>
            <input
                type="text"
                id="announcementSearch"
                oninput="applyFilters()"
                placeholder="Search title or content..."
            >
        </div>

        <div class="filter-pills">
            <button class="filter-pill active"
                onclick="setCategoryFilter('all', this)">All</button>
            <button class="filter-pill"
                onclick="setCategoryFilter('urgent', this)">Urgent</button>
            <button class="filter-pill"
                onclick="setCategoryFilter('notice', this)">Notices</button>
            <button class="filter-pill"
                onclick="setCategoryFilter('reminder', this)">Reminders</button>
        </div>
    </div>

    {{-- Announcements Feed --}}
    <div class="announcements-feed" id="announcementFeed">

        @forelse($announcements ?? [] as $announcement)

            @php
                $type = strtolower($announcement->type ?? 'notice');

                if (!in_array($type, ['urgent', 'notice', 'reminder'])) {
                    $type = 'notice';
                }

                $dateTimestamp = $announcement->created_at ?? null;

                $timeAgo = $dateTimestamp
                    ? \Carbon\Carbon::parse($dateTimestamp)->diffForHumans()
                    : '';

                $formattedDate = $dateTimestamp
                    ? \Carbon\Carbon::parse($dateTimestamp)->format('M d, Y')
                    : 'Date unset';

                $avatarText = match ($type) {
                    'urgent' => '!',
                    'notice' => 'NT',
                    'reminder' => 'RM',
                    default => 'AD',
                };
            @endphp

            <div
                class="announcement-card type-{{ $type }}"
                id="announcement-{{ $announcement->id }}"
                data-category="{{ $type }}"
            >
                {{-- Category --}}
                <div class="badge-row">
                    <span class="category-badge badge-{{ $type }}">
                        {{ ucfirst($type) }}
                    </span>

                    @if(isset($announcement->is_active) && !$announcement->is_active)
                        <span class="badge-status">Inactive</span>
                    @endif
                </div>

                {{-- Author --}}
                <div class="author-meta-row">
                    <div class="author-avatar type-{{ $type }}">
                        {{ $avatarText }}
                    </div>

                    <div class="author-info">
                        <div class="name">LEDIPO Main Office</div>
                        <div class="role-time">
                            Administrator &bull; Posted {{ $timeAgo }}
                            &bull; {{ $formattedDate }}
                        </div>
                    </div>
                </div>

                {{-- Announcement Content --}}
                <h2 class="announcement-title">
                    {{ $announcement->title ?? 'Announcement' }}
                </h2>

                <div class="announcement-body">{{ $announcement->message ?? '' }}</div>

                {{-- Footer --}}
                <div class="announcement-footer">
                    <div class="footer-actions">
                        <button
                            class="action-btn"
                            onclick="copyDirectLink('{{ $announcement->id }}', this)"
                        >
                            <i class="fa fa-share"></i>
                            <span>Share</span>
                        </button>
                    </div>
                </div>
            </div>

        @empty
            <div class="empty-state">
                <i class="fa fa-bullhorn"></i>
                <p>No announcements found in the database.</p>
            </div>
        @endforelse

        <div id="noSearchResults" class="empty-state" style="display: none;">
            <i class="fa fa-search"></i>
            <p>No announcements match your search criteria.</p>
        </div>

    </div>
</div>
@endsection


@section('scripts')
  <script>
    let currentCategory = 'all';

    function setCategoryFilter(category, buttonElement) {
      document.querySelectorAll('.filter-pill').forEach(pill => pill.classList
        .remove('active'));
      buttonElement.classList.add('active');
      currentCategory = category;
      applyFilters();
    }

    function applyFilters() {
      const searchQuery = document.getElementById('announcementSearch').value
        .trim().toLowerCase();
      const cards = document.querySelectorAll('.announcement-card');
      const noResultsNotice = document.getElementById('noSearchResults');
      let visibleCount = 0;

      cards.forEach(card => {
        const titleText = card.querySelector('.announcement-title')
          ?.textContent.toLowerCase() || '';
        const bodyText = card.querySelector('.announcement-body')?.textContent
          .toLowerCase() || '';
        const cardCategory = card.getAttribute('data-category');

        const matchesCategory = (currentCategory === 'all' || cardCategory ===
          currentCategory);
        const matchesSearch = (titleText.includes(searchQuery) || bodyText
          .includes(searchQuery));

        if (matchesCategory && matchesSearch) {
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      if (noResultsNotice) {
        noResultsNotice.style.display = (visibleCount === 0 && cards.length > 0) ?
          'block' : 'none';
      }
    }

    function copyDirectLink(id, btn) {
      const directUrl =
        `${window.location.origin}${window.location.pathname}#announcement-${id}`;

      if (navigator.clipboard) {
        navigator.clipboard.writeText(directUrl).then(() => {
          const label = btn.querySelector('span');
          const originalText = label.textContent;

          label.textContent = 'Copied!';
          btn.style.color = '#025628';

          setTimeout(() => {
            label.textContent = originalText;
            btn.style.color = '';
          }, 2000);
        });
      }
    }
  </script>
@endsection

