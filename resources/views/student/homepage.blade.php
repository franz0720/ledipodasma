@extends('student.layout')



@section('title', 'Dashboard · LEDIPO')



@section('css')



  <style>



    .main-content {



      background: #f4f6f0;



    }



    .greeting-banner {



      background: #1a4d2e;



      border-radius: 14px;



      padding: 24px 28px;



      color: white;



      display: flex;



      justify-content: space-between;



      align-items: center;



      flex-wrap: wrap;



      gap: 16px;



      margin-bottom: 24px;



    }



    .greeting-banner h2 {



      font-size: 24px;



      font-weight: 700;



      color: white;



      margin: 0;



    }



    .greeting-banner p {



      font-size: 13px;



      color: #b6d9c0;



      margin: 4px 0 0;



    }



    .banner-stats {



      display: flex;



      gap: 12px;



      flex-wrap: wrap;



      align-items: stretch;



    }



    .banner-stat {



      background: rgba(255, 255, 255, 0.12);



      border-radius: 10px;



      padding: 10px 18px;



      text-align: center;



      min-width: 90px;



    }



    .banner-stat .bval {



      font-size: 20px;



      font-weight: 700;



      color: #f5c842;



      display: block;



    }



    .banner-stat .blbl {



      font-size: 11px;



      color: #cde8d4;



      margin-top: 2px;



      display: block;



    }



    .prog-bar-wrap {



      background: rgba(255, 255, 255, 0.2);



      border-radius: 999px;



      height: 6px;



      margin-top: 10px;



      overflow: hidden;



      width: 220px;



    }



    .prog-bar-fill {



      background: #f5c842;



      height: 6px;



      border-radius: 999px;



    }



    .banner-register-btn {



      background: #f5c842;



      color: #1a4d2e;



      border-radius: 10px;



      padding: 10px 20px;



      display: flex;



      flex-direction: column;



      justify-content: center;



      align-items: center;



      text-align: center;



      text-decoration: none;



      min-width: 110px;



      font-family: inherit;



      transition: *background* 0.15s ease, transform 0.1s ease;



    }



    .banner-register-btn:hover {



      background: #ffd75e;



      color: #1a4d2e;



      transform: translateY(-1px);



    }



    .banner-register-btn .rb-icon {



      font-size: 18px;



      line-height: 1;



      margin-bottom: 4px;



    }



    .banner-register-btn .rb-label {



      font-size: 12px;



      font-weight: 700;



      letter-spacing: 0.02em;



    }



    .section-heading {



      font-size: 13px;



      font-weight: 600;



      color: #4a5568;



      text-transform: uppercase;



      letter-spacing: 0.05em;



      margin: 0 0 12px;



    }



    .stat-grid {



      display: grid;



      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));



      gap: 12px;



      margin-bottom: 24px;



    }



    .stat-card {



      background: white;



      border-radius: 12px;



      padding: 16px;



      border: 0.5px solid #e2e8f0;



    }



    .stat-card .sc-label {



      font-size: 12px;



      color: #718096;



      margin-bottom: 6px;



    }



    .stat-card .sc-value {



      font-size: 26px;



      font-weight: 700;



      color: #1a4d2e;



      line-height: 1;



    }



    .stat-card .sc-sub {



      font-size: 11px;



      color: #a0aec0;



      margin-top: 4px;



    }



    .badge-green {



      display: inline-block;



      background: #e6f4eb;



      color: #276749;



      font-size: 11px;



      padding: 2px 8px;



      border-radius: 999px;



      margin-top: 6px;



    }



    .badge-yellow {



      display: inline-block;



      background: #fff8e1;



      color: #b7791f;



      font-size: 11px;



      padding: 2px 8px;



      border-radius: 999px;



      margin-top: 6px;



    }



    /* =========================================



                           DASHBOARD SECTION



                        ========================================= */



    .chart-grid {



      display: grid;



      grid-template-columns: repeat(2, minmax(0, 1fr));



      gap: 18px;



      margin-bottom: 26px;



      align-items: stretch;



    }



    .chart-card {



      min-width: 0;



      min-height: 360px;



      padding: 22px;



      background: #ffffff;



      border: 1px solid #e2ebe5;



      border-radius: 16px;



      box-shadow: 0 3px 12px rgba(24, 51, 41, 0.035);



      transition:



        box-shadow 0.2s ease,



        transform 0.2s ease;



    }



    .chart-card:hover {



      box-shadow: 0 7px 20px rgba(24, 51, 41, 0.07);



      transform: translateY(-2px);



    }



    .cc-heading {



      min-height: 52px;



      margin-bottom: 16px;



    }



    .chart-card .cc-title {



      margin: 0 0 5px;



      color: #183329;



      font-size: 15px;



      font-weight: 700;



      line-height: 1.45;



    }



    .cc-subtitle {



      color: #87948c;



      font-size: 12px;



      line-height: 1.5;



    }



    .chart-wrap {



      position: relative;



      width: 100%;



      height: 250px;



      min-width: 0;



    }



    .chart-wrap canvas {



      display: block;



      width: 100% !important;



      max-width: 100%;



    }



    .chart-empty-note {



      display: none;



      position: absolute;



      inset: 0;



      align-items: center;



      justify-content: center;



      padding: 18px;



      color: #718096;



      background: #fbfdfb;



      border: 1px dashed #dce7df;



      border-radius: 10px;



      text-align: center;



      font-size: 13px;



      line-height: 1.6;



    }



    .chart-empty-note.is-visible {



      display: flex;



    }



    /* =========================================



                           DASHBOARD SECTION



                        ========================================= */



    @media (max-width: 900px) {



      .chart-grid {



        grid-template-columns: minmax(0, 1fr);



      }



      .chart-card {



        min-height: 340px;



      }



      .chart-wrap {



        height: 250px;



      }



    }



    @media (max-width: 700px) {



      .greeting-banner {



        padding: 20px;



        align-items: flex-start;



      }



      .prog-bar-wrap {



        width: min(220px, 100%);



      }



      .banner-stats {



        display: grid;



        grid-template-columns: repeat(2, minmax(0, 1fr));



        width: 100%;



      }



      .banner-register-btn,



      .banner-stat {



        min-width: 0;



      }



      .stat-grid {



        grid-template-columns: repeat(2, minmax(0, 1fr));



      }



      .chart-grid,



      .bottom-grid {



        grid-template-columns: minmax(0, 1fr);



      }



      .donut-wrap {



        flex-wrap: wrap;



      }



      .activity-item {



        align-items: flex-start;



        flex-wrap: wrap;



      }



      .activity-time {



        margin-left: 44px;



      }



    }



    @media (max-width: 480px) {



      .chart-card {



        min-height: 320px;



        padding: 15px;



        border-radius: 12px;



      }



      .cc-heading {



        min-height: auto;



        margin-bottom: 18px;



      }



      .chart-wrap {



        height: 230px;



      }



    }



    @media (max-width: 420px) {



      .stat-grid {



        grid-template-columns: minmax(0, 1fr);



      }



      .greeting-banner h2 {



        font-size: 21px;



      }



      .chart-card,



      .activity-card,



      .ann-card,



      .sched-card {



        padding: 14px;



      }



    }



    .donut-wrap {



      display: flex;



      align-items: center;



      gap: 20px;



      margin-top: 10px;



    }



    .donut-canvas-wrap {



      position: relative;



      width: 140px;



      height: 140px;



      flex-shrink: 0;



    }



    .donut-legend {



      font-size: 12px;



      color: #718096;



    }



    .donut-legend-item {



      display: flex;



      align-items: center;



      gap: 7px;



      margin-bottom: 8px;



    }



    .dl-dot {



      width: 10px;



      height: 10px;



      border-radius: 2px;



      flex-shrink: 0;



    }



    .activity-card {



      background: white;



      border-radius: 12px;



      padding: 18px;



      border: 0.5px solid #e2e8f0;



      margin-bottom: 24px;



    }



    .activity-item {



      display: flex;



      align-items: center;



      gap: 12px;



      padding: 9px 0;



      border-bottom: 0.5px solid #f0f4f8;



      font-size: 13px;



      color: #4a5568;



    }



    .activity-item:last-child {



      border-bottom: none;



    }



    .activity-icon {



      width: 32px;



      height: 32px;



      border-radius: 50%;



      background: #e6f4eb;



      display: flex;



      align-items: center;



      justify-content: center;



      flex-shrink: 0;



    }



    .activity-time {



      font-size: 11px;



      color: #a0aec0;



      margin-left: auto;



      white-space: nowrap;



    }



    .badge-pass {



      background: #e6f4eb;



      color: #276749;



      font-size: 11px;



      padding: 1px 7px;



      border-radius: 999px;



    }



    .badge-fail {



      background: #fef2f2;



      color: #c53030;



      font-size: 11px;



      padding: 1px 7px;



      border-radius: 999px;



    }



    .no-data {



      color: #a0aec0;



      font-size: 13px;



      text-align: center;



      padding: 20px 0;



    }



    .bottom-grid {



      display: grid;



      grid-template-columns: 1fr 1fr;



      gap: 16px;



      margin-bottom: 24px;



    }



    @media (max-width: 700px) {



      .bottom-grid {



        grid-template-columns: 1fr;



      }



    }



    .ann-card {



      background: white;



      border-radius: 12px;



      padding: 18px;



      border: 0.5px solid #e2e8f0;



    }



    .ann-item {



      padding: 10px 0;



      border-bottom: 0.5px solid #f0f4f8;



    }



    .ann-item:last-child {



      border-bottom: none;



    }



    .ann-title {



      font-size: 14px;



      font-weight: 600;



      color: #2d3748;



      margin-bottom: 3px;



    }



    .ann-msg {



      font-size: 13px;



      color: #718096;



    }



    .ann-type-reminder {



      background: #e6f4eb;



      color: #276749;



      font-size: 11px;



      padding: 2px 8px;



      border-radius: 999px;



    }



    .ann-type-notice {



      background: #ebf4ff;



      color: #1a5fa3;



      font-size: 11px;



      padding: 2px 8px;



      border-radius: 999px;



    }



    .ann-type-urgent {



      background: #fef2f2;



      color: #c53030;



      font-size: 11px;



      padding: 2px 8px;



      border-radius: 999px;



    }



    .sched-card {



      background: white;



      border-radius: 12px;



      padding: 18px;



      border: 0.5px solid #e2e8f0;



    }



    .sched-item {



      display: flex;



      gap: 12px;



      align-items: flex-start;



      padding: 8px 0;



      border-bottom: 0.5px solid #f0f4f8;



    }



    .sched-item:last-child {



      border-bottom: none;



    }



    .sched-badge {



      background: #e6f4eb;



      color: #1a4d2e;



      font-size: 11px;



      font-weight: 600;



      padding: 4px 10px;



      border-radius: 6px;



      white-space: nowrap;



      flex-shrink: 0;



    }



    .sched-title {



      font-size: 14px;



      font-weight: 600;



      color: #2d3748;



    }



    .sched-loc {



      font-size: 12px;



      color: #a0aec0;



      margin-top: 2px;



    }



    .current-course-card {



      background: #fff;



      border: 1px solid #e2e8f0;



      border-radius: 12px;



      padding: 18px;



      margin-bottom: 24px;



      display: flex;



      align-items: center;



      justify-content: space-between;



      gap: 18px;



      flex-wrap: wrap;



    }



    .current-course-main {



      display: flex;



      align-items: flex-start;



      gap: 14px;



      min-width: 0;



      flex: 1;



    }



    .current-course-icon {



      width: 44px;



      height: 44px;



      flex: 0 0 44px;



      display: flex;



      align-items: center;



      justify-content: center;



      border-radius: 12px;



      background: #e6f4eb;



      color: #1a4d2e;



      font-size: 19px;



    }



    .current-course-title {



      color: #1a4d2e;



      font-size: 16px;



      font-weight: 700;



      line-height: 1.4;



      overflow-wrap: anywhere;



    }



    .current-course-meta {



      color: #718096;



      font-size: 12px;



      margin-top: 5px;



      line-height: 1.6;



    }



    .course-progress-track {



      height: 7px;



      width: min(240px, 100%);



      margin-top: 10px;



      overflow: hidden;



      border-radius: 999px;



      background: #e8eee9;



    }



    .course-progress-fill {



      height: 100%;



      border-radius: inherit;



      background: #f5c842;



    }



    .current-course-status {



      background: #e6f4eb;



      color: #276749;



      border-radius: 999px;



      padding: 6px 10px;



      font-size: 11px;



      font-weight: 700;



      white-space: nowrap;



    }



    .chart-empty-note {



      display: none;



      position: absolute;



      inset: 0;



      align-items: center;



      justify-content: center;



      color: #718096;



      text-align: center;



      font-size: 12px;



      padding: 16px;



      line-height: 1.5;



    }



    .chart-empty-note.is-visible {



      display: flex;



    }



    @media (max-width: 700px) {



      .greeting-banner {



        padding: 20px;



        align-items: flex-start;



      }



      .prog-bar-wrap {



        width: min(220px, 100%);



      }



      .banner-stats {



        display: grid;



        grid-template-columns: repeat(2, minmax(0, 1fr));



        width: 100%;



      }



      .banner-register-btn,



      .banner-stat {



        min-width: 0;



      }



      .stat-grid {



        grid-template-columns: repeat(2, minmax(0, 1fr));



      }



      .chart-grid,



      .bottom-grid {



        grid-template-columns: minmax(0, 1fr);



      }



      .donut-wrap {



        flex-wrap: wrap;



      }



      .activity-item {



        align-items: flex-start;



        flex-wrap: wrap;



      }



      .activity-time {



        margin-left: 44px;



      }



    }



    @media (max-width: 420px) {



      .stat-grid {



        grid-template-columns: minmax(0, 1fr);



      }



      .greeting-banner h2 {



        font-size: 21px;



      }



      .chart-card,



      .activity-card,



      .ann-card,



      .sched-card {



        padding: 14px;



      }



    }



    /* TRAINING FACILITY CARD */



    .location-card {



      background: #ffffff;



      border: 1px solid #e2ebe5;



      border-radius: 16px;



      overflow: hidden;



      margin-bottom: 24px;



      box-shadow: 0 4px 16px rgba(24, 51, 41, 0.04);



    }



    .location-map {



      height: auto;



      min-height: 0;



      background: #ffffff;



      display: flex;



      flex-direction: column;



      overflow: hidden;



      padding: 0;



      color: #183329;



      font-size: 13px;



      gap: 0;



    }



    .facility-map-header {



      display: flex;



      justify-content: space-between;



      align-items: center;



      flex-wrap: wrap;



      gap: 10px;



      padding: 18px 20px;



      background: #ffffff;



      border-bottom: 1px solid #edf2ee;



    }



    .facility-map-header i {



      color: #075b35;



      font-size: 16px;



    }



    .facility-map-header span {



      color: #183329;



      font-size: 14px;



      font-weight: 700;



    }



    .training-facility-map {



      display: block;



      width: 100%;



      height: 300px;



      min-height: 300px;



      background: #eaf5ee;



      z-index: 1;



    }



    .facility-directions {



      display: flex;



      justify-content: center;



      align-items: center;



      gap: 9px;



      margin: 14px 18px;



      padding: 12px 18px;



      border: 1px solid #dce9df;



      border-radius: 10px;



      background: #eaf5ee;



      color: #075b35;



      text-decoration: none;



      font-size: 13px;



      font-weight: 700;



      transition: *background* 0.2s ease, transform 0.2s ease;



    }



    .facility-directions:hover {



      background: #dcefe2;



      color: #043f26;



      transform: translateY(-1px);



    }



    .location-info {



      padding: 16px 20px 20px;



      border-top: 1px solid #edf2ee;



    }



    .location-info .li-title {



      font-size: 14px;



      font-weight: 700;



      color: #183329;



      margin-bottom: 7px;



    }



    .location-info .li-addr {



      display: flex;



      align-items: flex-start;



      gap: 8px;



      color: #718096;



      font-size: 12px;



      line-height: 1.6;



    }



    @media (max-width: 600px) {



      .training-facility-map {



        height: 240px;



        min-height: 240px;



      }



      .facility-map-header {



        padding: 15px;



      }



      .facility-directions {



        margin: 12px;



      }



      .location-info {



        padding: 14px 15px 17px;



      }



    }





    /* ===== Refined homepage layout and schedule/announcement cards ===== */

    .main-content {

        background: #f4f6f0;

        min-width: 0;

    }



    .homepage-wrap {

        width: 100%;

        max-width: 1440px;

        margin: 0 auto;

        padding: 24px 28px;

        box-sizing: border-box;

    }



    .greeting-banner,

    .stat-card,

    .chart-card,

    .activity-card,

    .current-course-card,

    .sched-card,

    .ann-card,

    .location-card {

        box-sizing: border-box;

    }



    .chart-grid,

    .bottom-grid {

        grid-template-columns: repeat(2, minmax(0, 1fr));

        align-items: stretch;

    }



    .bottom-grid {

        gap: 18px;

        margin-bottom: 26px;

    }



    .sched-card,

    .ann-card {

        min-width: 0;

        padding: 22px;

        border: 1px solid #e2ebe5;

        border-radius: 16px;

        background: #fff;

        box-shadow: 0 3px 12px rgba(24, 51, 41, 0.035);

    }



    .sched-item {

        align-items: flex-start;

        gap: 12px;

        padding: 13px 0;

        border-bottom: 1px solid #edf2ee;

        min-width: 0;

    }



    .sched-item:last-child,

    .ann-item:last-child {

        border-bottom: none;

        padding-bottom: 0;

    }



    .sched-badge {

        display: flex;

        align-items: center;

        justify-content: center;

        width: 36px;

        height: 36px;

        min-width: 36px;

        padding: 0;

        border-radius: 9px;

        background: #eaf5ee;

        color: #075b35;

        flex-shrink: 0;

    }



    .sched-title,

    .ann-title {

        color: #183329;

        font-size: 13px;

        font-weight: 700;

        line-height: 1.5;

        overflow-wrap: anywhere;

    }



    .sched-loc,

    .ann-msg {

        color: #718096;

        font-size: 12px;

        line-height: 1.7;

        overflow-wrap: anywhere;

    }



    .ann-item {

        padding: 13px 0;

        border-bottom: 1px solid #edf2ee;

        min-width: 0;

    }



    .ann-item:first-child {

        padding-top: 4px;

    }



    .ann-title {

        margin-bottom: 5px;

    }



    .chart-card {

        min-width: 0;

    }



    .chart-wrap {

        width: 100%;

        min-width: 0;

    }



    .chart-wrap canvas {

        max-width: 100%;

    }



    .current-course-meta {

        overflow-wrap: anywhere;

    }



    .training-facility-map {

        width: 100%;

        min-width: 0;

    }



    @media (max-width: 900px) {

        .homepage-wrap {

            padding: 22px;

        }



        .chart-grid,

        .bottom-grid {

            grid-template-columns: minmax(0, 1fr);

        }

    }



    @media (max-width: 700px) {

        .homepage-wrap {

            padding: 18px;

        }



        .greeting-banner {

            padding: 20px;

            align-items: flex-start;

        }



        .banner-stats {

            display: grid;

            grid-template-columns: repeat(2, minmax(0, 1fr));

            width: 100%;

        }



        .banner-register-btn,

        .banner-stat {

            min-width: 0;

        }



        .stat-grid {

            grid-template-columns: repeat(2, minmax(0, 1fr));

        }



        .chart-grid,

        .bottom-grid {

            grid-template-columns: minmax(0, 1fr);

        }



        .activity-item {

            align-items: flex-start;

            flex-wrap: wrap;

        }



        .activity-time {

            margin-left: 44px;

        }

    }



    @media (max-width: 480px) {

        .homepage-wrap {

            padding: 14px;

        }



        .sched-card,

        .ann-card,

        .chart-card,

        .activity-card {

            padding: 15px;

            border-radius: 13px;

        }



        .stat-grid {

            grid-template-columns: minmax(0, 1fr);

        }



        .chart-wrap {

            height: 230px;

        }



        .sched-item {

            gap: 10px;

        }

    }



    
