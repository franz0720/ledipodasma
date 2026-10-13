  @extends('student.layout')

  @section('title', 'My Courses')

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

      .courses-overview-wrap {
        padding: clamp(18px, 3vw, 36px) clamp(14px, 3vw, 40px);
        font-family: 'Open Sans', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--ledipo-ink);
        min-width: 0;
      }

      .page-title {
        color: var(--ledipo-green-dark);
        font-size: clamp(25px, 3vw, 32px);
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -.6px;
        margin: 0 0 8px;
      }

      .page-subtitle {
        margin: 0 0 24px;
        color: var(--ledipo-muted);
        font-size: 14px;
        line-height: 1.6;
      }

      .learning-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin: 22px 0 30px;
      }

      .learning-stat {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
        padding: 18px;
        border: 1px solid var(--ledipo-border);
        border-radius: 18px;
        background: var(--ledipo-surface);
        box-shadow: 0 5px 18px rgba(15, 60, 37, .045);
      }

      .learning-stat-icon {
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        background: var(--ledipo-green-soft);
        color: var(--ledipo-green);
        font-size: 21px;
        font-weight: 800;
      }

      .learning-stat:nth-child(2) .learning-stat-icon {
        background: #fff9d9;
        color: #827700;
      }

      .learning-stat:nth-child(3) .learning-stat-icon {
        background: #edf2ff;
        color: #3159a8;
      }

      .learning-stat-value {
        display: block;
        color: var(--ledipo-green-dark);
        font-size: 23px;
        font-weight: 800;
        line-height: 1.15;
      }

      .learning-stat-label {
        display: block;
        margin-top: 4px;
        color: var(--ledipo-muted);
        font-size: 12px;
        font-weight: 600;
      }

      .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 285px), 1fr));
        gap: 22px;
        align-items: stretch;
      }

      .course-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        width: 100%;
        overflow: hidden;
        border: 1px solid var(--ledipo-border);
        border-radius: 20px;
        background: var(--ledipo-surface);
        box-shadow: 0 6px 22px rgba(15, 60, 37, .055);
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
      }

      .course-card:hover {
        transform: translateY(-4px);
        border-color: #c8dfd0;
        box-shadow: 0 14px 30px rgba(15, 60, 37, .10);
      }

      .card-banner {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 164px;
        padding: 20px;
        overflow: hidden;
        color: white;
        background: linear-gradient(135deg, #075b35 0%, #0b7545 68%, #32915d 100%);
        isolation: isolate;
      }

      .card-banner-image {
        position: absolute;
        inset: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
      }

      .card-banner.has-banner-image::before {
        z-index: 1;
        inset: 0;
        width: auto;
        height: auto;
        border-radius: 0;
        background: linear-gradient(180deg, rgba(4, 40, 24, .38), transparent 55%, rgba(4, 40, 24, .12));
      }

      .card-banner::before,
      .card-banner::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, .07);
        pointer-events: none;
      }

      .card-banner::before {
        width: 190px;
        height: 190px;
        right: -48px;
        top: -95px;
      }

      .card-banner::after {
        width: 130px;
        height: 130px;
        left: -45px;
        bottom: -80px;
      }

      .card-banner svg {
        position: relative;
        z-index: 1;
        width: 66px;
        height: 66px;
        fill: currentColor;
        opacity: .92;
        filter: drop-shadow(0 5px 8px rgba(0, 0, 0, .12));
      }

      .category-tag-overlay {
        position: absolute;
        z-index: 2;
        top: 15px;
        left: 15px;
        max-width: calc(100% - 30px);
        padding: 6px 11px;
        border-radius: 999px;
        background: var(--ledipo-yellow);
        color: #26301b;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .3px;
        text-transform: capitalize;
      }

      .card-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 21px;
      }

      .course-card-title {
        margin: 0 0 9px;
        color: var(--ledipo-green-dark);
        font-size: 17px;
        font-weight: 800;
        line-height: 1.4;
      }

      .course-card-desc {
        margin: 0 0 20px;
        color: #64756c;
        font-size: 13px;
        line-height: 1.65;
      }

      .progress-heading {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 8px;
        color: var(--ledipo-muted);
        font-size: 11px;
        font-weight: 700;
      }

      .progress-heading strong {
        color: var(--ledipo-green);
      }

      .progress-bar-bg {
        width: 100%;
        height: 8px;
        margin-bottom: 20px;
        overflow: hidden;
        border-radius: 999px;
        background: #e8efea;
      }

      .progress-bar-fill {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #087343, #39a66b);
        transition: width .3s ease;
      }

      .progress-text {
        display: none;
      }

      .btn-start-course {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        margin-top: auto;
        padding: 12px 16px;
        border: 0;
        border-radius: 11px;
        background: var(--ledipo-green);
        color: #fff;
        font-size: 13px;
        font-weight: 800;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
      }

      .btn-start-course:hover,
      .btn-start-course:focus {
        background: var(--ledipo-green-dark);
        color: #fff;
        transform: translateY(-1px);
      }

      .course-detail-container {
        width: 100%;
        max-width: none;
        margin: 0 auto;
        padding: 0 46px 32px;
        color: var(--ledipo-ink);
        box-sizing: border-box;
      }

      @media (max-width: 768px) {
        .course-detail-container {
          padding: 0 20px 24px;
        }
      }

      @media (max-width: 480px) {
        .course-detail-container {
          padding: 0 14px 20px;
        }
      }

      .course-main-header {
        margin: 0 0 20px;
        color: var(--ledipo-green-dark);
        font-size: clamp(23px, 3vw, 30px);
        font-weight: 800;
        letter-spacing: -.5px;
        line-height: 1.25;
      }

      .course-top-banner {
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
        padding: clamp(22px, 4vw, 32px);
        border-radius: 22px;
        background: linear-gradient(130deg, #064629, #087343 70%, #16814d);
        color: #fff;
        box-shadow: 0 12px 28px rgba(5, 91, 53, .16);
      }

      .course-top-banner::after {
        content: '';
        position: absolute;
        width: 210px;
        height: 210px;
        top: -115px;
        right: -45px;
        border: 28px solid rgba(255, 255, 255, .06);
        border-radius: 50%;
        pointer-events: none;
      }

      .banner-meta-row {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 22px;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255, 255, 255, .9);
      }

      .banner-divider {
        color: rgba(255, 255, 255, .4);
      }

      .banner-progress-bar {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 9px;
        margin-bottom: 9px;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(255, 255, 255, .2);
      }

      .banner-progress-fill {
        height: 100%;
        border-radius: inherit;
        background: var(--ledipo-yellow);
      }

      .banner-progress-text {
        position: relative;
        z-index: 1;
        text-align: right;
        font-size: 12px;
        font-weight: 800;
      }

      .welcome-title,
      .section-label {
        color: var(--ledipo-green-dark);
        font-size: 14px;
        font-weight: 800;
      }

      .welcome-title {
        margin: 0 0 10px;
        font-style: normal;
      }

      .welcome-desc {
        margin: 0 0 22px;
        color: #64756c;
        font-size: 13px;
        line-height: 1.8;
      }

      .section-label {
        margin: 18px 0 12px;
      }

      .bullet-list {
        margin: 0 0 24px;
        padding-left: 0;
        list-style: none;
      }

      .bullet-list li {
        position: relative;
        margin-bottom: 7px;
        padding-left: 20px;
        color: #64756c;
        font-size: 13px;
        line-height: 1.7;
      }

      .bullet-list li::before {
        position: absolute;
        left: 0;
        color: var(--ledipo-green);
        content: '✓';
        font-weight: 800;
      }

      .content-hr {
        margin: 28px 0;
        border: 0;
        border-top: 1px solid var(--ledipo-border);
      }

      .yellow-notice-box {
        margin-bottom: 24px;
        padding: 17px 20px;
        border: 1px solid #eee7a0;
        border-radius: 14px;
        background: #fffbe0;
        color: #5e5700;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.7;
      }

      .pretest-action-bar,
      .module-action-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 12px;
        padding: 17px 19px;
        border: 1px solid var(--ledipo-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(15, 60, 37, .035);
      }

      .pretest-score {
        color: var(--ledipo-ink);
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
      }

      .pretest-btn {
        flex: 0 0 auto;
        padding: 9px 14px;
        border: 0;
        border-radius: 10px;
        background: var(--ledipo-green);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: background .2s ease;
      }

      .pretest-btn:hover {
        background: var(--ledipo-green-dark);
      }

      .btn-back-overview {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 20px;
        padding: 9px 14px;
        border: 1px solid #cbded1;
        border-radius: 10px;
        background: #fff;
        color: var(--ledipo-green);
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        transition: background .2s ease;
      }

      .btn-back-overview:hover {
        background: var(--ledipo-green-soft);
        color: var(--ledipo-green-dark);
      }

      .unit-title-header {
        margin: 24px 0 14px;
        color: var(--ledipo-green-dark);
        font-size: 19px;
        font-weight: 800;
      }

      .module-action-title {
        color: var(--ledipo-ink);
        font-size: 13px;
        font-weight: 800;
        line-height: 1.5;
      }

      .module-buttons-group {
        display: flex;
        flex: 0 0 auto;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
      }

      .btn-module-done,
      .btn-module-view {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 7px 13px;
        border: 1px solid #b8d5c3;
        border-radius: 10px;
        background: #fff;
        color: var(--ledipo-green);
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition: all .2s ease;
      }

      .btn-module-done:hover,
      .btn-module-view:hover {
        border-color: var(--ledipo-green);
        background: var(--ledipo-green-soft);
        color: var(--ledipo-green-dark);
      }

      .btn-module-done.completed {
        border-color: #c5e5d0;
        background: #eaf7ee;
        color: #176b3c;
        cursor: default;
      }

      .no-data {
        padding: 30px 20px;
        border: 1px dashed #cbded1;
        border-radius: 16px;
        background: #fbfdfb;
        color: var(--ledipo-muted);
        font-size: 13px;
        line-height: 1.6;
        text-align: center;
      }

      .mayor-message-card {
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        padding: 28px;
        border: 1px solid #c8dfd0;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 6px 20px rgba(5, 91, 53, .06);
      }

      .mayor-seal-watermark {
        position: absolute;
        top: -20px;
        right: -20px;
        width: 240px;
        height: 240px;
        opacity: .09;
        pointer-events: none;
      }

      .mayor-card-title,
      .mayor-card-sign {
        color: var(--ledipo-green-dark);
        font-size: 14px;
        font-weight: 800;
      }

      .mayor-card-title {
        margin-bottom: 4px;
      }

      .mayor-card-sub {
        margin-bottom: 18px;
        color: var(--ledipo-muted);
        font-size: 11px;
      }

      .mayor-card-body {
        color: #40564a;
        font-size: 13px;
        line-height: 1.8;
      }

      .mayor-card-sign {
        margin-top: 18px;
      }

      .evaluation-block-label {
        margin-bottom: 4px;
        color: var(--ledipo-green);
        font-size: 13px;
        font-weight: 800;
      }

      .evaluation-block-desc {
        margin-bottom: 12px;
        color: var(--ledipo-muted);
        font-size: 11px;
      }

      .doc-viewer-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 10000;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(8, 31, 20, .66);
        backdrop-filter: blur(5px);
      }

      .doc-viewer-card {
        display: flex;
        flex-direction: column;
        width: min(780px, 100%);
        height: min(85vh, 900px);
        overflow: hidden;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
      }

      .doc-viewer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 17px 22px;
        background: var(--ledipo-green);
        color: #fff;
      }

      .doc-viewer-title {
        font-size: 15px;
        font-weight: 800;
      }

      .doc-viewer-sub {
        color: rgba(255, 255, 255, .78);
        font-size: 11px;
      }

      .doc-viewer-close {
        display: grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, .16);
        color: #fff;
        font-size: 16px;
        cursor: pointer;
      }

      .doc-viewer-body {
        flex: 1;
        overflow-y: auto;
        padding: 28px;
        background: #f6f9f7;
        color: #33483b;
        font-size: 13px;
        line-height: 1.8;
      }

      .doc-page-sheet {
        padding: 28px;
        border: 1px solid var(--ledipo-border);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .025);
      }

      .doc-viewer-footer {
        display: flex;
        justify-content: flex-end;
        padding: 14px 22px;
        border-top: 1px solid var(--ledipo-border);
        background: #fff;
      }

      /* =========================================
                                                                                                                LEDIPO QUIZ MODAL - RESPONSIVE REDESIGN
                                                                                                              ========================================= */
      .lms-quiz-overlay,
      .lms-confirm-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(5, 30, 18, .76);
        backdrop-filter: blur(7px);
      }

      .lms-quiz-modal {
        width: min(1440px, 100%);
        max-height: 94vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, .4);
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 30px 90px rgba(0, 0, 0, .32);
        animation: lmsQuizEnter .2s ease-out;
      }

      @keyframes lmsQuizEnter {
        from {
          opacity: 0;
          transform: translateY(10px);
        }

        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      /* Minimalist assessment header */
      .lms-quiz-header {
        flex-shrink: 0;
        min-height: 54px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 0 22px;
        color: #fff;
        background: #075b3a;
        border-bottom: 1px solid rgba(255, 255, 255, .12);
        box-shadow: none;
      }

      .lms-quiz-brand {
        display: flex;
        align-items: center;
        min-width: 0;
        gap: 0;
      }

      .lms-quiz-logo {
        display: none !important;
      }

      .lms-quiz-brand-name {
        font-size: 12px;
        line-height: 1.35;
        font-weight: 800;
        letter-spacing: 1px;
      }

      .lms-quiz-brand-caption {
        margin-top: 2px;
        color: rgba(255, 255, 255, .72);
        font-size: 10px;
        line-height: 1.35;
        font-weight: 400;
      }

      .lms-quiz-close {
        width: 30px;
        height: 30px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background: transparent;
        color: rgba(255, 255, 255, .72);
        font-size: 23px;
        font-weight: 300;
        line-height: 1;
        cursor: pointer;
        transition: background .18s ease, color .18s ease;
      }

      .lms-quiz-close:hover {
        background: rgba(255, 255, 255, .10);
        color: #fff;
      }

      .lms-quiz-close:focus-visible {
        outline: 2px solid #d7e8dc;
        outline-offset: 2px;
      }

      .lms-quiz-layout {
        display: grid;
        grid-template-columns: 250px minmax(0, 1fr) 260px;
        min-height: 0;
        overflow: hidden;
      }

      .lms-quiz-sidebar,
      .lms-quiz-rightbar {
        min-width: 0;
        overflow-y: auto;
        padding: 20px;
        background: #f8fbf9;
      }

      .lms-quiz-sidebar {
        border-right: 1px solid #e5eee8;
      }

      .lms-quiz-rightbar {
        border-left: 1px solid #e5eee8;
        background: #fff;
      }

      .lms-quiz-sidebar-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 23px;
        color: #193d29;
        font-size: 13px;
      }

      .lms-quiz-info-icon {
        width: 29px;
        height: 29px;
        display: grid;
        place-items: center;
        border-radius: 9px;
        background: #e1f1e6;
        color: #075b35;
        font-weight: 900;
      }

      .lms-quiz-label {
        color: #77857c;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .6px;
      }

      #quizModalTitle {
        margin: 7px 0 23px;
        color: #173d28;
        font-size: 18px;
        font-weight: 850;
        line-height: 1.45;
        overflow-wrap: anywhere;
      }

      .lms-quiz-info-block {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 19px;
      }

      .lms-quiz-info-symbol {
        width: 31px;
        height: 31px;
        display: grid;
        place-items: center;
        flex: 0 0 31px;
        border-radius: 9px;
        background: #eaf3ed;
        color: #075b35;
        font-size: 16px;
        font-weight: 800;
      }

      .lms-quiz-info-block strong {
        display: block;
        margin: 1px 0 5px;
        color: #304d3c;
        font-size: 11px;
      }

      .lms-quiz-info-block p {
        margin: 0;
        color: #68786e;
        font-size: 12px;
        line-height: 1.65;
        white-space: pre-line;
        overflow-wrap: anywhere;
      }

      .lms-quiz-important {
        margin-top: 24px;
        padding: 13px;
        border: 1px solid #eee3a1;
        border-radius: 12px;
        background: #fffbe1;
        color: #655800;
      }

      .lms-quiz-important strong {
        font-size: 11px;
      }

      .lms-quiz-important p {
        margin: 7px 0 0;
        font-size: 11px;
        line-height: 1.65;
      }

      .lms-quiz-main {
        display: flex;
        flex-direction: column;
        min-width: 0;
        min-height: 0;
        overflow: hidden;
        background: #f7faf8;
      }

      .lms-quiz-progress-top {
        flex-shrink: 0;
        padding: 20px 24px 16px;
        background: #fff;
        border-bottom: 1px solid #e5eee8;
      }

      .lms-quiz-progress-top>div:first-child {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
      }

      #quizQuestionCount {
        color: #203d2d;
        font-size: 12px;
        font-weight: 850;
      }

      #quizProgressPercent {
        color: #65786c;
        font-size: 11px;
        white-space: nowrap;
      }

      .lms-quiz-progress-track {
        height: 7px;
        overflow: hidden;
        border-radius: 999px;
        background: #e7eee9;
      }

      .lms-quiz-progress-fill {
        height: 100%;
        width: 0;
        border-radius: inherit;
        background: linear-gradient(90deg, #087343, #48aa6d);
        transition: width .2s ease;
      }

      .lms-quiz-body {
        flex: 1;
        min-height: 180px;
        overflow-y: auto;
        padding: 22px 24px;
        overscroll-behavior: contain;
      }

      .lms-quiz-question-card {
        display: none;
        padding: 22px;
        border: 1px solid #e4ece6;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(15, 60, 37, .04);
      }

      .lms-quiz-question-card.active {
        display: block;
        animation: lmsQuizEnter .16s ease-out;
      }

      .lms-question-topline {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-bottom: 19px;
      }

      .lms-question-number {
        padding: 7px 11px;
        border-radius: 9px;
        background: #e5f4e9;
        color: #075b35;
        font-size: 11px;
        font-weight: 900;
      }

      .lms-question-type {
        padding: 7px 10px;
        border-radius: 9px;
        background: #f0f3f1;
        color: #4f6256;
        font-size: 10px;
        font-weight: 750;
      }

      .lms-question-required {
        margin-left: auto;
        padding: 6px 9px;
        border-radius: 999px;
        background: #fff0f0;
        color: #b42323;
        font-size: 10px;
        font-weight: 800;
      }

      .lms-question-text {
        margin: 0 0 20px;
        color: #19372a;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.65;
        overflow-wrap: anywhere;
      }

      .lms-quiz-option {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-top: 9px;
        padding: 13px;
        border: 1px solid #dfe8e2;
        border-radius: 11px;
        background: #fff;
        color: #304739;
        font-size: 12px;
        line-height: 1.65;
        cursor: pointer;
        transition: .15s ease;
      }

      .lms-quiz-option:hover {
        border-color: #9ac8a8;
        background: #f8fcf9;
      }

      .lms-quiz-option:has(input:checked) {
        border-color: #50a96d;
        background: #edf8f0;
        color: #12462c;
      }

      .lms-quiz-option input {
        flex: 0 0 auto;
        margin-top: 4px;
        accent-color: #075b35;
      }

      .lms-quiz-option span {
        min-width: 0;
        overflow-wrap: anywhere;
      }

      .lms-quiz-answer-field {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 14px;
        border: 1px solid #d5e1d8;
        border-radius: 11px;
        outline: none;
        background: #fff;
        color: #19372a;
        font: inherit;
        font-size: 13px;
      }

      textarea.lms-quiz-answer-field {
        min-height: 125px;
        resize: vertical;
      }

      .lms-quiz-answer-field:focus {
        border-color: #16814d;
        box-shadow: 0 0 0 3px rgba(22, 129, 77, .1);
      }

      .lms-quiz-question-navigation {
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 24px;
        border-top: 1px solid #e5eee8;
        background: #fff;
      }

      .lms-quiz-nav-button {
        min-height: 42px;
        padding: 10px 15px;
        border: 1px solid #a8cbb4;
        border-radius: 10px;
        background: #fff;
        color: #075b35;
        font-size: 11px;
        font-weight: 850;
        cursor: pointer;
      }

      .lms-quiz-nav-button:disabled {
        opacity: .4;
        cursor: not-allowed;
      }

      .lms-quiz-next {
        border-color: #075b35;
        background: #075b35;
        color: #fff;
      }

      .lms-quiz-timer-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 12px;
        border: 1px solid #a7dfbd;
        border-radius: 13px;
        background: linear-gradient(120deg, #f4fcf6, #e9f8ef);
      }

      .lms-quiz-clock {
        width: 43px;
        height: 43px;
        display: grid;
        place-items: center;
        flex: 0 0 43px;
        border-radius: 50%;
        background: #fff;
        color: #075b35;
        font-size: 25px;
      }

      .lms-quiz-timer-card span {
        display: block;
        margin-bottom: 4px;
        color: #496e55;
        font-size: 10px;
      }

      .lms-quiz-timer-card strong {
        display: block;
        color: #064629;
        font-size: 25px;
        font-weight: 900;
        font-variant-numeric: tabular-nums;
      }

      .lms-quiz-timer-card.timer-urgent {
        border-color: #f4b4b4;
        background: #fff3f3;
      }

      .lms-quiz-timer-card.timer-urgent strong,
      .lms-quiz-timer-card.timer-urgent .lms-quiz-clock {
        color: #b42323;
      }

      .lms-quiz-timer-warning {
        margin-top: 9px;
        padding: 10px;
        border-radius: 9px;
        background: #fff5d7;
        color: #775b00;
        font-size: 10px;
        font-weight: 800;
        line-height: 1.5;
      }

      .lms-quiz-timer-warning.urgent {
        background: #fff0f0;
        color: #b42323;
      }

      .lms-quiz-navigator,
      .lms-quiz-progress-card {
        margin-top: 16px;
        padding: 14px;
        border: 1px solid #e3ece6;
        border-radius: 13px;
        background: #fff;
      }

      .lms-quiz-navigator h3,
      .lms-quiz-progress-card h3 {
        margin: 0 0 13px;
        color: #203d2d;
        font-size: 12px;
        font-weight: 850;
      }

      .lms-quiz-number-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 7px;
      }

      .lms-quiz-number {
        min-width: 0;
        aspect-ratio: 1;
        border: 1px solid #dce6df;
        border-radius: 8px;
        background: #f8faf9;
        color: #344c3d;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
      }

      .lms-quiz-number.answered {
        border-color: #087343;
        background: #087343;
        color: #fff;
      }

      .lms-quiz-number.current {
        outline: 2px solid #d6cf20;
        outline-offset: 1px;
        border-color: #d6cf20;
        background: #fff8b7;
        color: #3e3900;
      }

      .lms-quiz-legend {
        display: grid;
        gap: 8px;
        margin-top: 15px;
        color: #586d60;
        font-size: 10px;
      }

      .lms-quiz-legend span {
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .lms-quiz-legend i {
        width: 9px;
        height: 9px;
        display: inline-block;
        border-radius: 50%;
      }

      .legend-answered {
        background: #087343;
      }

      .legend-current {
        background: #e1dc36;
      }

      .legend-unanswered {
        background: #dce5df;
      }

      .lms-quiz-progress-card p {
        margin: 0 0 10px;
        color: #627568;
        font-size: 10px;
      }

      .lms-quiz-submit {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 19px;
        padding: 14px;
        border: 0;
        border-radius: 11px;
        background: linear-gradient(120deg, #075b35, #043f26);
        color: #fff;
        font-size: 12px;
        font-weight: 900;
        cursor: pointer;
      }

      .lms-quiz-submit:hover {
        background: #043f26;
      }

      .lms-quiz-submit:disabled {
        opacity: .6;
        cursor: not-allowed;
      }

      .lms-quiz-submit-note {
        margin: 9px 0 0;
        color: #819087;
        font-size: 10px;
        line-height: 1.6;
        text-align: center;
      }

      .lms-quiz-loading {
        padding: 45px 10px;
        color: #6b7c73;
        font-size: 13px;
        text-align: center;
      }

      .lms-result-overlay {
        position: fixed;
        inset: 0;
        z-index: 10010;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(5, 30, 18, .78);
        backdrop-filter: blur(8px);
      }

      .lms-result-card {
        width: min(480px, 100%);
        padding: 30px;
        border: 1px solid #dcebe1;
        border-radius: 22px;
        background: #fff;
        color: #183329;
        text-align: center;
        box-shadow: 0 28px 90px rgba(0, 0, 0, .32);
      }

      .lms-result-icon {
        width: 70px;
        height: 70px;
        display: grid;
        place-items: center;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: #eaf5ee;
        color: #075b35;
        font-size: 34px;
        font-weight: 900;
      }

      .lms-result-card h3 {
        margin: 0 0 8px;
        color: #064b2c;
        font-size: 25px;
        font-weight: 900;
      }

      .lms-result-message {
        margin: 0 0 20px;
        color: #6b7c73;
        font-size: 13px;
        line-height: 1.65;
      }

      .lms-result-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin: 20px 0;
      }

      .lms-result-stat {
        padding: 14px 8px;
        border: 1px solid #e2ebe5;
        border-radius: 13px;
        background: #f7fbf8;
      }

      .lms-result-stat span {
        display: block;
        margin-bottom: 6px;
        color: #718078;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
      }

      .lms-result-stat strong {
        color: #075b35;
        font-size: 22px;
        font-weight: 900;
      }

      .lms-result-status {
        display: inline-block;
        margin-bottom: 18px;
        padding: 7px 15px;
        border-radius: 999px;
        background: #eaf5ee;
        color: #075b35;
        font-size: 12px;
        font-weight: 900;
      }

      .lms-result-status.failed {
        background: #fff0ef;
        color: #b42318;
      }

      .lms-result-done {
        width: 100%;
        padding: 13px 18px;
        border: 0;
        border-radius: 11px;
        background: #075b35;
        color: #fff;
        font-size: 13px;
        font-weight: 850;
        cursor: pointer;
      }

      .lms-result-done:hover {
        background: #043f26;
      }

      @media(max-width:480px) {
        .lms-result-card {
          padding: 23px 18px
        }

        .lms-result-stats {
          gap: 6px
        }

        .lms-result-stat strong {
          font-size: 18px
        }
      }

      .lms-confirm-overlay {
        z-index: 10001;
      }

      .lms-confirm-card {
        width: min(420px, 100%);
        padding: 27px;
        border-radius: 18px;
        background: #fff;
        text-align: center;
        box-shadow: 0 25px 70px rgba(0, 0, 0, .25);
      }

      .lms-confirm-icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: #fff8d9;
        color: #776800;
        font-size: 25px;
        font-weight: 900;
      }

      .lms-confirm-card h3 {
        margin: 0 0 10px;
        color: #123d28;
        font-size: 21px;
      }

      .lms-confirm-card p {
        color: #6b7c73;
        font-size: 13px;
        line-height: 1.7;
      }

      .lms-confirm-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 22px;
      }

      .lms-confirm-cancel,
      .lms-confirm-submit {
        padding: 11px 14px;
        border: 1px solid #dce7df;
        border-radius: 9px;
        background: #fff;
        color: #305340;
        font-size: 11px;
        font-weight: 850;
        cursor: pointer;
      }

      .lms-confirm-submit {
        border-color: #075b35;
        background: #075b35;
        color: #fff;
      }

      @media(max-width:1050px) {
        .lms-quiz-layout {
          grid-template-columns: 210px minmax(0, 1fr);
        }

        .lms-quiz-rightbar {
          grid-column: 1 / -1;
          display: grid;
          grid-template-columns: repeat(2, minmax(0, 1fr));
          align-items: start;
          gap: 12px;
          border-top: 1px solid #e5eee8;
          border-left: 0;
        }

        .lms-quiz-timer-warning,
        .lms-quiz-navigator,
        .lms-quiz-progress-card,
        .lms-quiz-submit,
        .lms-quiz-submit-note {
          margin-top: 0;
        }

        .lms-quiz-navigator {
          grid-row: span 3;
        }
      }

      @media(max-width:650px) {
        .lms-quiz-overlay {
          padding: 5px;
        }

        .lms-quiz-modal {
          max-height: 98vh;
          border-radius: 13px;
        }

        .lms-quiz-header {
          min-height: 50px;
          padding: 0 14px;
        }

        .lms-quiz-brand-name {
          font-size: 10px;
        }

        .lms-quiz-brand-caption {
          font-size: 9px;
        }

        .lms-quiz-layout {
          display: flex;
          flex-direction: column;
          overflow-y: auto;
        }

        .lms-quiz-sidebar {
          display: none;
        }

        .lms-quiz-main {
          min-height: 400px;
          overflow: visible;
        }

        .lms-quiz-progress-top {
          padding: 14px;
        }

        .lms-quiz-body {
          padding: 13px;
        }

        .lms-quiz-question-card {
          padding: 15px;
        }

        .lms-question-text {
          font-size: 15px;
        }

        .lms-quiz-question-navigation {
          padding: 12px;
        }

        .lms-quiz-rightbar {
          display: flex;
          flex-direction: column;
          padding: 13px;
        }

        .lms-quiz-timer-card,
        .lms-quiz-navigator,
        .lms-quiz-progress-card,
        .lms-quiz-submit {
          width: 100%;
          box-sizing: border-box;
        }

        .lms-quiz-number-grid {
          grid-template-columns: repeat(5, minmax(0, 1fr));
        }
      }

      @media(prefers-reduced-motion:reduce) {

        .lms-quiz-modal,
        .lms-quiz-question-card {
          animation: none;
        }

        .lms-quiz-progress-fill {
          transition: none;
        }
      }

      @media (max-width:760px) {
        .learning-stats {
          gap: 10px;
        }

        .learning-stat {
          align-items: flex-start;
          flex-direction: column;
          gap: 9px;
          padding: 14px;
        }

        .learning-stat-icon {
          width: 38px;
          height: 38px;
          flex-basis: 38px;
          border-radius: 12px;
        }

        .learning-stat-value {
          font-size: 20px;
        }

        .module-action-row,
        .pretest-action-bar {
          align-items: flex-start;
          flex-direction: column;
        }

        .module-buttons-group {
          width: 100%;
        }

        .module-buttons-group>* {
          flex: 1 1 auto;
        }

        .pretest-btn {
          align-self: flex-start;
        }
      }

      @media (max-width:480px) {
        .learning-stats {
          grid-template-columns: 1fr;
        }

        .learning-stat {
          align-items: center;
          flex-direction: row;
        }

        .courses-grid {
          grid-template-columns: minmax(0, 1fr);
          gap: 16px;
        }

        .card-banner {
          height: 145px;
        }

        .card-body {
          padding: 18px;
        }

        .course-top-banner {
          border-radius: 17px;
        }

        .banner-meta-row {
          align-items: flex-start;
          flex-direction: column;
          gap: 8px;
        }

        .banner-divider {
          display: none;
        }

        .doc-viewer-body {
          padding: 14px;
        }

        .doc-page-sheet {
          padding: 18px;
        }

        #quizModal {
          padding: 8px;
        }

        #quizModal>div {
          max-height: 94vh !important;
          border-radius: 15px !important;
        }
      }

      @media (prefers-reduced-motion:reduce) {

        .course-card,
        .btn-start-course,
        .progress-bar-fill {
          transition: none;
        }
      }

      .module-quizzes-wrap {
        margin: -2px 0 18px 18px;
        padding: 12px 14px 14px;
        border: 1px solid #dcece2;
        border-left: 3px solid var(--ledipo-green);
        border-radius: 0 12px 12px 0;
        background: #f7fbf8;
      }

      .module-quizzes-heading {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: var(--ledipo-green-dark);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .2px;
      }

      .module-quiz-action-bar {
        margin: 0;
        padding: 12px 14px;
        border-color: #e0ebe4;
        background: #fff;
        border-radius: 10px;
      }

      .module-quiz-info {
        min-width: 0;
      }

      .module-quiz-title {
        color: #183329;
        font-size: 13px;
        line-height: 1.45;
        font-weight: 750;
        overflow-wrap: anywhere;
      }

      .module-quiz-meta {
        margin-top: 4px;
        color: #718096;
        font-size: 12px;
      }

      .module-quiz-result {
        white-space: nowrap;
        font-size: 12px;
        font-weight: 800;
      }

      /* Inline module PDF preview */
      .module-pdf-preview {
        margin: 0 0 18px 18px;
        padding: 14px;
        border: 1px solid var(--ledipo-border);
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(15, 60, 37, .035);
      }

      .module-pdf-preview-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
        color: var(--ledipo-green-dark);
        font-size: 13px;
        font-weight: 800;
      }

      .module-pdf-frame {
        display: block;
        width: 100%;
        height: min(72vh, 760px);
        min-height: 420px;
        border: 1px solid #dce7df;
        border-radius: 10px;
        background: #f7faf8;
      }

      .module-pdf-open-link {
        color: var(--ledipo-green);
        font-size: 12px;
        font-weight: 800;
        text-decoration: underline;
      }

      @media (max-width: 600px) {
        .module-pdf-preview {
          margin-left: 8px;
          padding: 10px;
        }

        .module-pdf-frame {
          height: 65vh;
          min-height: 360px;
        }
      }

      @media (max-width: 600px) {
        .module-quizzes-wrap {
          margin-left: 8px;
          padding: 10px;
        }

        .module-quiz-action-bar {
          align-items: flex-start;
        }

        .module-quiz-result {
          white-space: normal;
        }
      }

      /* Quiz attempt history */
      .quiz-attempt-history {
        width: 100%;
        margin-top: 12px;
        padding: 13px 14px;
        border: 1px solid var(--ledipo-border);
        border-radius: 12px;
        background: #fff;
        box-sizing: border-box;
      }

      .quiz-attempt-history-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
        color: var(--ledipo-green-dark);
        font-size: 12px;
      }

      .quiz-attempt-history-count {
        color: var(--ledipo-muted);
        font-size: 11px;
        font-weight: 600;
      }

      .quiz-attempt-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 0;
        border-top: 1px solid #edf2ee;
      }

      .quiz-attempt-row:first-of-type {
        border-top: 0;
      }

      .quiz-attempt-details {
        display: flex;
        flex-direction: column;
        min-width: 0;
        gap: 4px;
      }

      .quiz-attempt-name {
        color: var(--ledipo-ink);
        font-size: 12px;
        font-weight: 800;
      }

      .quiz-attempt-date {
        color: var(--ledipo-muted);
        font-size: 10px;
      }

      .quiz-attempt-result {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        flex: 0 0 auto;
        gap: 4px;
      }

      .quiz-attempt-percentage {
        color: var(--ledipo-green-dark);
        font-size: 14px;
        font-weight: 900;
      }

      .quiz-attempt-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        line-height: 1.2;
      }

      .quiz-attempt-status.passed {
        background: #eaf7ee;
        color: #276749;
      }

      .quiz-attempt-status.failed {
        background: #fff0ef;
        color: #b42318;
      }

      @media (max-width: 480px) {
        .quiz-attempt-history {
          padding: 11px;
        }

        .quiz-attempt-row {
          gap: 8px;
        }
      }

      /* Course syllabus beneath Introduction */
      .course-syllabus-section {
        margin: 22px 0 28px;
        padding: 20px;
        background: #fff;
        border: 1px solid #dce9df;
        border-radius: 16px;
        box-shadow: 0 5px 18px rgba(15, 60, 37, 0.05);
        scroll-margin-top: 90px;
      }

      .course-syllabus-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 18px;
      }

      .course-syllabus-icon {
        display: grid;
        place-items: center;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #eaf5ee;
        color: #075b35;
        font-size: 21px;
      }

      .course-syllabus-heading h3 {
        margin: 0 0 5px;
        color: #043f26;
        font-size: 17px;
        font-weight: 800;
      }

      .course-syllabus-heading p {
        margin: 0;
        color: #6b7c73;
        font-size: 12px;
        line-height: 1.6;
      }

      .course-syllabus-viewer {
        width: 100%;
        height: 75vh;
        min-height: 420px;
        max-height: 900px;
        overflow: hidden;
        border: 1px solid #dce9df;
        border-radius: 10px;
        background: #f5f8f6;
      }

      .course-syllabus-viewer iframe {
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
        background: #fff;
      }

      .course-syllabus-download {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border: 1px solid #dce9df;
        border-radius: 10px;
        background: #f8fcf9;
        color: #40564a;
        font-size: 13px;
      }

      .course-syllabus-download>i {
        color: #075b35;
        font-size: 22px;
      }

      .course-syllabus-download a {
        margin-left: auto;
        padding: 9px 13px;
        border-radius: 8px;
        background: #075b35;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
      }

      .course-syllabus-download a:hover {
        background: #043f26;
      }

      .course-syllabus-content {
        color: #40564a;
        font-size: 13px;
        line-height: 1.8;
        overflow-wrap: anywhere;
      }

      @media (max-width: 600px) {
        .course-syllabus-section {
          padding: 14px;
        }

        .course-syllabus-viewer {
          height: 65vh;
          min-height: 350px;
        }

        .course-syllabus-download a {
          margin-left: 0;
        }
      }

      /* Onsite activity scores screen */
      .onsite-scores-wrap {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
      }

      .onsite-scores-header {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 24px;
      }

      .onsite-scores-icon {
        display: grid;
        place-items: center;
        flex: 0 0 52px;
        width: 52px;
        height: 52px;
        border-radius: 15px;
        background: var(--ledipo-green-soft);
        color: var(--ledipo-green);
        font-size: 22px;
      }

      .onsite-scores-header h1 {
        margin: 0 0 7px;
        color: var(--ledipo-green-dark);
        font-size: clamp(23px, 3vw, 30px);
        font-weight: 800;
        line-height: 1.25;
      }

      .onsite-scores-header p {
        margin: 0;
        color: var(--ledipo-muted);
        font-size: 13px;
        line-height: 1.7;
      }

      .onsite-scores-card {
        overflow: hidden;
        border: 1px solid var(--ledipo-border);
        border-radius: 17px;
        background: #fff;
        box-shadow: 0 6px 22px rgba(15, 60, 37, .055);
      }

      .onsite-scores-card-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        padding: 18px 20px;
        border-bottom: 1px solid var(--ledipo-border);
        background: #fbfdfb;
      }

      .onsite-scores-card-heading h2 {
        margin: 0;
        color: var(--ledipo-green-dark);
        font-size: 15px;
        font-weight: 800;
      }

      .onsite-scores-count {
        padding: 6px 10px;
        border-radius: 999px;
        background: var(--ledipo-green-soft);
        color: var(--ledipo-green);
        font-size: 11px;
        font-weight: 800;
      }

      .onsite-scores-table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }

      .onsite-scores-table {
        width: 100%;
        min-width: 650px;
        border-collapse: collapse;
      }

      .onsite-scores-table th {
        padding: 14px 18px;
        background: #eaf5ee;
        color: #043f26;
        font-size: 11px;
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
      }

      .onsite-scores-table td {
        padding: 16px 18px;
        border-top: 1px solid #edf2ee;
        color: #40564a;
        font-size: 12px;
        line-height: 1.6;
        vertical-align: middle;
      }

      .onsite-scores-table tbody tr:hover {
        background: #fbfdfb;
      }

      .onsite-score-number {
        color: var(--ledipo-green);
        font-size: 13px;
        font-weight: 900;
        white-space: nowrap;
      }

      .onsite-score-pending {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 999px;
        background: #fff8d9;
        color: #776800;
        font-size: 10px;
        font-weight: 800;
      }

      .onsite-scores-empty {
        padding: 42px 22px;
        text-align: center;
      }

      .onsite-scores-empty i {
        display: block;
        margin-bottom: 13px;
        color: #7caf8e;
        font-size: 38px;
      }

      .onsite-scores-empty h3 {
        margin: 0 0 8px;
        color: var(--ledipo-green-dark);
        font-size: 17px;
        font-weight: 800;
      }

      .onsite-scores-empty p {
        max-width: 430px;
        margin: 0 auto;
        color: var(--ledipo-muted);
        font-size: 12px;
        line-height: 1.7;
      }

      @media (max-width: 600px) {
        .onsite-scores-header {
          gap: 11px;
        }

        .onsite-scores-icon {
          flex-basis: 43px;
          width: 43px;
          height: 43px;
          border-radius: 12px;
        }

        .onsite-scores-card-heading {
          padding: 15px;
        }

        .onsite-scores-table th,
        .onsite-scores-table td {
          padding: 13px 14px;
        }
      }

      /* ========================================
                                                              LEDIPO COURSE PROGRESS BANNER
                                                            ======================================== */
      .course-top-banner {
        width: 100%;
        box-sizing: border-box;
        margin: 20px 0 28px;
        padding: 28px 32px;
        overflow: hidden;
        background: var(--ledipo-green, #075b35);
        color: var(--ledipo-surface, #fff);
        border: 1px solid var(--ledipo-green, #075b35);
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(4, 63, 38, 0.12);
      }

      .course-banner-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
      }

      .course-banner-title {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
      }

      .course-banner-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 46px;
        height: 46px;
        background: var(--ledipo-yellow, #e1dc36);
        color: var(--ledipo-green-dark, #043f26);
        border-radius: 13px;
        font-size: 19px;
      }

      .course-banner-title h2 {
        margin: 0 0 5px;
        color: #fff;
        font-size: 19px;
        font-weight: 750;
        line-height: 1.3;
      }

      .course-banner-title p {
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
        font-size: 13px;
        line-height: 1.5;
      }

      .course-schedule-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        max-width: 100%;
        padding: 10px 13px;
        color: #fff;
        background: rgba(255, 255, 255, 0.09);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        overflow-wrap: anywhere;
      }

      .course-schedule-badge i {
        color: var(--ledipo-yellow, #e1dc36);
      }

      .course-banner-divider {
        height: 1px;
        margin: 23px 0;
        background: rgba(255, 255, 255, 0.20);
      }

      .course-progress-details {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 17px;
      }

      .course-progress-caption {
        display: block;
        margin-bottom: 6px;
        color: rgba(255, 255, 255, 0.80);
        font-size: 13px;
        font-weight: 500;
      }

      .course-progress-percentage {
        color: #fff;
        font-size: 36px;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -1px;
      }

      .course-progress-percentage span {
        margin-left: 2px;
        color: var(--ledipo-yellow, #e1dc36);
        font-size: 22px;
        font-weight: 750;
      }

      .course-progress-category {
        display: flex;
        align-items: center;
        gap: 9px;
        padding-bottom: 5px;
        color: rgba(255, 255, 255, 0.88);
        font-size: 12px;
        font-weight: 600;
        text-align: right;
      }

      .course-progress-category i {
        color: var(--ledipo-yellow, #e1dc36);
        font-size: 18px;
      }

      /* Progress track */
      .course-progress-track {
        width: 100%;
        height: 9px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.25);
        border: none;
        border-radius: 999px;
      }

      .course-progress-fill {
        height: 100%;
        max-width: 100%;
        background: var(--ledipo-yellow, #e1dc36);
        border-radius: inherit;
        transition: width 0.45s ease;
      }

      .course-progress-note {
        display: flex;
        align-items: center;
        gap: 7px;
        margin: 12px 0 0;
        color: rgba(255, 255, 255, 0.78);
        font-size: 11px;
        line-height: 1.5;
      }

      .course-progress-note i {
        color: var(--ledipo-yellow, #e1dc36);
      }

      /* TABLET */
      @media (max-width: 768px) {
        .course-top-banner {
          padding: 24px;
          border-radius: 17px;
        }

        .course-banner-title h2 {
          font-size: 17px;
        }

        .course-progress-percentage {
          font-size: 32px;
        }
      }

      /* MOBILE */
      @media (max-width: 480px) {
        .course-top-banner {
          padding: 19px;
          margin: 16px 0 22px;
          border-radius: 15px;
        }

        .course-banner-header {
          align-items: flex-start;
          flex-direction: column;
          gap: 15px;
        }

        .course-banner-title {
          gap: 11px;
        }

        .course-banner-icon {
          width: 42px;
          height: 42px;
          font-size: 17px;
        }

        .course-banner-title h2 {
          font-size: 16px;
        }

        .course-banner-title p {
          font-size: 12px;
        }

        .course-schedule-badge {
          align-self: flex-start;
          padding: 9px 11px;
          font-size: 11px;
        }

        .course-banner-divider {
          margin: 18px 0;
        }

        .course-progress-details {
          align-items: flex-start;
          flex-direction: column;
          gap: 13px;
          margin-bottom: 15px;
        }

        .course-progress-percentage {
          font-size: 32px;
        }

        .course-progress-percentage span {
          font-size: 20px;
        }

        .course-progress-category {
          padding-bottom: 0;
          text-align: left;
        }

        .course-progress-track {
          height: 8px;
        }

        .course-progress-note {
          font-size: 10px;
        }
      }

      @media (prefers-reduced-motion: reduce) {
        .course-progress-fill {
          transition: none;
        }
      }

      /* Course completion certificate preview */
      /* Completion view: keep only the course progress banner and certificate visible. */
      body.completion-screen-active .btn-back-overview {
        display: none !important;
      }

      body.completion-screen-active .course-detail-container>*:not(#courseOverview):not(#courseCompletionCertificate) {
        display: none !important;
      }

      body.completion-screen-active #courseOverview>*:not(.course-top-banner) {
        display: none !important;
      }

      body.completion-screen-active #courseOverview {
        display: block !important;
        margin-bottom: 20px;
      }

      body.completion-screen-active .course-top-banner {
        display: block !important;
        margin-bottom: 20px;
        padding: 20px 24px !important;
        border-radius: 16px !important;
        background: #075b35 !important;
        box-shadow: none !important;
      }

      /* In Completion mode, retain only the overall progress percentage and bar. */
      body.completion-screen-active .course-main-header,
      body.completion-screen-active .course-banner-header,
      body.completion-screen-active .course-banner-divider,
      body.completion-screen-active .course-progress-category,
      body.completion-screen-active .course-progress-note {
        display: none !important;
      }

      body.completion-screen-active .course-progress-details {
        margin: 0 0 12px !important;
      }

      body.completion-screen-active .course-progress-caption,
      body.completion-screen-active .course-progress-percentage {
        color: #fff !important;
      }

      body.completion-screen-active .course-progress-track {
        margin: 0 !important;
      }

      body.completion-screen-active #courseCompletionCertificate {
        display: block !important;
        margin-top: 0;
      }

      .course-certificate-section[hidden] {
        display: none !important;
      }

      .course-certificate-section {
        margin: 28px 0;
        padding: clamp(16px, 3vw, 26px);
        border: 1px solid var(--ledipo-border);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 6px 20px rgba(5, 91, 53, .05);
      }

      .course-certificate-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
      }

      .course-certificate-heading h3 {
        margin: 0 0 5px;
        color: var(--ledipo-green-dark);
        font-size: 18px;
        font-weight: 800;
      }

      .course-certificate-heading p {
        margin: 0;
        color: var(--ledipo-muted);
        font-size: 12px;
        line-height: 1.6;
      }

      .course-certificate-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border-radius: 999px;
        background: #f0f2f0;
        color: #5f6c63;
        font-size: 11px;
        font-weight: 800;
      }

      .course-certificate-badge.ready {
        background: var(--ledipo-green-soft);
        color: var(--ledipo-green);
      }

      /* Student certificate: match the compact Admin certificate artwork. */
      .course-certificate-preview {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 18px;
        box-sizing: border-box;
        width: min(100%, 560px);
        min-height: 548px;
        aspect-ratio: 1 / 1;
        margin: 0 auto;
        padding: 44px 34px 28px;
        border: 10px solid #075b35;
        background: #fff;
        color: #202020;
        text-align: center;
        filter: grayscale(100%);
        font-family: Arial, Helvetica, sans-serif;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .10);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .course-certificate-preview::before {
        content: "";
        position: absolute;
        inset: 23px;
        border: 1px dashed #075b35;
        pointer-events: none;
      }

      .course-certificate-preview>* {
        position: relative;
        z-index: 1;
      }

      /* Color is enabled only when both release and competency are confirmed. */
      .course-certificate-preview.is-color {
        filter: none;
        border-color: #075b35;
        box-shadow: 0 8px 24px rgba(4, 63, 38, .12);
      }

      .course-certificate-preview:not(.is-color) {
        border-color: #333;
      }

      .course-certificate-preview:not(.is-color)::before {
        border-color: #555;
      }

      .course-certificate-preview:not(.is-color) .course-certificate-title,
      .course-certificate-preview:not(.is-color) .course-certificate-name,
      .course-certificate-preview:not(.is-color) .course-certificate-course,
      .course-certificate-preview:not(.is-color) .course-certificate-seal {
        color: #333 !important;
      }

      .course-certificate-logos {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 18px;
        margin: 5px 0 25px;
      }

      .course-certificate-logos img {
        display: block;
        width: 44px;
        height: 44px;
        max-width: 22%;
        object-fit: contain;
      }

      .course-certificate-authority {
        color: #222;
        font: 700 10px/1.45 Arial, Helvetica, sans-serif;
        letter-spacing: 0;
        text-transform: uppercase;
      }

      .course-certificate-title {
        margin: 27px 0 22px;
        color: #075b35;
        font: 700 clamp(16px, 2vw, 20px)/1.25 Georgia, "Times New Roman", serif;
        letter-spacing: .025em;
        text-transform: uppercase;
      }

      .course-certificate-small {
        margin: 0;
        color: #333;
        font: 9px/1.6 Arial, Helvetica, sans-serif;
        letter-spacing: .08em;
        text-transform: uppercase;
      }

      .course-certificate-name {
        margin: 20px 0 20px;
        color: #151515;
        font: italic 700 clamp(25px, 3.4vw, 34px)/1.15 "Brush Script MT", "Segoe Script", "URW Chancery L", cursive;
        text-decoration: underline;
        text-underline-offset: 3px;
        overflow-wrap: anywhere;
      }

      .course-certificate-course {
        max-width: 94%;
        margin: 16px auto 0;
        color: #075b35;
        font: 700 clamp(13px, 1.8vw, 17px)/1.4 Arial, Helvetica, sans-serif;
        text-decoration: underline;
        text-underline-offset: 2px;
        text-transform: uppercase;
        overflow-wrap: anywhere;
      }

      .course-certificate-signatories {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 35px;
        width: 88%;
        margin: 38px auto 0;
        color: #222;
        font-family: Arial, Helvetica, sans-serif;
      }

      .course-certificate-signatory {
        min-width: 0;
        text-align: center;
        font-size: 9px;
        line-height: 1.35;
      }

      .course-certificate-signature-line {
        display: block;
        height: 1px;
        margin-bottom: 6px;
        background: #333;
      }

      .course-certificate-signatory strong {
        display: block;
        font-size: 9px;
        font-weight: 700;
        overflow-wrap: anywhere;
      }

      .course-certificate-signatory span:last-child {
        display: block;
        color: #555;
        font-size: 8px;
      }

      .course-certificate-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 26px;
        color: #333;
        font: 9px/1.4 Arial, Helvetica, sans-serif;
        text-transform: uppercase;
      }

      .course-certificate-footer span {
        min-width: 0;
        overflow-wrap: anywhere;
      }

      /* Keep the certificate artwork crisp and centered like the reference. */
      .course-certificate-preview>div {
        min-width: 0;
      }

      .course-certificate-preview.is-color .course-certificate-title,
      .course-certificate-preview.is-color .course-certificate-course {
        color: #075b35;
      }

      .course-certificate-note {
        max-width: 560px;
        margin: 14px auto 0;
        color: var(--ledipo-muted);
        font-size: 12px;
        line-height: 1.7;
      }

      .course-certificate-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 10px;
        max-width: 560px;
        margin: 16px auto 0;
      }

      .course-certificate-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 14px;
        border: 1px solid var(--ledipo-green);
        border-radius: 10px;
        background: var(--ledipo-green);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
      }

      .course-certificate-btn.secondary {
        background: #fff;
        color: var(--ledipo-green);
      }

      .course-certificate-btn:hover {
        background: var(--ledipo-green-dark);
        color: #fff;
      }

      .course-certificate-btn.secondary:hover {
        background: var(--ledipo-green-soft);
        color: var(--ledipo-green-dark);
      }

      @media (max-width: 600px) {
        .course-certificate-preview {
          min-height: 500px;
          aspect-ratio: auto;
          padding: 38px 24px 24px;
          border-width: 7px;
        }

        .course-certificate-preview::before {
          inset: 16px;
        }

        .course-certificate-logos {
          gap: 12px;
          margin-bottom: 20px;
        }

        .course-certificate-logos img {
          width: 36px;
          height: 36px;
        }

        .course-certificate-title {
          margin: 22px 0 18px;
        }

        .course-certificate-name {
          margin: 17px 0;
        }

        .course-certificate-signatories {
          width: 94%;
          gap: 16px;
          margin-top: 30px;
        }

        .course-certificate-footer {
          align-items: flex-start;
          font-size: 8px;
        }

        .course-certificate-actions {
          justify-content: stretch;
        }

        .course-certificate-btn {
          flex: 1 1 140px;
        }
      }

      @media print {
        @page {
          size: A4 landscape;
          margin: 0;
        }

        html,
        body {
          width: 297mm !important;
          height: 210mm !important;
          margin: 0 !important;
          padding: 0 !important;
          overflow: hidden !important;
          background: #fff !important;
        }

        body.course-certificate-printing * {
          visibility: hidden !important;
        }

        body.course-certificate-printing .course-certificate-preview.print-target,
        body.course-certificate-printing .course-certificate-preview.print-target * {
          visibility: visible !important;
        }

        /* Print only the certificate, sized to the printable A4 landscape page. */
        body.course-certificate-printing .course-certificate-preview.print-target {
          position: fixed !important;
          top: 8mm !important;
          left: 8mm !important;
          display: flex !important;
          flex-direction: column !important;
          justify-content: space-between !important;
          gap: 0 !important;
          box-sizing: border-box !important;
          width: 281mm !important;
          max-width: none !important;
          height: 194mm !important;
          min-height: 0 !important;
          aspect-ratio: auto !important;
          margin: 0 !important;
          padding: 10mm 13mm 7mm !important;
          border: 3mm solid #075b35 !important;
          border-radius: 0 !important;
          background: #fff !important;
          color: #202020 !important;
          box-shadow: none !important;
          filter: none !important;
          overflow: hidden !important;
          -webkit-print-color-adjust: exact !important;
          print-color-adjust: exact !important;
        }

        body.course-certificate-printing .course-certificate-preview.print-target::before {
          inset: 5mm !important;
          border: .35mm dashed #075b35 !important;
        }

        body.course-certificate-printing .course-certificate-preview.print-target>div:first-child {
          display: flex !important;
          flex-direction: column !important;
          align-items: center !important;
          width: 100% !important;
        }

        body.course-certificate-printing .course-certificate-logos {
          gap: 7mm !important;
          margin: 0 0 5mm !important;
        }

        body.course-certificate-printing .course-certificate-logos img {
          width: 16mm !important;
          height: 16mm !important;
          max-width: none !important;
          object-fit: contain !important;
        }

        body.course-certificate-printing .course-certificate-authority {
          font-size: 10.5pt !important;
          line-height: 1.4 !important;
        }

        body.course-certificate-printing .course-certificate-title {
          margin: 7mm 0 5mm !important;
          font-size: 25pt !important;
          line-height: 1.15 !important;
          letter-spacing: .03em !important;
          color: #075b35 !important;
        }

        body.course-certificate-printing .course-certificate-small {
          font-size: 8pt !important;
          line-height: 1.5 !important;
        }

        body.course-certificate-printing .course-certificate-name {
          margin: 5mm 0 !important;
          font-size: 31pt !important;
          line-height: 1.1 !important;
          color: #151515 !important;
        }

        body.course-certificate-printing .course-certificate-course {
          max-width: 95% !important;
          margin: 4mm auto 0 !important;
          font-size: 17pt !important;
          line-height: 1.25 !important;
          color: #075b35 !important;
        }

        body.course-certificate-printing .course-certificate-signatories {
          width: 72% !important;
          gap: 18mm !important;
          /* Keep the signature block lower, closer to the Admin certificate design. */
          margin: 25mm auto 0 !important;
        }

        body.course-certificate-printing .course-certificate-signatory {
          font-size: 8pt !important;
          line-height: 1.3 !important;
        }

        body.course-certificate-printing .course-certificate-signatory strong {
          font-size: 8pt !important;
        }

        body.course-certificate-printing .course-certificate-signatory span:last-child {
          font-size: 7pt !important;
        }

        body.course-certificate-printing .course-certificate-footer {
          width: 100% !important;
          margin-top: 5mm !important;
          font-size: 8pt !important;
          line-height: 1.3 !important;
        }
      }

      /* =========================================
                 LEDIPO — MINIMAL MY COURSES CARDS
              ========================================= */
      .courses-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        align-items: stretch;
        gap: 20px;
      }

      .course-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        overflow: hidden;
        border: 1px solid #e2ebe5;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(4, 63, 38, 0.035);
        transition: border-color 0.2s ease, box-shadow 0.2s ease,
          transform 0.2s ease;
      }

      .course-card:hover {
        transform: translateY(-3px);
        border-color: #b7d3bf;
        box-shadow: 0 10px 25px rgba(4, 63, 38, 0.08);
      }

      .card-banner {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 160px;
        flex-shrink: 0;
        overflow: hidden;
        background: #075b35;
        color: #fff;
        isolation: isolate;
      }

      .card-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: 1;
        background: linear-gradient(180deg,
            rgba(4, 35, 21, 0.12),
            rgba(4, 35, 21, 0.2));
        pointer-events: none;
      }

      .card-banner::after {
        display: none;
      }

      .card-banner-image {
        position: absolute;
        inset: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.3s ease;
      }

      .course-card:hover .card-banner-image {
        transform: scale(1.025);
      }

      .card-banner svg {
        position: relative;
        z-index: 1;
        width: 42px;
        height: 42px;
        fill: currentColor;
        opacity: 0.85;
      }

      .category-tag-overlay {
        position: absolute;
        z-index: 2;
        top: 12px;
        left: 12px;
        max-width: calc(100% - 24px);
        padding: 6px 10px;
        overflow: hidden;
        border-radius: 6px;
        background: #e1dc36;
        color: #26301b;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.2px;
        text-overflow: ellipsis;
        white-space: nowrap;
        box-shadow: none;
      }

      .card-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        min-width: 0;
        padding: 18px;
      }

      .course-card-title {
        display: -webkit-box;
        margin: 0 0 7px;
        overflow: hidden;
        color: #183329;
        font-size: 16px;
        font-weight: 750;
        line-height: 1.45;
        overflow-wrap: anywhere;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
      }

      .course-card-desc {
        display: -webkit-box;
        min-height: 0;
        margin: 0 0 16px;
        overflow: hidden;
        color: #718078;
        font-size: 12px;
        line-height: 1.7;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
      }

      /* Training schedule */
      .course-card-schedule {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 20px;
        padding: 11px 12px;
        border: 1px solid #e7eee9;
        border-radius: 9px;
        background: #f8fbf9;
      }

      .course-schedule-icon {
        display: grid;
        place-items: center;
        flex: 0 0 30px;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #eaf5ee;
        color: #075b35;
        font-size: 13px;
      }

      .course-schedule-content {
        display: flex;
        flex-direction: column;
        min-width: 0;
        gap: 3px;
      }

      .course-schedule-label {
        color: #78877e;
        font-size: 10px;
        font-weight: 600;
      }

      .course-schedule-value {
        color: #294936;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.5;
        overflow-wrap: anywhere;
      }

      /* Minimal training timetable */
      .course-timetable {
        display: block;
        margin-bottom: 20px;
        padding: 13px;
        border: 1px solid #e2ebe5;
        border-radius: 11px;
        background: #f8fbf9;
      }

      .course-timetable-heading {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e5eee8;
        color: #075b35;
        font-size: 12px;
        font-weight: 800;
      }

      .course-timetable-heading-icon {
        display: grid;
        place-items: center;
        flex: 0 0 27px;
        width: 27px;
        height: 27px;
        border-radius: 8px;
        background: #eaf5ee;
        color: #075b35;
      }

      .course-timetable-row {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        padding: 7px 0;
      }

      .course-timetable-icon {
        display: grid;
        place-items: center;
        flex: 0 0 28px;
        width: 28px;
        height: 28px;
        border: 1px solid #e2ebe5;
        border-radius: 8px;
        background: #fff;
        color: #075b35;
        font-size: 12px;
      }

      .course-timetable-detail {
        display: flex;
        flex: 1;
        flex-direction: column;
        min-width: 0;
        gap: 3px;
      }

      .course-timetable-label {
        color: #78877e;
        font-size: 10px;
        font-weight: 600;
      }

      .course-timetable-value {
        color: #294936;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.5;
        overflow-wrap: anywhere;
      }

      .course-timetable-note {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        margin-top: 9px;
        padding-top: 10px;
        border-top: 1px solid #e5eee8;
        color: #6b7c73;
        font-size: 10px;
        line-height: 1.5;
      }

      .course-timetable-note i {
        flex-shrink: 0;
        margin-top: 2px;
        color: #075b35;
      }

      @media (max-width: 600px) {
        .course-timetable {
          padding: 11px;
        }
      }

      /* Progress */
      .course-card-progress {
        margin-top: auto;
        padding: 0;
        border: 0;
        background: transparent;
      }

      .progress-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
        color: #718078;
        font-size: 11px;
        font-weight: 600;
      }

      .progress-heading strong {
        color: #075b35;
        font-size: 12px;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
      }

      .progress-bar-bg {
        width: 100%;
        height: 6px;
        margin-bottom: 0;
        overflow: hidden;
        border-radius: 999px;
        background: #e8eee9;
      }

      .progress-bar-fill {
        height: 100%;
        border-radius: inherit;
        background: #e1dc36;
        transition: width 0.3s ease;
      }

      /* Action button */
      .btn-start-course {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        width: 100%;
        box-sizing: border-box;
        margin-top: 18px;
        padding: 9px 11px 9px 14px;
        border: 1px solid #075b35;
        border-radius: 9px;
        background: #075b35;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        text-decoration: none;
        transition: background 0.2s ease, border-color 0.2s ease;
      }

      .course-button-arrow {
        display: grid;
        place-items: center;
        flex: 0 0 26px;
        width: 26px;
        height: 26px;
        border-radius: 7px;
        background: rgba(255, 255, 255, 0.13);
        font-size: 16px;
        transition: transform 0.2s ease;
      }

      .btn-start-course:hover,
      .btn-start-course:focus-visible {
        border-color: #043f26;
        background: #043f26;
        color: #fff;
      }

      .btn-start-course:hover .course-button-arrow {
        transform: translateX(2px);
      }

      .btn-start-course:focus-visible {
        outline: 2px solid #e1dc36;
        outline-offset: 3px;
      }

      .no-data {
        grid-column: 1 / -1;
      }

      /* Responsive layout */
      @media (max-width: 1050px) {
        .courses-grid {
          grid-template-columns: repeat(2, minmax(0, 1fr));
          gap: 18px;
        }
      }

      @media (max-width: 600px) {
        .courses-grid {
          grid-template-columns: minmax(0, 1fr);
          gap: 16px;
        }

        .card-banner {
          height: 155px;
        }

        .card-body {
          padding: 16px;
        }
      }

      @media (prefers-reduced-motion: reduce) {

        .course-card,
        .card-banner-image,
        .btn-start-course,
        .course-button-arrow,
        .progress-bar-fill {
          transition: none;
        }

        .course-card:hover,
        .course-card:hover .card-banner-image {
          transform: none;
        }
      }
    </style>
  @endsection

  @section('content')
    <div class="courses-overview-wrap">

      @if ($enrollment && request('course_id'))

        @php
          // A course is complete only when every active module is marked done
          // and every course quiz has a passing result. This does not alter
          // the existing overallProgress/score calculation.
          $courseHasModules = $modules->isNotEmpty();
          $allModulesCompleted =
              $courseHasModules &&
              count($completedModuleIds) >= $modules->count();

          $allCourseQuizzesPassed = $quizzes->every(function ($quiz) use (
              $quizResults,
          ) {
              // A quiz is considered passed if the student has at least
              // one passing attempt, even if earlier attempts failed.
              return $quizResults
                  ->where('quiz_id', $quiz->id)
                  ->contains(function ($attempt) {
                      return strtolower((string) ($attempt->status ?? '')) ===
                          'passed';
                  });
          });

          $courseFullyCompleted =
              $allModulesCompleted && $allCourseQuizzesPassed;

          $courseCertificate = $certificate ?? null;
          $certificateIsReleased =
              $courseCertificate &&
              in_array(
                  strtolower((string) ($courseCertificate->status ?? '')),
                  ['released', 'claimed'],
                  true,
              );
          $certificateIsCompetent =
              $courseCertificate &&
              strtolower(trim((string) ($courseCertificate->remarks ?? ''))) ===
                  'competent';
          $certificateCanPrint =
              $certificateIsReleased && $certificateIsCompetent;
          $certificateStudentName = trim(
              (Auth::user()->firstname ?? '') .
                  ' ' .
                  (Auth::user()->lastname ?? ''),
          );
          $certificateCourseName =
              $enrollment->course->title ?? 'Training Course';
          $certificatePreviewId =
              'course-certificate-preview-' . $enrollment->course_id;
        @endphp

        <a href="{{ route('student.modules') }}" class="btn-back-overview">
          ← Back to Overview
        </a>

        <div class="course-detail-container">

          <section id="courseOverview" data-content-screen="introduction">
            <h1 class="course-main-header">
              {{ strtoupper($enrollment->course->title) }}
            </h1>

            <div class="course-top-banner">

              <div class="course-banner-header">
                <div class="course-banner-title">

                  <div>
                    <h2>Course Progress</h2>
                    <p>
                      Duration:
                      {{ $enrollment->course->duration ?? 'TBA' }} Days
                    </p>
                  </div>
                </div>

                <div class="course-schedule-badge">
                  <i class="fa-regular fa-calendar"></i>
                  <span>
                    Schedule: {{ $enrollment->course->schedule ?? 'TBA' }}
                  </span>
                </div>
              </div>

              <div class="course-banner-divider"></div>

              <div class="course-progress-details">
                <div>
                  <span class="course-progress-caption">
                    Overall Progress
                  </span>

                  <div class="course-progress-percentage">
                    {{ $overallProgress }}<span>%</span>
                  </div>
                </div>

                <div class="course-progress-category">
                  <span>Quizzes + Onsite Scores</span>
                </div>
              </div>

              <div class="course-progress-track" role="progressbar"
                aria-label="Overall course progress" aria-valuemin="0"
                aria-valuemax="100" aria-valuenow="{{ $overallProgress }}">
                <div class="course-progress-fill"
                  style="width: {{ $overallProgress }}%;"></div>
              </div>

              <p class="course-progress-note">
                <i class="fa-solid fa-circle-check"></i>
                Based on your recorded assessment scores
              </p>

            </div>

            @if ($enrollment->course->description)
              <p class="welcome-desc">
                {{ $enrollment->course->description }}
              </p>
            @endif

            {{-- INTRODUCTION: COURSE SYLLABUS --}}
            @if (
                !empty($enrollment->course->syllabus_path) &&
                    \Illuminate\Support\Facades\Storage::disk('public')->exists(
                        $enrollment->course->syllabus_path))
              @php
                $syllabusPath = $enrollment->course->syllabus_path;
                $syllabusUrl = asset('storage/' . $syllabusPath);
                $syllabusExtension = strtolower(
                    pathinfo($syllabusPath, PATHINFO_EXTENSION),
                );
              @endphp

              <section id="courseSyllabus" class="course-syllabus-section">
                <div class="course-syllabus-heading">
                  <div class="course-syllabus-icon">
                    <i class="fa fa-file-pdf"></i>
                  </div>

                  <div>
                    <h3>
                      {{ $enrollment->course->syllabus_title ?: 'Course Syllabus' }}
                    </h3>
                    <p>Review the syllabus and course requirements below.</p>
                  </div>
                </div>

                @if ($syllabusExtension === 'pdf')
                  <div class="course-syllabus-viewer">
                    <iframe
                      src="{{ $syllabusUrl }}#toolbar=1&navpanes=0&view=FitH"
                      title="Course Syllabus PDF" loading="lazy">
                    </iframe>
                  </div>
                @else
                  <div class="course-syllabus-download">
                    <i class="fa fa-file-lines"></i>
                    <span>
                      Your course syllabus is available as a document.
                    </span>
                    <a href="{{ $syllabusUrl }}" target="_blank"
                      rel="noopener noreferrer">
                      Open Syllabus
                    </a>
                  </div>
                @endif
              </section>
            @elseif (!empty($enrollment->course->syllabus_content))
              <section id="courseSyllabus" class="course-syllabus-section">
                <div class="course-syllabus-heading">
                  <div class="course-syllabus-icon">
                    <i class="fa fa-book-open"></i>
                  </div>
                  <div>
                    <h3>
                      {{ $enrollment->course->syllabus_title ?: 'Course Syllabus' }}
                    </h3>
                    <p>Course syllabus and requirements</p>
                  </div>
                </div>

                <div class="course-syllabus-content">
                  {!! nl2br(e($enrollment->course->syllabus_content)) !!}
                </div>
              </section>
            @endif

            @if ($enrollment->course->objectives)
              <div class="section-label">Objectives</div>
              <p class="welcome-desc">
                {{ $enrollment->course->objectives }}
              </p>
            @endif

            <hr class="content-hr">
          </section>

          {{-- CERTIFICATE: rendered on the page but shown only when Completion is clicked --}}
          <section class="course-certificate-section"
            id="courseCompletionCertificate" hidden
            aria-labelledby="courseCompletionCertificateTitle">
            <div class="course-certificate-heading">
              <div>
                <h3 id="courseCompletionCertificateTitle"><i
                    class="fa-solid fa-award"
                    style="color:var(--ledipo-green);margin-right:7px;"></i>Course
                  Completion Certificate</h3>
                <p>
                  @if ($certificateCanPrint)
                    Your certificate has been marked Competent and released. You
                    can print it or save it as PDF.
                  @elseif ($courseFullyCompleted)
                    Your course requirements are complete. This black-and-white
                    preview will remain until the administrator marks the
                    certificate Competent and releases it.
                  @else
                    This is a black-and-white certificate preview. Complete all
                    course modules and pass all quizzes; the administrator must
                    then mark it Competent and release it before printing is
                    enabled.
                  @endif
                </p>
              </div>
              <span
                class="course-certificate-badge {{ $certificateCanPrint ? 'ready' : '' }}">
                <i
                  class="fa {{ $certificateCanPrint ? 'fa-check-circle' : 'fa-clock' }}"></i>
                {{ $certificateCanPrint ? 'Released · Competent' : 'Preview · Awaiting release' }}
              </span>
            </div>

            <div
              class="course-certificate-preview {{ $certificateCanPrint ? 'is-color' : '' }}"
              id="{{ $certificatePreviewId }}">
              <div>
                <div class="course-certificate-logos">
                  <img src="{{ asset('images/logo.png') }}" alt="Partner logo">
                  <img src="{{ asset('images/tesda.png') }}" alt="TESDA logo">
                  <img src="{{ asset('images/logo_ledipo.png') }}"
                    alt="LEDIPO logo">
                </div>
                <div class="course-certificate-authority">
                  Technical Education and Skills Development Authority<br>
                  City Government of Dasmariñas — LEDIPO
                </div>
                <h2 class="course-certificate-title">Certificate of
                  {{ $courseCertificate->document_type ?? 'Completion' }}</h2>
                <p class="course-certificate-small">This certifies that</p>
                <div class="course-certificate-name">
                  {{ $certificateStudentName ?: 'Trainee Name' }}</div>
                <p class="course-certificate-small">has successfully completed the
                  training in</p>
                <div class="course-certificate-course">
                  {{ $certificateCourseName }}</div>
                <div class="course-certificate-signatories">
                  <div class="course-certificate-signatory">
                    <span class="course-certificate-signature-line"></span>
                    <strong>HON. JENNIFER A. BARZAGA</strong>
                    <span>City Mayor</span>
                  </div>
                  <div class="course-certificate-signatory">
                    <span class="course-certificate-signature-line"></span>
                    <strong>MR. CARLOS H. LEGASPI</strong>
                    <span>LEDIPO Head</span>
                  </div>
                </div>
              </div>
              <div class="course-certificate-footer">
                <span>Cert. No.:
                  {{ $courseCertificate->certificate_no ?? 'Pending issuance' }}</span>
                <span>Training ID:
                  {{ $courseCertificate->training_id ?? ($enrollment->course->training_id ?? 'Pending assignment') }}</span>
              </div>
            </div>

            @if ($certificateCanPrint)
              <div class="course-certificate-actions">
                <button type="button" class="course-certificate-btn secondary"
                  onclick="printCourseCertificate('{{ $certificatePreviewId }}')">
                  <i class="fa-solid fa-print"></i> Print
                </button>
                <button type="button" class="course-certificate-btn"
                  onclick="printCourseCertificate('{{ $certificatePreviewId }}')">
                  <i class="fa-solid fa-file-pdf"></i> Download / Save as PDF
                </button>
              </div>
              <p class="course-certificate-note">To save a PDF, choose
                <strong>Save as PDF</strong> in your browser's print dialog.
              </p>
            @else
              <p class="course-certificate-note">
                Your course is complete. The preview stays black and white until
                the administrator releases a certificate marked Competent.
                Download and print options will appear after release.
              </p>
            @endif
          </section>

          <section id="modulesSection" data-content-screen="modules">

            @php
              $groupedModules = $modules->groupBy('unit_number');
            @endphp

            @forelse ($groupedModules as $unitNum => $unitModules)
              <div class="sidebar-unit-heading"
                data-unit-number="{{ $unitNum }}"
                style="font-weight:700;font-size:15px;color:#025628;margin:16px 0 8px;">
                Unit {{ $unitNum }}
              </div>

              @foreach ($unitModules as $i => $module)
                @php
                  $isDone = in_array($module->id, $completedModuleIds);
                @endphp

                <div class="module-action-row"
                  id="module-item-{{ $module->id }}"
                  data-module-id="{{ $module->id }}"
                  data-unit-number="{{ $module->unit_number }}">
                  <div>
                    <div class="module-action-title">
                      {{ $i + 1 }}. {{ $module->title }}
                    </div>

                    @if ($module->description)
                      <div style="font-size:12px;color:#718096;margin-top:2px;">
                        {{ $module->description }}
                      </div>
                    @endif
                  </div>

                  <div class="module-buttons-group">
                    @if ($module->file_path)
                      @if (strtolower(pathinfo($module->file_path, PATHINFO_EXTENSION)) !== 'pdf')
                        <a href="{{ asset('storage/' . $module->file_path) }}"
                          target="_blank" rel="noopener noreferrer"
                          class="btn-module-view">
                          Open File
                        </a>
                      @else
                        <a href="{{ asset('storage/' . $module->file_path) }}"
                          target="_blank" rel="noopener noreferrer"
                          class="btn-module-view">
                          Open PDF in New Tab
                        </a>
                      @endif
                    @else
                      <span style="font-size:12px;color:#a0aec0;">No file</span>
                    @endif

                    @if ($isDone)
                      <span class="btn-module-done completed">✓ Done</span>
                    @else
                      <button type="button"
                        onclick="markDone({{ $module->id }}, this)"
                        data-module-id="{{ $module->id }}"
                        class="btn-module-done">
                        Mark as Done
                      </button>
                    @endif
                  </div>
                </div>

                {{-- INLINE PDF PREVIEW: visible without clicking View --}}
                @if (
                    $module->file_path &&
                        strtolower(pathinfo($module->file_path, PATHINFO_EXTENSION)) === 'pdf')
                  <div class="module-pdf-preview"
                    data-module-id="{{ $module->id }}"
                    data-unit-number="{{ $module->unit_number }}">
                    <div class="module-pdf-preview-heading">
                      <span> {{ $module->title }} — Learning Material</span>
                      <a class="module-pdf-open-link"
                        href="{{ asset('storage/' . $module->file_path) }}"
                        target="_blank" rel="noopener noreferrer">Open PDF in
                        new
                        tab</a>
                    </div>
                    <iframe class="module-pdf-frame"
                      src="{{ asset('storage/' . $module->file_path) }}#toolbar=1&navpanes=0&view=FitH"
                      title="PDF learning material: {{ $module->title }}"
                      loading="lazy">
                      Your browser cannot display this PDF inline.
                      <a href="{{ asset('storage/' . $module->file_path) }}"
                        target="_blank" rel="noopener noreferrer">Open the
                        PDF</a>.
                    </iframe>
                  </div>
                @endif

                {{-- QUIZZES LINKED TO THIS MODULE --}}
                @php
                  $moduleQuizzes = $quizzes->where('module_id', $module->id);
                @endphp

                @if ($moduleQuizzes->isNotEmpty())
                  <div class="module-quizzes-wrap"
                    data-module-id="{{ $module->id }}"
                    data-unit-number="{{ $module->unit_number }}">
                    <div class="module-quizzes-heading">
                      <span aria-hidden="true"></span>
                      <span>Quiz for this module</span>
                    </div>
