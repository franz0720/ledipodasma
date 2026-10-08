@extends('trainer.layout')

@section('title', 'Course Preview')

@section('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    [contenteditable="true"]:empty:before {
      content: attr(data-placeholder);
      color: #94a3b8;
      cursor: text;
    }

    .custom-scroll::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 9999px;
    }

    .custom-scroll::-webkit-scrollbar-track {
      background: transparent;
    }

    /* Classroom Tab Styles */
    .classroom-tab {
      display: inline-flex;
      align-items: center;
      padding: 12px 20px;
      font-size: 14.5px;
      font-weight: 600;
      color: #5f6368;
      text-decoration: none;
      cursor: pointer;
      border: none;
      background: transparent;
      position: relative;
      transition: color 0.2s ease;
    }

    .classroom-tab:hover {
      color: #025628;
      background: #f8fafc;
      border-radius: 8px 8px 0 0;
    }

    .classroom-tab.active {
      color: #025628;
    }

    .classroom-tab.active::after {
      content: '';
      position: absolute;
      bottom: -1px;
      left: 0;
      right: 0;
      height: 3px;
      background: #025628;
      border-top-left-radius: 4px;
      border-top-right-radius: 4px;
    }

    .quiz-attachment-preview:hover {
      border-color: #cbd5e1;
      background: #f8fafc;
    }

    /* Lesson Card Hover Animation */
    .lesson-item-card {
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .lesson-item-card:hover {
      border-color: #94a3b8 !important;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.06);
    }
  </style>
@endsection

@section('content')
  <div
    style="padding: 16px 16px 32px 16px; max-width: 1080px; margin: 0 auto; font-family: inherit; color: #1e293b; width: 100%; box-sizing: border-box;">

    {{-- Top Action Row: Back Button & Navigation Tabs --}}
    <div
      style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
      <div>
        <a href="{{ route('trainer.courses') }}"
          style="display: inline-flex; align-items: center; gap: 8px; border: 1px solid #e2e8f0; background: #ffffff; color: #025628; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s;"
          onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1';"
          onmouseout="this.style.background='#ffffff'; this.style.borderColor='#e2e8f0';">
          <i class="fa fa-arrow-left" style="font-size: 11px;"></i> Back to My
          Courses
        </a>
      </div>

      {{-- Stream & Classwork Tabs --}}
      <div style="display: flex; gap: 4px;">
        <button type="button" id="tabStreamBtn" class="classroom-tab active"
          onclick="switchClassroomTab('stream')">
          Stream
        </button>
        <button type="button" id="tabClassworkBtn" class="classroom-tab"
          onclick="switchClassroomTab('classwork')">
          Classwork
        </button>
      </div>

      <div
        style="min-width: 130px; display: none; @media(min-width: 768px){ display: block; }">
      </div>
    </div>

    {{-- Classroom Hero Banner --}}
    <div id="mainBannerCard"
      style="position: relative; width: 100%; box-sizing: border-box; background: linear-gradient(135deg, #025628 0%, #0f766e 100%); color: #fff; border-radius: 16px; min-height: 200px; padding: 32px; margin-bottom: 24px; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; box-shadow: 0 4px 20px -2px rgba(2, 86, 40, 0.15);">
      <div id="bannerImageLayer"
        style="position: absolute; inset: 0; background: url('{{ !empty($course->banner_image) ? asset($course->banner_image) : '' }}') center/cover no-repeat; opacity: 0.45; pointer-events: none; {{ empty($course->banner_image) ? 'display:none;' : '' }}">
      </div>
      <div
        style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.15) 60%, transparent 100%); pointer-events: none;">
      </div>

      <div style="position: absolute; top: 20px; right: 20px; z-index: 2;">
        <button type="button" onclick="openCustomizeModal()"
          style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.92); backdrop-filter: blur(8px); color: #025628; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 6px rgba(0,0,0,0.08);"
          onmouseover="this.style.background='#ffffff'; this.style.transform='translateY(-1px)';"
          onmouseout="this.style.background='rgba(255,255,255,0.92)'; this.style.transform='none';">
          <i class="fa fa-pencil" style="font-size: 12px;"></i> Customize
        </button>
      </div>

      <div style="position: relative; z-index: 2; max-width: 80%;">
        <h1
          style="font-size: 28px; font-weight: 700; margin: 0 0 6px 0; line-height: 1.25; color: #ffffff; text-shadow: 0 1px 2px rgba(0,0,0,0.2); word-break: break-word;">
          {{ $course->title }}
        </h1>
      </div>

      <div
        style="position: absolute; bottom: 20px; right: 20px; z-index: 2; color: rgba(255,255,255,0.8); cursor: pointer;"
        title="Course Details">
        <i class="fa fa-circle-info" style="font-size: 18px;"></i>
      </div>
    </div>

    {{-- TAB 1: STREAM VIEW --}}
    <div id="tabContentStream"
      style="display: flex; gap: 24px; flex-wrap: wrap; align-items: flex-start; width: 100%;">
      {{-- Stream Feed (Left) --}}
      <div
        style="flex: 1 1 560px; min-width: 0; display: flex; flex-direction: column; gap: 20px;">
        <div
          style="display: flex; align-items: center; justify-content: space-between; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 18px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
          <button type="button" onclick="openAnnouncementModal()"
            style="display: inline-flex; align-items: center; gap: 10px; background: #f0fdf4; color: #025628; border: 1px solid #bbf7d0; border-radius: 8px; padding: 9px 20px; font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
            onmouseover="this.style.background='#dcfce7'; this.style.borderColor='#86efac';"
            onmouseout="this.style.background='#f0fdf4'; this.style.borderColor='#bbf7d0';">
            <i class="fa fa-pencil" style="font-size: 13px;"></i>
            <span>New announcement</span>
          </button>
        </div>

        <div id="announcementsStream"
          style="display: flex; flex-direction: column; gap: 18px;">
          @php
            $announcements =
                $course->trainerAnnouncements ??
                ($course->announcements ?? collect());
          @endphp

          @if ($announcements->count() > 0)
            @foreach ($announcements as $announcement)
              @php
                $author =
                    $announcement->trainer ??
                    ($announcement->user ?? auth()->user());
                $fName = trim($author->firstname ?? '');
                $lName = trim($author->lastname ?? '');

                if ($fName !== '' && $lName !== '') {
                    $displayName = ucwords(strtolower("{$fName} {$lName}"));
                    $badgeInitials = strtoupper(
                        mb_substr($fName, 0, 1) . mb_substr($lName, 0, 1),
                    );
                } else {
                    $fallback = trim(
                        $author->name ?? (auth()->user()->name ?? 'Trainer'),
                    );
                    $displayName = ucwords(strtolower($fallback));
                    $words = array_values(
                        array_filter(preg_split('/\s+/', $fallback)),
                    );
                    $badgeInitials =
                        count($words) >= 2
                            ? strtoupper(
                                mb_substr($words[0], 0, 1) .
                                    mb_substr(end($words), 0, 1),
                            )
                            : strtoupper(mb_substr($words[0] ?? 'TR', 0, 2));
                }

                $avatarUrl = !empty($author->avatar)
                    ? asset($author->avatar)
                    : null;
              @endphp

              <div class="announcement-card" data-id="{{ $announcement->id }}"
                style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: visible; box-shadow: 0 1px 3px rgba(0,0,0,0.04); position: relative; transition: border-color 0.2s;"
                onmouseover="this.style.borderColor='#cbd5e1'"
                onmouseout="this.style.borderColor='#e2e8f0'">

                <div style="padding: 20px 22px;">
                  <div
                    style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                      @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $displayName }}"
                          style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 1px solid #e2e8f0;">
                      @else
                        <div
                          style="width: 42px; height: 42px; border-radius: 50%; background: #dcfce7; color: #025628; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0; border: 1px solid #bbf7d0;">
                          {{ $badgeInitials }}
                        </div>
                      @endif

                      <div>
                        <div
                          style="font-size: 14.5px; font-weight: 700; color: #1e293b; line-height: 1.2;">
                          {{ $displayName }}</div>
                        <div
                          style="font-size: 12px; color: #64748b; margin-top: 3px;">
                          {{ $announcement->created_at ? $announcement->created_at->format('M d, Y • g:i A') : 'Just now' }}
                        </div>
                      </div>
                    </div>

                    <div style="position: relative;">
                      <button type="button"
                        onclick="toggleAnnouncementMenu(event, 'menu-{{ $announcement->id }}')"
                        style="background: none; border: none; color: #94a3b8; cursor: pointer; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 15px; transition: all 0.2s;"
                        onmouseover="this.style.background='#f1f5f9'; this.style.color='#334155';"
                        onmouseout="this.style.background='transparent'; this.style.color='#94a3b8';"
                        title="Options">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>

                      <div id="menu-{{ $announcement->id }}"
                        class="announcement-dropdown-menu"
                        style="display: none; position: absolute; top: calc(100% + 4px); right: 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); width: 140px; padding: 6px; z-index: 50; font-size: 13.5px;">
                        <button type="button"
                          onclick="editAnnouncement({{ $announcement->id }})"
                          style="width: 100%; text-align: left; background: none; border: none; border-radius: 6px; padding: 8px 12px; color: #334155; cursor: pointer; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                          <i class="fa-regular fa-pen-to-square"
                            style="font-size: 12px; color: #64748b;"></i> Edit
                        </button>
                        <button type="button"
                          onclick="deleteAnnouncement({{ $announcement->id }})"
                          style="width: 100%; text-align: left; background: none; border: none; border-radius: 6px; padding: 8px 12px; color: #e11d48; cursor: pointer; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                          <i class="fa-regular fa-trash-can"
                            style="font-size: 12px; color: #e11d48;"></i> Delete
                        </button>
                      </div>
                    </div>
                  </div>

                  <div
                    style="margin-top: 14px; font-size: 14px; color: #334155; line-height: 1.6; word-break: break-word;">
                    {!! $announcement->content !!}
                  </div>
                </div>

                {{-- Comments Section --}}
                <div
                  style="border-top: 1px solid #f1f5f9; padding: 16px 22px; background: #fafafa; border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
                  <div id="commentsList-{{ $announcement->id }}"
                    style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 14px;">
                    @foreach ($announcement->comments ?? [] as $cmt)
                      @php
                        $cUser = $cmt->user;
                        $cFirst = trim($cUser->firstname ?? '');
                        $cLast = trim($cUser->lastname ?? '');
                        $cName =
                            $cFirst && $cLast
                                ? "{$cFirst} {$cLast}"
                                : trim($cUser->name ?? 'User');
                        $cInitials =
                            $cFirst && $cLast
                                ? strtoupper($cFirst[0] . $cLast[0])
                                : strtoupper(substr($cName, 0, 2));
                      @endphp
                      <div
                        style="display: flex; align-items: flex-start; gap: 10px;">
                        <div
                          style="width: 32px; height: 32px; border-radius: 50%; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px; flex-shrink: 0; border: 1px solid #bae6fd;">
                          {{ $cInitials }}
                        </div>
                        <div
                          style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 8px 14px; flex: 1;">
                          <div
                            style="display: flex; justify-content: space-between; align-items: baseline; gap: 8px;">
                            <span
                              style="font-size: 12.5px; font-weight: 700; color: #1e293b;">{{ $cName }}</span>
                            <span
                              style="font-weight: 400; color: #94a3b8; font-size: 11px;">{{ $cmt->created_at->format('g:i A') }}</span>
                          </div>
                          <div
                            style="font-size: 13px; color: #475569; margin-top: 3px; line-height: 1.4;">
                            {!! $cmt->comment !!}
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>

                  <div id="commentTriggerBox-{{ $announcement->id }}">
                    <button type="button"
                      onclick="openCommentBox({{ $announcement->id }})"
                      style="background: transparent; border: 1px dashed #cbd5e1; border-radius: 8px; width: 100%; color: #64748b; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; padding: 8px 12px;">
                      <i class="fa-regular fa-message"
                        style="font-size: 13px;"></i>
                      <span>Add a class comment...</span>
                    </button>
                  </div>

                  <div id="commentInputBox-{{ $announcement->id }}"
                    style="display: none; align-items: flex-end; gap: 10px;">
                    <div
                      style="flex: 1; background: #ffffff; border: 1.5px solid #025628; border-radius: 12px; padding: 10px 14px 8px 14px; display: flex; flex-direction: column; gap: 6px;">
                      <div id="commentEditor-{{ $announcement->id }}"
                        contenteditable="true"
                        data-placeholder="Add class comment..."
                        oninput="handleCommentInput({{ $announcement->id }})"
                        style="outline: none; font-size: 13.5px; color: #1e293b; line-height: 1.45; min-height: 24px; max-height: 120px; overflow-y: auto;">
                      </div>
                      <div
                        style="display: flex; align-items: center; gap: 6px; padding-top: 6px; border-top: 1px solid #f1f5f9;">
                        <button type="button"
                          onclick="formatCommentText('bold')"
                          style="background: none; border: none; color: #64748b; font-weight: bold; cursor: pointer; padding: 2px 6px; font-size: 12px;"><b>B</b></button>
                        <button type="button"
                          onclick="formatCommentText('italic')"
                          style="background: none; border: none; color: #64748b; font-style: italic; cursor: pointer; padding: 2px 6px; font-size: 12px;"><i>I</i></button>
                        <button type="button"
                          onclick="formatCommentText('underline')"
                          style="background: none; border: none; color: #64748b; text-decoration: underline; cursor: pointer; padding: 2px 6px; font-size: 12px;">U</button>
                      </div>
                    </div>

                    <button type="button"
                      id="commentSendBtn-{{ $announcement->id }}" disabled
                      onclick="submitComment({{ $announcement->id }})"
                      style="background: #f1f5f9; border: none; color: #94a3b8; cursor: not-allowed; font-size: 15px; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                      <i class="fa-regular fa-paper-plane"></i>
                    </button>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div
              style="background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 36px 20px; text-align: center; color: #64748b;">
              <i class="fa-regular fa-comments"
                style="font-size: 28px; margin-bottom: 8px; color: #94a3b8;"></i>
              <p style="margin: 0; font-size: 14px; font-weight: 500;">No
                announcements shared yet.</p>
              <span style="font-size: 12px; color: #94a3b8;">Click "New
                announcement" above to broadcast updates to your class.</span>
            </div>
          @endif
        </div>
      </div>

      {{-- Stream Sidebar --}}
      <div
        style="flex: 0 0 320px; min-width: 280px; display: flex; flex-direction: column; gap: 20px;">
        <div
          style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px 20px;">
          <div
            style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.04em; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-regular fa-calendar-check" style="color: #025628;"></i>
            Schedule & Duration
          </div>
          <div
            style="display: flex; flex-direction: column; gap: 8px; font-size: 13.5px; color: #334155;">
            <div
              style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;">
              <span style="color: #64748b;">Duration</span>
              <strong style="color: #0f172a;">{{ $course->duration ?? 'TBA' }}
                Days</strong>
            </div>
            <div
              style="display: flex; justify-content: space-between; padding-top: 2px;">
              <span style="color: #64748b;">Class Schedule</span>
              <strong
                style="color: #0f172a;">{{ $course->schedule ?? 'TBA' }}</strong>
            </div>
          </div>
        </div>

        <div
          style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px;">
          <div
            style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 8px;">
            Course Description
          </div>
          <textarea id="descriptionInput" rows="4"
            style="width: 100%; box-sizing: border-box; font-size: 13.5px; color: #1e293b; font-family: inherit; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 12px; resize: vertical; line-height: 1.5;"
            onfocus="this.style.borderColor='#025628'; this.style.outline='none';"
            onblur="this.style.borderColor='#cbd5e1';">{{ $course->description }}</textarea>
          <div
            style="margin-top: 10px; display: flex; justify-content: flex-end;">
            <button type="button" onclick="saveDescription()"
              style="background: #025628; color: #fff; border: none; border-radius: 6px; padding: 7px 14px; font-size: 12.5px; font-weight: 600; cursor: pointer;">
              Save Description
            </button>
          </div>
        </div>

        <div
          style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px;">
          <div
            style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 8px;">
            Learning Objectives
          </div>
          <textarea id="objectivesInput" rows="4"
            style="width: 100%; box-sizing: border-box; font-size: 13.5px; color: #1e293b; font-family: inherit; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 12px; resize: vertical; line-height: 1.5;"
            onfocus="this.style.borderColor='#025628'; this.style.outline='none';"
            onblur="this.style.borderColor='#cbd5e1';">{{ $course->objectives }}</textarea>
          <div
            style="margin-top: 10px; display: flex; justify-content: flex-end;">
            <button type="button" onclick="saveObjectives()"
              style="background: #025628; color: #fff; border: none; border-radius: 6px; padding: 7px 14px; font-size: 12.5px; font-weight: 600; cursor: pointer;">
              Save Objectives
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- TAB 2: CLASSWORK VIEW (Modules + Quizzes) --}}
    <div id="tabContentClasswork"
      style="display: none; flex-direction: column; gap: 24px; width: 100%;">

      {{-- SECTION 1: QUIZZES & ASSESSMENTS --}}
      <div
        style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
        <div
          style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
          <div>
            <h3
              style="margin: 0; font-size: 17px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px;">
              <i class="fa-solid fa-list-check"
                style="color: #025628; font-size: 16px;"></i>
              Assessments
            </h3>
          </div>

          @php
            $quizzes = $course->quizzes ?? collect();
          @endphp

          <div style="display: flex; align-items: center; gap: 10px;">
            <span
              style="background: #f0fdf4; color: #025628; border: 1px solid #bbf7d0; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700;">
              {{ count($quizzes) }}
              {{ count($quizzes) === 1 ? 'Quiz' : 'Quizzes' }}
            </span>

            <button type="button" onclick="openAddQuizModal()"
              style="display: inline-flex; align-items: center; gap: 8px; background: #025628; color: #ffffff; border: 1px solid #025628; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
              onmouseover="this.style.background='#01401e'; this.style.transform='translateY(-1px)';"
              onmouseout="this.style.background='#025628'; this.style.transform='none';">
              <i class="fa-solid fa-plus" style="font-size: 11px;"></i>
              <span>Create Quiz</span>
            </button>
          </div>
        </div>

        {{-- Quizzes List --}}
        <div id="quizzesContainer"
          style="display: flex; flex-direction: column; gap: 14px;">

          @forelse ($quizzes as $qIndex => $quiz)
            <div id="quiz-card-{{ $quiz->id }}"
              style="
                background: #ffffff;
                border: 1px solid #d1fae5;
                border-radius: 14px;
                padding: 18px 20px;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 18px;
                transition: all 0.2s ease;
                box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            "
              onmouseover="
                this.style.borderColor='#86efac';
                this.style.boxShadow='0 4px 12px rgba(21,128,61,0.10)';
                this.style.transform='translateY(-1px)';
            "
              onmouseout="
                this.style.borderColor='#d1fae5';
                this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)';
                this.style.transform='translateY(0)';
            ">

              {{-- LEFT SIDE --}}
              <div
                style="
                    display: flex;
                    align-items: flex-start;
                    gap: 15px;
                    flex: 1;
                    min-width: 0;
                ">

                {{-- Quiz Icon --}}
                <div
                  style="
                        width: 42px;
                        height: 42px;
                        border-radius: 10px;
                        background: #ecfdf5;
                        border: 1px solid #bbf7d0;
                        color: #15803d;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 17px;
                        flex-shrink: 0;
                    ">
                  <i class="fa-solid fa-clipboard-question"></i>
                </div>

                {{-- QUIZ INFORMATION --}}
                <div style="flex: 1; min-width: 0;">

                  {{-- Header --}}
                  <div
                    style="
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            margin-bottom: 5px;
                            flex-wrap: wrap;
                        ">

                    <span
                      style="
                                font-size: 10px;
                                font-weight: 800;
                                letter-spacing: 0.04em;
                                color: #166534;
                                background: #dcfce7;
                                border: 1px solid #bbf7d0;
                                padding: 3px 8px;
                                border-radius: 5px;
                                text-transform: uppercase;
                            ">
                      Assessment
                    </span>

                    <span
                      style="
                                font-size: 12px;
                                color: #94a3b8;
                                font-weight: 600;
                            ">
                      Quiz {{ $qIndex + 1 }}
                    </span>

                  </div>

                  {{-- Quiz Title --}}
                  <div
                    style="
                            font-weight: 750;
                            font-size: 15px;
                            color: #1e293b;
                            line-height: 1.4;
                            margin-bottom: 7px;
                            word-break: break-word;
                        ">
                    {{ $quiz->title }}
                  </div>

                  {{-- Instructions --}}
                  @if (!empty($quiz->instructions))
                    <div
                      style="
                                font-size: 12.5px;
                                color: #64748b;
                                line-height: 1.5;
                                margin-bottom: 10px;
                                max-width: 760px;
                                word-break: break-word;
                            ">
                      {!! $quiz->instructions !!}
                    </div>
                  @endif

                  {{-- LINKED LOCATION --}}
                  <div
                    style="
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            flex-wrap: wrap;
                            margin-top: 8px;
                        ">

                    {{-- Unit --}}
                    <div
                      style="
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                background: #f0fdf4;
                                border: 1px solid #bbf7d0;
                                color: #166534;
                                border-radius: 7px;
                                padding: 6px 9px;
                            ">

                      <i class="fa-solid fa-layer-group"
                        style="font-size: 11px; color: #15803d;">
                      </i>

                      <span
                        style="
                                    font-size: 11px;
                                    font-weight: 700;
                                ">
                        Unit
                      </span>

                      <span
                        style="
                                    font-size: 11px;
                                    color: #4d7c5a;
                                ">
                        {{ $quiz->module->unit_number ?? '—' }}
                      </span>

                    </div>

                    {{-- Arrow --}}
                    <i class="fa-solid fa-chevron-right"
                      style="
                                font-size: 9px;
                                color: #86efac;
                            ">
                    </i>

                    {{-- Lesson --}}
                    <div
                      style="
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                background: #f0fdf4;
                                border: 1px solid #bbf7d0;
                                color: #166534;
                                border-radius: 7px;
                                padding: 6px 9px;
                            ">

                      <i class="fa-solid fa-book-open"
                        style="font-size: 11px; color: #15803d;">
                      </i>

                      <span
                        style="
                                    font-size: 11px;
                                    font-weight: 700;
                                ">
                        Lesson
                      </span>

                      <span
                        style="
                                    font-size: 11px;
                                    color: #4d7c5a;
                                ">
                        {{ $quiz->module->title ?? 'Not linked' }}
                      </span>

                    </div>

                  </div>

                  {{-- QUESTION COUNT --}}
                  <div
                    style="
                            display: flex;
                            align-items: center;
                            gap: 14px;
                            margin-top: 10px;
                        ">

                    <span
                      style="
                                display: inline-flex;
                                align-items: center;
                                gap: 5px;
                                font-size: 11px;
                                color: #64748b;
                                font-weight: 600;
                            ">
                      <i class="fa-solid fa-list-check" style="color: #16a34a;">
                      </i>

                      {{ $quiz->questions->count() }}
                      {{ $quiz->questions->count() == 1 ? 'Question' : 'Questions' }}
                    </span>

                    @if ($quiz->passing_score)
                      <span
                        style="
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 5px;
                                    font-size: 11px;
                                    color: #64748b;
                                    font-weight: 600;
                                ">
                        <i class="fa-solid fa-circle-check"
                          style="color: #16a34a;">
                        </i>

                        Passing:
                        {{ $quiz->passing_score }}%
                      </span>
                    @endif

                    @if ($quiz->time_limit)
                      <span
                        style="
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 5px;
                                    font-size: 11px;
                                    color: #64748b;
                                    font-weight: 600;
                                ">
                        <i class="fa-regular fa-clock" style="color: #16a34a;">
                        </i>

                        {{ $quiz->time_limit }} min
                      </span>
                    @endif

                  </div>

                </div>
              </div>

              {{-- ACTION BUTTONS --}}
              <div
                style="
                    display: flex;
                    align-items: center;
                    gap: 5px;
                    flex-shrink: 0;
                ">

                {{-- EDIT --}}
                <button type="button"
                  onclick="openEditQuizModal({{ $quiz->id }})"
                  title="Edit quiz"
                  style="
                        background: transparent;
                        border: none;
                        color: #64748b;
                        cursor: pointer;
                        width: 34px;
                        height: 34px;
                        border-radius: 7px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 13px;
                        transition: all 0.15s;
                    "
                  onmouseover="
                        this.style.background='#ecfdf5';
                        this.style.color='#15803d';
                    "
                  onmouseout="
                        this.style.background='transparent';
                        this.style.color='#64748b';
                    ">

                  <i class="fa-regular fa-pen-to-square"></i>

                </button>

                {{-- DELETE --}}
                <button type="button"
                  onclick='openDeleteQuizModal(
                        {{ $quiz->id }},
                        @json($quiz->title)
                    )'
                  title="Delete quiz"
                  style="
                        background: transparent;
                        border: none;
                        color: #94a3b8;
                        cursor: pointer;
                        width: 34px;
                        height: 34px;
                        border-radius: 7px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 13px;
                        transition: all 0.15s;
                    "
                  onmouseover="
                        this.style.background='#fff1f2';
                        this.style.color='#e11d48';
                    "
                  onmouseout="
                        this.style.background='transparent';
                        this.style.color='#94a3b8';
                    ">

                  <i class="fa-regular fa-trash-can"></i>

                </button>

              </div>

            </div>

          @empty

            {{-- EMPTY STATE --}}
            <div
              style="
                background: linear-gradient(
                    135deg,
                    #f0fdf4 0%,
                    #ffffff 100%
                );
                border: 1px dashed #86efac;
                border-radius: 14px;
                padding: 42px 20px;
                text-align: center;
            ">

              <div
                style="
                    width: 52px;
                    height: 52px;
                    border-radius: 14px;
                    background: #dcfce7;
                    border: 1px solid #bbf7d0;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    color: #15803d;
                    margin-bottom: 12px;
                ">

                <i class="fa-solid fa-clipboard-list" style="font-size: 20px;">
                </i>

              </div>

              <h4
                style="
                    margin: 0 0 5px 0;
                    font-size: 15px;
                    font-weight: 750;
                    color: #14532d;
                ">
                No Quizzes Yet
              </h4>

              <p
                style="
                    margin: 0 auto 17px auto;
                    font-size: 12.5px;
                    color: #64748b;
                    max-width: 390px;
                    line-height: 1.5;
                ">
                Create a quiz and link it directly to a unit and lesson
                for your trainees.
              </p>

              <button type="button" onclick="openAddQuizModal()"
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    background: #15803d;
                    color: #ffffff;
                    border: none;
                    border-radius: 8px;
                    padding: 9px 16px;
                    font-size: 12.5px;
                    font-weight: 700;
                    cursor: pointer;
                    box-shadow: 0 2px 5px rgba(21,128,61,0.18);
                    transition: all 0.15s;
                "
                onmouseover="
                    this.style.background='#166534';
                    this.style.transform='translateY(-1px)';
                "
                onmouseout="
                    this.style.background='#15803d';
                    this.style.transform='translateY(0)';
                ">

                <i class="fa-solid fa-plus" style="font-size: 11px;">
                </i>

                <span>Create Quiz</span>

              </button>

            </div>
          @endforelse

        </div>
      </div>

      {{-- SECTION 2: CURRICULUM MODULES & LESSONS (GROUPED BY UNIT) --}}
      <div
        style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">

        {{-- Section Header --}}
        <div
          style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
          <div>
            <h3
              style="margin: 0; font-size: 18px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
              <span
                style="width: 32px; height: 32px; border-radius: 8px; background: #f0fdf4; border: 1px solid #bbf7d0; display: inline-flex; align-items: center; justify-content: center; color: #025628; font-size: 15px;">
                <i class="fa-solid fa-book-bookmark"></i>
              </span>
              Modules
            </h3>
          </div>

          @php
            // Group modules by unit_number and sort unit keys ascending
            $groupedUnits = collect($modules)
                ->groupBy(function ($mod) {
                    return (int) ($mod->unit_number ?? 1);
                })
                ->sortKeys();
            $totalUnits = $groupedUnits->count();
            $totalLessons = count($modules);
          @endphp

          <div style="display: flex; align-items: center; gap: 10px;">
            <div
              style="display: inline-flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 4px 12px; gap: 10px; font-size: 12px; font-weight: 700; color: #475569;">
              <span>{{ $totalUnits }}
                {{ $totalUnits === 1 ? 'Unit' : 'Units' }}</span>
              <span
                style="width: 4px; height: 4px; border-radius: 50%; background: #cbd5e1;"></span>
              <span style="color: #025628;">{{ $totalLessons }}
                {{ $totalLessons === 1 ? 'Lesson' : 'Lessons' }}</span>
            </div>

            <button type="button" onclick="openAddModuleModal()"
              style="display: inline-flex; align-items: center; gap: 8px; background: #025628; color: #ffffff; border: 1px solid #025628; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
              onmouseover="this.style.background='#01401e'; this.style.transform='translateY(-1px)';"
              onmouseout="this.style.background='#025628'; this.style.transform='none';">
              <i class="fa-solid fa-plus" style="font-size: 11px;"></i>
              <span>Add Module</span>
            </button>
          </div>
        </div>

        {{-- Units & Lessons Accordion List --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">
          @forelse ($groupedUnits as $unitNum => $unitModules)
            <div id="unit-section-{{ $unitNum }}"
              style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">

              {{-- Unit Top Banner / Header Bar --}}
              <div
                style="padding: 14px 18px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                  <button type="button"
                    onclick="toggleUnitCollapse({{ $unitNum }})"
                    id="unitToggleBtn-{{ $unitNum }}"
                    style="border: none; background: transparent; cursor: pointer; padding: 4px; font-size: 13px; color: #64748b; transition: transform 0.2s;">
                    <i class="fa-solid fa-chevron-down"></i>
                  </button>

                  <div style="display: flex; align-items: center; gap: 10px;">
                    <span
                      style="background: #025628; color: #ffffff; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase;">
                      Unit {{ $unitNum }}
                    </span>
                    <span
                      style="font-size: 13px; font-weight: 600; color: #475569;">
                      {{ $unitModules->count() }}
                      {{ $unitModules->count() === 1 ? 'Lesson' : 'Lessons' }}
                    </span>
                  </div>
                </div>

                {{-- Action: Quick Add To This Specific Unit --}}
                <div style="display: flex; align-items: center; gap: 8px;">
                  <button type="button"
                    onclick="openAddModuleModal({{ $unitNum }})"
                    style="display: inline-flex; align-items: center; gap: 6px; background: #ffffff; color: #025628; border: 1px solid #bbf7d0; border-radius: 6px; padding: 6px 13px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.15s;"
                    onmouseover="this.style.background='#f0fdf4'; this.style.borderColor='#86efac';"
                    onmouseout="this.style.background='#ffffff'; this.style.borderColor='#bbf7d0';">
                    <i class="fa-solid fa-plus" style="font-size: 10px;"></i>
                    <span>Add to Unit {{ $unitNum }}</span>
                  </button>
                </div>
              </div>

              {{-- Lessons Body (Collapsible) --}}
              <div id="unitLessonsContainer-{{ $unitNum }}"
                style="padding: 16px 18px; display: flex; flex-direction: column; gap: 12px; background: #ffffff;">
                @foreach ($unitModules->sortBy('order') as $lessonIndex => $module)
                  @php
                    $filePath = $module->file_path;
                    $fileType = $module->file_type ?? 'other';
                    $ext = $filePath
                        ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION))
                        : '';

                    if ($ext === 'pdf' || $fileType === 'pdf') {
                        $iconConfig = [
                            'icon' => 'fa-solid fa-file-pdf',
                            'color' => '#dc2626',
                            'bg' => '#fee2e2',
                            'label' => 'PDF Document',
                        ];
                    } elseif (
                        in_array($ext, ['doc', 'docx']) ||
                        $fileType === 'docx'
                    ) {
                        $iconConfig = [
                            'icon' => 'fa-solid fa-file-word',
                            'color' => '#0284c7',
                            'bg' => '#e0f2fe',
                            'label' => 'Word Doc',
                        ];
                    } elseif (in_array($ext, ['ppt', 'pptx'])) {
                        $iconConfig = [
                            'icon' => 'fa-solid fa-file-powerpoint',
                            'color' => '#ea580c',
                            'bg' => '#ffedd5',
                            'label' => 'Presentation',
                        ];
                    } elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) {
                        $iconConfig = [
                            'icon' => 'fa-solid fa-file-excel',
                            'color' => '#16a34a',
                            'bg' => '#dcfce7',
                            'label' => 'Spreadsheet',
                        ];
                    } elseif (
                        $fileType === 'video' ||
                        in_array($ext, ['mp4', 'mov', 'avi', 'webm'])
                    ) {
                        $iconConfig = [
                            'icon' => 'fa-solid fa-file-video',
                            'color' => '#059669',
                            'bg' => '#d1fae5',
                            'label' => 'Video Lecture',
                        ];
                    } else {
                        $iconConfig = [
                            'icon' => 'fa-solid fa-file-lines',
                            'color' => '#64748b',
                            'bg' => '#f1f5f9',
                            'label' => 'Resource File',
                        ];
                    }

                    $cleanFileName = preg_replace(
                        '/^\d+_/',
                        '',
                        basename($module->file_path),
                    );
                    $fileUrl = asset('storage/' . $module->file_path);
                    $lessonNumber = $module->order ?? $lessonIndex + 1;
                  @endphp

                  <div id="module-card-{{ $module->id }}"
                    class="lesson-item-card"
                    style="background: #ffffff; border: 1px solid #e2e8f0; border-left: 3px solid #025628; border-radius: 10px; padding: 16px; display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;">

                    <div
                      style="display: flex; align-items: flex-start; gap: 14px; flex: 1; min-width: 0;">

                      {{-- Lesson Step Number Badge --}}
                      <div
                        style="width: 36px; height: 36px; border-radius: 8px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #025628; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                        <span
                          style="font-size: 8px; text-transform: uppercase; font-weight: 800; line-height: 1; color: #15803d;">LSN</span>
                        <span
                          style="font-size: 13.5px; font-weight: 800; line-height: 1.1;">{{ $lessonNumber }}</span>
                      </div>

                      {{-- Lesson Details --}}
                      <div style="flex: 1; min-width: 0;">
                        <div
                          style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; flex-wrap: wrap;">
                          <span
                            style="font-size: 11px; font-weight: 700; color: #025628; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                            Unit {{ $unitNum }} • Lesson
                            {{ $lessonNumber }}
                          </span>
                        </div>

                        <div
                          style="font-weight: 700; font-size: 15px; color: #0f172a; line-height: 1.35; word-break: break-word;">
                          {{ $module->title }}
                        </div>

                        @if (!empty($module->description))
                          <div
                            style="font-size: 13px; color: #64748b; margin-top: 5px; line-height: 1.5; word-break: break-word;">
                            {{ $module->description }}
                          </div>
                        @endif

                        {{-- Attached Resource Pill --}}
                        @if (!empty($module->file_path))
                          <div
                            style="margin-top: 12px; display: inline-flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <div
                              style="display: inline-flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 12px; gap: 10px;">
                              <span
                                style="width: 24px; height: 24px; border-radius: 6px; background: {{ $iconConfig['bg'] }}; display: flex; align-items: center; justify-content: center;">
                                <i class="{{ $iconConfig['icon'] }}"
                                  style="color: {{ $iconConfig['color'] }}; font-size: 12px;"></i>
                              </span>

                              <div
                                style="display: flex; flex-direction: column;">
                                <span
                                  style="font-size: 12.5px; font-weight: 600; color: #1e293b; max-width: 240px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                  {{ $cleanFileName }}
                                </span>
                                <span style="font-size: 10.5px; color: #94a3b8;">
                                  {{ $iconConfig['label'] }} @if (!empty($module->file_size))
                                    • {{ $module->file_size }}
                                  @endif
                                </span>
                              </div>

                              <div
                                style="display: flex; align-items: center; gap: 4px; margin-left: 6px; border-left: 1px solid #e2e8f0; padding-left: 8px;">
                                <a href="{{ $fileUrl }}" target="_blank"
                                  title="Preview document"
                                  style="color: #025628; padding: 4px 6px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;"
                                  onmouseover="this.style.background='#dcfce7';"
                                  onmouseout="this.style.background='transparent';">
                                  <i class="fa-regular fa-eye"></i> Preview
                                </a>

                                <a href="{{ $fileUrl }}"
                                  download="{{ $cleanFileName }}"
                                  title="Download file"
                                  style="color: #64748b; padding: 4px 6px; border-radius: 4px; text-decoration: none; font-size: 12px;"
                                  onmouseover="this.style.background='#e2e8f0'; this.style.color='#0f172a';"
                                  onmouseout="this.style.background='transparent'; this.style.color='#64748b';">
                                  <i class="fa-solid fa-arrow-down"></i>
                                </a>
                              </div>
                            </div>
                          </div>
                        @endif
                      </div>

                    </div>

                    {{-- Actions Menu / Delete --}}
                    <div>
                      <button type="button"
                        onclick="openDeleteModuleModal({{ $module->id }}, '{{ addslashes($module->title) }}')"
                        title="Delete lesson"
                        style="background: transparent; border: none; color: #94a3b8; cursor: pointer; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 13px; transition: all 0.15s;"
                        onmouseover="this.style.background='#fff1f2'; this.style.color='#e11d48';"
                        onmouseout="this.style.background='transparent'; this.style.color='#94a3b8';">
                        <i class="fa-regular fa-trash-can"></i>
                      </button>
                    </div>

                  </div>
                @endforeach
              </div>
            </div>
          @empty
            {{-- Empty State --}}
            <div
              style="background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 48px 20px; text-align: center;">
              <div
                style="width: 52px; height: 52px; border-radius: 50%; background: #ffffff; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; justify-content: center; color: #025628; margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <i class="fa-solid fa-folder-open" style="font-size: 22px;"></i>
              </div>
              <h4
                style="margin: 0 0 6px 0; font-size: 16px; font-weight: 700; color: #1e293b;">
                No Curriculum Modules Yet
              </h4>
              <p
                style="margin: 0 0 18px 0; font-size: 13px; color: #64748b; max-width: 420px; margin-left: auto; margin-right: auto; line-height: 1.5;">
                Organize your course syllabus into Units. Add lessons, upload
                video lectures, PDF guides, or assignments for your trainees.
              </p>
              <button type="button" onclick="openAddModuleModal(1)"
                style="display: inline-flex; align-items: center; gap: 8px; background: #025628; color: #ffffff; border: none; border-radius: 8px; padding: 9px 20px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 4px rgba(2,86,40,0.2);">
                <i class="fa-solid fa-plus" style="font-size: 11px;"></i>
                <span>Add Unit 1 Lesson</span>
              </button>
            </div>
          @endforelse
        </div>

      </div>

    </div>

    {{-- ========================================================================= --}}
    {{-- MODALS SECTION                                                            --}}
    {{-- ========================================================================= --}}

    @php
      $quizLessonOptions = collect($modules)
          ->map(function ($mod) {
              return [
                  'id' => $mod->id,
                  'unit' => (int) ($mod->unit_number ?? 1),
                  'title' => $mod->title,
                  'order' => (int) ($mod->order ?? 0),
              ];
          })
          ->sortBy([['unit', 'asc'], ['order', 'asc']])
          ->values();
    @endphp

    {{-- QUIZ MAKER MODAL --}}
    <div id="addQuizModal"
      style="display:none; position:fixed; z-index:3200; inset:0;
  background:rgba(15,23,42,.60); backdrop-filter:blur(5px);
  align-items:center; justify-content:center;">

      <div
        style="background:#fff; border-radius:14px; width:96%; max-width:900px;
    max-height:92vh; box-shadow:0 24px 60px rgba(15,23,42,.22);
    overflow:hidden; display:flex; flex-direction:column;">

        {{-- HEADER --}}
        <div
          style="padding:16px 22px; border-bottom:1px solid #dcfce7;
      display:flex; align-items:center; justify-content:space-between;
      background:#fff;">

          <div style="display:flex; align-items:center; gap:11px;">
            <div
              style="width:38px;height:38px;border-radius:10px;
          background:#f0fdf4;border:1px solid #bbf7d0;
          color:#15803d;display:flex;align-items:center;
          justify-content:center;font-size:16px;">
              <i class="fa-solid fa-clipboard-question"></i>
            </div>

            <div>
              <h3 id="quizModalTitle"
                style="margin:0;font-size:17px;font-weight:800;color:#0f172a;">
                Create Quiz
              </h3>
              <div style="font-size:11.5px;color:#64748b;margin-top:2px;">
                Create and manage your quiz assessment
              </div>
            </div>
          </div>

          <button type="button" onclick="closeAddQuizModal()"
            style="background:none;border:none;color:#94a3b8;
        font-size:22px;cursor:pointer;line-height:1;">
            &times;
          </button>
        </div>

        {{-- QUIZ TITLE --}}
        <div id="quizTitleBar"
          style="padding:14px 24px 12px;background:#ffffff;
      border-bottom:1px solid #dcfce7;">

          <label for="quizTitleInput"
            style="display:block;font-size:11px;font-weight:800;
        color:#15803d;text-transform:uppercase;
        letter-spacing:.04em;margin-bottom:6px;">
            Quiz Title
          </label>

          <input type="text" id="quizTitleInput" value=""
            placeholder="Enter quiz title" autocomplete="off"
            style="display:block;width:100%;box-sizing:border-box;
        border:0;border-bottom:2px solid #cbd5e1;
        background:#fff;font-size:25px;font-weight:500;
        color:#0f172a;outline:none;padding:3px 0 8px;"
            onfocus="this.style.borderBottomColor='#16a34a';"
            onblur="this.style.borderBottomColor='#cbd5e1';">

          <div style="font-size:11px;color:#94a3b8;margin-top:5px;">
            Enter a title trainees will recognize.
          </div>
        </div>

        <form id="addQuizForm" onsubmit="submitTrainerQuiz(event)"
          style="margin:0;display:flex;flex-direction:column;min-height:0;flex:1;">

          {{-- SCROLLABLE CONTENT BODY --}}
          <div
            style="padding:16px 24px;overflow-y:auto;
        display:flex;flex-direction:column;gap:14px;flex:1;">

            {{-- QUIZ PLACEMENT --}}
            <div
              style="border:1px solid #bbf7d0;border-radius:10px;
          background:#f0fdf4;padding:14px 15px;">

              <div
                style="display:flex;align-items:center;gap:8px;
            margin-bottom:10px;color:#166534;
            font-size:13px;font-weight:800;">
                <i class="fa-solid fa-link"></i>
                Quiz Placement
              </div>

              <div
                style="display:grid;
            grid-template-columns:1fr 1.5fr;
            gap:10px;">

                {{-- UNIT --}}
                <div>
                  <label for="quizUnitInput"
                    style="display:block;font-size:11px;
                font-weight:700;color:#475569;margin-bottom:5px;">
                    Unit
                  </label>

                  <select id="quizUnitInput"
                    onchange="populateQuizLessons(this.value)"
                    style="width:100%;box-sizing:border-box;
                border:1px solid #bbf7d0;border-radius:8px;
                background:#fff;padding:9px 10px;
                font-size:12.5px;color:#334155;outline:none;">
                    <option value="">Select a unit</option>
                    @foreach ($groupedUnits as $unitNum => $unitModules)
                      <option value="{{ $unitNum }}">
                        Unit {{ $unitNum }}
                      </option>
                    @endforeach
                  </select>
                </div>

                {{-- LESSON --}}
                <div>
                  <label for="quizLessonInput"
                    style="display:block;font-size:11px;
                font-weight:700;color:#475569;margin-bottom:5px;">
                    Lesson
                  </label>

                  <select id="quizLessonInput" disabled
                    onchange="quizPlacementChanged()"
                    style="width:100%;box-sizing:border-box;
                border:1px solid #bbf7d0;border-radius:8px;
                background:#fff;padding:9px 10px;
                font-size:12.5px;color:#334155;outline:none;">
                    <option value="">
                      Select a unit first
                    </option>
                  </select>
                </div>

              </div>

              <div id="quizPlacementHint"
                style="font-size:11px;color:#64748b;margin-top:8px;">
                Choose the Unit and Lesson where this quiz should appear.
              </div>
            </div>

            {{-- QUIZ SETTINGS --}}
            <div id="quizSettingsSection"
              style="border:1px solid #bbf7d0;
          border-radius:10px;
          background:#ffffff;
          padding:14px 15px;
          position:relative;
          z-index:5;
          flex-shrink:0;">

              <div
                style="display:flex;
            align-items:center;
            gap:8px;
            margin-bottom:12px;
            color:#166534;
            font-size:13px;
            font-weight:800;">
                <i class="fa-solid fa-sliders"></i>
                Quiz Settings
              </div>

              <div
                style="display:grid;
            grid-template-columns:1fr 1fr;
            gap:12px;">

                {{-- PASSING SCORE --}}
                <div>
                  <label for="quizPassingScore"
                    style="display:block;
                font-size:11px;
                font-weight:700;
                color:#475569;
                margin-bottom:5px;">
                    Passing Score (%)
                  </label>

                  <div style="position:relative;">
                    <input type="number" id="quizPassingScore"
                      name="passing_score" min="0" max="100"
                      step="1" value="75"
                      placeholder="Enter percentage" inputmode="numeric"
                      autocomplete="off"
                      style="width:100%;
                  box-sizing:border-box;
                  border:1px solid #bbf7d0;
                  border-radius:8px;
                  background:#fff;
                  padding:9px 40px 9px 10px;
                  font-size:12.5px;
                  color:#334155;
                  outline:none;">

                    <span
                      style="position:absolute;
                  right:11px;
                  top:50%;
                  transform:translateY(-50%);
                  font-size:12px;
                  font-weight:700;
                  color:#64748b;
                  pointer-events:none;">
                      %
                    </span>
                  </div>

                  <div
                    style="font-size:10.5px;
                color:#94a3b8;
                margin-top:5px;">
                    Enter a score from 0 to 100.
                  </div>
                </div>

                {{-- TIME LIMIT --}}
                <div>
                  <label for="quizTimeLimit"
                    style="display:block;
                font-size:11px;
                font-weight:700;
                color:#475569;
                margin-bottom:5px;">
                    Time Limit
                  </label>

                  <div style="position:relative;">
                    <input type="number" id="quizTimeLimit" name="time_limit"
                      min="0" step="1" value="30"
                      placeholder="Enter minutes" inputmode="numeric"
                      autocomplete="off"
                      style="width:100%;
                  box-sizing:border-box;
                  border:1px solid #bbf7d0;
                  border-radius:8px;
                  background:#fff;
                  padding:9px 70px 9px 10px;
                  font-size:12.5px;
                  color:#334155;
                  outline:none;">

                    <span
                      style="position:absolute;
                  right:11px;
                  top:50%;
                  transform:translateY(-50%);
                  font-size:12px;
                  font-weight:700;
                  color:#64748b;
                  pointer-events:none;">
                      minutes
                    </span>
                  </div>

                  <div
                    style="font-size:10.5px;
                color:#94a3b8;
                margin-top:5px;">
                    Enter 0 for no time limit.
                  </div>
                </div>

              </div>
            </div>

            {{-- INSTRUCTIONS --}}
            <div
              style="border:1px solid #e2e8f0;
          border-radius:12px;
          background:#ffffff;
          overflow:hidden;">

              <div style="padding:14px 15px 0;">
                <label
                  style="display:block;
              font-size:11.5px;
              font-weight:800;
              color:#334155;
              margin-bottom:7px;">
                  Instructions
                </label>

                <div id="quizInstructionsEditor" contenteditable="true"
                  data-placeholder="Add instructions for your trainees..."
                  style="min-height:72px;
              outline:none;
              color:#334155;
              font-size:13px;
              line-height:1.55;
              padding:4px 0;"
                  onfocus="if(this.innerText.trim()==='Add instructions for your trainees...') this.innerHTML='';">
                  Add instructions for your trainees...
                </div>

                {{-- Formatting Toolbar --}}
                <div
                  style="display:flex;
              align-items:center;
              gap:4px;
              padding:9px 0 7px;
              margin-top:8px;
              border-top:1px solid #f1f5f9;">

                  <button type="button" title="Bold"
                    onclick="formatQuizDoc('bold')"
                    style="border:none;background:none;width:30px;height:30px;border-radius:6px;cursor:pointer;color:#64748b;font-weight:800;"
                    onmouseover="this.style.background='#f0fdf4';this.style.color='#15803d';"
                    onmouseout="this.style.background='none';this.style.color='#64748b';">
                    B
                  </button>

                  <button type="button" title="Italic"
                    onclick="formatQuizDoc('italic')"
                    style="border:none;background:none;width:30px;height:30px;border-radius:6px;cursor:pointer;color:#64748b;font-style:italic;"
                    onmouseover="this.style.background='#f0fdf4';this.style.color='#15803d';"
                    onmouseout="this.style.background='none';this.style.color='#64748b';">
                    I
                  </button>

                  <button type="button" title="Underline"
                    onclick="formatQuizDoc('underline')"
                    style="border:none;background:none;width:30px;height:30px;border-radius:6px;cursor:pointer;color:#64748b;text-decoration:underline;"
                    onmouseover="this.style.background='#f0fdf4';this.style.color='#15803d';"
                    onmouseout="this.style.background='none';this.style.color='#64748b';">
                    U
                  </button>

                  <button type="button" title="Bullet List"
                    onclick="formatQuizDoc('insertUnorderedList')"
                    style="border:none;background:none;width:30px;height:30px;border-radius:6px;cursor:pointer;color:#64748b;"
                    onmouseover="this.style.background='#f0fdf4';this.style.color='#15803d';"
                    onmouseout="this.style.background='none';this.style.color='#64748b';">
                    <i class="fa-solid fa-list-ul"></i>
                  </button>
                </div>
              </div>
            </div>

            {{-- QUESTIONS --}}
            <div
              style="display:flex;
          align-items:center;
          justify-content:space-between;
          margin-top:2px;">

              <div>
                <div
                  style="font-size:13px;
              font-weight:800;
              color:#334155;">
                  Quiz Questions
                </div>

                <div
                  style="font-size:10.5px;
              color:#94a3b8;
              margin-top:2px;">
                  Add questions, answer choices, points, and answer keys.
                </div>
              </div>
            </div>

            <div id="quizQuestionsContainer"
              style="display:flex;
          flex-direction:column;
          gap:14px;">
            </div>

            {{-- ADD QUESTION --}}
            <button type="button" onclick="addQuizQuestion()"
              style="align-self:center;
          display:inline-flex;
          align-items:center;
          justify-content:center;
          gap:8px;
          border:1px solid #86efac;
          background:#f0fdf4;
          color:#15803d;
          border-radius:9px;
          padding:10px 17px;
          font-size:12.5px;
          font-weight:800;
          cursor:pointer;
          transition:.15s;"
              onmouseover="this.style.background='#dcfce7';this.style.borderColor='#4ade80';"
              onmouseout="this.style.background='#f0fdf4';this.style.borderColor='#86efac';">
              <i class="fa-solid fa-plus"></i>
              Add question
            </button>

          </div> {{-- Closes the scrollable padding container --}}

          {{-- FOOTER --}}
          <div
            style="padding:14px 24px;background:#f8fafc;
        border-top:1px solid #e2e8f0;
        display:flex;justify-content:space-between;
        align-items:center;gap:10px;">

            <div style="font-size:11.5px;color:#64748b;">
              <i class="fa-solid fa-circle-info"
                style="color:#16a34a;margin-right:4px;"></i>
              Answer keys are stored with each question.
            </div>

            <div style="display:flex;gap:10px;">
              <button type="button" onclick="closeAddQuizModal()"
                style="background:#fff;border:1px solid #cbd5e1;
            color:#475569;font-size:13px;font-weight:600;
            padding:8px 16px;border-radius:8px;cursor:pointer;">
                Cancel
              </button>

              <button type="submit" id="saveQuizBtn"
                style="display:inline-flex;align-items:center;
            gap:8px;background:#15803d;
            border:1px solid #15803d;color:#fff;
            font-size:13px;font-weight:700;
            padding:8px 20px;border-radius:8px;
            cursor:pointer;">
                <i class="fa-solid fa-check"></i>
                <span>Create Quiz</span>
              </button>
            </div>

          </div>

        </form>

      </div>
    </div>

    {{-- DELETE QUIZ CONFIRMATION MODAL --}}
    <div id="deleteQuizModal"
      style="display: none; position: fixed; z-index: 3300; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
      <div
        style="background: #ffffff; border-radius: 16px; width: 90%; max-width: 420px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15); box-sizing: border-box; overflow: hidden; text-align: center;">
        <div style="padding: 28px 24px 20px 24px;">
          <div
            style="width: 52px; height: 52px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 16px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
          </div>
          <h3
            style="margin: 0 0 8px 0; font-size: 17px; font-weight: 700; color: #0f172a;">
            Delete Quiz?</h3>
          <p
            style="margin: 0; font-size: 13.5px; color: #64748b; line-height: 1.5;">
            Are you sure you want to remove <strong id="deleteQuizTitleText"
              style="color: #1e293b;">this quiz</strong>? Trainees will no longer
            be able to submit responses.
          </p>
        </div>
        <div
          style="padding: 14px 20px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; gap: 10px;">
          <button type="button" onclick="closeDeleteQuizModal()"
            style="background: #ffffff; border: 1px solid #cbd5e1; color: #475569; font-size: 13px; font-weight: 600; padding: 8px 18px; border-radius: 8px; cursor: pointer;">
            Cancel
          </button>
          <button type="button" id="confirmDeleteQuizBtn"
            onclick="confirmDeleteQuizAction()"
            style="display: inline-flex; align-items: center; gap: 6px; background: #dc2626; border: 1px solid #dc2626; color: #ffffff; font-size: 13px; font-weight: 600; padding: 8px 20px; border-radius: 8px; cursor: pointer;">
            <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
            <span>Delete</span>
          </button>
        </div>
      </div>
    </div>

    {{-- ADD MODULE MODAL --}}
    <div id="addModuleModal"
      style="display: none; position: fixed; z-index: 3200; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
      <div
        style="background: #ffffff; border-radius: 16px; width: 95%; max-width: 540px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.12); box-sizing: border-box; overflow: hidden;">
        <div
          style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <div
              style="width: 36px; height: 36px; border-radius: 8px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #025628; display: flex; align-items: center; justify-content: center; font-size: 15px;">
              <i class="fa-solid fa-folder-plus"></i>
            </div>
            <div>
              <h3
                style="margin: 0; font-size: 16px; font-weight: 700; color: #0f172a;">
                New Curriculum Module</h3>
              <p style="margin: 1px 0 0 0; font-size: 12px; color: #64748b;">Add
                a structured unit lesson and attach resources</p>
            </div>
          </div>
          <button type="button" onclick="closeAddModuleModal()"
            style="background: transparent; border: none; color: #94a3b8; font-size: 18px; cursor: pointer; padding: 4px; border-radius: 6px;">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <form id="addModuleForm" onsubmit="submitTrainerModule(event)"
          style="margin: 0;" enctype="multipart/form-data">
          <div
            style="padding: 22px 24px; display: flex; flex-direction: column; gap: 16px;">
            <div>
              <label for="moduleUnitInput"
                style="display: block; font-size: 12.5px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                Unit / Chapter Number <span style="color: #e11d48;">*</span>
              </label>
              <div style="display: flex; align-items: center; gap: 10px;">
                <span
                  style="font-size: 13px; font-weight: 600; color: #64748b;">Unit</span>
                <input type="number" id="moduleUnitInput" min="1"
                  value="1" required
                  style="width: 80px; box-sizing: border-box; font-size: 13.5px; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; outline: none;">
                <span style="font-size: 12px; color: #94a3b8;">(Groups lessons
                  into Units for trainees)</span>
              </div>
            </div>

            <div>
              <label for="moduleTitleInput"
                style="display: block; font-size: 12.5px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                Module Title <span style="color: #e11d48;">*</span>
              </label>
              <input type="text" id="moduleTitleInput" required
                placeholder="e.g., Fundamentals of Bread Formulation"
                style="width: 100%; box-sizing: border-box; font-size: 13.5px; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; outline: none;">
            </div>

            <div>
              <div
                style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                <label for="moduleDescInput"
                  style="font-size: 12.5px; font-weight: 600; color: #334155;">Topic
                  Summary / Outline</label>
                <span style="font-size: 11px; color: #94a3b8;">Optional</span>
              </div>
              <textarea id="moduleDescInput" rows="2"
                placeholder="Brief overview of lessons, practical procedures, or instructions..."
                style="width: 100%; box-sizing: border-box; font-size: 13px; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 8px; padding: 9px 14px; outline: none; resize: vertical; line-height: 1.5; font-family: inherit;"></textarea>
            </div>

            <div>
              <div
                style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                <label
                  style="font-size: 12.5px; font-weight: 600; color: #334155;">Module
                  Resource (PDF Handout / Slides / Video)</label>
                <span style="font-size: 11px; color: #94a3b8;">Max 50MB</span>
              </div>

              <div id="fileUploadPlaceholder"
                onclick="document.getElementById('moduleFileInput').click()"
                style="border: 1.5px dashed #cbd5e1; border-radius: 10px; padding: 16px; text-align: center; cursor: pointer; background: #f8fafc; transition: all 0.2s;">
                <input type="file" id="moduleFileInput"
                  style="display: none;"
                  onchange="handleTrainerFileSelect(event)">
                <div
                  style="width: 36px; height: 36px; border-radius: 50%; background: #ffffff; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 6px;">
                  <i class="fa-solid fa-file-arrow-up"
                    style="font-size: 16px; color: #025628;"></i>
                </div>
                <div style="font-size: 13px; font-weight: 600; color: #1e293b;">
                  Click to select or upload a document</div>
                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                  Supports PDF, DOCX, PPTX, MP4, or archive files</div>
              </div>

              <div id="fileSelectedPreview"
                style="display: none; align-items: center; justify-content: space-between; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px;">
                <div
                  style="display: flex; align-items: center; gap: 10px; overflow: hidden;">
                  <div id="filePreviewIcon"
                    style="font-size: 20px; flex-shrink: 0;"></div>
                  <div
                    style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <div id="fileNameDisplay"
                      style="font-size: 13px; font-weight: 600; color: #1e293b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    </div>
                    <div id="fileSizeDisplay"
                      style="font-size: 11px; color: #64748b;"></div>
                  </div>
                </div>
                <button type="button" onclick="removeTrainerFile()"
                  title="Remove file"
                  style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px; font-size: 14px;">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </div>
            </div>
          </div>

          <div
            style="padding: 14px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
            <button type="button" onclick="closeAddModuleModal()"
              style="background: transparent; border: 1px solid #cbd5e1; color: #475569; font-size: 13px; font-weight: 600; padding: 8px 16px; border-radius: 8px; cursor: pointer;">
              Cancel
            </button>
            <button type="submit" id="saveModuleBtn"
              style="display: inline-flex; align-items: center; gap: 8px; background: #025628; border: 1px solid #025628; color: #ffffff; font-size: 13px; font-weight: 600; padding: 8px 20px; border-radius: 8px; cursor: pointer;">
              <span>Save Module</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- DELETE MODULE MODAL --}}
    <div id="deleteModuleModal"
      style="display: none; position: fixed; z-index: 3300; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
      <div
        style="background: #ffffff; border-radius: 16px; width: 90%; max-width: 420px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15); box-sizing: border-box; overflow: hidden; text-align: center;">
        <div style="padding: 28px 24px 20px 24px;">
          <div
            style="width: 52px; height: 52px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 16px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
          </div>
          <h3
            style="margin: 0 0 8px 0; font-size: 17px; font-weight: 700; color: #0f172a;">
            Delete Module?</h3>
          <p
            style="margin: 0; font-size: 13.5px; color: #64748b; line-height: 1.5;">
            Are you sure you want to remove <strong id="deleteModuleTitleText"
              style="color: #1e293b;">this module</strong>? Any attached
            resources will be permanently removed.
          </p>
        </div>
        <div
          style="padding: 14px 20px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; gap: 10px;">
          <button type="button" onclick="closeDeleteModuleModal()"
            style="background: #ffffff; border: 1px solid #cbd5e1; color: #475569; font-size: 13px; font-weight: 600; padding: 8px 18px; border-radius: 8px; cursor: pointer;">
            Cancel
          </button>
          <button type="button" id="confirmDeleteModuleBtn"
            onclick="confirmDeleteModuleAction()"
            style="display: inline-flex; align-items: center; gap: 6px; background: #dc2626; border: 1px solid #dc2626; color: #ffffff; font-size: 13px; font-weight: 600; padding: 8px 20px; border-radius: 8px; cursor: pointer;">
            <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
            <span>Delete</span>
          </button>
        </div>
      </div>
    </div>

    {{-- ANNOUNCEMENT MODAL --}}
    <div id="announcementModal"
      style="display: none; position: fixed; z-index: 3100; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
      <div
        style="background: #fff; border-radius: 16px; width: 95%; max-width: 680px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); box-sizing: border-box; display: flex; flex-direction: column; overflow: hidden;">
        <div
          style="padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
          <h2
            style="margin: 0; font-size: 17px; font-weight: 600; color: #1e293b;">
            Course Announcement</h2>
          <button type="button" onclick="closeAnnouncementModal()"
            style="border: none; background: none; color: #94a3b8; font-size: 18px; cursor: pointer;">&times;</button>
        </div>
        <div
          style="padding: 20px 24px; display: flex; flex-direction: column; gap: 16px;">
          <div>
            <span
              style="font-size: 12px; font-weight: 600; color: #64748b; display: block; margin-bottom: 6px;">Audience</span>
            <div
              style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
              <div
                style="display: inline-flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 12px; gap: 8px;">
                <span
                  style="font-size: 13px; color: #334155; font-weight: 600;">{{ $course->title }}</span>
                <i class="fa fa-caret-down"
                  style="color: #94a3b8; font-size: 12px;"></i>
              </div>
            </div>
          </div>
          <div
            style="background: #ffffff; border: 1.5px solid #025628; border-radius: 10px; padding: 12px 14px 8px 14px; box-sizing: border-box;">
            <label
              style="font-size: 11.5px; color: #025628; font-weight: 600; display: block; margin-bottom: 6px; text-transform: uppercase;">
              Announce something to your class
            </label>
            <div id="announcementEditor" contenteditable="true"
              style="min-height: 120px; outline: none; font-size: 14px; color: #1e293b; line-height: 1.5; overflow-y: auto; padding-bottom: 12px;"
              oninput="handleAnnouncementInput()">
            </div>
            <div
              style="display: flex; align-items: center; gap: 6px; padding-top: 8px; border-top: 1px solid #f1f5f9;">
              <button type="button" title="Bold" onclick="formatDoc('bold')"
                style="border: none; background: none; width: 28px; height: 28px; border-radius: 4px; cursor: pointer; color: #64748b; font-weight: bold; font-size: 13px;"><b>B</b></button>
              <button type="button" title="Italic"
                onclick="formatDoc('italic')"
                style="border: none; background: none; width: 28px; height: 28px; border-radius: 4px; cursor: pointer; color: #64748b; font-style: italic; font-size: 13px;"><i>I</i></button>
              <button type="button" title="Underline"
                onclick="formatDoc('underline')"
                style="border: none; background: none; width: 28px; height: 28px; border-radius: 4px; cursor: pointer; color: #64748b; text-decoration: underline; font-size: 13px;">U</button>
              <button type="button" title="Bullet List"
                onclick="formatDoc('insertUnorderedList')"
                style="border: none; background: none; width: 28px; height: 28px; border-radius: 4px; cursor: pointer; color: #64748b; font-size: 13px;"><i
                  class="fa fa-list-ul"></i></button>
              <button type="button" title="Remove Formatting"
                onclick="formatDoc('removeFormat')"
                style="border: none; background: none; width: 28px; height: 28px; border-radius: 4px; cursor: pointer; color: #64748b; font-size: 13px;"><i
                  class="fa fa-text-slash"></i></button>
            </div>
          </div>
        </div>
        <div
          style="padding: 14px 24px; display: flex; justify-content: flex-end; align-items: center; gap: 10px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
          <button type="button" onclick="closeAnnouncementModal()"
            style="background: transparent; border: none; color: #64748b; font-size: 13.5px; font-weight: 600; padding: 8px 16px; border-radius: 6px; cursor: pointer;">
            Cancel
          </button>
          <div id="postBtnGroup"
            style="display: inline-flex; border-radius: 6px; overflow: hidden; background: #e2e8f0; transition: background 0.2s ease;">
            <button type="button" id="postSubmitBtn"
              onclick="submitAnnouncement()" disabled
              style="border: none; background: transparent; color: #94a3b8; padding: 8px 18px; font-size: 13.5px; font-weight: 600; cursor: not-allowed; border-right: 1px solid #cbd5e1;">
              Post
            </button>
            <button type="button" id="postDropdownBtn" disabled
              style="border: none; background: transparent; color: #94a3b8; padding: 8px 10px; font-size: 11px; cursor: not-allowed;">
              <i class="fa fa-caret-down"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- CUSTOMIZE MODAL --}}
    <div id="customizeModal"
      style="display: none; position: fixed; z-index: 3000; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
      <div
        style="background: #fff; border-radius: 16px; width: 95%; max-width: 580px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); box-sizing: border-box;">
        <div
          style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
          <h2
            style="margin: 0; font-size: 18px; font-weight: 600; color: #1e293b;">
            Customize appearance</h2>
          <button type="button" onclick="closeCustomizeModal()"
            style="border: none; background: none; color: #94a3b8; font-size: 18px; cursor: pointer;">&times;</button>
        </div>
        <div id="modalBannerPreview"
          style="position: relative; background: linear-gradient(135deg, #025628 0%, #0f766e 100%); border-radius: 12px; height: 120px; margin-bottom: 20px; overflow: hidden;">
          <div id="modalBannerImageLayer"
            style="position: absolute; inset: 0; background: url('{{ !empty($course->banner_image) ? asset($course->banner_image) : '' }}') center/cover no-repeat; opacity: 0.45; {{ empty($course->banner_image) ? 'display:none;' : '' }}">
          </div>
        </div>
        <div
          style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
          <span
            style="font-size: 13.5px; color: #475569; font-weight: 500;">Select
            stream header image</span>
          <div style="display: flex; gap: 8px;">
            <label
              style="display: inline-flex; align-items: center; gap: 6px; background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px; padding: 7px 14px; font-size: 13px; font-weight: 600; cursor: pointer; margin: 0;">
              <i class="fa fa-arrow-up-from-bracket"></i> Upload photo
              <input type="file" id="bannerPhotoInput" accept="image/*"
                style="display: none;" onchange="handleImageUpload(event)">
            </label>
          </div>
        </div>
        <div
          style="display: flex; justify-content: flex-end; gap: 8px; align-items: center;">
          <button type="button" onclick="closeCustomizeModal()"
            style="background: transparent; border: none; color: #64748b; font-size: 13.5px; font-weight: 600; cursor: pointer; padding: 8px 16px; border-radius: 6px;">
            Cancel
          </button>
          <button type="button" onclick="saveAppearance()"
            style="background: #025628; border: none; color: #ffffff; font-size: 13.5px; font-weight: 600; cursor: pointer; padding: 8px 18px; border-radius: 6px;">
            Save
          </button>
        </div>
      </div>
    </div>

    {{-- ALERT MODAL --}}
    <div id="mcAlertModal"
      style="display: none; position: fixed; z-index: 3400; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
      <div
        style="background: #fff; margin: auto; border-radius: 14px; width: 90%; max-width: 380px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15);">
        <div
          style="background: #ffffff; padding: 18px 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
          <h3
            style="color: #025628; margin: 0; font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-circle-info"></i> Notice
          </h3>
          <span onclick="closeAlertModal()"
            style="font-size: 20px; color: #94a3b8; cursor: pointer; line-height: 1;">&times;</span>
        </div>
        <div style="padding: 24px 20px; text-align: center;">
          <p id="mcAlertMessage"
            style="font-size: 14px; color: #334155; margin: 0; line-height: 1.5;">
          </p>
        </div>
        <div
          style="padding: 12px 20px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: center;">
          <button type="button" onclick="closeAlertModal()"
            style="background: #025628; color: #fff; border: none; border-radius: 6px; padding: 8px 24px; font-size: 13px; font-weight: 600; cursor: pointer;">
            OK
          </button>
        </div>
      </div>
    </div>

  </div>