/* ==========================================

   TRAINING SCHEDULE + ANNOUNCEMENTS

   Matching card heights and clean layout

\========================================== */



.bottom-grid {

    display: grid;

    grid-template-columns: repeat(2, minmax(0, 1fr));

    gap: 22px;

    align-items: stretch;

    margin-bottom: 26px;

}



.dashboard-info-card {

    display: flex;

    flex-direction: column;

    min-width: 0;

    min-height: 440px;

    height: 100%;

    padding: 22px;

    box-sizing: border-box;

    background: #ffffff;

    border: 1px solid #e2ebe5;

    border-radius: 16px;

    box-shadow: 0 4px 16px rgba(24, 51, 41, 0.045);

    transition: box-shadow 0.2s ease, transform 0.2s ease;

}



.dashboard-info-card:hover {

    box-shadow: 0 8px 24px rgba(24, 51, 41, 0.075);

    transform: translateY(-2px);

}



.info-card-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 12px;

    padding-bottom: 18px;

    margin-bottom: 6px;

    border-bottom: 1px solid #edf2ee;

}



.info-card-heading {

    display: flex;

    align-items: center;

    gap: 12px;

    min-width: 0;

}



.info-card-heading-icon,

.info-card-corner-icon {

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    width: 42px;

    height: 42px;

    border-radius: 12px;

}