@foreach ($moduleQuizzes as $quiz)
    @php
        /*
         * quiz_results stores one summary/best-result row per student + quiz.
         * quiz_attempt_histories stores each individual submission.
         * Do not use quiz_results as the attempt-history collection.
         */
        $allAttemptHistories = $allQuizAttemptHistories ?? collect();

        $quizAttempts = $allAttemptHistories
            ->where('quiz_id', $quiz->id)
            ->sortBy(function ($attempt) {
                return (int) ($attempt->attempt_number ?? $attempt->id ?? 0);
            })
            ->values();

        // Summary/best result for this quiz, if one exists.
        $latestResult = $quizResults->firstWhere('quiz_id', $quiz->id);

        $attemptsAllowed = max(
            1,
            (int) ($quiz->attempts_allowed ?? 2)
        );

        // Use both sources to avoid under-counting attempts if the summary
        // counter is newer than the history rows.
        $attemptsUsed = max(
            (int) ($latestResult->attempts_used ?? 0),
            $quizAttempts->count()
        );

        // A pass on any recorded attempt permanently closes retries.
        $hasPassed = $quizAttempts->contains(function ($attempt) {
            return strtolower((string) ($attempt->status ?? '')) === 'passed';
        }) || strtolower((string) ($latestResult->status ?? '')) === 'passed';

        $canRetry = !$hasPassed && $attemptsUsed < $attemptsAllowed;

        $attemptsRemaining = max(0, $attemptsAllowed - $attemptsUsed);
    @endphp

    <div
        class="pretest-action-bar module-quiz-action-bar"
        id="quiz-item-{{ $quiz->id }}"
        data-quiz-id="{{ $quiz->id }}"
        data-module-id="{{ $module->id }}"
        data-unit-number="{{ $module->unit_number }}"
    >
        <div class="module-quiz-info">
            <div class="module-quiz-title">
                {{ $quiz->title }}
            </div>

            <div class="module-quiz-meta">
    {{ $quiz->time_limit }} mins
    <span aria-hidden="true">·</span>
    {{ $quiz->passing_score }}% to pass
    <span aria-hidden="true">·</span>
    {{ $attemptsUsed }}/{{ $attemptsAllowed }} used
    <span aria-hidden="true">·</span>

    <strong style="color: {{ $attemptsRemaining > 0 && !$hasPassed ? '#075b35' : '#b42318' }};">
        {{ $hasPassed ? 0 : $attemptsRemaining }}
        {{ ($hasPassed ? 0 : $attemptsRemaining) === 1 ? 'attempt' : 'attempts' }}
        left
    </strong>