@endsection

@section('scripts')
  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')
      ?.getAttribute('content');
    let uploadedFile = null;
    let selectedPresetPath = null;
    let editingAnnouncementId = null;

    /* Classroom View Tabs Switcher */
    function switchClassroomTab(tab) {
      const streamBtn = document.getElementById('tabStreamBtn');
      const classworkBtn = document.getElementById('tabClassworkBtn');
      const streamContent = document.getElementById('tabContentStream');
      const classworkContent = document.getElementById('tabContentClasswork');

      if (tab === 'stream') {
        streamBtn.classList.add('active');
        classworkBtn.classList.remove('active');
        streamContent.style.display = 'flex';
        classworkContent.style.display = 'none';
      } else {
        classworkBtn.classList.add('active');
        streamBtn.classList.remove('active');
        streamContent.style.display = 'none';
        classworkContent.style.display = 'flex';
      }
    }

    function showAlert(message) {
      document.getElementById('mcAlertMessage').textContent = message;
      document.getElementById('mcAlertModal').style.display = 'flex';
    }

    function closeAlertModal() {
      document.getElementById('mcAlertModal').style.display = 'none';
    }

    function toggleAnnouncementMenu(event, menuId) {
      event.stopPropagation();
      const currentMenu = document.getElementById(menuId);
      const willOpen = currentMenu.style.display !== 'block';
      document.querySelectorAll('.announcement-dropdown-menu').forEach(m => m
        .style.display = 'none');
      if (willOpen) currentMenu.style.display = 'block';
    }

    document.addEventListener('click', () => {
      document.querySelectorAll('.announcement-dropdown-menu').forEach(m => m
        .style.display = 'none');
    });

    function openAnnouncementModal() {
      document.getElementById('announcementModal').style.display = 'flex';
      document.getElementById('announcementEditor').focus();
    }

    function closeAnnouncementModal() {
      document.getElementById('announcementModal').style.display = 'none';
      editingAnnouncementId = null;
    }

    function formatDoc(command) {
      document.execCommand(command, false, null);
    }

    function handleAnnouncementInput() {
      const editor = document.getElementById('announcementEditor');
      const text = editor.innerText.trim();
      const postBtnGroup = document.getElementById('postBtnGroup');
      const postSubmitBtn = document.getElementById('postSubmitBtn');
      const postDropdownBtn = document.getElementById('postDropdownBtn');

      if (text.length > 0) {
        postBtnGroup.style.background = '#025628';
        postSubmitBtn.disabled = false;
        postSubmitBtn.style.color = '#ffffff';
        postSubmitBtn.style.cursor = 'pointer';
        postDropdownBtn.disabled = false;
        postDropdownBtn.style.color = '#ffffff';
        postDropdownBtn.style.cursor = 'pointer';
      } else {
        postBtnGroup.style.background = '#e2e8f0';
        postSubmitBtn.disabled = true;
        postSubmitBtn.style.color = '#94a3b8';
        postSubmitBtn.style.cursor = 'not-allowed';
        postDropdownBtn.disabled = true;
        postDropdownBtn.style.color = '#94a3b8';
        postDropdownBtn.style.cursor = 'not-allowed';
      }
    }

    function editAnnouncement(id) {
      const card = document.querySelector(`.announcement-card[data-id="${id}"]`);
      const contentEl = card ? card.querySelector(
          '[style*="word-break:break-word"], [style*="word-break: break-word"]') :
        null;
      editingAnnouncementId = id;
      const editor = document.getElementById('announcementEditor');
      editor.innerHTML = contentEl ? contentEl.innerHTML.trim() : '';
      handleAnnouncementInput();
      openAnnouncementModal();
    }

    function deleteAnnouncement(id) {
      if (!confirm('Are you sure you want to delete this announcement?')) return;
      fetch(`/trainer/course/announcements/${id}`, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          }
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            document.querySelector(`.announcement-card[data-id="${id}"]`)
              ?.remove();
            showAlert(data.message || 'Announcement deleted successfully.');
          } else {
            showAlert(data.message || 'Failed to delete announcement.');
          }
        })
        .catch(err => showAlert(err.message || 'Failed to delete announcement.'));
    }

    function submitAnnouncement() {
      const editor = document.getElementById('announcementEditor');
      const content = editor.innerHTML.trim();
      if (!content || content === '<br>') {
        showAlert('Please enter an announcement before posting.');
        return;
      }

      const url = editingAnnouncementId ?
        `/trainer/course/announcements/${editingAnnouncementId}` :
        `{{ route('trainer.course.announcements.store', $course->id) }}`;
      const method = editingAnnouncementId ? 'PUT' : 'POST';

      fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            content: content,
            target_audience: 'all_students'
          })
        })
        .then(async r => {
          const d = await r.json();
          if (!r.ok) throw new Error(d.message || 'Server error');
          return d;
        })
        .then(data => {
          closeAnnouncementModal();
          location.reload();
        })
        .catch(err => showAlert('Error: ' + err.message));
    }

    function openCommentBox(id) {
      document.getElementById(`commentTriggerBox-${id}`).style.display = 'none';
      const box = document.getElementById(`commentInputBox-${id}`);
      box.style.display = 'flex';
      document.getElementById(`commentEditor-${id}`).focus();
    }

    function formatCommentText(command) {
      document.execCommand(command, false, null);
    }

    function handleCommentInput(id) {
      const editor = document.getElementById(`commentEditor-${id}`);
      const sendBtn = document.getElementById(`commentSendBtn-${id}`);
      const hasText = editor.innerText.trim().length > 0;
      sendBtn.disabled = !hasText;
      sendBtn.style.color = hasText ? '#ffffff' : '#94a3b8';
      sendBtn.style.background = hasText ? '#025628' : '#f1f5f9';
      sendBtn.style.cursor = hasText ? 'pointer' : 'not-allowed';
    }

    function submitComment(announcementId) {
      const editor = document.getElementById(`commentEditor-${announcementId}`);
      const content = editor.innerHTML.trim();
      if (!content || content === '<br>') return;

      const sendBtn = document.getElementById(`commentSendBtn-${announcementId}`);
      sendBtn.disabled = true;

      fetch(`/trainer/announcements/${announcementId}/comments`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            comment: content
          })
        })
        .then(r => r.json())
        .then(() => location.reload())
        .catch(err => showAlert(err.message || 'Failed to post comment.'));
    }

    function openCustomizeModal() {
      document.getElementById('customizeModal').style.display = 'flex';
    }

    function closeCustomizeModal() {
      document.getElementById('customizeModal').style.display = 'none';
    }

    function handleImageUpload(e) {
      const file = e.target.files[0];
      if (!file) return;
      uploadedFile = file;
      const reader = new FileReader();
      reader.onload = (evt) => {
        const preview = document.getElementById('modalBannerImageLayer');
        preview.style.backgroundImage = `url('${evt.target.result}')`;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }

    function saveAppearance() {
      if (!uploadedFile) return closeCustomizeModal();
      const formData = new FormData();
      formData.append('banner_image', uploadedFile);

      fetch(`{{ route('trainer.course.appearance', $course->id) }}`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            const bannerLayer = document.getElementById('bannerImageLayer');
            bannerLayer.style.backgroundImage = `url('${data.banner_url}')`;
            bannerLayer.style.display = 'block';
            closeCustomizeModal();
            showAlert('Header image updated successfully!');
          }
        })
        .catch(err => showAlert(err.message || 'Failed to update appearance.'));
    }

    function saveDescription() {
      fetch(`/trainer/course/{{ $course->id }}/description`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            description: document.getElementById('descriptionInput').value
          })
        })
        .then(r => r.json())
        .then(data => data.success && showAlert(
          'Description updated successfully!'))
        .catch(() => showAlert('Something went wrong.'));
    }

    function saveObjectives() {
      fetch(`/trainer/course/{{ $course->id }}/objectives`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            objectives: document.getElementById('objectivesInput').value
          })
        })
        .then(r => r.json())
        .then(data => data.success && showAlert(
          'Objectives updated successfully!'))
        .catch(() => showAlert('Something went wrong.'));
    }

    /* ==========================================================================
       NATIVE QUIZ MAKER HANDLERS
       ========================================================================== */
    let targetQuizIdToDelete = null;
    let quizQuestions = [];
    let answerKeyOpen = null;

    function newQuestion(type = 'multiple_choice') {
      return {
        id: 'q_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7),
        type,
        text: '',
        required: false,
        points: 0,
        options: type === 'multiple_choice' || type === 'checkboxes' ? [
          'Option 1', 'Option 2'
        ] : [],
        correctAnswers: [],
        feedback: ''
      };
    }

    const quizLessonOptions = @json($quizLessonOptions);

    function openAddQuizModal() {

      // Reset edit state
      window.editingQuizId = null;

      // Reset quiz questions
      quizQuestions = [newQuestion('multiple_choice')];
      answerKeyOpen = null;

      // Reset title
      const titleInput = document.getElementById('quizTitleInput');
      if (titleInput) {
        titleInput.value = '';
      }

      // Reset placement
      const unitInput = document.getElementById('quizUnitInput');
      if (unitInput) {
        unitInput.value = '';
      }

      const lessonInput = document.getElementById('quizLessonInput');

      if (lessonInput) {
        lessonInput.innerHTML =
          '<option value="">Select a unit first</option>';

        lessonInput.disabled = true;
        lessonInput.value = '';
      }

      const placementHint =
        document.getElementById('quizPlacementHint');

      if (placementHint) {
        placementHint.textContent =
          'Choose the Unit and Lesson where this quiz should appear.';
      }

      // RESET QUIZ SETTINGS
      const passingScoreInput =
        document.getElementById('quizPassingScore');

      const timeLimitInput =
        document.getElementById('quizTimeLimit');

      if (passingScoreInput) {
        passingScoreInput.value = '';
      }

      if (timeLimitInput) {
        timeLimitInput.value = '';
      }

      // Reset instructions
      const instructionsEditor =
        document.getElementById('quizInstructionsEditor');

      if (instructionsEditor) {
        instructionsEditor.innerHTML =
          'Add instructions for your trainees...';
      }

      // Modal title
      const modalTitle =
        document.getElementById('quizModalTitle');

      if (modalTitle) {
        modalTitle.textContent = 'Create Quiz';
      }

      // Save button
      const saveBtn =
        document.getElementById('saveQuizBtn');

      if (saveBtn) {
        saveBtn.disabled = false;

        saveBtn.innerHTML =
          '<i class="fa-solid fa-check"></i>' +
          '<span>Create Quiz</span>';
      }

      // Show modal
      document.getElementById('addQuizModal').style.display = 'flex';

      // Scroll to top
      const scrollArea =
        document.querySelector('#addQuizForm > div');

      if (scrollArea) {
        scrollArea.scrollTop = 0;
      }

      renderQuizQuestions();

      setTimeout(() => {
        titleInput?.focus();
      }, 50);
    }

    function openEditQuizModal(quizId) {

      if (!quizId) {
        showAlert('Invalid quiz ID.');
        return;
      }

      const modal = document.getElementById('addQuizModal');

      if (!modal) {
        showAlert('Quiz editor could not be found.');
        return;
      }

      // =========================================================
      // OPEN MODAL
      // =========================================================

      modal.style.display = 'flex';

      // Store quiz ID globally
      window.editingQuizId = quizId;

      // Also update hidden input if it exists
      const quizIdInput = document.getElementById('quizIdInput');

      if (quizIdInput) {
        quizIdInput.value = quizId;
      }

      // =========================================================
      // SHOW LOADING STATE
      // =========================================================

      const container =
        document.getElementById('quizQuestionsContainer');

      if (container) {

        container.innerHTML = `
            <div style="
                padding:40px 20px;
                text-align:center;
                color:#64748b;
            ">
                <i class="fa-solid fa-spinner fa-spin"
                   style="
                       font-size:24px;
                       color:#15803d;
                       margin-bottom:10px;
                   ">
                </i>

                <div style="
                    font-size:13px;
                    font-weight:600;
                ">
                    Loading quiz...
                </div>
            </div>
        `;
      }

      // =========================================================
      // FETCH QUIZ
      // =========================================================

      fetch(`/trainer/quiz/${quizId}/edit`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })

        .then(async response => {

          const data = await response.json().catch(() => ({}));

          if (!response.ok) {
            throw new Error(
              data.message || 'Failed to load quiz.'
            );
          }

          if (!data.success || !data.quiz) {
            throw new Error(
              data.message || 'Quiz data was not found.'
            );
          }

          return data.quiz;
        })

        .then(quiz => {

          console.log('Loaded quiz:', quiz);

          // =====================================================
          // STORE EDITING ID
          // =====================================================

          window.editingQuizId = quiz.id;

          if (typeof editingQuizId !== 'undefined') {
            editingQuizId = quiz.id;
          }

          if (quizIdInput) {
            quizIdInput.value = quiz.id;
          }

          // =====================================================
          // MODAL TITLE
          // =====================================================

          const modalTitle =
            document.getElementById('quizModalTitle');

          if (modalTitle) {
            modalTitle.textContent = 'Edit Quiz';
          }

          // =====================================================
          // SAVE BUTTON
          // =====================================================

          const saveBtn =
            document.getElementById('saveQuizBtn');

          if (saveBtn) {

            saveBtn.disabled = false;

            saveBtn.innerHTML = `
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save Changes</span>
            `;
          }

          // =====================================================
          // QUIZ TITLE
          // =====================================================

          const titleInput =
            document.getElementById('quizTitleInput');

          if (titleInput) {
            titleInput.value = quiz.title || '';
          }

          // =====================================================
          // PASSING SCORE
          // =====================================================

          const passingScoreInput =
            document.getElementById('quizPassingScore');

          if (passingScoreInput) {

            passingScoreInput.value =
              quiz.passing_score !== null &&
              quiz.passing_score !== undefined ?
              quiz.passing_score :
              75;
          }

          // =====================================================
          // TIME LIMIT
          // =====================================================

          const timeLimitInput =
            document.getElementById('quizTimeLimit');

          if (timeLimitInput) {

            timeLimitInput.value =
              quiz.time_limit !== null &&
              quiz.time_limit !== undefined ?
              quiz.time_limit :
              30;
          }

          // =====================================================
          // INSTRUCTIONS
          // =====================================================

          const instructionsEditor =
            document.getElementById('quizInstructionsEditor');

          if (instructionsEditor) {

            instructionsEditor.innerHTML =
              quiz.instructions || '';
          }

          // =====================================================
          // UNIT / LESSON
          // =====================================================

          const lessonInput =
            document.getElementById('quizLessonInput');

          const unitInput =
            document.getElementById('quizUnitInput');

          let selectedUnit = '';

          if (
            quiz.module &&
            quiz.module.unit_number !== undefined &&
            quiz.module.unit_number !== null
          ) {

            selectedUnit =
              quiz.module.unit_number;
          }

          if (
            !selectedUnit &&
            quiz.unit_number !== undefined &&
            quiz.unit_number !== null
          ) {

            selectedUnit =
              quiz.unit_number;
          }

          // Set Unit
          if (unitInput) {

            unitInput.value =
              selectedUnit || '';
          }

          // Enable lesson dropdown
          if (lessonInput) {

            lessonInput.disabled = !selectedUnit;
          }

          // Populate lessons
          if (
            selectedUnit &&
            typeof populateQuizLessons === 'function'
          ) {

            populateQuizLessons(selectedUnit);
          }

          // Select lesson after options have been populated
          setTimeout(() => {

            if (
              lessonInput &&
              quiz.module_id
            ) {

              lessonInput.value =
                quiz.module_id;
            }

            if (
              typeof quizPlacementChanged === 'function'
            ) {

              quizPlacementChanged();
            }

          }, 150);

          // =====================================================
          // CONVERT QUESTIONS
          // =====================================================

          quizQuestions =
            (quiz.questions || []).map(
              (question, index) => {

                let options = [];

                // -----------------------------------------
                // NEW JSON OPTIONS
                // -----------------------------------------

                if (Array.isArray(question.options)) {

                  options =
                    question.options;
                }

                // -----------------------------------------
                // FALLBACK TO OLD A-D FIELDS
                // -----------------------------------------

                if (!options.length) {

                  options = [
                    question.choice_a,
                    question.choice_b,
                    question.choice_c,
                    question.choice_d
                  ].filter(option =>
                    option !== null &&
                    option !== undefined &&
                    String(option).trim() !== ''
                  );
                }

                // -----------------------------------------
                // CORRECT ANSWERS
                // -----------------------------------------

                let correctAnswers = [];

                if (
                  Array.isArray(
                    question.correct_answers
                  )
                ) {

                  correctAnswers =
                    question.correct_answers.map(
                      answer => Number(answer)
                    );

                } else if (
                  question.correct_answer
                ) {

                  const oldAnswerMap = {
                    a: 0,
                    b: 1,
                    c: 2,
                    d: 3
                  };

                  const oldAnswer =
                    String(
                      question.correct_answer
                    ).toLowerCase();

                  if (
                    oldAnswerMap[oldAnswer] !== undefined
                  ) {

                    correctAnswers = [
                      oldAnswerMap[oldAnswer]
                    ];
                  }
                }

                // -----------------------------------------
                // RETURN BUILDER FORMAT
                // -----------------------------------------

                return {

                  id: 'q_' +
                    Date.now() +
                    '_' +
                    index,

                  type: question.question_type ||
                    'multiple_choice',

                  text: question.question || '',

                  required: Boolean(question.required),

                  points: Number(
                    question.points || 1
                  ),

                  options: options,

                  correctAnswers: correctAnswers,

                  feedback: question.feedback || ''
                };
              }
            );

          // =====================================================
          // IF NO QUESTIONS
          // =====================================================

          if (!quizQuestions.length) {

            quizQuestions = [
              newQuestion('multiple_choice')
            ];
          }

          // =====================================================
          // RESET ANSWER KEY
          // =====================================================

          answerKeyOpen = null;

          // =====================================================
          // RENDER QUESTIONS
          // =====================================================

          renderQuizQuestions();

          // =====================================================
          // SCROLL TO TOP
          // =====================================================

          const scrollArea =
            document.querySelector(
              '#addQuizForm > div'
            );

          if (scrollArea) {

            scrollArea.scrollTop = 0;
          }

          // =====================================================
          // FOCUS TITLE
          // =====================================================

          setTimeout(() => {

            if (titleInput) {

              titleInput.focus();

              // Put cursor at the end
              try {

                titleInput.setSelectionRange(
                  titleInput.value.length,
                  titleInput.value.length
                );

              } catch (e) {}
            }

          }, 200);

        })

        .catch(error => {

          console.error(
            'Error loading quiz:',
            error
          );

          modal.style.display = 'none';

          showAlert(
            error.message ||
            'Unable to load quiz.'
          );
        });
    }



    function populateQuizLessons(unit) {
      const lesson = document.getElementById('quizLessonInput');
      if (!lesson) return;
      const items = quizLessonOptions.filter(x => String(x.unit) === String(
        unit));
      lesson.innerHTML = '<option value="">Select a lesson</option>' + items.map(
        x => `<option value="${x.id}">${escapeHtml(x.title)}</option>`).join('');
      lesson.disabled = items.length === 0;
      document.getElementById('quizPlacementHint').textContent = items.length ?
        'This quiz will be attached to the selected lesson.' :
        'No lessons are available in this unit yet.';
    }

    function quizPlacementChanged() {
      const lessonId = document.getElementById('quizLessonInput').value;
      const lesson = quizLessonOptions.find(x => String(x.id) === String(
        lessonId));
      document.getElementById('quizPlacementHint').textContent = lesson ?
        `Quiz will be linked to Unit ${lesson.unit} • ${lesson.title}` :
        'Choose the Unit and Lesson where this quiz should appear.';
    }

    function closeAddQuizModal() {

      const modal =
        document.getElementById('addQuizModal');

      if (modal) {
        modal.style.display = 'none';
      }


      const titleInput =
        document.getElementById('quizTitleInput');

      if (titleInput) {
        titleInput.value = '';
      }


      const unitInput =
        document.getElementById('quizUnitInput');

      if (unitInput) {
        unitInput.value = '';
      }


      const lessonInput =
        document.getElementById('quizLessonInput');

      if (lessonInput) {

        lessonInput.innerHTML =
          '<option value="">Select a unit first</option>';

        lessonInput.disabled = true;
        lessonInput.value = '';
      }


      const passingScoreInput =
        document.getElementById('quizPassingScore');

      const timeLimitInput =
        document.getElementById('quizTimeLimit');


      if (passingScoreInput) {
        passingScoreInput.value = '';
      }


      if (timeLimitInput) {
        timeLimitInput.value = '';
      }


      const instructionsEditor =
        document.getElementById(
          'quizInstructionsEditor'
        );

      if (instructionsEditor) {
        instructionsEditor.innerHTML =
          'Add instructions for your trainees...';
      }


      const placementHint =
        document.getElementById(
          'quizPlacementHint'
        );

      if (placementHint) {

        placementHint.textContent =
          'Choose the Unit and Lesson where this quiz should appear.';
      }


      quizQuestions = [];

      answerKeyOpen = null;


      const questionsContainer =
        document.getElementById(
          'quizQuestionsContainer'
        );

      if (questionsContainer) {
        questionsContainer.innerHTML = '';
      }


      // IMPORTANT
      window.editingQuizId = null;


      const modalTitle =
        document.getElementById(
          'quizModalTitle'
        );

      if (modalTitle) {
        modalTitle.textContent =
          'Create Quiz';
      }


      const saveBtn =
        document.getElementById(
          'saveQuizBtn'
        );

      if (saveBtn) {

        saveBtn.disabled = false;

        saveBtn.innerHTML =
          '<i class="fa-solid fa-check"></i>' +
          '<span>Create Quiz</span>';
      }
    }

    function formatQuizDoc(command) {
      document.execCommand(command, false, null);
    }

    function addQuizQuestion(type = 'multiple_choice') {
      quizQuestions.push(newQuestion(type));
      renderQuizQuestions();
    }

    function removeQuizQuestion(index) {
      if (quizQuestions.length === 1) {
        showAlert('A quiz must have at least one question.');
        return;
      }
      quizQuestions.splice(index, 1);
      answerKeyOpen = null;
      renderQuizQuestions();
    }

    function duplicateQuizQuestion(index) {
      const copy = JSON.parse(JSON.stringify(quizQuestions[index]));
      copy.id = 'q_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7);
      quizQuestions.splice(index + 1, 0, copy);
      renderQuizQuestions();
    }

    function changeQuestionType(index, type) {
      const q = quizQuestions[index];
      q.type = type;
      if (type === 'multiple_choice' || type === 'checkboxes') {
        if (!q.options.length) q.options = ['Option 1', 'Option 2'];
      } else {
        q.options = [];
        q.correctAnswers = [];
      }
      renderQuizQuestions();
    }

    function escapeHtml(value) {
      return String(value ?? '').replace(/[&<>"']/g, c => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      } [c]));
    }

    function renderQuizQuestions() {
      const container = document.getElementById('quizQuestionsContainer');
      if (!container) return;
      container.innerHTML = quizQuestions.map((q, i) => {
        const isChoice = q.type === 'multiple_choice' || q.type ===
          'checkboxes';
        const typeLabel = {
          multiple_choice: 'Multiple choice',
          checkboxes: 'Checkboxes',
          short_answer: 'Short answer',
          paragraph: 'Paragraph'
        } [q.type];
        const answerOpen = answerKeyOpen === i;
        return `
          <div class="native-question-card" style="background:#fff;border:1px solid #dbe4df;border-left:4px solid #22c55e;border-radius:10px;overflow:hidden;box-shadow:0 1px 2px rgba(15,23,42,.04);">
            <div style="padding:15px 16px 12px;">
              <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:12px;">
                <div style="font-size:11px;font-weight:800;color:#15803d;text-transform:uppercase;letter-spacing:.04em;">Question ${i+1}</div>
                <div style="display:flex;gap:3px;">
                  <button type="button" onclick="duplicateQuizQuestion(${i})" title="Duplicate" style="border:0;background:transparent;color:#94a3b8;width:30px;height:30px;border-radius:6px;cursor:pointer;"><i class="fa-regular fa-copy"></i></button>
                  <button type="button" onclick="removeQuizQuestion(${i})" title="Delete" style="border:0;background:transparent;color:#94a3b8;width:30px;height:30px;border-radius:6px;cursor:pointer;"><i class="fa-regular fa-trash-can"></i></button>
                </div>
              </div>
              <div style="display:grid;grid-template-columns:minmax(0,1fr) 185px;gap:10px;align-items:start;">
                <input value="${escapeHtml(q.text)}" oninput="quizQuestions[${i}].text=this.value" placeholder="Question" aria-label="Question ${i+1}" style="width:100%;box-sizing:border-box;border:0;border-bottom:2px solid #e2e8f0;padding:8px 2px;font-size:16px;outline:none;color:#0f172a;">
                <select onchange="changeQuestionType(${i},this.value)" style="width:100%;box-sizing:border-box;border:1px solid #cbd5e1;border-radius:7px;padding:9px 10px;font-size:12.5px;background:#fff;color:#334155;outline:none;">
                  <option value="short_answer" ${q.type==='short_answer'?'selected':''}>Short answer</option>
                  <option value="paragraph" ${q.type==='paragraph'?'selected':''}>Paragraph</option>
                  <option value="multiple_choice" ${q.type==='multiple_choice'?'selected':''}>Multiple choice</option>
                  <option value="checkboxes" ${q.type==='checkboxes'?'selected':''}>Checkboxes</option>
                </select>
              </div>
              ${isChoice ? `
                                                                                      <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px;">
                                                                                        ${q.options.map((opt,oi)=>`<div style="display:flex;align-items:center;gap:9px;">
                    <span style="width:18px;height:18px;border:2px solid #cbd5e1;${q.type==='multiple_choice'?'border-radius:50%;':'border-radius:4px;'}box-sizing:border-box;flex:none;"></span>
                    <input value="${escapeHtml(opt)}" oninput="quizQuestions[${i}].options[${oi}]=this.value" aria-label="Question ${i+1} option ${oi+1}" style="flex:1;border:0;border-bottom:1px solid #e2e8f0;padding:5px 2px;font-size:13px;outline:none;">
                    <button type="button" onclick="removeQuizOption(${i},${oi})" style="border:0;background:none;color:#94a3b8;cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
                  </div>`).join('')}
                                                                                        <button type="button" onclick="addQuizOption(${i})" style="display:inline-flex;align-items:center;gap:7px;align-self:flex-start;border:1px solid #86efac;background:#f0fdf4;color:#15803d;border-radius:7px;font-size:12.5px;font-weight:800;cursor:pointer;padding:7px 11px;margin-top:2px;"><i class="fa-solid fa-plus"></i> Add option</button>
                                                                                      </div>` : `
                                                                                      <div style="margin-top:12px;padding:11px 12px;border:1px dashed #cbd5e1;border-radius:7px;color:#94a3b8;font-size:12px;">${q.type==='paragraph'?'Long answer response area':'Short answer response'}</div>`}
            </div>
            <div style="border-top:1px solid #f1f5f9;padding:9px 14px;display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
              <button type="button" onclick="toggleAnswerKey(${i})" style="display:inline-flex;align-items:center;gap:7px;border:0;background:${answerOpen?'#dcfce7':'transparent'};color:#15803d;border-radius:7px;padding:8px 10px;font-size:12.5px;font-weight:800;cursor:pointer;">
                <i class="fa-solid fa-square-check"></i> ${answerOpen?'Hide answer key':'Answer key'}
              </button>
              <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:#475569;cursor:pointer;">
                <input type="checkbox" ${q.required?'checked':''} onchange="quizQuestions[${i}].required=this.checked" style="accent-color:#16a34a;"> Required
              </label>
            </div>
            ${answerOpen ? renderAnswerKey(i,q) : ''}
          </div>`;
      }).join('');
    }

    function renderAnswerKey(i, q) {
      const isChoice = q.type === 'multiple_choice' || q.type === 'checkboxes';
      return `<div style="border-top:1px solid #bbf7d0;background:#f0fdf4;padding:15px 16px 16px;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:13px;">
          <div style="display:flex;align-items:center;gap:8px;color:#166534;font-weight:800;font-size:13px;"><i class="fa-solid fa-square-check"></i> Choose correct answers:</div>
          <label style="display:flex;align-items:center;gap:5px;font-size:12px;color:#166534;">Points <input type="number" min="0" step="1" value="${q.points}" onchange="quizQuestions[${i}].points=Math.max(0,Number(this.value)||0)" style="width:58px;border:0;border-bottom:2px solid #15803d;background:transparent;text-align:center;padding:3px;color:#166534;font-weight:800;outline:none;"></label>
        </div>
        ${isChoice ? q.options.map((opt,oi)=>`<label style="display:flex;align-items:center;gap:9px;padding:7px 4px;cursor:pointer;color:#334155;font-size:13px;">
                                                                                <input type="${q.type==='multiple_choice'?'radio':'checkbox'}" name="answer-${i}" ${q.correctAnswers.includes(oi)?'checked':''} onchange="toggleCorrectAnswer(${i},${oi},this.checked)" style="accent-color:#16a34a;width:17px;height:17px;">
                                                                                <span>${escapeHtml(opt || 'Option '+(oi+1))}</span>
                                                                              </label>`).join('') : `<div style="padding:8px 4px;color:#64748b;font-size:12px;">This question type is manually graded.</div>`}
        <div style="margin-top:9px;padding-top:11px;border-top:1px solid #bbf7d0;">
          <button type="button" onclick="toggleFeedback(${i})" style="border:0;background:none;color:#15803d;font-size:12.5px;font-weight:700;cursor:pointer;padding:0;"><i class="fa-regular fa-comment-dots"></i> ${q.feedback?'Edit answer feedback':'Add answer feedback'}</button>
          <div id="feedback-${i}" style="display:${q.feedback?'block':'none'};margin-top:8px;"><textarea oninput="quizQuestions[${i}].feedback=this.value" placeholder="Write feedback shown after answering..." style="width:100%;box-sizing:border-box;min-height:60px;border:1px solid #bbf7d0;border-radius:7px;padding:8px;font-size:12px;resize:vertical;outline:none;background:#fff;">${escapeHtml(q.feedback)}</textarea></div>
        </div>
        <div style="display:flex;justify-content:flex-end;margin-top:12px;"><button type="button" onclick="doneAnswerKey()" style="background:#15803d;border:1px solid #15803d;color:#fff;border-radius:7px;padding:7px 17px;font-size:12.5px;font-weight:700;cursor:pointer;">Done</button></div>
      </div>`;
    }

    function toggleAnswerKey(i) {
      answerKeyOpen = answerKeyOpen === i ? null : i;
      renderQuizQuestions();
    }

    function doneAnswerKey() {
      answerKeyOpen = null;
      renderQuizQuestions();
    }

    function addQuizOption(i) {
      const q = quizQuestions[i];
      q.options.push('Option ' + (q.options.length + 1));
      answerKeyOpen = i;
      renderQuizQuestions();
      setTimeout(() => {
        const inputs = document.querySelectorAll(
          `[aria-label=\"Question ${i+1} option ${q.options.length}\"]`);
        inputs[0]?.focus();
      }, 30);
    }

    function removeQuizOption(i, oi) {
      if (quizQuestions[i].options.length <= 2) {
        showAlert('Keep at least two options.');
        return;
      }
      quizQuestions[i].options.splice(oi, 1);
      quizQuestions[i].correctAnswers = quizQuestions[i].correctAnswers.filter(
        x => x !== oi).map(x => x > oi ? x - 1 : x);
      renderQuizQuestions();
    }

    function toggleCorrectAnswer(i, oi, checked) {
      const q = quizQuestions[i];
      if (q.type === 'multiple_choice') q.correctAnswers = checked ? [oi] : [];
      else {
        if (checked && !q.correctAnswers.includes(oi)) q.correctAnswers.push(oi);
        if (!checked) q.correctAnswers = q.correctAnswers.filter(x => x !== oi);
      }
    }

    function toggleFeedback(i) {
      const el = document.getElementById('feedback-' + i);
      if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }

    function getQuizSettings() {

      const passingScoreInput =
        document.getElementById('quizPassingScore');

      const timeLimitInput =
        document.getElementById('quizTimeLimit');

      if (!passingScoreInput || !timeLimitInput) {

        console.error(
          'Quiz settings inputs were not found.'
        );

        showAlert(
          'Quiz settings could not be loaded.'
        );

        return null;
      }


      const passingScoreRaw =
        passingScoreInput.value.trim();

      const timeLimitRaw =
        timeLimitInput.value.trim();


      // ==============================
      // PASSING SCORE
      // ==============================

      if (passingScoreRaw === '') {

        showAlert(
          'Please enter a passing score.'
        );

        passingScoreInput.focus();

        return null;
      }


      const passingScore =
        Number(passingScoreRaw);


      if (
        !Number.isFinite(passingScore) ||
        passingScore < 0 ||
        passingScore > 100
      ) {

        showAlert(
          'Passing score must be between 0 and 100.'
        );

        passingScoreInput.focus();

        return null;
      }


      // ==============================
      // TIME LIMIT
      // ==============================

      if (timeLimitRaw === '') {

        showAlert(
          'Please enter a time limit.'
        );

        timeLimitInput.focus();

        return null;
      }


      const timeLimit =
        Number(timeLimitRaw);


      if (
        !Number.isFinite(timeLimit) ||
        timeLimit < 0 ||
        timeLimit > 300
      ) {

        showAlert(
          'Time limit must be between 0 and 300 minutes.'
        );

        timeLimitInput.focus();

        return null;
      }


      return {
        passing_score: passingScore,
        time_limit: timeLimit
      };
    }

    async function submitTrainerQuiz(event) {

      event.preventDefault();

      const saveBtn =
        document.getElementById('saveQuizBtn');

      try {

        // ==========================================
        // FORM ELEMENTS
        // ==========================================

        const titleInput =
          document.getElementById('quizTitleInput');

        const instructionsEditor =
          document.getElementById('quizInstructionsEditor');

        const unitInput =
          document.getElementById('quizUnitInput');

        const lessonInput =
          document.getElementById('quizLessonInput');


        // ==========================================
        // BASIC VALUES
        // ==========================================

        const title =
          titleInput?.value.trim() || '';

        const instructions =
          instructionsEditor?.innerHTML.trim() || '';

        const unitNumber =
          unitInput?.value || '';

        const moduleId =
          lessonInput?.value || '';


        // ==========================================
        // CREATE / EDIT
        // ==========================================

        const quizId =
          window.editingQuizId || null;

        const isEditing =
          quizId !== null &&
          String(quizId).trim() !== '';


        // ==========================================
        // BASIC VALIDATION
        // ==========================================

        if (!title) {

          showAlert(
            'Please provide a quiz title.'
          );

          titleInput?.focus();

          return;
        }


        if (!unitNumber) {

          showAlert(
            'Please select the Unit for this quiz.'
          );

          unitInput?.focus();

          return;
        }


        if (!moduleId) {

          showAlert(
            'Please select the Lesson for this quiz.'
          );

          lessonInput?.focus();

          return;
        }


        // ==========================================
        // QUIZ SETTINGS
        // ==========================================

        const settings =
          getQuizSettings();

        if (!settings) {
          return;
        }


        const passingScore =
          settings.passing_score;

        const timeLimit =
          settings.time_limit;


        console.log(
          'Passing Score:',
          passingScore
        );

        console.log(
          'Time Limit:',
          timeLimit
        );


        // ==========================================
        // QUESTIONS
        // ==========================================

        if (
          !Array.isArray(quizQuestions) ||
          quizQuestions.length === 0
        ) {

          showAlert(
            'Please add at least one question.'
          );

          return;
        }


        for (
          let i = 0; i < quizQuestions.length; i++
        ) {

          const question =
            quizQuestions[i];


          // Question text
          if (
            !question.text ||
            !question.text.trim()
          ) {

            showAlert(
              'Please enter text for Question ' +
              (i + 1) +
              '.'
            );

            return;
          }


          // Multiple choice / checkboxes
          if (
            question.type === 'multiple_choice' ||
            question.type === 'checkboxes'
          ) {

            const options =
              Array.isArray(question.options) ?
              question.options : [];


            if (options.length === 0) {

              showAlert(
                'Please add options for Question ' +
                (i + 1) +
                '.'
              );

              return;
            }


            const hasEmptyOption =
              options.some(
                option =>
                !String(option || '').trim()
              );


            if (hasEmptyOption) {

              showAlert(
                'Please fill in all options for Question ' +
                (i + 1) +
                '.'
              );

              return;
            }


            const correctAnswers =
              Array.isArray(
                question.correctAnswers
              ) ?
              question.correctAnswers : [];


            if (correctAnswers.length === 0) {

              showAlert(
                'Choose at least one correct answer for Question ' +
                (i + 1) +
                '.'
              );

              return;
            }
          }
        }


        // ==========================================
        // PREPARE QUESTIONS
        // ==========================================

        const questions =
          quizQuestions.map(question => ({
            text: question.text || '',

            type: question.type ||
              'multiple_choice',

            options: Array.isArray(question.options) ?
              question.options : [],

            correctAnswers: Array.isArray(
                question.correctAnswers
              ) ?
              question.correctAnswers : [],

            points: Number(question.points || 1),

            feedback: question.feedback || '',

            required: Boolean(question.required)
          }));


        // ==========================================
        // DISABLE SAVE BUTTON
        // ==========================================

        if (saveBtn) {

          saveBtn.disabled = true;

          saveBtn.innerHTML = isEditing ?
            `
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span>Saving Changes...</span>
                  ` :
            `
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span>Creating Quiz...</span>
                  `;
        }


        // ==========================================
        // URL / METHOD
        // ==========================================

        const url = isEditing ?
          `/trainer/quiz/${quizId}` :
          '/trainer/quiz';

        const method = isEditing ?
          'PUT' :
          'POST';


        // ==========================================
        // SEND TO LARAVEL
        // ==========================================

        const response = await fetch(url, {

          method: method,

          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },

          body: JSON.stringify({

            course_id: {{ $course->id }},

            module_id: moduleId || null,

            title: title,

            instructions: instructions,

            // IMPORTANT
            passing_score: passingScore,

            // IMPORTANT
            time_limit: timeLimit,

            questions: questions
          })
        });


        // ==========================================
        // RESPONSE
        // ==========================================

        const data =
          await response.json()
          .catch(() => ({}));


        if (!response.ok) {

          // Laravel validation errors
          if (data.errors) {

            const firstError =
              Object.values(data.errors)
              .flat()[0];

            throw new Error(
              firstError ||
              'Validation failed.'
            );
          }

          throw new Error(
            data.message ||
            'Failed to save quiz.'
          );
        }


        if (data.success === false) {

          throw new Error(
            data.message ||
            'Failed to save quiz.'
          );
        }


        // ==========================================
        // SUCCESS
        // ==========================================

        sessionStorage.setItem(
          'courseActiveTab',
          'classwork'
        );


        if (isEditing) {

          sessionStorage.setItem(
            'lastEditedQuizId',
            String(quizId)
          );
        }


        window.editingQuizId = null;


        closeAddQuizModal();


        showAlert(
          isEditing ?
          'Quiz updated successfully.' :
          'Quiz created successfully.'
        );


        setTimeout(() => {
          window.location.reload();
        }, 700);


      } catch (error) {

        console.error(
          'Quiz save error:',
          error
        );


        if (saveBtn) {

          saveBtn.disabled = false;

          saveBtn.innerHTML =
            window.editingQuizId ?
            `
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Save Changes</span>
                      ` :
            `
                        <i class="fa-solid fa-check"></i>
                        <span>Create Quiz</span>
                      `;
        }


        showAlert(
          error.message ||
          'Something went wrong while saving the quiz.'
        );
      }
    }

    function openDeleteQuizModal(id, title) {
      targetQuizIdToDelete = id;
      document.getElementById('deleteQuizTitleText').textContent = title ||
        'this quiz';
      document.getElementById('deleteQuizModal').style.display = 'flex';
    }

    function closeDeleteQuizModal() {
      targetQuizIdToDelete = null;
      document.getElementById('deleteQuizModal').style.display = 'none';
    }

    function confirmDeleteQuizAction() {
      if (!targetQuizIdToDelete) return;
      const btn = document.getElementById('confirmDeleteQuizBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Deleting...';

      fetch(`/trainer/quiz/${targetQuizIdToDelete}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        })
        .then(async res => {
          const d = await res.json();
          if (!res.ok) throw new Error(d.message || 'Failed to delete quiz.');
          return d;
        })
        .then(() => {
          document.getElementById(`quiz-card-${targetQuizIdToDelete}`)
            ?.remove();
          closeDeleteQuizModal();
          showAlert('Quiz removed successfully.');
        })
        .catch(err => showAlert(err.message ||
          'An error occurred while deleting the quiz.'))
        .finally(() => {
          btn.disabled = false;
          btn.innerHTML =
            '<i class="fa-solid fa-trash-can" style="font-size: 12px;"></i> <span>Delete</span>';
        });
    }

    /* ==========================================================================
       CURRICULUM MODULE & UNIT HANDLERS
       ========================================================================== */
    let selectedTrainerFile = null;

    /* Unit Collapse / Expand Toggle */
    function toggleUnitCollapse(unitNum) {
      const container = document.getElementById(
        `unitLessonsContainer-${unitNum}`);
      const btn = document.getElementById(`unitToggleBtn-${unitNum}`);
      if (!container) return;

      const isHidden = container.style.display === 'none';
      container.style.display = isHidden ? 'flex' : 'none';
      if (btn) {
        btn.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(-90deg)';
      }
    }

    function openAddModuleModal(unitNumber = null) {
      const modal = document.getElementById('addModuleModal');
      const unitInput = document.getElementById('moduleUnitInput');

      if (unitNumber) {
        unitInput.value = unitNumber;
      }

      modal.style.display = 'flex';
      document.getElementById('moduleTitleInput').focus();
    }

    function closeAddModuleModal() {
      document.getElementById('addModuleModal').style.display = 'none';
      document.getElementById('addModuleForm').reset();
      document.getElementById('moduleUnitInput').value = '1';
      removeTrainerFile();
    }

    function handleTrainerFileSelect(e) {
      const file = e.target.files[0];
      if (!file) return;

      if (file.size > 50 * 1024 * 1024) {
        showAlert('File size cannot exceed 50MB.');
        removeTrainerFile();
        return;
      }

      selectedTrainerFile = file;
      const ext = file.name.split('.').pop().toLowerCase();
      let iconClass = 'fa-solid fa-file';
      let iconColor = '#64748b';

      if (ext === 'pdf') {
        iconClass = 'fa-solid fa-file-pdf';
        iconColor = '#dc2626';
      } else if (['doc', 'docx'].includes(ext)) {
        iconClass = 'fa-solid fa-file-word';
        iconColor = '#0284c7';
      } else if (['ppt', 'pptx'].includes(ext)) {
        iconClass = 'fa-solid fa-file-powerpoint';
        iconColor = '#ea580c';
      } else if (['xls', 'xlsx', 'csv'].includes(ext)) {
        iconClass = 'fa-solid fa-file-excel';
        iconColor = '#16a34a';
      } else if (['mp4', 'mov', 'avi'].includes(ext)) {
        iconClass = 'fa-solid fa-file-video';
        iconColor = '#059669';
      }

      document.getElementById('filePreviewIcon').innerHTML =
        `<i class="${iconClass}" style="color: ${iconColor};"></i>`;
      document.getElementById('fileNameDisplay').textContent = file.name;
      document.getElementById('fileSizeDisplay').textContent = (file.size / (
        1024 * 1024)).toFixed(2) + ' MB';
      document.getElementById('fileUploadPlaceholder').style.display = 'none';
      document.getElementById('fileSelectedPreview').style.display = 'flex';
    }

    function removeTrainerFile() {
      selectedTrainerFile = null;
      document.getElementById('moduleFileInput').value = '';
      document.getElementById('fileUploadPlaceholder').style.display = 'block';
      document.getElementById('fileSelectedPreview').style.display = 'none';
    }

    function submitTrainerModule(e) {
      e.preventDefault();
      const title = document.getElementById('moduleTitleInput').value.trim();
      const description = document.getElementById('moduleDescInput').value.trim();
      const unitNumber = document.getElementById('moduleUnitInput').value || 1;
      const saveBtn = document.getElementById('saveModuleBtn');

      if (!title) {
        showAlert('Please enter a module title.');
        return;
      }

      saveBtn.disabled = true;
      saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';

      const formData = new FormData();
      formData.append('title', title);
      formData.append('description', description);
      formData.append('unit_number', unitNumber);
      if (selectedTrainerFile) {
        formData.append('file', selectedTrainerFile);
      }

      fetch(`/trainer/course/{{ $course->id }}/modules`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: formData
        })
        .then(async (res) => {
          const data = await res.json();
          if (!res.ok) throw new Error(data.message ||
            'Failed to save module.');
          return data;
        })
        .then(() => {
          closeAddModuleModal();
          showAlert('Module added successfully!');
          setTimeout(() => location.reload(), 700);
        })
        .catch((err) => {
          saveBtn.disabled = false;
          saveBtn.innerHTML = 'Save Module';
          showAlert(err.message ||
            'Something went wrong while saving the module.');
        });
    }

    let targetModuleIdToDelete = null;

    function openDeleteModuleModal(moduleId, moduleTitle) {
      targetModuleIdToDelete = moduleId;
      document.getElementById('deleteModuleTitleText').textContent =
        moduleTitle || 'this module';
      document.getElementById('deleteModuleModal').style.display = 'flex';
    }

    function closeDeleteModuleModal() {
      targetModuleIdToDelete = null;
      document.getElementById('deleteModuleModal').style.display = 'none';
    }

    function confirmDeleteModuleAction() {
      if (!targetModuleIdToDelete) return;
      const btn = document.getElementById('confirmDeleteModuleBtn');
      btn.disabled = true;
      btn.innerHTML =
        `<i class="fa-solid fa-spinner fa-spin" style="font-size: 12px;"></i> <span>Deleting...</span>`;

      fetch(`/trainer/modules/${targetModuleIdToDelete}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        })
        .then(async (response) => {
          const data = await response.json();
          if (!response.ok) throw new Error(data.message ||
            'Failed to delete module.');
          return data;
        })
        .then(() => {
          document.getElementById(`module-card-${targetModuleIdToDelete}`)
            ?.remove();
          closeDeleteModuleModal();
        })
        .catch((error) => alert(error.message ||
          'An error occurred while deleting the module.'))
        .finally(() => {
          btn.disabled = false;
          btn.innerHTML =
            '<i class="fa-solid fa-trash-can" style="font-size: 12px;"></i> <span>Delete</span>';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {

      const activeTab =
        sessionStorage.getItem('courseActiveTab');

      if (activeTab === 'classwork') {

        sessionStorage.removeItem('courseActiveTab');

        // Try common Classwork tab selectors
        const classworkTab =
          document.querySelector('[data-tab="classwork"]') ||
          document.querySelector('#classworkTab') ||
          document.querySelector('[onclick*="classwork"]');

        if (classworkTab) {

          setTimeout(() => {
            classworkTab.click();
          }, 100);
        }
      }
    });
  </script>
@endsection