.schedule-heading-icon {

    color: #075b35;

    background: #eaf5ee;

}



.announcement-heading-icon {

    color: #075b35;

    background: #eaf5ee;

}



.info-card-corner-icon {

    width: 38px;

    height: 38px;

}



.schedule-corner-icon {

    color: #075b35;

    background: #eaf5ee;

}



.announcement-corner-icon {

    color: #b7791f;

    background: #fff7df;

}



.info-card-header h3 {

    margin: 0;

    color: #183329;

    font-size: 16px;

    font-weight: 750;

    line-height: 1.4;

}



.info-card-header p {

    margin: 5px 0 0;

    color: #87948c;

    font-size: 12px;

    line-height: 1.5;

}



/* Schedule course summary */



.schedule-course-summary {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 16px 0;

    border-bottom: 1px solid #edf2ee;

}



.schedule-course-icon {

    display: flex;

    align-items: center;

    justify-content: center;

    flex: 0 0 42px;

    width: 42px;

    height: 42px;

    color: #075b35;

    background: #eaf5ee;

    border-radius: 12px;

}



.schedule-course-info {

    min-width: 0;

}



.schedule-course-info h4 {

    margin: 0;

    color: #183329;

    font-size: 14px;

    font-weight: 750;

    line-height: 1.5;

    overflow-wrap: anywhere;

}