</div>
        </div>

        <div class="module-buttons-group">
            @if ($latestResult)
                <span
                    class="pretest-score module-quiz-result"
                    style="color: {{ strtolower($latestResult->status ?? '') === 'passed' ? '#276749' : '#c53030' }};"
                >
                    {{ ucfirst($latestResult->status ?? 'Completed') }}
                    · {{ $latestResult->percentage ?? 0 }}%
                </span>
            @endif

            @if ($canRetry)
                <button
                    type="button"
                    onclick='openQuiz(@json($quiz->id), @json($quiz->title))'
                    class="pretest-btn"
                >
                    {{ $attemptsUsed > 0 ? 'Retry Quiz' : 'Take Quiz' }}
                    @if ($attemptsUsed > 0)
                        ({{ $attemptsRemaining }} left)
                    @endif
                </button>
            @elseif ($latestResult && !$hasPassed)
                <span class="module-quiz-meta">
                    No attempts remaining
                </span>
            @endif
        </div>
    </div>

    {{-- Show every saved attempt for this quiz, not only the latest score. --}}
    @if ($quizAttempts->isNotEmpty())
        <div class="quiz-attempt-history" aria-label="Quiz attempt history for {{ $quiz->title }}">
            <div class="quiz-attempt-history-heading">
                <strong>Attempt History</strong>
                <span class="quiz-attempt-history-count">
                    {{ $quizAttempts->count() }} of {{ $attemptsAllowed }} attempt(s)
                </span>
            </div>

            @foreach ($quizAttempts->sortBy(function ($attempt) {
                return (int) ($attempt->attempt_number ?? $attempt->id ?? 0);
            })->values() as $attempt)
                @php
                    $attemptStatus = strtolower((string) ($attempt->status ?? 'completed'));
                    $attemptPassed = $attemptStatus === 'passed';
                    $attemptNumber = $attempt->attempt_number ?? $loop->iteration;
                @endphp
                <div class="quiz-attempt-row">
                    <div class="quiz-attempt-details">
                        <span class="quiz-attempt-name">Attempt {{ $attemptNumber }}</span>
                        <span class="quiz-attempt-date">
                            {{ $attempt->created_at ? $attempt->created_at->format('M d, Y · h:i A') : 'Date unavailable' }}
                        </span>
                    </div>
                    <div class="quiz-attempt-result">
                        <span class="quiz-attempt-percentage">{{ number_format((float) ($attempt->percentage ?? 0), 1) }}%</span>
                        <span class="quiz-attempt-status {{ $attemptPassed ? 'passed' : 'failed' }}">
                            {{ ucfirst($attemptStatus) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endforeach
                  </div>
                @endif
              @endforeach

            @empty
              <div class="no-data">
                No modules available yet for this course.
              </div>
            @endforelse

          </section>

          {{-- COURSE-LEVEL QUIZZES: quizzes not linked to a specific module --}}
          @php
            $courseLevelQuizzes = $quizzes->filter(function ($quiz) {
                return empty($quiz->module_id);
            });
          @endphp

          @if ($courseLevelQuizzes->isNotEmpty())
            <section class="course-level-quizzes-wrap"
              data-content-screen="course-quizzes">
              <hr class="content-hr">
              <div class="section-label">General Course Quizzes</div>
@foreach ($courseLevelQuizzes as $quiz)
    @php
        /*
         * quiz_results stores one summary/best-result row per student + quiz.
         * quiz_attempt_histories stores each individual submission.
         * Do not use quiz_results as the attempt-history collection.
         */
        $allAttemptHistories = $allQuizAttemptHistories ?? collect();

        $quizAttempts = $allAttemptHistories
            ->where('quiz_id', $quiz->id)
            ->sortBy(function ($attempt) {
                return (int) ($attempt->attempt_number ?? $attempt->id ?? 0);
            })
            ->values();

        // Summary/best result for this quiz, if one exists.
        $latestResult = $quizResults->firstWhere('quiz_id', $quiz->id);

        $attemptsAllowed = max(
            1,
            (int) ($quiz->attempts_allowed ?? 2)
        );

        // Use both sources to avoid under-counting attempts if the summary
        // counter is newer than the history rows.
        $attemptsUsed = max(
            (int) ($latestResult->attempts_used ?? 0),
            $quizAttempts->count()
        );

        // A pass on any recorded attempt permanently closes retries.
        $hasPassed = $quizAttempts->contains(function ($attempt) {
            return strtolower((string) ($attempt->status ?? '')) === 'passed';
        }) || strtolower((string) ($latestResult->status ?? '')) === 'passed';

        $canRetry = !$hasPassed && $attemptsUsed < $attemptsAllowed;

        $attemptsRemaining = max(0, $attemptsAllowed - $attemptsUsed);
    @endphp

    <div class="pretest-action-bar">
        <div>
            <div style="font-weight:700;font-size:14px;color:#2d3748;">
                {{ $quiz->title }}
            </div>

            <div style="font-size:12px;color:#718096;margin-top:2px;">
                {{ $quiz->time_limit }} mins
                &nbsp;·&nbsp;
                {{ $quiz->passing_score }}% to pass
                &nbsp;·&nbsp;
                {{ $attemptsUsed }}/{{ $attemptsAllowed }} attempts used
            </div>
        </div>

        <div class="module-buttons-group">
            @if ($latestResult)
                <span
                    class="pretest-score"
                    style="color: {{ strtolower($latestResult->status ?? '') === 'passed' ? '#276749' : '#c53030' }};"
                >
                    {{ ucfirst($latestResult->status ?? 'Completed') }}
                    · {{ $latestResult->percentage ?? 0 }}%
                </span>
            @endif

            @if ($canRetry)
                <button
                    type="button"
                    onclick='openQuiz(@json($quiz->id), @json($quiz->title))'
                    class="pretest-btn"
                >
                    {{ $attemptsUsed > 0 ? 'Retry Quiz' : 'Take Quiz' }}
                    @if ($attemptsUsed > 0)
                        ({{ $attemptsRemaining }} left)
                    @endif
                </button>
            @elseif ($latestResult && !$hasPassed)
                <span style="font-size:12px;color:#718096;">
                    No attempts remaining
                </span>
            @endif
        </div>
    </div>

    {{-- Show every saved attempt for this general course quiz. --}}
    @if ($quizAttempts->isNotEmpty())
        <div class="quiz-attempt-history" aria-label="Quiz attempt history for {{ $quiz->title }}">
            <div class="quiz-attempt-history-heading">
                <strong>Attempt History</strong>
                <span class="quiz-attempt-history-count">
                    {{ $quizAttempts->count() }} of {{ $attemptsAllowed }} attempt(s)
                </span>
            </div>

            @foreach ($quizAttempts->sortBy(function ($attempt) {
                return (int) ($attempt->attempt_number ?? $attempt->id ?? 0);
            })->values() as $attempt)
                @php
                    $attemptStatus = strtolower((string) ($attempt->status ?? 'completed'));
                    $attemptPassed = $attemptStatus === 'passed';
                    $attemptNumber = $attempt->attempt_number ?? $loop->iteration;
                @endphp
                <div class="quiz-attempt-row">
                    <div class="quiz-attempt-details">
                        <span class="quiz-attempt-name">Attempt {{ $attemptNumber }}</span>
                        <span class="quiz-attempt-date">
                            {{ $attempt->created_at ? $attempt->created_at->format('M d, Y · h:i A') : 'Date unavailable' }}
                        </span>
                    </div>
                    <div class="quiz-attempt-result">
                        <span class="quiz-attempt-percentage">{{ number_format((float) ($attempt->percentage ?? 0), 1) }}%</span>
                        <span class="quiz-attempt-status {{ $attemptPassed ? 'passed' : 'failed' }}">
                            {{ ucfirst($attemptStatus) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endforeach
            </section>
          @endif

        </div>
      @else
        <h1 class="page-title">My Learning</h1>

        <p class="page-subtitle">
          Pick up where you left off and keep building your skills.
        </p>

        @php
          $totalEnrolledCourses = $enrollments->count();

          $completedCoursesCount = $enrollments
              ->filter(function ($item) {
                  return ($item->status ?? '') === 'completed' ||
                      (int) ($item->progress ?? 0) >= 100;
              })
              ->count();

          $inProgressCoursesCount = $enrollments
              ->filter(function ($item) {
                  return ($item->status ?? '') !== 'completed' &&
                      (int) ($item->progress ?? 0) > 0 &&
                      (int) ($item->progress ?? 0) < 100;
              })
              ->count();
        @endphp

        <div class="learning-stats" aria-label="Course summary">

          <div class="learning-stat">
            <div class="learning-stat-icon" aria-hidden="true">▤</div>
            <div>
              <span class="learning-stat-value">
                {{ $totalEnrolledCourses }}
              </span>
              <span class="learning-stat-label">Enrolled courses</span>
            </div>
          </div>

          <div class="learning-stat">
            <div class="learning-stat-icon" aria-hidden="true">◷</div>
            <div>
              <span class="learning-stat-value">
                {{ $inProgressCoursesCount }}
              </span>
              <span class="learning-stat-label">In progress</span>
            </div>
          </div>

          <div class="learning-stat">
            <div class="learning-stat-icon" aria-hidden="true">✓</div>
            <div>
              <span class="learning-stat-value">
                {{ $completedCoursesCount }}
              </span>
              <span class="learning-stat-label">Completed</span>
            </div>
          </div>

        </div>

        <div class="courses-grid">

          @forelse ($enrollments as $e)
            @php
              $course = $e->course;

              $cardProgress = min(100, max(0, (float) ($overallProgress ?? 0)));
              $cardProgressDisplay = (int) round($cardProgress);

              // Course banner
              $bannerImagePath = $course->banner_image ?? null;
              $bannerImageUrl = null;

              if (
                  is_string($bannerImagePath) &&
                  trim($bannerImagePath) !== ''
              ) {
                  $bannerImagePath = trim($bannerImagePath);

                  if (preg_match('/^https?:\/\//i', $bannerImagePath)) {
                      $bannerImageUrl = $bannerImagePath;
                  } else {
                      $bannerImagePath = ltrim($bannerImagePath, '/');

                      $bannerImageUrl = str_starts_with(
                          $bannerImagePath,
                          'storage/',
                      )
                          ? asset($bannerImagePath)
                          : asset('storage/' . $bannerImagePath);
                  }
              }

              $courseTitle = $course->title ?? 'Untitled Course';
              $courseDescription = trim((string) ($course->description ?? ''));
              $courseSchedule = trim((string) ($course->schedule ?? ''));

              // Training days saved in the database
              $trainingDays = $course->training_days ?? [];

              if (is_string($trainingDays)) {
                  $decodedDays = json_decode($trainingDays, true);
                  $trainingDays = is_array($decodedDays) ? $decodedDays : [];
              }

              $trainingDays = is_array($trainingDays)
                  ? array_values(
                      array_filter(
                          $trainingDays,
                          fn($day) => is_string($day) && trim($day) !== '',
                      ),
                  )
                  : [];

              $trainingDaysLabel = count($trainingDays)
                  ? implode(', ', $trainingDays)
                  : 'To be announced';

              // Training period
              $trainingStartDate = $course->start_date
                  ? \Illuminate\Support\Carbon::parse(
                      $course->start_date,
                  )->format('M d, Y')
                  : null;

              $trainingEndDate = $course->end_date
                  ? \Illuminate\Support\Carbon::parse(
                      $course->end_date,
                  )->format('M d, Y')
                  : null;

              if ($trainingStartDate && $trainingEndDate) {
                  $trainingPeriod =
                      $trainingStartDate . ' – ' . $trainingEndDate;
              } elseif ($trainingStartDate) {
                  $trainingPeriod = 'Starts ' . $trainingStartDate;
              } elseif ($trainingEndDate) {
                  $trainingPeriod = 'Ends ' . $trainingEndDate;
              } else {
                  $trainingPeriod = 'Dates to be announced';
              }

              // Training hours
              $trainingStartTime = $course->start_time
                  ? \Illuminate\Support\Carbon::parse(
                      $course->start_time,
                  )->format('g:i A')
                  : null;

              $trainingEndTime = $course->end_time
                  ? \Illuminate\Support\Carbon::parse(
                      $course->end_time,
                  )->format('g:i A')
                  : null;

              $trainingHours =
                  $trainingStartTime && $trainingEndTime
                      ? $trainingStartTime . ' – ' . $trainingEndTime
                      : 'Time to be announced';

              // Duration
              $trainingDuration = $course->duration_days ?? null;

              if (!$trainingDuration && !empty($course->duration)) {
                  $trainingDuration = $course->duration;
              }

              $trainingDurationLabel = $trainingDuration
                  ? $trainingDuration .
                      (is_numeric($trainingDuration)
                          ? ((int) $trainingDuration === 1
                              ? ' day'
                              : ' days')
                          : '')
                  : 'To be announced';
            @endphp

            <article class="course-card">

              {{-- Course cover --}}
              <div
                class="card-banner {{ $bannerImageUrl ? 'has-banner-image' : '' }}">
                @if ($bannerImageUrl)
                  <img src="{{ $bannerImageUrl }}"
                    alt="{{ $courseTitle }} banner" class="card-banner-image"
                    loading="lazy"
                    onerror="this.remove(); this.parentElement.classList.remove('has-banner-image');">
                @else
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path
                      d="M19 2H6c-1.2 0-2 .8-2 2v16c0 1.2.8 2 2 2h13c1.1 0 2-.9 2-2V4c0-1.2-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4zm13 16H6c-.6 0-1-.4-1-1s.4-1 1-1h13v2zm0-3H6c-.2 0-.4 0-.6.1V14h13.6v3z" />
                  </svg>
                @endif

              </div>

              {{-- Course details --}}
              <div class="card-body">

                <h3 class="course-card-title">
                  {{ $courseTitle }}
                </h3>

                <p class="course-card-desc">
                  {{ $courseDescription !== ''
                      ? \Illuminate\Support\Str::limit($courseDescription, 105)
                      : 'Explore the learning materials and activities for this training course.' }}
                </p>

                {{-- Training timetable --}}
                <div class="course-timetable">

                  <div class="course-timetable-heading">
                    <span class="course-timetable-heading-icon">
                      <i class="fa-regular fa-calendar-check"></i>
                    </span>
                    <span>Training Schedule</span>
                  </div>

                  <div class="course-timetable-row">
                    <span class="course-timetable-icon">
                      <i class="fa-regular fa-calendar"></i>
                    </span>
                    <div class="course-timetable-detail">
                      <span class="course-timetable-label">Training days</span>
                      <span class="course-timetable-value">
                        {{ $trainingDaysLabel }}
                      </span>
                    </div>
                  </div>

                  <div class="course-timetable-row">
                    <span class="course-timetable-icon">
                      <i class="fa-regular fa-clock"></i>
                    </span>
                    <div class="course-timetable-detail">
                      <span class="course-timetable-label">Training hours</span>
                      <span class="course-timetable-value">
                        {{ $trainingHours }}
                      </span>
                    </div>
                  </div>

                  <div class="course-timetable-row">
                    <span class="course-timetable-icon">
                      <i class="fa-regular fa-calendar-days"></i>
                    </span>
                    <div class="course-timetable-detail">
                      <span class="course-timetable-label">Training period</span>
                      <span class="course-timetable-value">
                        {{ $trainingPeriod }}
                      </span>
                    </div>
                  </div>

                  <div class="course-timetable-row">
                    <span class="course-timetable-icon">
                      <i class="fa-solid fa-hourglass-half"></i>
                    </span>
                    <div class="course-timetable-detail">
                      <span class="course-timetable-label">Duration</span>
                      <span class="course-timetable-value">
                        {{ $trainingDurationLabel }}
                      </span>
                    </div>
                  </div>

                  @if ($courseSchedule !== '')
                    <div class="course-timetable-note">
                      <i class="fa-solid fa-circle-info"></i>
                      <span>{{ $courseSchedule }}</span>
                    </div>
                  @endif

                </div>

                {{-- Learning progress --}}
                <div class="course-card-progress">
                  <div class="progress-heading">
                    <span>Overall progress</span>
                    <strong>{{ $cardProgressDisplay }}%</strong>
                  </div>

                  <div class="progress-bar-bg" role="progressbar"
                    aria-label="Course learning progress"
                    aria-valuenow="{{ $cardProgressDisplay }}"
                    aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar-fill"
                      style="width: {{ $cardProgress }}%;"></div>
                  </div>
                </div>

                {{-- Existing course navigation --}}
                <a href="{{ route('student.modules', ['course_id' => $e->course_id]) }}"
                  class="btn-start-course">
                  <span class="course-button-label">
                    {{ $cardProgressDisplay > 0 ? 'Continue Learning' : 'Start Learning' }}
                  </span>

                  <span class="course-button-arrow" aria-hidden="true">→</span>
                </a>

              </div>
            </article>

          @empty
            <div class="no-data">
              <strong>No enrolled courses yet</strong>
              <p>Your enrolled training courses will appear here.</p>
            </div>
          @endforelse

        </div>

      @endif

      {{-- QUIZ MODAL --}}
      <div id="quizModal" class="lms-quiz-overlay" style="display:none;">
        <div class="lms-quiz-modal" role="dialog" aria-modal="true"
          aria-labelledby="quizModalTitle">

          <header class="lms-quiz-header">
            <div class="lms-quiz-brand">
              <div class="lms-quiz-logo">L</div>
              <div>
                <div class="lms-quiz-brand-name">LEDIPO LEARNING PORTAL</div>
                <div class="lms-quiz-brand-caption">Student Assessment</div>
              </div>
            </div>

            <button type="button" id="quizCloseButton" class="lms-quiz-close"
              onclick="closeQuiz()" aria-label="Close quiz"
              title="Submit the quiz to finish">×</button>
          </header>

          <div class="lms-quiz-layout">
            {{-- LEFT: QUIZ INFORMATION --}}
            <aside class="lms-quiz-sidebar">
              <div class="lms-quiz-sidebar-heading">
                <span class="lms-quiz-info-icon">i</span>
                <strong>Quiz Information</strong>
              </div>

              <span class="lms-quiz-label">Assessment title</span>
              <h2 id="quizModalTitle">Loading quiz...</h2>

              <div class="lms-quiz-info-block">
                <span class="lms-quiz-info-symbol">▤</span>
                <div>
                  <strong>Instructions</strong>
                  <p id="quizModalInstructions">
                    Follow the trainer's instructions carefully.
                  </p>
                </div>
              </div>

              <div class="lms-quiz-info-block">
                <span class="lms-quiz-info-symbol">◷</span>
                <div>
                  <strong>Time limit</strong>
                  <p id="quizTimeLimit">Loading...</p>
                </div>
              </div>

              <div class="lms-quiz-info-block">
                <span class="lms-quiz-info-symbol">✓</span>
                <div>
                  <strong>Passing score</strong>
                  <p id="quizPassingScore">--%</p>
                </div>
              </div>

              <div class="lms-quiz-info-block">
                <span class="lms-quiz-info-symbol">☷</span>
                <div>
                  <strong>Total questions</strong>
                  <p id="quizTotalQuestions">0 questions</p>
                </div>
              </div>

              <div class="lms-quiz-important">
                <strong>Important reminder</strong>
                <p>
                  Keep an eye on the timer. When the time runs out,
                  your answers will be submitted automatically.
                </p>
              </div>
            </aside>

            {{-- CENTER: QUESTIONS --}}
            <main class="lms-quiz-main">
              <div class="lms-quiz-progress-top">
                <div>
                  <strong id="quizQuestionCount">Your questions</strong>
                  <span id="quizProgressPercent">0% complete</span>
                </div>

                <div class="lms-quiz-progress-track">
                  <div id="quizProgressFill" class="lms-quiz-progress-fill"
                    style="width:0%;"></div>
                </div>
              </div>

              <div id="quizModalBody" class="lms-quiz-body">
                <div class="lms-quiz-loading">
                  Preparing your quiz...
                </div>
              </div>

              <div class="lms-quiz-question-navigation">
                <button type="button" id="quizPreviousButton"
                  class="lms-quiz-nav-button" onclick="quizNavigate(-1)"
                  disabled>
                  ← Previous
                </button>

                <button type="button" id="quizNextButton"
                  class="lms-quiz-nav-button lms-quiz-next"
                  onclick="quizNavigate(1)">
                  Next Question →
                </button>
              </div>
            </main>

            {{-- RIGHT: TIMER AND QUESTION NAVIGATOR --}}
            <aside class="lms-quiz-rightbar">
              <div class="lms-quiz-timer-card">
                <div class="lms-quiz-clock">◷</div>
                <div>
                  <span>Time Remaining</span>
                  <strong id="quizTimer">--:--</strong>
                </div>
              </div>

              <div id="quizTimerWarning" class="lms-quiz-timer-warning"
                style="display:none;" role="status" aria-live="polite">
                Time is running out.
              </div>

              <div class="lms-quiz-navigator">
                <h3>Question Navigator</h3>
                <div id="quizQuestionNavigator" class="lms-quiz-number-grid">
                </div>

                <div class="lms-quiz-legend">
                  <span><i class="legend-answered"></i> Answered</span>
                  <span><i class="legend-current"></i> Current question</span>
                  <span><i class="legend-unanswered"></i> Not answered</span>
                </div>
              </div>

              <div class="lms-quiz-progress-card">
                <h3>Quiz Progress</h3>
                <p id="quizAnsweredSummary">0 questions answered</p>
                <div class="lms-quiz-progress-track">
                  <div id="quizSideProgressFill" class="lms-quiz-progress-fill"
                    style="width:0%;"></div>
                </div>
              </div>

              <button type="button" id="quizSubmitButton"
                class="lms-quiz-submit" onclick="confirmQuizSubmission()">
                Submit Quiz <span>→</span>
              </button>

              <p class="lms-quiz-submit-note">
                Review your answers before submitting your assessment.
              </p>
            </aside>
          </div>
        </div>
      </div>

      {{-- QUIZ SUBMISSION CONFIRMATION --}}
      <div id="quizConfirmModal" class="lms-confirm-overlay"
        style="display:none;">
        <div class="lms-confirm-card" role="dialog" aria-modal="true"
          aria-labelledby="quizConfirmTitle">
          <div class="lms-confirm-icon">?</div>
          <h3 id="quizConfirmTitle">Submit your quiz?</h3>
          <p id="quizConfirmMessage">
            Are you sure you want to submit your answers?
          </p>

          <div class="lms-confirm-actions">
            <button type="button" class="lms-confirm-cancel"
              onclick="hideQuizConfirmation()">
              Review Answers
            </button>

            <button type="button" class="lms-confirm-submit"
              onclick="submitQuizAnswers(false)">
              Yes, Submit
            </button>
          </div>
        </div>
      </div>

      {{-- QUIZ RESULT MODAL --}}
      <div id="quizResultModal" class="lms-result-overlay"
        style="display:none;">
        <div class="lms-result-card" role="dialog" aria-modal="true"
          aria-labelledby="quizResultTitle">
          <div id="quizResultIcon" class="lms-result-icon">✓</div>
          <h3 id="quizResultTitle">Assessment Completed</h3>
          <p id="quizResultMessage" class="lms-result-message">Your answers have
            been submitted and your result is ready.</p>
          <div class="lms-result-stats">
            <div class="lms-result-stat"><span>Score</span><strong
                id="quizResultScore">0/0</strong></div>
            <div class="lms-result-stat"><span>Percentage</span><strong
                id="quizResultPercentage">0%</strong></div>
            <div class="lms-result-stat"><span>Passing score</span><strong
                id="quizResultPassingScore">75%</strong></div>
          </div>
          <div id="quizResultStatus" class="lms-result-status">PASSED</div>
          <p id="quizModuleCompletionMessage" class="lms-result-message">Your
            module progress is being updated.</p>
          <button type="button" class="lms-result-done"
            onclick="finishQuizResult()">Done — Return to My Courses</button>
        </div>
      </div>

    </div>
  @endsection

  @section('onsite-activities-content')
    @php
      /*
       * The controller should pass $onsiteGrades as a collection of grades
       * joined with onsite_activities and limited to this logged-in learner
       * and the selected enrollment/course.
       */
      $onsiteScoreRows =
          isset($onsiteGrades) && $onsiteGrades
              ? collect($onsiteGrades)
              : collect();

      if (isset($enrollment) && $enrollment && $onsiteScoreRows->isNotEmpty()) {
          $onsiteScoreRows = $onsiteScoreRows
              ->filter(function ($grade) use ($enrollment) {
                  if (isset($grade->enrollment_id)) {
                      return (string) $grade->enrollment_id ===
                          (string) $enrollment->id;
                  }

                  return true;
              })
              ->values();
      }
    @endphp

    <div class="courses-overview-wrap">
      <div class="onsite-scores-wrap">
        <div class="onsite-scores-header">
          <div>
            <h1>Onsite Activity Scores</h1>
          </div>
        </div>

        <div class="onsite-scores-card">
          <div class="onsite-scores-card-heading">
            <h2>Your Activity Results</h2>
            <span class="onsite-scores-count">
              {{ $onsiteScoreRows->count() }}
              {{ $onsiteScoreRows->count() === 1 ? 'activity' : 'activities' }}
            </span>
          </div>

          @if ($onsiteScoreRows->isNotEmpty())
            <div class="onsite-scores-table-wrap">
              <table class="onsite-scores-table">
                <thead>
                  <tr>
                    <th scope="col">Activity</th>
                    <th scope="col">Date</th>
                    <th scope="col">Your Score</th>
                    <th scope="col">Remarks / Feedback</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($onsiteScoreRows as $grade)
                    @php
                      $activityTitle =
                          $grade->activity_title ??
                          ($grade->title ??
                              ($grade->activity_name ?? 'Onsite Activity'));

                      $activityDate =
                          $grade->activity_date ?? ($grade->date ?? null);

                      $score = $grade->score ?? ($grade->grade ?? null);
                      $maxScore =
                          $grade->max_score ?? ($grade->total_score ?? null);
                      $remarks =
                          $grade->remarks ??
                          ($grade->feedback ??
                              ($grade->trainer_remarks ?? null));
                    @endphp
                    <tr>
                      <td><strong>{{ $activityTitle }}</strong></td>
                      <td>
                        @if ($activityDate)
                          {{ \Illuminate\Support\Carbon::parse($activityDate)->format('M d, Y') }}
                        @else
                          —
                        @endif
                      </td>
                      <td>
                        @if ($score !== null)
                          <span class="onsite-score-number">
                            {{ rtrim(rtrim(number_format((float) $score, 2), '0'), '.') }}
                            @if ($maxScore !== null)
                              /
                              {{ rtrim(rtrim(number_format((float) $maxScore, 2), '0'), '.') }}
                            @endif
                          </span>
                        @else
                          <span class="onsite-score-pending">Pending</span>
                        @endif
                      </td>
                      <td>{{ $remarks ?: 'No feedback provided yet.' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="onsite-scores-empty">
              <i class="fa fa-clipboard-list" aria-hidden="true"></i>
              <h3>No onsite scores available yet</h3>
              <p>
                Your onsite activity scores will appear here once your trainer
                records them.
              </p>
            </div>
          @endif
        </div>
      </div>
    </div>
  @endsection

  @section('scripts')
    <script>
      const csrfToken = document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') || '';

      let _currentQuizId = null;
      let _currentQuizModuleId = null;
      let _quizSubmittedSuccessfully = false;
      let _quizSubmitting = false;
      let _quizTimerInterval = null;
      let _quizDeadline = null;
      let _quizTimeExpired = false;
      let _quizCurrentQuestion = 0;
      let _quizQuestions = [];

      async function readJsonResponse(response) {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
          throw new Error(data.message || `Request failed (${response.status}).`);
        }
        return data;
      }

      function showQuizMessage(message, isError = false) {
        const body = document.getElementById('quizModalBody');
        body.replaceChildren();
        const p = document.createElement('p');
        p.className = 'lms-quiz-loading';
        p.textContent = message;
        if (isError) p.style.color = '#b42323';
        body.appendChild(p);
      }

      function normalizeOptions(question) {
        let options = question.options;
        if (typeof options === 'string') {
          try {
            options = JSON.parse(options);
          } catch (_) {
            options = [];
          }
        }

        if (Array.isArray(options) && options.length) {
          return options.map((option, index) => {
            let label = '';
            let value = ['a', 'b', 'c', 'd'][index] ?? String(index);

            if (option && typeof option === 'object') {
              // Supports options saved as {label, value}, {text}, or {option}.
              label = String(option.label ?? option.text ?? option.option ??
                option.name ?? option.value ?? '');
              value = String(option.value ?? option.id ?? value);
            } else {
              label = String(option ?? '');
            }

            return {
              label,
              value
            };
          }).filter(option => String(option.label ?? '').trim() !== '');
        }

        // Legacy database columns may be null or omitted from the API response.
        return [{
            label: question.choice_a ?? question.option_a ?? question.answer_a ??
              '',
            value: 'a'
          },
          {
            label: question.choice_b ?? question.option_b ?? question.answer_b ??
              '',
            value: 'b'
          },
          {
            label: question.choice_c ?? question.option_c ?? question.answer_c ??
              '',
            value: 'c'
          },
          {
            label: question.choice_d ?? question.option_d ?? question.answer_d ??
              '',
            value: 'd'
          }
        ].filter(option => String(option.label ?? '').trim() !== '');
      }

      function getQuestionType(question) {
        const type = String(question.question_type || question.type ||
          'multiple_choice').toLowerCase();
        if (['checkboxes', 'checkbox', 'multi_select'].includes(type))
          return 'checkboxes';
        if (['short_answer', 'short-answer'].includes(type)) return 'short_answer';
        if (type === 'paragraph') return 'paragraph';
        return 'multiple_choice';
      }

      function isQuestionRequired(question) {
        const value = question?.required;
        return value === true || value === 1 || value === '1' ||
          String(value ?? '').toLowerCase() === 'true';
      }

      function quizGetAnswer(questionId) {
        const targetId = String(questionId ?? '');
        const card = [...document.querySelectorAll(
            '#quizModalBody .lms-quiz-question-card')]
          .find(item => String(item.dataset.questionId ?? '') === targetId);

        // Prefer the matching question card so answer lookup is independent of
        // how the backend originally named the question ID field.
        const inputs = card ? [...card.querySelectorAll(
            'input, textarea, select')] : [...document.querySelectorAll(
            '#quizModalBody [data-question-id]')]
          .filter(input => String(input.dataset.questionId ?? '') === targetId);

        if (!inputs.length) return null;

        const first = inputs[0];
        if (first.type === 'checkbox') {
          return inputs.filter(input => input.checked).map(input => input.value);
        }
        if (first.type === 'radio') {
          return inputs.find(input => input.checked)?.value ?? null;
        }
        return String(first.value ?? '').trim();
      }

      function quizHasAnswer(questionId) {
        const answer = quizGetAnswer(questionId);
        return Array.isArray(answer) ?
          answer.length > 0 :
          answer !== null && String(answer).trim() !== '';
      }

      function renderQuizQuestions(questions) {
        const body = document.getElementById('quizModalBody');
        body.replaceChildren();

        questions.forEach((question, index) => {
          const questionId = String(question.id);
          const type = getQuestionType(question);
          const required = isQuestionRequired(question);

          const card = document.createElement('section');
          card.className = 'lms-quiz-question-card';
          card.dataset.questionId = questionId;

          const top = document.createElement('div');
          top.className = 'lms-question-topline';

          const number = document.createElement('span');
          number.className = 'lms-question-number';
          number.textContent = `Question ${index + 1}`;

          const typeLabel = document.createElement('span');
          typeLabel.className = 'lms-question-type';
          typeLabel.textContent = {
            multiple_choice: 'Multiple Choice',
            checkboxes: 'Multiple Select',
            short_answer: 'Short Answer',
            paragraph: 'Written Response'
          } [type];

          top.append(number, typeLabel);

          if (required) {
            const req = document.createElement('span');
            req.className = 'lms-question-required';
            req.textContent = '* Required';
            top.appendChild(req);
          }

          const title = document.createElement('h3');
          title.className = 'lms-question-text';
          title.textContent = String(question.question ?? question.text ??
            question.question_text ?? question.title ?? 'Untitled question');
          card.append(top, title);

          if (type === 'short_answer' || type === 'paragraph') {
            const field = document.createElement(type === 'paragraph' ?
              'textarea' : 'input');
            if (field.tagName === 'INPUT') field.type = 'text';
            field.className = 'lms-quiz-answer-field';
            field.name = `q${questionId}`;
            field.dataset.questionId = questionId;
            field.dataset.answerType = 'text';
            field.required = required;
            field.maxLength = type === 'paragraph' ? 5000 : 1000;
            field.placeholder = type === 'paragraph' ?
              'Write your answer here...' : 'Type your answer here...';
            if (field.tagName === 'TEXTAREA') field.rows = 5;
            field.addEventListener('input', quizUpdateProgress);
            card.appendChild(field);
          } else {
            const options = normalizeOptions(question);
            if (!options.length) {
              const hint = document.createElement('p');
              hint.textContent =
                'No answer choices have been configured for this question.';
              hint.style.color = '#b42323';
              card.appendChild(hint);
            }

            options.forEach((option, optionIndex) => {
              const label = document.createElement('label');
              label.className = 'lms-quiz-option';

              const input = document.createElement('input');
              input.type = type === 'checkboxes' ? 'checkbox' : 'radio';
              input.name = `q${questionId}`;
              input.value = option.value;
              input.dataset.questionId = questionId;
              input.dataset.answerType = type === 'checkboxes' ?
                'checkbox' : 'radio';
              input.addEventListener('change', quizUpdateProgress);

              const text = document.createElement('span');
              text.textContent =
                `${String.fromCharCode(65 + optionIndex)}. ${option.label}`;
              label.append(input, text);
              card.appendChild(label);
            });
          }

          body.appendChild(card);
        });

        document.getElementById('quizTotalQuestions').textContent =
          `${questions.length} question${questions.length === 1 ? '' : 's'}`;

        buildQuizNavigator();
        showQuizQuestion(0);
        quizUpdateProgress();
      }

      function buildQuizNavigator() {
        const navigator = document.getElementById('quizQuestionNavigator');
        navigator.replaceChildren();

        _quizQuestions.forEach((question, index) => {
          const button = document.createElement('button');
          button.type = 'button';
          button.className = 'lms-quiz-number';
          button.textContent = index + 1;
          button.setAttribute('aria-label', `Go to question ${index + 1}`);
          button.addEventListener('click', () => showQuizQuestion(index));
          navigator.appendChild(button);
        });
      }

      function showQuizQuestion(index) {
        if (index < 0 || index >= _quizQuestions.length) return;
        _quizCurrentQuestion = index;

        document.querySelectorAll('#quizModalBody .lms-quiz-question-card').forEach(
          (card, i) => {
            card.classList.toggle('active', i === index);
          });

        document.getElementById('quizQuestionCount').textContent =
          `Question ${index + 1} of ${_quizQuestions.length}`;

        document.getElementById('quizPreviousButton').disabled = index === 0;
        document.getElementById('quizNextButton').textContent =
          index === _quizQuestions.length - 1 ? 'Back to First Question ↺' :
          'Next Question →';
        document.getElementById('quizNextButton').disabled = _quizQuestions
          .length <= 1;

        quizUpdateProgress();
      }

      function quizNavigate(direction) {
        let index = _quizCurrentQuestion + direction;
        if (index >= _quizQuestions.length) index = 0;
        if (index < 0) index = 0;
        showQuizQuestion(index);
      }

      function quizUpdateProgress() {
        // Count answers from each rendered question card instead of relying only
        // on matching database IDs. This prevents the counter staying at zero
        // when the API uses a different question ID field.
        const cards = [...document.querySelectorAll(
          '#quizModalBody .lms-quiz-question-card')];
        const total = _quizQuestions.length || cards.length;
        let answered = 0;
        const answeredByIndex = cards.map(card => {
          const fields = [...card.querySelectorAll('input, textarea, select')];
          const hasAnswer = fields.some(field => {
            if (field.type === 'radio' || field.type === 'checkbox') {
              return field.checked;
            }
            return String(field.value ?? '').trim().length > 0;
          });
          if (hasAnswer) answered++;
          return hasAnswer;
        });
        const percentage = total ? Math.round(answered / total * 100) : 0;

        const percentEl = document.getElementById('quizProgressPercent');
        const fillEl = document.getElementById('quizProgressFill');
        const sideFillEl = document.getElementById('quizSideProgressFill');
        const summaryEl = document.getElementById('quizAnsweredSummary');

        if (percentEl) percentEl.textContent = `${percentage}% complete`;
        if (fillEl) fillEl.style.width = `${percentage}%`;
        if (sideFillEl) sideFillEl.style.width = `${percentage}%`;
        if (summaryEl) summaryEl.textContent =
          `${answered} of ${total} questions answered`;

        document.querySelectorAll('#quizQuestionNavigator .lms-quiz-number')
          .forEach((button, index) => {
            button.classList.toggle('answered', Boolean(answeredByIndex[index]));
            button.classList.toggle('current', index === _quizCurrentQuestion);
          });
      }

      function stopQuizTimer() {
        if (_quizTimerInterval !== null) {
          clearInterval(_quizTimerInterval);
          _quizTimerInterval = null;
        }
      }

      function updateQuizTimer() {
        if (!_quizDeadline || _quizSubmitting) return;
        const remaining = Math.max(0, Math.ceil((_quizDeadline - Date.now()) /
          1000));
        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;

        document.getElementById('quizTimer').textContent =
          `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

        const timerCard = document.querySelector('.lms-quiz-timer-card');
        const warning = document.getElementById('quizTimerWarning');

        if (remaining <= 60) {
          timerCard.classList.add('timer-urgent');
          warning.textContent =
            'One minute or less remaining. Your answers will be submitted when time expires.';
          warning.classList.add('urgent');
          warning.style.display = 'block';
        } else if (remaining <= 300) {
          timerCard.classList.remove('timer-urgent');
          warning.textContent =
            'Five minutes or less remaining. Review your answers.';
          warning.classList.remove('urgent');
          warning.style.display = 'block';
        } else {
          timerCard.classList.remove('timer-urgent');
          warning.style.display = 'none';
        }

        if (remaining <= 0) {
          stopQuizTimer();
          _quizTimeExpired = true;
          warning.textContent = 'Time is up. Submitting your answers...';
          warning.style.display = 'block';
          warning.classList.add('urgent');
          submitQuizAnswers(true);
        }
      }


      function startQuizTimer(minutes) {
        stopQuizTimer();

        const limit = Number(minutes) || 0;
        const timerCard = document.querySelector('.lms-quiz-timer-card');
        const warning = document.getElementById('quizTimerWarning');
        const timerDisplay = document.getElementById('quizTimer');
        const timeLimitDisplay = document.getElementById('quizTimeLimit');

        // Create a separate saved deadline for each quiz.
        const deadlineKey = _currentQuizId ?
          `ledipo_quiz_deadline_${_currentQuizId}` :
          null;

        if (timerCard) {
          timerCard.classList.remove('timer-urgent');
        }

        if (warning) {
          warning.style.display = 'none';
          warning.classList.remove('urgent');
        }

        // No time limit.
        if (limit <= 0) {
          _quizDeadline = null;

          if (deadlineKey) {
            localStorage.removeItem(deadlineKey);
          }

          if (timerDisplay) {
            timerDisplay.textContent = 'No limit';
          }

          if (timeLimitDisplay) {
            timeLimitDisplay.textContent = 'No time limit';
          }

          return;
        }

        // Restore the existing deadline if the student refreshes the page.
        let savedDeadline = deadlineKey ?
          Number(localStorage.getItem(deadlineKey)) :
          0;

        if (!savedDeadline || !Number.isFinite(savedDeadline)) {
          savedDeadline = Date.now() + Math.floor(limit * 60 * 1000);

          if (deadlineKey) {
            localStorage.setItem(deadlineKey, String(savedDeadline));
          }
        }

        _quizDeadline = savedDeadline;

        if (timeLimitDisplay) {
          timeLimitDisplay.textContent =
            `${limit} minute${limit === 1 ? '' : 's'}`;
        }

        // Update immediately, including when the saved deadline has expired.
        updateQuizTimer();

        // Start the interval only if the quiz has time remaining.
        if (_quizDeadline > Date.now()) {
          _quizTimerInterval = setInterval(updateQuizTimer, 500);
        }
      }


      async function openQuiz(quizId, quizTitle) {
        if (_quizSubmitting) return;
        stopQuizTimer();

        _currentQuizId = quizId;
        _quizSubmitting = false;
        _quizTimeExpired = false;
        _quizQuestions = [];
        _quizCurrentQuestion = 0;
        _quizDeadline = null;

        _quizSubmittedSuccessfully = false;
        document.getElementById('quizModal').style.display = 'flex';
        const closeButton = document.getElementById('quizCloseButton');
        if (closeButton) {
          closeButton.disabled = false;
          closeButton.style.opacity = '.35';
          closeButton.style.cursor = 'not-allowed';
          closeButton.title =
            'You can close this assessment after submitting it.';
        }
        document.getElementById('quizModalTitle').textContent = quizTitle ||
          'Loading quiz...';
        document.getElementById('quizModalInstructions').textContent =
          'Loading trainer instructions...';
        document.getElementById('quizTimeLimit').textContent = 'Loading...';
        document.getElementById('quizPassingScore').textContent = '--%';
        document.getElementById('quizTotalQuestions').textContent = '0 questions';
        document.getElementById('quizSubmitButton').disabled = true;
        showQuizMessage('Preparing your assessment...');

        try {
          const response = await fetch(
            `/student/quiz/${encodeURIComponent(quizId)}`, {
              headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
              },
              credentials: 'same-origin'
            });
          const data = await readJsonResponse(response);

          if (data.taken === true || data.can_attempt === false) {
            // The student has already submitted this quiz. Show the saved result
            // instead of leaving them on the empty quiz-taking screen.
            const savedResult = data.result || data.quiz_result || data.attempt ||
            {};
            const quizInfo = data.quiz || {};

            stopQuizTimer();
            document.getElementById('quizModal').style.display = 'none';
            document.getElementById('quizConfirmModal').style.display = 'none';

            const resultForModal = {
              ...savedResult,
              score: savedResult.score ?? savedResult.points_earned ?? 0,
              total: savedResult.total ?? savedResult.total_items ??
                savedResult.total_questions ?? quizInfo.questions?.length ?? 0,
              percentage: savedResult.percentage ?? savedResult
                .score_percentage ?? 0,
              passing_score: savedResult.passing_score ?? quizInfo
                .passing_score ?? 75,
              status: savedResult.status ?? savedResult.result_status ?? '',
              attempts_used: data.attempts_used ?? savedResult.attempts_used ?? 0,
              attempts_allowed: data.attempts_allowed ?? 2,
              attempts_remaining: data.attempts_remaining ?? 0
            };

            showQuizResult(resultForModal, {
              success: true,
              alreadyDone: true
            }, true);
            return;
          }

          const quiz = data.quiz;
          const questions = quiz?.questions;
          _currentQuizModuleId = quiz?.module_id ?? quiz?.moduleId ?? null;

          if (!quiz || !Array.isArray(questions) || !questions.length) {
            showQuizMessage('No questions are available for this quiz yet.',
              true);
            return;
          }

          // Normalize common API/database field-name variants before rendering.
          _quizQuestions = questions.map((question, index) => ({
            ...question,
            id: question.id ?? question.question_id ?? (index + 1),
            question: question.question ?? question.text ?? question
              .question_text ?? question.title ?? '',
            question_type: question.question_type ?? question.type ??
              'multiple_choice'
          }));
          document.getElementById('quizModalTitle').textContent = quiz.title ||
            quizTitle || 'Student Assessment';
          document.getElementById('quizModalInstructions').textContent =
            quiz.instructions ||
            'Read each question carefully and select the best answer.';
          document.getElementById('quizPassingScore').textContent =
            `${Number(quiz.passing_score ?? 0)}%`;

          renderQuizQuestions(_quizQuestions);
          startQuizTimer(quiz.time_limit ?? quiz.timeLimit ?? quiz.duration ?? 0);
          document.getElementById('quizSubmitButton').disabled = false;
        } catch (error) {
          showQuizMessage(error.message ||
            'Unable to load the quiz. Please try again.', true);
        }
      }

      function confirmQuizSubmission() {
        if (!_currentQuizId || _quizSubmitting) return;

        const unanswered = _quizQuestions.filter(question =>
          isQuestionRequired(question) && !quizHasAnswer(question.id)
        ).length;

        document.getElementById('quizConfirmMessage').textContent = unanswered ?
          `You still have ${unanswered} required question${unanswered === 1 ? '' : 's'} unanswered. Do you want to submit anyway?` :
          'Are you sure you want to submit your answers? You cannot change them after submission.';

        document.getElementById('quizConfirmModal').style.display = 'flex';
      }

      function hideQuizConfirmation() {
        document.getElementById('quizConfirmModal').style.display = 'none';
      }

      function closeQuiz() {
        if (_currentQuizId && !_quizSubmittedSuccessfully) return;
        stopQuizTimer();
        document.getElementById('quizModal').style.display = 'none';
        document.getElementById('quizConfirmModal').style.display = 'none';
        _currentQuizId = null;
        _currentQuizModuleId = null;
        _quizQuestions = [];
        _quizDeadline = null;
        _quizTimeExpired = false;
      }

      async function completeModuleAfterQuiz(moduleId) {
        if (!moduleId) {
          return {
            success: false,
            message: 'This quiz is not linked to a module, so module completion was not changed.'
          };
        }

        const moduleRow = document.getElementById(`module-item-${String(moduleId)}`);
        if (!moduleRow) {
          return {
            success: false,
            message: 'The related module row could not be found.'
          };
        }

        // Scope the selector to the module row. Do not select the row or quiz wrapper itself.
        const markDoneButton = moduleRow.querySelector('button.btn-module-done');
        if (!markDoneButton) {
          return {
            success: true,
            alreadyDone: true
          };
        }

        try {
          const response = await fetch(
            `/student/module/${encodeURIComponent(moduleId)}/complete`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
              },
              credentials: 'same-origin'
            });

          const data = await readJsonResponse(response);
          if (!data.success) {
            throw new Error(data.message || 'Could not update module progress.');
          }

          const done = document.createElement('span');
          done.className = 'btn-module-done completed';
          done.textContent = '✓ Done';
          markDoneButton.replaceWith(done);

          return { success: true };
        } catch (error) {
          return {
            success: false,
            message: error.message ||
              'The quiz was submitted, but module completion could not be updated.'
          };
        }
      }

      function showQuizResult(data, moduleCompletion = {}, isPreviousResult = false) {
        const total = Number(
          data.total ?? data.total_items ?? _quizQuestions.length ?? 0
        );

        const savedScore = Number(data.score ?? data.points_earned ?? 0);
        const savedPercentage = Number(
          data.percentage ?? data.score_percentage ?? 0
        );
        const savedStatus = String(data.status ?? data.result_status ?? '').toLowerCase();

        // For a new submission, show the current attempt while explaining whether
        // the highest saved score changed. For a previous result, show the saved best.
        const score = isPreviousResult
          ? savedScore
          : Number(data.current_attempt_score ?? savedScore);
        const percentage = isPreviousResult
          ? savedPercentage
          : Number(data.current_attempt_percentage ?? savedPercentage);
        const attemptStatus = isPreviousResult
          ? savedStatus
          : String(data.current_attempt_status ?? savedStatus).toLowerCase();

        const displayedPassing = document.getElementById('quizPassingScore')
          .textContent || '75%';
        const passingScore = Number(
          data.passing_score ?? data.passing ?? displayedPassing.replace('%', '') ?? 75
        );
        const passed = attemptStatus === 'passed'
          || (!attemptStatus && percentage >= passingScore);

        document.getElementById('quizResultIcon').textContent = passed ? '✓' : '!';
        document.getElementById('quizResultTitle').textContent = passed
          ? 'Congratulations!'
          : 'Quiz Completed';

        let resultMessage = '';
        if (isPreviousResult) {
          if (passed) {
            resultMessage = `Your highest recorded score is ${savedPercentage}%. You have already passed this assessment.`;
          } else if (Number(data.attempts_remaining ?? 0) > 0) {
            const remaining = Number(data.attempts_remaining);
            resultMessage = `Your highest recorded score is ${savedPercentage}%. You have ${remaining} attempt${remaining === 1 ? '' : 's'} remaining.`;
          } else {
            resultMessage = `Your highest recorded score is ${savedPercentage}%. You have used all available attempts.`;
          }
        } else if (passed) {
          resultMessage = `You passed this attempt with ${percentage}%. Your highest saved score is ${savedPercentage}%.`;
        } else if (Number(data.attempts_remaining ?? 0) > 0) {
          const remaining = Number(data.attempts_remaining);
          resultMessage = `You did not reach the passing score. You have ${remaining} attempt${remaining === 1 ? '' : 's'} remaining.`;
          if (savedPercentage > percentage) {
            resultMessage += ` Your highest saved score remains ${savedPercentage}%.`;
          } else {
            resultMessage += ` Your highest saved score is ${savedPercentage}%.`;
          }
        } else {
          resultMessage = 'You have used all available attempts.';
          if (savedPercentage > percentage) {
            resultMessage += ` Your highest saved score remains ${savedPercentage}%.`;
          } else {
            resultMessage += ` Your highest saved score is ${savedPercentage}%.`;
          }
        }
        document.getElementById('quizResultMessage').textContent = resultMessage;

        document.getElementById('quizResultScore').textContent = `${score}/${total}`;
        document.getElementById('quizResultPercentage').textContent = `${percentage}%`;
        document.getElementById('quizResultPassingScore').textContent = `${passingScore}%`;

        const status = document.getElementById('quizResultStatus');
        status.textContent = passed ? 'PASSED' : 'FAILED';
        status.classList.toggle('failed', !passed);

        const completionMessage = document.getElementById('quizModuleCompletionMessage');
        if (isPreviousResult) {
          completionMessage.textContent = 'This is your previously saved highest score.';
        } else if (!passed) {
          completionMessage.textContent =
            'This quiz attempt did not pass, so module completion was not updated.';
        } else if (moduleCompletion.success) {
          completionMessage.textContent = moduleCompletion.alreadyDone
            ? 'This module was already marked as done.'
            : 'Quiz passed successfully. Module marked as done.';
        } else {
          completionMessage.textContent = moduleCompletion.message
            || 'Quiz passed, but module completion could not be updated.';
        }

        document.getElementById('quizResultModal').style.display = 'flex';
      }

      function finishQuizResult() {
        document.getElementById('quizResultModal').style.display = 'none';
        document.getElementById('quizModal').style.display = 'none';
        document.getElementById('quizConfirmModal').style.display = 'none';
        _currentQuizId = null;
        _currentQuizModuleId = null;
        _quizQuestions = [];
        stopQuizTimer();
        window.location.reload();
      }


      async function submitQuizAnswers(autoSubmit = false) {
        if (!_currentQuizId || _quizSubmitting) return;

        hideQuizConfirmation();

        if (!autoSubmit) {
          const unansweredIndex = _quizQuestions.findIndex(question =>
            isQuestionRequired(question) && !quizHasAnswer(question.id)
          );

          if (unansweredIndex !== -1) {
            showQuizQuestion(unansweredIndex);
            alert('Please answer all required questions before submitting.');
            return;
          }
        }

        // Collect the student's answers.
        const answers = {};

        _quizQuestions.forEach(question => {
          const answer = quizGetAnswer(question.id);

          if (answer !== null) {
            answers[String(question.id)] = answer;
          }
        });

        _quizSubmitting = true;
        stopQuizTimer();

        const button = document.getElementById('quizSubmitButton');

        if (button) {
          button.disabled = true;
          button.textContent = autoSubmit ?
            'Time up — submitting...' :
            'Submitting...';
        }

        try {
          const response = await fetch('/student/quiz/submit', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
              quiz_id: _currentQuizId,
              answers: answers,
              // Tell Laravel when this submission was triggered by the timer.
              // The submit controller must save timed-out attempts as failed.
              timed_out: autoSubmit === true
            })
          });

          const data = await readJsonResponse(response);

          if (!data.success) {
            throw new Error(
              data.message || 'Your answers could not be submitted.'
            );
          }

          // Submission succeeded. Clear this quiz's saved timer.
          try {
            localStorage.removeItem(
              `ledipo_quiz_deadline_${_currentQuizId}`
            );
          } catch (storageError) {
            console.warn(
              'Unable to clear the saved quiz timer.',
              storageError
            );
          }

          _quizSubmittedSuccessfully = true;

          const closeButton = document.getElementById('quizCloseButton');

          if (closeButton) {
            closeButton.disabled = false;
            closeButton.style.opacity = '1';
            closeButton.style.cursor = 'pointer';
            closeButton.title = 'Close assessment';
          }

          // Mark the module complete only when this attempt passed.
          const currentAttemptStatus = String(
            data.current_attempt_status ?? data.status ?? ''
          ).toLowerCase();

          let moduleCompletion = {
            success: false,
            message: 'This quiz attempt did not pass, so module completion was not updated.'
          };

          if (currentAttemptStatus === 'passed') {
            moduleCompletion = await completeModuleAfterQuiz(
              _currentQuizModuleId
            );
          }

          // Hide the assessment and display this attempt plus the saved best score.
          document.getElementById('quizModal').style.display = 'none';
          showQuizResult(data, moduleCompletion);

        } catch (error) {
          _quizSubmitting = false;

          if (button) {
            button.disabled = false;
            button.textContent = 'Submit Quiz →';
          }

          // Resume the countdown only when there is still time remaining.
          if (_quizDeadline && Date.now() < _quizDeadline) {
            _quizTimerInterval = setInterval(updateQuizTimer, 500);
          }

          if (autoSubmit) {
            // Do not retry forever when the server rejects an expired attempt.
            // The controller must accept timed_out=true and persist this attempt.
            _quizTimeExpired = true;

            const warning = document.getElementById('quizTimerWarning');
            if (warning) {
              warning.textContent =
                'Time expired, but the attempt could not be saved. Please contact your trainer or administrator; do not refresh until this is resolved.';
              warning.style.display = 'block';
              warning.classList.add('urgent');
            }

            if (button) {
              button.disabled = true;
              button.textContent = 'Submission failed';
            }

            console.error('Timed-out quiz submission failed:', error);
            alert(
              'Time expired, but the system could not save this attempt. ' +
              'The quiz controller must be updated to accept timed-out submissions. ' +
              'Please contact your trainer or administrator.'
            );
          } else {
            if (button) {
              button.disabled = false;
              button.textContent = 'Submit Quiz →';
            }

            alert(
              error.message || 'Something went wrong. Please try again.'
            );
          }
        }
      }


      function showCertificateScreen(event) {
        if (event) {
          event.preventDefault();
        }

        const certificateSection = document.getElementById(
          'courseCompletionCertificate'
        );

        // The preview is always rendered in the page, but remains hidden
        // until the student selects Completion.
        if (!certificateSection) {
          console.error('The course certificate preview element is missing.');
          return false;
        }

        // Switch the content area to Completion mode: show only the overall
        // course progress banner and the certificate preview.
        document.body.classList.add('completion-screen-active');
        certificateSection.hidden = false;
        certificateSection.removeAttribute('hidden');

        certificateSection.scrollIntoView({
          behavior: window.matchMedia('(prefers-reduced-motion: reduce)')
            .matches ?
            'auto' : 'smooth',
          block: 'start'
        });

        certificateSection.setAttribute('tabindex', '-1');
        certificateSection.focus({
          preventScroll: true
        });
        return true;
      }

      // Connect the existing sidebar/navigation item named "Completion" to
      // this certificate preview, without requiring changes to student.layout.
      document.addEventListener('click', function(event) {
        const trigger = event.target.closest('a, button, [role="button"]');
        if (!trigger) return;

        const label = (trigger.innerText || trigger.textContent || '')
          .replace(/\\s+/g, ' ')
          .trim()
          .toLowerCase();

        if (label === 'completion') {
          showCertificateScreen(event);
          return;
        }

        // Do not leave Completion when the student uses controls inside
        // the certificate section (for example Print or Download).
        if (trigger.closest('#courseCompletionCertificate')) {
          return;
        }

        // Leaving Completion through another navigation item restores
        // the normal course content.
        if (document.body.classList.contains('completion-screen-active')) {
          document.body.classList.remove('completion-screen-active');
          const certificateSection = document.getElementById(
            'courseCompletionCertificate');
          if (certificateSection) {
            certificateSection.hidden = true;
            certificateSection.setAttribute('hidden', 'hidden');
          }
        }
      });

      function printCourseCertificate(certificateId) {
        const certificate = document.getElementById(certificateId);
        if (!certificate || !certificate.classList.contains('is-color')) return;

        document.querySelectorAll('.course-certificate-preview').forEach(item => {
          item.classList.remove('print-target');
        });
        certificate.classList.add('print-target');
        document.body.classList.add('course-certificate-printing');

        const cleanup = () => {
          document.body.classList.remove('course-certificate-printing');
          certificate.classList.remove('print-target');
          window.removeEventListener('afterprint', cleanup);
        };

        window.addEventListener('afterprint', cleanup);
        window.print();
        window.setTimeout(cleanup, 1500);
      }

      async function markDone(moduleId, btn) {
        if (!btn || btn.dataset.loading === '1') return;
        const originalText = btn.textContent;
        btn.dataset.loading = '1';
        btn.disabled = true;
        btn.textContent = 'Saving...';

        try {
          const response = await fetch(
            `/student/module/${encodeURIComponent(moduleId)}/complete`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
              },
              credentials: 'same-origin'
            });
          const data = await readJsonResponse(response);
          if (!data.success) throw new Error(data.message ||
            'Could not update module progress.');

          const done = document.createElement('span');
          done.className = 'btn-module-done completed';
          done.textContent = '✓ Done';
          btn.replaceWith(done);

          // Refresh after the save so the server can reveal the certificate
          // once all modules and quizzes satisfy the completion rule.
          const moduleRows = Array.from(document.querySelectorAll(
            '.module-action-row'));
          const allModulesNowDone = moduleRows.length > 0 && moduleRows.every(
            row =>
            row.querySelector('.btn-module-done.completed')
          );
          if (allModulesNowDone) {
            window.location.reload();
          }
        } catch (error) {
          alert(error.message || 'Something went wrong. Please try again.');
          btn.disabled = false;
          btn.dataset.loading = '0';
          btn.textContent = originalText;
        }
      }

      document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
          if (document.getElementById('quizResultModal')?.style.display ===
            'flex') {
            event.preventDefault();
            return;
          }
          const confirmModal = document.getElementById('quizConfirmModal');
          if (confirmModal?.style.display === 'flex') hideQuizConfirmation();
          else if (document.getElementById('quizModal')?.style.display ===
            'flex') {
            event.preventDefault();
            closeQuiz();
          }
        }
      });

      document.getElementById('quizModal')?.addEventListener('click', event => {
        if (event.target.id === 'quizModal') {
          event.preventDefault();
          closeQuiz();
        }
      });

      document.getElementById('quizResultModal')?.addEventListener('click',
        event => {
          if (event.target.id === 'quizResultModal') event.preventDefault();
        });

      window.addEventListener('beforeunload', event => {
        if (_currentQuizId && !_quizSubmittedSuccessfully) {
          event.preventDefault();
          event.returnValue = '';
        }
      });

      document.getElementById('quizConfirmModal')?.addEventListener('click',
        event => {
          if (event.target.id === 'quizConfirmModal') hideQuizConfirmation();
        });
    </script>
  @endsection

