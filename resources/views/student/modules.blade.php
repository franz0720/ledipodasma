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
      max-width: 980px;
      color: var(--ledipo-ink);
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
  </style>
@endsection

@section('content')
  <div class="courses-overview-wrap">

    @if ($enrollment && request('course_id'))

      <a href="{{ route('student.modules') }}" class="btn-back-overview">
        ← Back to Overview
      </a>

      <div class="course-detail-container">

        <section id="courseOverview" data-content-screen="introduction">
          <h1 class="course-main-header">
            {{ strtoupper($enrollment->course->title) }}
          </h1>

          <div class="course-top-banner">
            <div class="banner-meta-row">
              <span>
                Duration: {{ $enrollment->course->duration ?? 'TBA' }} Days
              </span>

              <span class="banner-divider">|</span>

              <span>
                Schedule: {{ $enrollment->course->schedule ?? 'TBA' }}
              </span>
            </div>

            <div class="banner-progress-bar">
              <div class="banner-progress-fill"
                style="width: {{ min(100, max(0, (int) ($enrollment->progress ?? 0))) }}%">
              </div>
            </div>

            <div class="banner-progress-text">
              {{ $enrollment->progress ?? 0 }}% Complete
            </div>
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
                  <iframe src="{{ $syllabusUrl }}#toolbar=1&navpanes=0&view=FitH"
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

        <section id="modulesSection" data-content-screen="modules">
          <div class="section-label">Modules</div>

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

              <div class="module-action-row" id="module-item-{{ $module->id }}"
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
                      target="_blank" rel="noopener noreferrer">Open PDF in new
                      tab</a>
                  </div>
                  <iframe class="module-pdf-frame"
                    src="{{ asset('storage/' . $module->file_path) }}#toolbar=1&navpanes=0&view=FitH"
                    title="PDF learning material: {{ $module->title }}"
                    loading="lazy">
                    Your browser cannot display this PDF inline.
                    <a href="{{ asset('storage/' . $module->file_path) }}"
                      target="_blank" rel="noopener noreferrer">Open the PDF</a>.
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
                      $result = $quizResults->firstWhere('quiz_id', $quiz->id);
                    @endphp

                    <div class="pretest-action-bar module-quiz-action-bar"
                      id="quiz-item-{{ $quiz->id }}"
                      data-quiz-id="{{ $quiz->id }}"
                      data-module-id="{{ $module->id }}"
                      data-unit-number="{{ $module->unit_number }}">
                      <div class="module-quiz-info">
                        <div class="module-quiz-title">{{ $quiz->title }}</div>
                        <div class="module-quiz-meta">
                          {{ $quiz->time_limit }} mins
                          <span aria-hidden="true">·</span>
                          {{ $quiz->passing_score }}% to pass
                        </div>
                      </div>

                      @if ($result)
                        <span class="pretest-score module-quiz-result"
                          style="color:{{ strtolower($result->status ?? '') === 'passed' ? '#276749' : '#c53030' }};">
                          {{ ucfirst($result->status ?? 'Completed') }} ·
                          {{ $result->percentage ?? 0 }}%
                        </span>
                      @else
                        <button type="button"
                          onclick='openQuiz(@json($quiz->id), @json($quiz->title))'
                          class="pretest-btn">
                          Take Quiz
                        </button>
                      @endif
                    </div>
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
                $result = $quizResults->firstWhere('quiz_id', $quiz->id);
              @endphp

              <div class="pretest-action-bar">
                <div>
                  <div style="font-weight:700;font-size:14px;color:#2d3748;">
                    {{ $quiz->title }}
                  </div>
                  <div style="font-size:12px;color:#718096;margin-top:2px;">
                    {{ $quiz->time_limit }} mins &nbsp;·&nbsp;
                    {{ $quiz->passing_score }}% to pass
                  </div>
                </div>

                @if ($result)
                  <span class="pretest-score"
                    style="color:{{ strtolower($result->status ?? '') === 'passed' ? '#276749' : '#c53030' }};">
                    {{ ucfirst($result->status ?? 'Completed') }} ·
                    {{ $result->percentage ?? 0 }}%
                  </span>
                @else
                  <button type="button"
                    onclick='openQuiz(@json($quiz->id), @json($quiz->title))'
                    class="pretest-btn">
                    Take Quiz
                  </button>
                @endif
              </div>
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
          <div class="course-card">

            <div class="card-banner">
              <span class="category-tag-overlay">
                {{ $e->course->sector ?? 'Livelihood' }}
              </span>

              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path
                  d="M19 2H6c-1.2 0-2 .8-2 2v16c0 1.2.8 2 2 2h13c1.1 0 2-.9 2-2V4c0-1.2-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4zm13 16H6c-.6 0-1-.4-1-1s.4-1 1-1h13v2zm0-3H6c-.2 0-.4 0-.6.1V14h13.6v3z" />
              </svg>
            </div>

            <div class="card-body">

              <div class="course-card-title">
                {{ $e->course->title ?? 'Untitled Course' }}
              </div>

              <div class="course-card-desc">
                {{ Str::limit($e->course->description ?? 'No description available.', 90) }}
              </div>

              <div class="progress-heading">
                <span>Course progress</span>
                <strong>
                  {{ min(100, max(0, (int) ($e->progress ?? 0))) }}%
                </strong>
              </div>

              <div class="progress-bar-bg">
                <div class="progress-bar-fill"
                  style="width:{{ min(100, max(0, (int) ($e->progress ?? 0))) }}%;">
                </div>
              </div>

              <a href="{{ route('student.modules', ['course_id' => $e->course_id]) }}"
                class="btn-start-course">
                {{ (int) ($e->progress ?? 0) > 0 ? 'Continue Learning' : 'Start this Course' }}
                <span aria-hidden="true">→</span>
              </a>

            </div>
          </div>

        @empty
          <div class="no-data">
            You are not enrolled in any courses yet.
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
                class="lms-quiz-nav-button" onclick="quizNavigate(-1)" disabled>
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
    <div id="quizResultModal" class="lms-result-overlay" style="display:none;">
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

        if (data.taken) {
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
            status: savedResult.status ?? savedResult.result_status ?? ''
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
      if (!moduleId) return {
        success: false,
        message: 'This quiz is not linked to a module, so module completion was not changed.'
      };
      const button = document.querySelector(
        `[data-module-id="${String(moduleId)}"]`);
      if (!button) return {
        success: true,
        alreadyDone: true
      };
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
        done.dataset.moduleId = String(moduleId);
        done.textContent = '✓ Done';
        button.replaceWith(done);
        return {
          success: true
        };
      } catch (error) {
        return {
          success: false,
          message: error.message ||
            'The quiz was submitted, but module completion could not be updated.'
        };
      }
    }

    function showQuizResult(data, moduleCompletion, isPreviousResult = false) {
      const score = Number(data.score ?? 0);
      const total = Number(data.total ?? data.total_items ?? _quizQuestions
        .length);
      const percentage = Number(data.percentage ?? (total ? Math.round(score /
        total * 100) : 0));
      const displayedPassing = document.getElementById('quizPassingScore')
        .textContent || '75%';
      const passingScore = Number(data.passing_score ?? displayedPassing.replace(
        '%', '') ?? 75);
      const passed = String(data.status || '').toLowerCase() === 'passed' ||
        (!data.status && percentage >= passingScore);
      document.getElementById('quizResultIcon').textContent = passed ? '✓' : '!';
      document.getElementById('quizResultTitle').textContent = passed ?
        'Congratulations!' : 'Quiz Completed';
      document.getElementById('quizResultMessage').textContent =
        isPreviousResult ?
        (passed ?
          'You already completed this assessment and passed. Here is your saved result.' :
          'You already completed this assessment. Here is your saved result.') :
        (passed ?
          'You passed this assessment. Keep up the good work!' :
          'Your answers have been submitted. Review the learning materials and continue practicing.'
        );
      document.getElementById('quizResultScore').textContent =
        `${score}/${total}`;
      document.getElementById('quizResultPercentage').textContent =
        `${percentage}%`;
      document.getElementById('quizResultPassingScore').textContent =
        `${passingScore}%`;
      const status = document.getElementById('quizResultStatus');
      status.textContent = passed ? 'PASSED' : 'FAILED';
      status.classList.toggle('failed', !passed);
      document.getElementById('quizModuleCompletionMessage').textContent =
        isPreviousResult ?
        'This is your previously saved quiz result.' :
        (moduleCompletion.success ?
          (moduleCompletion.alreadyDone ?
            'This module was already marked as done.' :
            'Quiz submitted successfully. Module marked as done.') :
          (moduleCompletion.message ||
            'Quiz submitted, but module completion could not be updated.'));
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
            answers
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

        // Update the module's completion status.
        const moduleCompletion = await completeModuleAfterQuiz(
          _currentQuizModuleId
        );

        // Hide the assessment and display the saved result.
        document.getElementById('quizModal').style.display = 'none';

        showQuizResult(data, moduleCompletion);

      } catch (error) {
        _quizSubmitting = false;

        if (button) {
          button.disabled = false;
          button.textContent = 'Submit Quiz →';
        }

        // Resume the countdown if time remains.
        if (_quizDeadline && Date.now() < _quizDeadline) {
          _quizTimerInterval = setInterval(updateQuizTimer, 500);
        } else if (_quizDeadline && Date.now() >= _quizDeadline) {
          // Time has expired; retry automatic submission.
          _quizTimeExpired = true;

          if (button) {
            button.disabled = true;
          }

          setTimeout(() => {
            submitQuizAnswers(true);
          }, 1000);
        }

        alert(
          error.message || 'Something went wrong. Please try again.'
        );
      }
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