.schedule-course-info p {

    margin: 4px 0 0;

    color: #718096;

    font-size: 12px;

    line-height: 1.5;

    overflow-wrap: anywhere;

}



.schedule-course-info p i {

    margin-right: 4px;

    color: #075b35;

}



/* Schedule details */



.schedule-details {

    display: flex;

    flex-direction: column;

}



.schedule-detail-row {

    display: flex;

    align-items: flex-start;

    gap: 12px;

    padding: 15px 0;

    border-bottom: 1px solid #edf2ee;

}



.schedule-detail-row:last-child {

    border-bottom: 0;

}



.schedule-detail-icon {

    display: flex;

    align-items: center;

    justify-content: center;

    flex: 0 0 34px;

    width: 34px;

    height: 34px;

    color: #075b35;

    background: #f0f7f2;

    border-radius: 10px;

    font-size: 13px;

}



.schedule-detail-content {

    display: flex;

    flex-direction: column;

    gap: 5px;

    min-width: 0;

    padding-top: 1px;

}



.schedule-detail-label {

    color: #718096;

    font-size: 11px;

    font-weight: 600;

}



.schedule-detail-content strong {

    color: #183329;

    font-size: 12px;

    font-weight: 650;

    line-height: 1.6;

    overflow-wrap: anywhere;

}



/* Training day pills */



.training-day-pills {

    display: flex;

    flex-wrap: wrap;

    gap: 5px;

}



.training-day-pills span {

    padding: 4px 7px;

    color: #075b35;

    background: #eaf5ee;

    border: 1px solid #dceee1;

    border-radius: 6px;

    font-size: 10px;

    font-weight: 700;

}



/* Announcement list */



.announcement-list {

    flex: 1;

    min-width: 0;

}



.announcement-entry {

    display: flex;

    align-items: flex-start;

    gap: 11px;

    padding: 15px 0;

    border-bottom: 1px solid #edf2ee;

}



.announcement-entry:last-child {

    border-bottom: 0;

}



.announcement-entry-icon {

    display: flex;

    align-items: center;

    justify-content: center;

    flex: 0 0 36px;

    width: 36px;

    height: 36px;

    color: #075b35;

    background: #eaf5ee;

    border-radius: 10px;

    font-size: 13px;

}



.announcement-entry-content {

    flex: 1;

    min-width: 0;

}



.announcement-entry-content h4 {

    margin: 0 0 6px;

    color: #183329;

    font-size: 13px;

    font-weight: 750;

    line-height: 1.5;

    overflow-wrap: anywhere;

}



.announcement-entry-content p {

    margin: 0;

    color: #687b70;

    font-size: 12px;

    line-height: 1.7;

    overflow-wrap: anywhere;

    white-space: normal;

}



.announcement-time {

    display: block;

    margin-top: 8px;

    color: #98a69e;

    font-size: 11px;

}



.announcement-time i {

    margin-right: 4px;

}



/* View all announcements footer */



.announcement-footer {

    margin-top: auto;

    padding-top: 15px;

    border-top: 1px solid #edf2ee;

}



.view-all-announcements {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    padding: 11px 13px;

    color: #075b35;

    background: #f0f7f2;

    border: 1px solid #dceee1;

    border-radius: 10px;

    text-decoration: none;

    font-size: 12px;

    font-weight: 750;

    transition: *background* 0.2s ease, color 0.2s ease;

}



.view-all-announcements:hover {

    color: #ffffff;

    background: #075b35;

    border-color: #075b35;

}



.view-all-announcements i {

    transition: transform 0.2s ease;

}



.view-all-announcements:hover i {

    transform: translateX(3px);

}



/* Empty state */



.info-card-empty {

    display: flex;

    flex: 1;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    padding: 35px 16px;

    text-align: center;

}



.info-card-empty > i {

    margin-bottom: 12px;

    color: #8aa695;

    font-size: 26px;

}



.info-card-empty strong {

    color: #183329;

    font-size: 13px;

}



.info-card-empty p {

    max-width: 240px;

    margin: 7px 0 0;

    color: #87948c;

    font-size: 12px;

    line-height: 1.6;

}



/* Responsive */



@media (max-width: 900px) {

    .bottom-grid {

        grid-template-columns: minmax(0, 1fr);

    }



    .dashboard-info-card {

        min-height: 0;

    }

}



@media (max-width: 480px) {

    .dashboard-info-card {

        padding: 16px;

        border-radius: 13px;

    }



    .info-card-heading {

        gap: 9px;

    }



    .info-card-heading-icon {

        width: 36px;

        height: 36px;

    }



    .info-card-header h3 {

        font-size: 14px;

    }



    .info-card-corner-icon {

        width: 33px;

        height: 33px;

    }

}




/* ===== Larger Training Schedule Text & Icons ===== */



/* Main card heading */

.sched-card .info-card-header h3 {

    font-size: 20px !important;

    font-weight: 750;

}



.sched-card .info-card-header p {

    font-size: 14px !important;

}



/* Header icons */

.sched-card .info-card-heading-icon {

    width: 52px !important;

    height: 52px !important;

    font-size: 22px !important;

    border-radius: 14px !important;

}



.sched-card .info-card-corner-icon {

    width: 46px !important;

    height: 46px !important;

    font-size: 20px !important;

}



/* Course icon */

.sched-card .schedule-course-icon {

    width: 50px !important;

    height: 50px !important;

    flex-basis: 50px !important;

    font-size: 23px !important;

}



/* Course title */

.sched-card .schedule-course-info h4 {

    font-size: 17px !important;

    line-height: 1.5;

}



/* Facility name */

.sched-card .schedule-course-info p {

    font-size: 14px !important;

}



/* Detail row icons */

.sched-card .schedule-detail-icon {

    width: 42px !important;

    height: 42px !important;

    flex-basis: 42px !important;

    font-size: 17px !important;

    border-radius: 12px !important;

}



/* Training Dates, Days, and Time labels */

.sched-card .schedule-detail-label {

    font-size: 13px !important;

}



/* Actual date and time values */

.sched-card .schedule-detail-content strong {

    font-size: 15px !important;

    line-height: 1.7;

}



/* Training day pills */

.sched-card .training-day-pills span {

    padding: 6px 10px !important;

    font-size: 12px !important;

    border-radius: 7px !important;

}



/* Give schedule rows a little more breathing room */

