@extends('student.layout')

@section('title', 'Announcements & Updates')

@section('css')

  <style>
    :root {
      --ledipo-green: #075b35;
      --ledipo-green-dark: #043f26;
      --ledipo-green-soft: #eaf5ee;
      --ledipo-yellow: #e1dc36;
      --ledipo-ink: #183329;
      --ledipo-muted: #6b7c73;
      --ledipo-border: #e2ebe5;
      --ledipo-surface: #fff;
    }

    .announcements-wrap {
      width: 100%;
      max-width: 1080px;
      margin: 0 auto;
      padding: 22px 18px 36px;
      box-sizing: border-box;
      color: var(--ledipo-ink);
    }

    .announcements-header {
      margin-bottom: 24px;
    }

    .announcements-title {
      margin: 0 0 7px;
      color: var(--ledipo-green);
      font-size: 26px;
      font-weight: 800;
      letter-spacing: -.45px;
      line-height: 1.3;
    }

    .announcements-subtitle {
      margin: 0;
      color: var(--ledipo-muted);
      font-size: 13.5px;
      line-height: 1.7;
    }

    .announcement-search-row {
      margin: 20px 0 24px;
    }

    .announcement-search {
      position: relative;
      width: 100%;
    }

    .announcement-search i {
      position: absolute;
      top: 50%;
      left: 16px;
      transform: translateY(-50%);
      color: #82968a;
      pointer-events: none;
      font-size: 14px;
    }

    .announcement-search input {
      display: block;
      width: 100%;
      min-height: 46px;
      box-sizing: border-box;
      padding: 12px 16px 12px 44px;
      border: 1px solid var(--ledipo-border);
      border-radius: 11px;
      background: #fff;
      color: var(--ledipo-ink);
      font: inherit;
      font-size: 13.5px;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }

    .announcement-search input::placeholder {
      color: #9aaba1;
    }

    .announcement-search input:focus {
      border-color: var(--ledipo-green);
      box-shadow: 0 0 0 3px rgba(7, 91, 53, .09);
    }

    .announcements-feed {
      display: flex;
      flex-direction: column;
      gap: 18px;
      min-width: 0;
    }

    .announcement-card {
      position: relative;
      min-width: 0;
      overflow: hidden;
      border: 1px solid var(--ledipo-border);
      border-radius: 15px;
      background: var(--ledipo-surface);
      box-shadow: 0 2px 7px rgba(24, 51, 41, .035);
      transition: border-color .2s, box-shadow .2s, transform .2s;
    }

    .announcement-card:hover {
      border-color: #b7d4c1;
      box-shadow: 0 7px 20px rgba(24, 51, 41, .065);
      transform: translateY(-1px);
    }

    .announcement-card-inner {
      padding: 21px 23px;
    }

    .announcement-author-row {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      min-width: 0;
    }

    .announcement-avatar {
      display: flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 42px;
      width: 42px;
      height: 42px;
      border: 1px solid #c4e4ce;
      border-radius: 50%;
      background: var(--ledipo-green-soft);
      color: var(--ledipo-green);
      font-size: 13px;
      font-weight: 800;
      text-transform: uppercase;
    }

    .announcement-author-info {
      flex: 1;
      min-width: 0;
      padding-top: 2px;
    }

    .announcement-author-name {
      color: var(--ledipo-ink);
      font-size: 14px;
      font-weight: 750;
      line-height: 1.4;
      overflow-wrap: anywhere;
    }

    .announcement-meta {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 5px;
      margin-top: 4px;
      color: var(--ledipo-muted);
      font-size: 11.5px;
      line-height: 1.6;
    }

    .announcement-course-tag {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      max-width: 100%;
      margin-top: 16px;
      padding: 6px 10px;
      border: 1px solid #cce6d4;
      border-radius: 7px;
      background: #f1f8f3;
      color: var(--ledipo-green);
      font-size: 11px;
      font-weight: 750;
      line-height: 1.4;
      overflow-wrap: anywhere;
    }

    .announcement-course-tag i {
      flex-shrink: 0;
    }

    .announcement-content {
      margin-top: 15px;
      color: #344b3e;
      font-size: 13.5px;
      line-height: 1.8;
      overflow-wrap: anywhere;
      word-break: break-word;
    }

    .announcement-content> :first-child {
      margin-top: 0;
    }

    .announcement-content> :last-child {
      margin-bottom: 0;
    }

    .announcement-content p {
      margin: 0 0 10px;
    }

    .announcement-content h1,
    .announcement-content h2,
    .announcement-content h3,
    .announcement-content h4 {
      margin: 14px 0 8px;
      color: var(--ledipo-green-dark);
      font-weight: 750;
      line-height: 1.4;
    }

    .announcement-content ul,
    .announcement-content ol {
      padding-left: 22px;
    }

    .announcement-content a {
      color: var(--ledipo-green);
      text-decoration: underline;
      overflow-wrap: anywhere;
    }

    .announcement-content img,
    .announcement-content video,
    .announcement-content iframe {
      max-width: 100%;
      height: auto;
      border-radius: 9px;
    }

    .announcement-card-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 19px;
      padding-top: 14px;
      border-top: 1px solid #edf2ee;
      color: var(--ledipo-muted);
      font-size: 11.5px;
    }

    .announcement-posted-date {
      display: inline-flex;
      align-items: center;
      gap: 7px;
    }

    .announcement-posted-date i {
      color: var(--ledipo-green);
    }

    .announcement-share-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      padding: 7px 11px;
      border: 1px solid transparent;
      border-radius: 7px;
      background: transparent;
      color: var(--ledipo-green);
      font-size: 11.5px;
      font-weight: 700;
      cursor: pointer;
      transition: background .2s, border-color .2s;
    }

    .announcement-share-btn:hover {
      border-color: #d3e8d9;
      background: var(--ledipo-green-soft);
    }

    /* ==========================================
         STUDENT ANNOUNCEMENT COMMENTS
         Reference layout: avatar outside, all comment
         details inside one full-width bubble
         ========================================== */
    .student-announcement-comments {
      padding: 18px 26px 20px;
      border-top: 1px solid #edf1f5;
      border-radius: 0 0 15px 15px;
      background: #f8f9fb;
      box-sizing: border-box;
    }

    .student-announcement-comments-heading,
    .student-announcement-comments-count {
      display: none;
    }

    .student-announcement-comments-list {
      display: flex;
      flex-direction: column;
      gap: 13px;
      min-width: 0;
      margin: 0 0 16px;
      padding: 0;
    }

    .student-announcement-no-comments {
      margin: 0;
      padding: 12px 14px;
      border: 1px dashed #d5e3d9;
      border-radius: 9px;
      background: #fff;
      color: var(--ledipo-muted);
      font-size: 12px;
      line-height: 1.6;
    }

    /* Avatar and bubble are siblings in the row */
    .student-announcement-comments .student-announcement-comment-row {
      display: flex;
      flex-direction: row;
      align-items: flex-start;
      justify-content: flex-start;
      gap: 13px;
      width: 100%;
      min-width: 0;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .student-announcement-comments .comment-author-avatar {
      display: flex;
      flex: 0 0 40px;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      min-height: 40px;
      margin: 0;
      padding: 0;
      border: 1px solid #b9e5ff;
      border-radius: 50%;
      background: #e2f3ff;
      color: #0875b9;
      font-size: 12px;
      font-weight: 800;
      line-height: 1;
      box-sizing: border-box;
    }

    /* Bubble uses all available space beside the avatar */
    .student-announcement-comments .comment-body-wrap {
      flex: 1 1 0;
      min-width: 0;
      width: 0;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .student-announcement-comments .ledipo-comment-card {
      display: block;
      position: static;
      width: 100%;
      max-width: 100%;
      min-width: 0;
      height: auto;
      min-height: 0;
      max-height: none;
      margin: 0;
      padding: 12px 17px;
      border: 1px solid #dce5f0;
      border-radius: 13px;
      background: #fff;
      box-shadow: none;
      box-sizing: border-box;
      text-align: left;
      overflow-wrap: anywhere;
    }

    /* Student name on the left, timestamp on the right */
    .student-announcement-comments .comment-outer-header {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 5px 12px;
      width: 100%;
      margin: 0 0 7px;
      padding: 0;
      box-sizing: border-box;
    }

    .student-announcement-comments .comment-outer-name {
      display: inline-block;
      min-width: 0;
      margin: 0;
      padding: 0;
      color: #1e2e42;
      font-size: 13px;
      font-weight: 800;
      line-height: 1.4;
      overflow-wrap: anywhere;
    }

    .student-announcement-comments .comment-outer-date {
      display: inline-block;
      flex: 0 0 auto;
      margin: 0;
      padding: 0;
      color: #8da0bb;
      font-size: 11px;
      font-weight: 400;
      line-height: 1.4;
    }

    /* Message text sits directly under the header */
    .student-announcement-comments .ledipo-comment-body {
      display: block;
      position: static;
      float: none;
      width: 100%;
      max-width: 100%;
      height: auto;
      min-height: 0;
      max-height: none;
      margin: 0;
      padding: 0;
      transform: none;
      color: #4b586e;
      font-size: 13px;
      font-weight: 400;
      line-height: 1.6;
      text-align: left;
      text-indent: 0;
      white-space: pre-wrap;
      overflow-wrap: anywhere;
      word-break: normal;
    }

    /* Comment input form */
    .student-announcement-comment-form {
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
      width: 100%;
      min-width: 0;
      margin: 0;
    }

    .student-announcement-comment-form::before {
      content: "\f075";
      position: absolute;
      z-index: 1;
      top: 50%;
      left: 16px;
      transform: translateY(-50%);
      color: #667b98;
      font-family: "Font Awesome 6 Free", "FontAwesome";
      font-size: 14px;
      font-weight: 900;
      pointer-events: none;
      transition: opacity 0.18s ease;
    }

    .student-announcement-comment-input {
      display: block;
      flex: 1 1 auto;
      width: 100%;
      min-width: 0;
      min-height: 44px;
      max-height: 140px;
      margin: 0;
      padding: 10px 14px 10px 42px;
      border: 1px dashed #cbd8e9;
      border-radius: 11px;
      background: transparent;
      color: #34445d;
      font: inherit;
      font-size: 12.5px;
      line-height: 1.5;
      text-align: left;
      resize: vertical;
      box-sizing: border-box;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .student-announcement-comment-input::placeholder {
      color: #667b98;
      opacity: 1;
      font-weight: 600;
    }

    .student-announcement-comment-input:focus {
      border-style: solid;
      border-color: #a9bfdc;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(79, 115, 163, 0.07);
    }

    .student-announcement-comment-form:focus-within::before {
      opacity: 0.6;
    }

    .student-announcement-comment-submit {
      display: none;
      flex: 0 0 auto;
      align-items: center;
      justify-content: center;
      gap: 7px;
      min-height: 40px;
      padding: 0 15px;
      border: 1px solid var(--ledipo-green);
      border-radius: 9px;
      background: var(--ledipo-green);
      color: #fff;
      font-size: 11.5px;
      font-weight: 750;
      cursor: pointer;
      white-space: nowrap;
      transition: background 0.2s, border-color 0.2s;
    }

    .student-announcement-comment-form:focus-within .student-announcement-comment-submit {
      display: inline-flex;
    }

    .student-announcement-comment-submit:hover:not(:disabled) {
      border-color: var(--ledipo-green-dark);
      background: var(--ledipo-green-dark);
    }

    .student-announcement-comment-submit:disabled {
      opacity: 0.55;
      cursor: not-allowed;
    }

    .student-announcement-comment-error {
      display: none;
      margin: 8px 0 0 46px;
      color: #b91c1c;
      font-size: 12px;
      line-height: 1.5;
    }

    .announcement-empty-state {
      padding: 42px 22px;
      border: 1px dashed #cbded0;
      border-radius: 14px;
      background: #fff;
      color: var(--ledipo-muted);
      text-align: center;
    }

    .announcement-empty-state>i {
      display: block;
      margin-bottom: 12px;
      color: var(--ledipo-green);
      font-size: 27px;
    }

    .announcement-empty-state h3 {
      margin: 0 0 7px;
      color: var(--ledipo-ink);
      font-size: 15px;
      font-weight: 800;
    }

    .announcement-empty-state p {
      max-width: 440px;
      margin: 0 auto;
      color: var(--ledipo-muted);
      font-size: 12.5px;
      line-height: 1.7;
    }

    @media (max-width: 768px) {
      .announcements-wrap {
        padding: 18px 14px 28px;
      }

      .announcement-card-inner {
        padding: 19px 18px;
      }

      .student-announcement-comments {
        padding: 16px 18px;
      }
    }

    @media (max-width: 520px) {
      .announcements-wrap {
        padding: 15px 11px 24px;
      }

      .announcements-title {
        font-size: 22px;
      }

      .announcements-subtitle {
        font-size: 12.5px;
      }

      .announcement-search-row {
        margin: 17px 0 19px;
      }

      .announcements-feed {
        gap: 14px;
      }

      .announcement-card-inner {
        padding: 16px 14px;
      }

      .announcement-author-row {
        gap: 10px;
      }

      .announcement-avatar {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 12px;
      }

      .announcement-author-name {
        font-size: 13px;
      }

      .announcement-meta {
        font-size: 10.5px;
      }

      .announcement-course-tag {
        margin-top: 13px;
        font-size: 10.5px;
      }

      .announcement-content {
        font-size: 13px;
        line-height: 1.75;
      }

      .announcement-card-footer {
        align-items: flex-start;
        font-size: 10.5px;
      }

      .student-announcement-comments {
        padding: 14px 12px 16px;
      }

      .student-announcement-comments-list {
        gap: 12px;
      }

      .student-announcement-comments .student-announcement-comment-row {
        gap: 9px;
      }

      .student-announcement-comments .comment-author-avatar {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
        min-height: 34px;
        font-size: 10.5px;
      }

      .student-announcement-comments .ledipo-comment-card {
        padding: 10px 11px;
        border-radius: 11px;
      }

      .student-announcement-comments .comment-outer-header {
        gap: 4px 8px;
        margin-bottom: 6px;
      }

      .student-announcement-comments .comment-outer-name {
        font-size: 12px;
      }

      .student-announcement-comments .comment-outer-date {
        font-size: 10px;
      }

      .student-announcement-comments .ledipo-comment-body {
        font-size: 12px;
      }

      .student-announcement-comment-form {
        gap: 7px;
      }

      .student-announcement-comment-input {
        min-height: 40px;
        padding: 9px 10px 9px 35px;
        font-size: 12px;
      }

      .student-announcement-comment-form::before {
        left: 12px;
        font-size: 13px;
      }

      .student-announcement-comment-submit {
        width: 40px;
        min-width: 40px;
        padding: 0;
      }

      .student-announcement-comment-submit span {
        display: none;
      }

      .student-announcement-comment-error {
        margin-left: 0;
      }

      .announcement-empty-state {
        padding: 32px 15px;
      }
    }
  </style>

@endsection

@section('content')

  @php

    $announcementsList = $announcements ?? [];

  @endphp

  <div class="announcements-wrap">

    <header class="announcements-header">

      <h1 class="announcements-title">Announcements &amp; Updates</h1>

      <p class="announcements-subtitle">

        Updates and announcements shared by your course trainers.

      </p>

    </header>

    <div class="announcement-search-row">

      <div class="announcement-search">

        <i class="fa fa-search" aria-hidden="true"></i>

        <input type="search" id="announcementSearch"
          oninput="filterTrainerAnnouncements()"
          placeholder="Search announcements or courses..."
          aria-label="Search trainer announcements">

      </div>

    </div>

    <div class="announcements-feed" id="announcementsFeed">

      @if (count($announcementsList) > 0)

        @foreach ($announcementsList as $announcement)
          @php

            $trainerName = trim(
                ($announcement->trainer_firstname ?? '') .
                    ' ' .
                    ($announcement->trainer_lastname ?? ''),
            );

            $trainerName = $trainerName ?: 'Course Trainer';

            $courseName = $announcement->course_title ?? 'Training Course';

            $postedAt = !empty($announcement->created_at)
                ? \Illuminate\Support\Carbon::parse($announcement->created_at)
                : null;

            $nameParts = preg_split(
                '/\s+/',

                trim($trainerName),

                -1,

                PREG_SPLIT_NO_EMPTY,
            );

            $initials = collect($nameParts)
                ->take(2)

                ->map(fn($part) => mb_substr($part, 0, 1))

                ->implode('');

            $comments = \Illuminate\Support\Facades\DB::table(
                'announcement_comments as ac',
            )

                ->leftJoin('user_tbls as u', 'u.id', '=', 'ac.user_id')

                ->where('ac.announcement_id', $announcement->id)

                ->select(
                    'ac.id',

                    'ac.comment',

                    'ac.created_at',

                    'u.firstname as user_firstname',

                    'u.lastname as user_lastname',
                )

                ->orderBy('ac.created_at')

                ->get();
          @endphp

          <article class="announcement-card"
            id="announcement-{{ $announcement->id }}">

            <div class="announcement-card-inner">

              <div class="announcement-author-row">

                <div class="announcement-avatar" aria-hidden="true">

                  {{ $initials ?: 'TR' }}

                </div>

                <div class="announcement-author-info">

                  <div class="announcement-author-name">

                    {{ $trainerName }}

                  </div>

                  <div class="announcement-meta">

                    <span>{{ $courseName }}</span>

                    <span aria-hidden="true">&bull;</span>

                    <span>

                      {{ $postedAt ? $postedAt->diffForHumans() : 'Date unavailable' }}

                    </span>

                  </div>

                </div>

              </div>

              <div class="announcement-course-tag">

                <i class="fa fa-book-open" aria-hidden="true"></i>

                <span>{{ $courseName }}</span>

              </div>

              <div class="announcement-content">

                {!! $announcement->content ?? '' !!}

              </div>

              <div class="announcement-card-footer">

                @if ($postedAt)
                  <time class="announcement-posted-date"
                    datetime="{{ $postedAt->toIso8601String() }}">

                    <i class="fa-regular fa-clock" aria-hidden="true"></i>

                    {{ $postedAt->format('M d, Y · g:i A') }}

                  </time>
                @else
                  <span class="announcement-posted-date">Date unavailable</span>
                @endif

                <button type="button" class="announcement-share-btn"
                  onclick="shareTrainerAnnouncement('{{ $announcement->id }}', this)">

                  <i class="fa fa-share" aria-hidden="true"></i>

                  <span>Share</span>

                </button>

              </div>

            </div>

            <section class="student-announcement-comments"
              aria-label="Comments on this announcement">

              <h3 class="student-announcement-comments-heading">

                <i class="fa-regular fa-comments" aria-hidden="true"></i>

                <span>Class comments</span>

                <span
                  class="student-announcement-comments-count">{{ $comments->count() }}</span>

              </h3>

              <div class="student-announcement-comments-list"
                id="commentsList-{{ $announcement->id }}">

                @if ($comments->count() > 0)
                  @foreach ($comments as $comment)
                    @php

                      $commentFirst = trim($comment->user_firstname ?? '');

                      $commentLast = trim($comment->user_lastname ?? '');

                      $commentName = trim($commentFirst . ' ' . $commentLast);

                      if ($commentName === '') {
                          $commentName = 'Student';
                      }

                      $commentInitials = collect(
                          preg_split(
                              '/\s+/',

                              $commentName,

                              -1,

                              PREG_SPLIT_NO_EMPTY,
                          ),
                      )
                          ->take(2)

                          ->map(fn($part) => mb_substr($part, 0, 1))

                          ->implode('');

                    @endphp

                    <div class="student-announcement-comment-row"
                      id="comment-{{ $comment->id }}">

                      <div class="comment-author-avatar" aria-hidden="true">
                        {{ $commentInitials ?: 'S' }}
                      </div>

                      <div class="comment-body-wrap">
                        <div class="ledipo-comment-card">
                          <div class="comment-outer-header">
                            <span
                              class="comment-outer-name">{{ $commentName }}</span>

                            <span class="comment-outer-date">
                              {{ $comment->created_at
                                  ? \Illuminate\Support\Carbon::parse($comment->created_at)->format('g:i A')
                                  : '' }}
                            </span>
                          </div>

                          <div class="ledipo-comment-body">
                            {{ strip_tags($comment->comment ?? '') }}</div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @else
                  <p id="noComments-{{ $announcement->id }}"
                    class="student-announcement-no-comments">

                    No comments yet. Start the conversation.

                  </p>
                @endif

              </div>

              <form class="student-announcement-comment-form"
                onsubmit="submitStudentComment(event, {{ $announcement->id }})">

                @csrf

                <textarea class="student-announcement-comment-input"
                  id="commentInput-{{ $announcement->id }}" maxlength="2000" rows="1"
                  placeholder="Add a class comment..."
                  aria-label="Add a comment to this announcement" required
                  oninput="updateCommentButton({{ $announcement->id }})"></textarea>

                <button class="student-announcement-comment-submit"
                  id="commentSubmit-{{ $announcement->id }}" type="submit">

                  <i class="fa-regular fa-paper-plane" aria-hidden="true"></i>

                  <span>Comment</span>

                </button>

              </form>

              <p class="student-announcement-comment-error"
                id="commentError-{{ $announcement->id }}" role="alert"></p>

            </section>

          </article>
        @endforeach
      @else
        <div class="announcement-empty-state">

          <i class="fa-regular fa-comments" aria-hidden="true"></i>

          <h3>No course announcements yet</h3>

          <p>

            Announcements posted by your trainers for your enrolled courses will

            appear here.

          </p>

        </div>

      @endif

      <div id="announcementNoSearchResults" class="announcement-empty-state"
        style="display: none;">

        <i class="fa fa-search" aria-hidden="true"></i>

        <h3>No matching announcements</h3>

        <p>Try searching with a different word or course name.</p>

      </div>

    </div>

  </div>

@endsection

@section('scripts')

  <script>
    function filterTrainerAnnouncements() {

      const searchInput = document.getElementById('announcementSearch');

      const query = (searchInput?.value || '').trim().toLowerCase();

      const cards = document.querySelectorAll(

        '#announcementsFeed .announcement-card');

      const noResults = document.getElementById('announcementNoSearchResults');

      let visibleCount = 0;

      cards.forEach(card => {

        const matches = card.textContent.toLowerCase().includes(query);

        card.style.display = matches ? '' : 'none';

        if (matches) visibleCount++;

      });

      if (noResults) {

        noResults.style.display = cards.length > 0 && visibleCount === 0 ?

          'block' : 'none';

      }

    }

    function updateCommentButton(announcementId) {

      const input = document.getElementById(`commentInput-${announcementId}`);

      const button = document.getElementById(`commentSubmit-${announcementId}`);

      if (button) button.disabled = !input || !input.value.trim();

    }

    document.addEventListener('DOMContentLoaded', function() {

      document.querySelectorAll(

          '.student-announcement-comment-input[id^="commentInput-"]')

        .forEach(function(input) {

          const match = input.id.match(/^commentInput-(.+)$/);

          if (match) updateCommentButton(match[1]);

        });

    });

    async function submitStudentComment(event, announcementId) {

      event.preventDefault();

      const input = document.getElementById(`commentInput-${announcementId}`);

      const button = document.getElementById(`commentSubmit-${announcementId}`);

      const error = document.getElementById(`commentError-${announcementId}`);

      const content = (input?.value || '').trim();

      if (!button) return;

      if (!content) {

        if (error) {

          error.textContent = 'Please type a comment before submitting.';

          error.style.display = 'block';

        }

        input?.focus();

        return;

      }

      button.disabled = true;

      if (error) {

        error.style.display = 'none';

        error.textContent = '';

      }

      try {

        const response = await fetch(

          `/student/announcements/${encodeURIComponent(announcementId)}/comments`, {

            method: 'POST',

            headers: {

              'Content-Type': 'application/json',

              'Accept': 'application/json',

              'X-CSRF-TOKEN': document.querySelector(

                  'meta[name="csrf-token"]')?.content ||

                '{{ csrf_token() }}'

            },

            body: JSON.stringify({

              comment: content

            })

          });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) throw new Error(data.message ||

          'Unable to post your comment.');

        window.location.reload();

      } catch (err) {

        if (error) {

          error.textContent = err.message ||

            'Unable to post your comment. Please try again.';

          error.style.display = 'block';

        }

        button.disabled = false;

      }

    }

    async function shareTrainerAnnouncement(id, button) {

      const url =

        `${window.location.origin}${window.location.pathname}#announcement-${encodeURIComponent(id)}`;

      const label = button?.querySelector('span');

      try {

        if (navigator.share) {

          await navigator.share({

            title: 'Course Announcement',

            url: url

          });

          return;

        }

        if (navigator.clipboard && window.isSecureContext) {

          await navigator.clipboard.writeText(url);

        } else {

          const input = document.createElement('textarea');

          input.value = url;

          input.style.position = 'fixed';

          input.style.opacity = '0';

          document.body.appendChild(input);

          input.select();

          const copied = document.execCommand('copy');

          input.remove();

          if (!copied) throw new Error('Unable to copy announcement link.');

        }

        if (label) {

          const oldLabel = label.textContent;

          label.textContent = 'Copied!';

          window.setTimeout(() => {

            label.textContent = oldLabel;

          }, 1800);

        }

      } catch (error) {

        if (error?.name !== 'AbortError') {

          window.prompt('Copy this announcement link:', url);

        }

      }

    }
  </script>

@endsection