.sched-card .schedule-detail-row {

    gap: 14px;

    padding: 17px 0;

}



.sched-card .schedule-course-summary {

    gap: 14px;

    padding: 18px 0;

}



/* Smaller screens */

@media (max-width: 480px) {

    .sched-card .info-card-header h3 {

        font-size: 17px !important;

    }



    .sched-card .schedule-detail-content strong {

        font-size: 13px !important;

    }



    .sched-card .schedule-course-info h4 {

        font-size: 15px !important;

    }

}






    
    .training-facility-map-wrap {
        width: 100%;
        min-width: 0;
        background: #eaf5ee;
    }

    .training-facility-google-map {
        display: block;
        width: 100%;
        height: 360px;
        border: 0;
        background: #eaf5ee;
    }

    .training-facility-map-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 110px;
        padding: 22px;
        color: #52665a;
        background: #f7fbf8;
        text-align: center;
        font-size: 13px;
        line-height: 1.6;
    }

    .training-facility-map-fallback[hidden] {
        display: none !important;
    }

    @media (max-width: 600px) {
        .training-facility-google-map {
            height: 260px;
        }
    }
</style>



  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />



@endsection



@section('content')



  <div class="homepage-wrap">



    {{-- ── Greeting Banner ──────────────────────────────────────────────── --}}



    <div class="greeting-banner">



      <div>



        <h2>Hello, {{ Auth::user()->firstname }}! 👋</h2>



        <p>{{ now()->format('l, F j, Y') }} · LEDIPO</p>



        @if ($enrollment)



          <div class="prog-bar-wrap">



            <div class="prog-bar-fill" style="width:{{ $avgProgress }}%;"></div>



          </div>



          <p style="font-size:11px;color:#b6d9c0;margin-top:4px;">Overall progress:



            {{ $avgProgress }}%</p>



        @endif



      </div>



      <div class="banner-stats">



        <a href="{{ route('registration.step1') }}" class="banner-register-btn">



          <span class="rb-icon">📝</span>



          <span class="rb-label">Register</span>



        </a>



        <div class="banner-stat">



          <span



            class="bval">{{ $enrollment ? ucfirst($enrollment->status) : 'N/A' }}</span>



          <span class="blbl">Status</span>



        </div>



        <div class="banner-stat">



          <span class="bval">{{ $avgProgress }}%</span>



          <span class="blbl">Progress</span>



        </div>



        <div class="banner-stat">



          <span class="bval">{{ $upcomingDeadlines }}</span>



          <span class="blbl">Deadlines</span>



        </div>



      </div>



    </div>



    {{-- ── Personal Analytics ────────────────────────────────────────────── --}}



    <p class="section-heading">Personal Analytics</p>



    <div class="stat-grid">



      <div class="stat-card">



        <div class="sc-label">Courses enrolled</div>



        <div class="sc-value">{{ $totalEnrolled }}</div>



        <div class="sc-sub">{{ $totalCompleted }} completed</div>



      </div>



      <div class="stat-card">



        <div class="sc-label">Overall progress</div>



        <div class="sc-value">{{ $avgProgress }}%</div>



        @if ($avgProgress >= 50)



          <span class="badge-green">On track</span>



        @else



          <span class="badge-yellow">Keep going!</span>



        @endif



      </div>



      <div class="stat-card">



        <div class="sc-label">Quizzes taken</div>



        <div class="sc-value">{{ $quizzesTaken }}</div>



        <div class="sc-sub">{{ $quizzesPassed }} passed</div>



      </div>



    </div>



    {{-- ── Continue Learning / Current Course ───────────────────────── --}}



    <p class="section-heading">Continue Learning</p>



    <div class="current-course-card">



      @if ($enrollment && $enrollment->course)



        @php



          $courseProgress = (int) ($enrollment->calculated_progress ?? 0);



          $courseProgress = max(0, min(100, $courseProgress));



        @endphp



        <div class="current-course-main">



          <div class="current-course-icon" aria-hidden="true">



            <i class="fa fa-book-open"></i>



          </div>



          <div style="min-width:0;flex:1;">



            <div class="current-course-title">{{ $enrollment->course->title }}



            </div>



            <div class="current-course-meta">



              <span>



    <i class="fa fa-clock" aria-hidden="true"></i>



    @if ($enrollment->course?->start_date && $enrollment->course?->end_date)



        {{ \Carbon\Carbon::parse($enrollment->course->start_date)->format('M d, Y') }}



        -&#x20;



        {{ \Carbon\Carbon::parse($enrollment->course->end_date)->format('M d, Y') }}



    @else



        Training dates not specified



    @endif



</span>



<span style="margin: 0 6px;">·</span>



              <span><i class="fa fa-location-dot" aria-hidden="true"></i>



                {{ $enrollment->course->facility->name ?? 'No facility assigned' }}</span>



            </div>



            <div class="course-progress-track" role="progressbar"



              aria-label="Selected course progress" aria-valuemin="0"



              aria-valuemax="100"



              aria-valuenow="{{ min(100, max(0, (float) $courseProgress)) }}">



              <div class="course-progress-fill"



                style="width: {{ min(100, max(0, (float) $courseProgress)) }}%;">



              </div>



            </div>



            <div class="current-course-meta">



              {{ min(100, max(0, (float) $courseProgress)) }}% course progress



              · Continue from the My Courses page.



            </div>



          </div>



        </div>



        <span class="current-course-status">



          {{ ucfirst($enrollment->status ?? 'Enrolled') }}



        </span>



      @else



        <div class="current-course-main">



          <div class="current-course-icon" aria-hidden="true">



            <i class="fa fa-book-open"></i>



          </div>



          <div>



            <div class="current-course-title">No active course yet</div>



            <div class="current-course-meta">



              Your enrolled course will appear here once your registration is



              active.



            </div>



          </div>



        </div>



      @endif



    </div>



    {{-- ── Personal Analytics Charts ───────────────────────────────────── --}}



    <p class="section-heading">Learning Summary</p>



    <div class="chart-grid">



      <div class="chart-card">



        <div class="cc-heading">



          <div class="cc-title">1. Quiz Performance Trend</div>



          <div class="cc-subtitle">Your quiz scores over time</div>



        </div>



        <div class="chart-wrap">



          <canvas id="quizTrendChart" role="img"



            aria-label="Line chart showing quiz scores over time">Quiz performance



            trend chart.</canvas>



          <div id="quizTrendEmpty" class="chart-empty-note">No quiz score history



            is available yet.</div>



        </div>



      </div>



      <div class="chart-card">



        <div class="cc-heading">



          <div class="cc-title">2. Learning Progress by Unit</div>



          <div class="cc-subtitle">Completion percentage for each unit</div>



        </div>



        <div class="chart-wrap">



          <canvas id="unitProgressChart" role="img"



            aria-label="Bar chart showing learning progress by unit">Learning



            progress by unit chart.</canvas>



          <div id="unitProgressEmpty" class="chart-empty-note">Unit progress will



            appear when learning progress is recorded.</div>



        </div>



      </div>



      <div class="chart-card">



        <div class="cc-heading">



          <div class="cc-title">3. Quiz vs. Onsite Performance</div>



          <div class="cc-subtitle">Compare average results by assessment type



          </div>



        </div>



        <div class="chart-wrap">



          <canvas id="performanceCompareChart" role="img"



            aria-label="Bar chart comparing quiz and onsite performance percentages">Quiz



            and onsite performance comparison chart.</canvas>



          <div id="performanceCompareEmpty" class="chart-empty-note">Performance



            comparison will appear when assessment results are available.</div>



        </div>



      </div>



      <div class="chart-card">



        <div class="cc-heading">



          <div class="cc-title">4. Assessment Results Summary</div>



          <div class="cc-subtitle">Passed and failed assessment attempts</div>



        </div>



        <div class="chart-wrap">



          <canvas id="assessmentSummaryChart" role="img"



            aria-label="Bar chart summarizing passed and failed assessments">Assessment



            results summary chart.</canvas>



          <div id="assessmentSummaryEmpty" class="chart-empty-note">Assessment



            results will appear after you complete a quiz or onsite evaluation.



          </div>



        </div>



      </div>



    </div>



    {{-- ── Recent Activity ──────────────────────────────────────────────── --}}



    <p class="section-heading">Recent activity</p>



    <div class="activity-card">



      @forelse($recentActivity as $item)



        <div class="activity-item">



          <div class="activity-icon">



            <svg width="14" height="14" fill="none"



              viewBox="0 0 16 16">



              <path d="M13 5L7 11l-3-3" stroke="#276749" stroke-width="1.5"



                stroke-linecap="round" stroke-linejoin="round" />



            </svg>



          </div>



          <div style="flex:1;">



            Took quiz: <strong>{{ $item->quiz->title ?? 'Quiz' }}</strong>



            &nbsp;



            @if ($item->status === 'passed')



              <span class="badge-pass">Passed · {{ $item->percentage }}%</span>



            @else



              <span class="badge-fail">Failed · {{ $item->percentage }}%</span>



            @endif



          </div>



          <span



            class="activity-time">{{ $item->created_at->diffForHumans() }}</span>



        </div>



      @empty



        <div class="no-data">No recent activity yet.</div>



      @endforelse



    </div>



{{-- ── Training Schedule & Trainer Announcements ── --}}

<div class="bottom-grid">



    {{-- TRAINING SCHEDULE CARD --}}

    <div class="sched-card dashboard-info-card">



        <div class="info-card-header">

            <div class="info-card-heading">

                <div class="info-card-heading-icon schedule-heading-icon">

                    <i class="fa fa-calendar-days"></i>

                </div>

                <div>

                    <h3>Training Schedule</h3>

                </div>

            </div>



            <span class="info-card-corner-icon schedule-corner-icon">

                <i class="fa fa-clock"></i>

            </span>

        </div>



        @if ($enrollment && $enrollment->course)



            @php

                $scheduledCourse = $enrollment->course;

                $trainingDays = $scheduledCourse->training_days ?? [];



                if (is_string($trainingDays)) {

                    $trainingDays = json_decode($trainingDays, true) ?? [];

                }



                $trainingDays = is_array($trainingDays) ? $trainingDays : [];

            @endphp



            <div class="schedule-course-summary">

                <div class="schedule-course-icon">

                    <i class="fa fa-book-open"></i>

                </div>



                <div class="schedule-course-info">

                    <h4>{{ $scheduledCourse->title }}</h4>

                    <p>

                        <i class="fa fa-location-dot"></i>

                        {{ $scheduledCourse->facility->name ?? 'No facility assigned' }}

                    </p>

                </div>

            </div>



            <div class="schedule-details">



                <div class="schedule-detail-row">

                    <div class="schedule-detail-icon">

                        <i class="fa fa-calendar"></i>

                    </div>

                    <div class="schedule-detail-content">

                        <span class="schedule-detail-label">Training Dates</span>

                        <strong>

                            @if ($scheduledCourse->start_date && $scheduledCourse->end_date)

                                {{ \Carbon\Carbon::parse($scheduledCourse->start_date)->format('M d, Y') }}

                                –

                                {{ \Carbon\Carbon::parse($scheduledCourse->end_date)->format('M d, Y') }}

                            @else

                                Dates not yet specified

                            @endif

                        </strong>

                    </div>

                </div>



                <div class="schedule-detail-row">

                    <div class="schedule-detail-icon">

                        <i class="fa fa-calendar-week"></i>

                    </div>

                    <div class="schedule-detail-content">

                        <span class="schedule-detail-label">Training Days</span>



                        @if (count($trainingDays))

                            <div class="training-day-pills">

                                @foreach ($trainingDays as $day)

                                    <span>{{ substr($day, 0, 3) }}</span>

                                @endforeach

                            </div>

                        @else

                            <strong>Days not yet specified</strong>

                        @endif

                    </div>

                </div>



                <div class="schedule-detail-row">

                    <div class="schedule-detail-icon">

                        <i class="fa fa-clock"></i>

                    </div>

                    <div class="schedule-detail-content">

                        <span class="schedule-detail-label">Training Time</span>

                        <strong>

                            @if ($scheduledCourse->start_time && $scheduledCourse->end_time)

                                {{ \Carbon\Carbon::parse($scheduledCourse->start_time)->format('g:i A') }}

                                –

                                {{ \Carbon\Carbon::parse($scheduledCourse->end_time)->format('g:i A') }}

                            @else

                                Time not yet specified

                            @endif

                        </strong>

                    </div>

                </div>



            </div>



        @else



            <div class="info-card-empty">

                <i class="fa fa-calendar-xmark"></i>

                <strong>No training schedule yet</strong>

                <p>Your schedule will appear here once you are enrolled in a course.</p>

            </div>



        @endif

    </div>





    {{-- TRAINER ANNOUNCEMENTS CARD --}}

    <div class="ann-card dashboard-info-card">



        <div class="info-card-header">

            <div class="info-card-heading">

                <div class="info-card-heading-icon announcement-heading-icon">

                    <i class="fa fa-bullhorn"></i>

                </div>

                <div>

                    <h3>Trainer Announcements</h3>

                    <p>Important updates from your trainer</p>

                </div>

            </div>



            <span class="info-card-corner-icon announcement-corner-icon">

                <i class="fa fa-bell"></i>

            </span>

        </div>



        <div class="announcement-list">



            @forelse ($trainerAnnouncements->take(3) as $announcement)



                <div class="announcement-entry">



                    <div class="announcement-entry-icon">

                        <i class="fa fa-bullhorn"></i>

                    </div>



                    <div class="announcement-entry-content">



                        <h4>

                            {{ $announcement->title

                                ?? $announcement->announcement_title

                                ?? $announcement->course->title

                                ?? 'Training Announcement' }}

                        </h4>



                        <p>

                            {{ \Illuminate\Support\Str::limit(

                                trim(strip_tags($announcement->content)),

                                130

                            ) }}

                        </p>



                        <span class="announcement-time">

                            <i class="fa fa-clock"></i>

                            {{ $announcement->created_at

                                ? \Carbon\Carbon::parse($announcement->created_at)->diffForHumans()

                                : 'Recently posted' }}

                        </span>



                    </div>



                </div>



            @empty



                <div class="info-card-empty">

                    <i class="fa fa-bullhorn"></i>

                    <strong>No announcements yet</strong>

                    <p>Updates from your trainer will appear here.</p>

                </div>



            @endforelse



        </div>



        <div class="announcement-footer">

            <a href="{{ route('student.announcements') }}"

               class="view-all-announcements">

                View All Announcements

                <i class="fa fa-arrow-right"></i>

            </a>

        </div>



    </div>



</div>





    {{-- ── Training Facility ────────────────────────────────────────────── --}}
    <p class="section-heading">Training Facility</p>

    <div class="location-map">
        @if ($enrollment && $enrollment->course && $enrollment->course->facility)
            <div class="facility-map-header">
                <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                <span>{{ $enrollment->course->facility->name }}</span>
            </div>

            @if (!empty($facilityMap) && !empty($facilityMap->url))
                <div class="training-facility-map-wrap">
                    <iframe
    id="trainingFacilityGoogleMap"
    class="training-facility-google-map"
    width="100%"
    height="360"
    title="Map for {{ $enrollment->course->facility->name }}"
    data-map-url="{{ $facilityMap->url }}"
    data-facility-address="{{ $enrollment->course->facility->address ?? $enrollment->course->facility->name }}"
    loading="lazy"
    referrerpolicy="strict-origin-when-cross-origin"
    allowfullscreen
    src="about:blank">
</iframe>
                    <div id="trainingFacilityMapFallback" class="training-facility-map-fallback" hidden>
                        <i class="fa fa-map-marked-alt" aria-hidden="true"></i>
                        <span>This map link opens directly in Google Maps.</span>
                    </div>
                </div>

                @if (!empty($facilityMap->description))
                    <div class="location-info">
                        <div class="li-title">{{ $facilityMap->title ?: 'Facility location' }}</div>
                        <div class="li-addr">{{ $facilityMap->description }}</div>
                    </div>
                @endif

                
            @else
                <div class="training-facility-map-fallback">
                    <i class="fa fa-map-marked-alt" aria-hidden="true"></i>
                    <span>The facility location has not been added by the administrator yet.</span>
                </div>
            @endif
        @else
            <div class="training-facility-map-fallback">
                <i class="fa fa-building" aria-hidden="true"></i>
                <span>No facility assigned</span>
            </div>
        @endif
    </div>

  </div>

  </div>

@endsection



@section('scripts')



  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js">



  </script>



  <script>



    (function() {



      function initStudentCharts() {



        if (typeof Chart === 'undefined') {



          console.error('Chart.js failed to load.');



          return;



        }



        const colors = {



          green: '#075b35',



          greenLight: 'rgba(7, 91, 53, 0.10)',



          yellow: '#e1dc36',



          red: '#dc4c4c',



          muted: '#718096',



          grid: '#e8eee9'



        };



        const commonOptions = {



          responsive: true,



          maintainAspectRatio: false,



          animation: {



            duration: 600



          },



          interaction: {



            mode: 'index',



            intersect: false



          },



          plugins: {



            legend: {



              position: 'top',



              labels: {



                color: colors.muted,



                usePointStyle: true,



                pointStyle: 'circle',



                boxWidth: 9,



                padding: 16,



                font: {



                  size: 11



                }



              }



            },



            tooltip: {



              backgroundColor: '#183329',



              titleColor: '#fff',



              bodyColor: '#fff',



              padding: 10,



              cornerRadius: 8



            }



          }



        };



        const percentAxis = () => ({



          beginAtZero: true,



          min: 0,



          max: 100,



          grid: {



            color: colors.grid



          },



          border: {



            display: false



          },



          ticks: {



            stepSize: 25,



            color: colors.muted,



            font: {



              size: 11



            },



            callback: value => value + '%'



          }



        });



        const categoryAxis = () => ({



          grid: {



            display: false



          },



          border: {



            display: false



          },



          ticks: {



            color: colors.muted,



            font: {



              size: 11



            }



          }



        });



        function hasNumericValue(values) {



          return Array.isArray(values) && values.some(value =>



            value !== null && value !== '' && value !== undefined &&



            Number.isFinite(Number(value))



          );



        }



        function makeChart(canvasId, emptyId, config, hasData, emptyMessage) {



          const canvas = document.getElementById(canvasId);



          const empty = document.getElementById(emptyId);



          if (!canvas || !empty) {



            console.warn('Missing chart element:', canvasId, emptyId);



            return;



          }



          const previous = Chart.getChart(canvas);



          if (previous) previous.destroy();



          if (!hasData) {



            canvas.style.display = 'none';



            empty.textContent = emptyMessage;



            empty.classList.add('is-visible');



            return;



          }



          canvas.style.display = 'block';



          empty.classList.remove('is-visible');



          try {



            new Chart(canvas, config);



          } catch (error) {



            console.error('Could not render chart:', canvasId, error);



            canvas.style.display = 'none';



            empty.textContent =



              'This chart could not be displayed. Please refresh the page.';



            empty.classList.add('is-visible');



          }



        }



        // 1. Quiz Performance Trend



        const trendLabels = @json($quizTrendLabels ?? []);



        const trendScores = @json($quizTrendScores ?? []);



        const validTrend = Array.isArray(trendLabels) &&



          Array.isArray(trendScores) &&



          trendLabels.length > 0 &&



          trendLabels.length === trendScores.length &&



          hasNumericValue(trendScores);



        makeChart('quizTrendChart', 'quizTrendEmpty', {



            type: 'line',



            data: {



              labels: trendLabels,



              datasets: [{



                label: 'Quiz score',



                data: trendScores,



                borderColor: colors.green,



                backgroundColor: colors.greenLight,



                pointBackgroundColor: colors.yellow,



                pointBorderColor: colors.green,



                pointBorderWidth: 2,



                pointRadius: 4,



                pointHoverRadius: 6,



                borderWidth: 3,



                fill: true,



                tension: 0.3,



                spanGaps: false



              }]



            },



            options: {



              ...commonOptions,



              scales: {



                x: {



                  ...categoryAxis(),



                  ticks: {



                    color: colors.muted,



                    autoSkip: true,



                    maxTicksLimit: 6,



                    maxRotation: 0,



                    font: {



                      size: 11



                    }



                  }



                },



                y: percentAxis()



              },



              plugins: {



                ...commonOptions.plugins,



                legend: {



                  display: false



                },



                tooltip: {



                  ...commonOptions.plugins.tooltip,



                  callbacks: {



                    label: context => ` Score: ${context.parsed.y}%`



                  }



                }



              }



            }



          }, validTrend,



          'Your quiz scores will appear here after you complete quizzes.');



        // 2. Learning Progress by Unit



        const unitLabels = @json($unitProgressLabels ?? []);



        const unitValues = @json($unitProgressValues ?? []);



        const validUnits = Array.isArray(unitLabels) &&



          Array.isArray(unitValues) &&



          unitLabels.length > 0 &&



          unitLabels.length === unitValues.length &&



          hasNumericValue(unitValues);



        makeChart('unitProgressChart', 'unitProgressEmpty', {



            type: 'bar',



            data: {



              labels: unitLabels,



              datasets: [{



                label: 'Completion',



                data: unitValues,



                backgroundColor: colors.green,



                hoverBackgroundColor: '#0b7546',



                borderRadius: 6,



                borderSkipped: false,



                maxBarThickness: 34



              }]



            },



            options: {



              ...commonOptions,



              indexAxis: 'y',



              scales: {



                x: percentAxis(),



                y: categoryAxis()



              },



              plugins: {



                ...commonOptions.plugins,



                legend: {



                  display: false



                },



                tooltip: {



                  ...commonOptions.plugins.tooltip,



                  callbacks: {



                    label: context => ` Completion: ${context.parsed.x}%`



                  }



                }



              }



            }



          }, validUnits,



          'Unit progress will appear as you complete course modules.');



        // 3. Quiz vs. Onsite Performance



        const comparisonLabels = @json($comparisonLabels ?? ['Overall']);



        const quizAverages = @json($quizAverages ?? []);



        const onsiteAverages = @json($onsiteAverages ?? []);



        const validComparison = Array.isArray(comparisonLabels) &&



          Array.isArray(quizAverages) &&



          Array.isArray(onsiteAverages) &&



          comparisonLabels.length > 0 &&



          comparisonLabels.length === quizAverages.length &&



          comparisonLabels.length === onsiteAverages.length &&



          (hasNumericValue(quizAverages) || hasNumericValue(onsiteAverages));



        makeChart('performanceCompareChart', 'performanceCompareEmpty', {



            type: 'bar',



            data: {



              labels: comparisonLabels,



              datasets: [{



                  label: 'Quiz performance',



                  data: quizAverages,



                  backgroundColor: colors.green,



                  borderRadius: 5,



                  borderSkipped: false,



                  maxBarThickness: 42



                },



                {



                  label: 'Onsite performance',



                  data: onsiteAverages,



                  backgroundColor: colors.yellow,



                  borderRadius: 5,



                  borderSkipped: false,



                  maxBarThickness: 42



                }



              ]



            },



            options: {



              ...commonOptions,



              scales: {



                x: categoryAxis(),



                y: percentAxis()



              },



              plugins: {



                ...commonOptions.plugins,



                tooltip: {



                  ...commonOptions.plugins.tooltip,



                  callbacks: {



                    label: context => context.parsed.y === null ?



                      ` ${context.dataset.label}: No data` :



                      ` ${context.dataset.label}: ${context.parsed.y}%`



                  }



                }



              }



            }



          }, validComparison,



          'Quiz and onsite results will appear when assessment scores are available.'



        );



        // 4. Assessment Results Summary — counts, not percentages



        const passedCount = Number(@json($assessmentPassed ?? 0));



        const failedCount = Number(@json($assessmentFailed ?? 0));



        const validAssessment = Number.isFinite(passedCount) &&



          Number.isFinite(failedCount) &&



          (passedCount > 0 || failedCount > 0);



        makeChart('assessmentSummaryChart', 'assessmentSummaryEmpty', {



            type: 'bar',



            data: {



              labels: ['Passed', 'Failed'],



              datasets: [{



                label: 'Assessment attempts',



                data: [passedCount, failedCount],



                backgroundColor: [colors.green, colors.red],



                borderRadius: 6,



                borderSkipped: false,



                maxBarThickness: 78



              }]



            },



            options: {



              ...commonOptions,



              scales: {



                x: categoryAxis(),



                y: {



                  beginAtZero: true,



                  min: 0,



                  suggestedMax: Math.max(4, Math.ceil(Math.max(passedCount,



                    failedCount) * 1.2)),



                  grid: {



                    color: colors.grid



                  },



                  border: {



                    display: false



                  },



                  ticks: {



                    precision: 0,



                    stepSize: 1,



                    color: colors.muted,



                    font: {



                      size: 11



                    }



                  }



                }



              },



              plugins: {



                ...commonOptions.plugins,



                legend: {



                  display: false



                },



                tooltip: {



                  ...commonOptions.plugins.tooltip,



                  callbacks: {



                    label: context =>



                      ` Assessment attempts: ${context.parsed.y}`



                  }



                }



              }



            }



          }, validAssessment,



          'Assessment results will appear after your assessment records are available.'



        );



      }



      if (document.readyState === 'loading') {



        document.addEventListener('DOMContentLoaded', initStudentCharts);



      } else {



        initStudentCharts();



      }



    })();



  </script>



  
<script>
document.addEventListener('DOMContentLoaded', function () {
    const iframe = document.getElementById('trainingFacilityGoogleMap');
    const fallback = document.getElementById('trainingFacilityMapFallback');

    if (!iframe) return;

    const rawUrl = (iframe.dataset.mapUrl || '').trim();
    const facilityAddress = (iframe.dataset.facilityAddress || '').trim();

    function showFallback(message) {
        iframe.hidden = true;
        iframe.removeAttribute('src');

        if (fallback) {
            fallback.hidden = false;
            const span = fallback.querySelector('span');

            if (span && message) {
                span.textContent = message;
            }
        }
    }

    function makeAddressEmbed() {
        if (!facilityAddress) return null;

        return 'https://www.google.com/maps?q='
            + encodeURIComponent(facilityAddress)
            + '&output=embed';
    }

    try {
        if (!rawUrl) {
            showFallback('No Google Maps link has been provided.');
            return;
        }

        const url = new URL(rawUrl);
        const host = url.hostname.toLowerCase();

        const allowedHosts = [
            'google.com',
            'www.google.com',
            'maps.google.com',
            'maps.app.goo.gl',
            'goo.gl'
        ];

        if (
            !['https:', 'http:'].includes(url.protocol) ||
            !allowedHosts.includes(host)
        ) {
            showFallback(
                'Please open this location in Google Maps using the button below.'
            );
            return;
        }

        let embedUrl = null;

        const isStandardGoogleHost = [
            'google.com',
            'www.google.com',
            'maps.google.com'
        ].includes(host);

        // Use an existing Google Maps embed URL.
        if (
            isStandardGoogleHost &&
            /^\/maps\/embed(?:\/|$)/i.test(url.pathname)
        ) {
            embedUrl = url.toString();
        }

        // Convert a regular Google Maps URL into an embeddable URL.
        else if (
            isStandardGoogleHost &&
            /^\/maps(?:\/|$)/i.test(url.pathname)
        ) {
            const place =
                url.searchParams.get('q') ||
                url.searchParams.get('query');

            if (place) {
                embedUrl = 'https://www.google.com/maps?q='
                    + encodeURIComponent(place)
                    + '&output=embed';
            } else {
                const match = url.pathname.match(
                    /\/maps\/place\/([^/]+)/i
                );

                if (match) {
                    embedUrl = 'https://www.google.com/maps?q='
                        + encodeURIComponent(
                            decodeURIComponent(match[1])
                        )
                        + '&output=embed';
                }
            }
        }

        // Shortened Google Maps links cannot be resolved to their
        // exact destination using this client-side script alone.
        // Use the facility address for the embedded map instead.
        if (
            !embedUrl &&
            ['maps.app.goo.gl', 'goo.gl'].includes(host)
        ) {
            embedUrl = makeAddressEmbed();
        }

        // Fallback for other supported Google Maps links.
        if (!embedUrl) {
            embedUrl = makeAddressEmbed();
        }

        if (!embedUrl) {
            showFallback(
                'Unable to display the map. Please open Google Maps using the button below.'
            );
            return;
        }

        iframe.src = embedUrl;
        iframe.hidden = false;

        if (fallback) {
            fallback.hidden = true;
        }

    } catch (error) {
        const addressEmbed = makeAddressEmbed();

        if (addressEmbed) {
            iframe.src = addressEmbed;
            iframe.hidden = false;

            if (fallback) {
                fallback.hidden = true;
            }
        } else {
            showFallback(
                'Unable to display the map. Please open it in Google Maps.'
            );
        }

        console.error('Training facility map error:', error);
    }
});
</script>


@endsection


