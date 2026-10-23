  <!DOCTYPE html>
@php
    // Defensive defaults only; the controller should pass real dashboard data.
    $emptyPaginator = static fn () => new \Illuminate\Pagination\LengthAwarePaginator(
        collect(), 0, 10, 1, ['path' => request()->url(), 'query' => request()->query()]
    );
    $announcements = $announcements ?? $emptyPaginator();
    $courses = $courses ?? $emptyPaginator();
    $registrations = $registrations ?? $emptyPaginator();
    $trainersList = $trainersList ?? collect();
    $facilities = $facilities ?? collect();
    $registrationCourses = $registrationCourses ?? collect();
@endphp

  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin Dashboard - System Overview (Read Only)</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('stylesheet/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('stylesheet/certificates.css') }}">
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Scripts -->
    <script
      src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js">
    </script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js">
    </script>


    <style>
      /* Registrations view refresh — scoped to this view only */
      #view-registrations { color: #183329; }
      #view-registrations .registrations-heading { display:flex; align-items:flex-end; justify-content:space-between; gap:18px; flex-wrap:wrap; margin: 0 0 20px; }
      #view-registrations .registrations-heading h2 { margin:0 0 5px; color:#075b35; font-size:27px; font-weight:750; letter-spacing:-.5px; }
      #view-registrations .registrations-heading p { margin:0; color:#718078; font-size:13px; }
      #view-registrations .registrations-total { display:inline-flex; align-items:center; gap:8px; background:#eaf5ee; color:#075b35; border:1px solid #d6e9dc; border-radius:999px; padding:8px 12px; font-size:12px; font-weight:700; white-space:nowrap; }
      #view-registrations .registrations-card { background:#fff; border:1px solid #dce9df; border-radius:16px; overflow:hidden; box-shadow:0 5px 18px rgba(4,63,38,.045); }
      #view-registrations .registrations-toolbar { display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap; padding:18px 20px; border-bottom:1px solid #e8eee9; }
      #view-registrations .registrations-toolbar h3 { margin:0; font-size:16px; font-weight:750; color:#075b35; }
      #view-registrations .registrations-toolbar .subtext { display:block; margin-top:4px; color:#829087; font-size:12px; font-weight:400; }
      #view-registrations .registrations-tools { display:flex; align-items:center; gap:9px; flex-wrap:wrap; }
      #view-registrations .registrations-search { position:relative; min-width:250px; }
      #view-registrations .registrations-search i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#8a9990; font-size:13px; }
      #view-registrations .registrations-search input { width:100%; box-sizing:border-box; height:40px; padding:0 12px 0 35px; border:1px solid #dce7df; border-radius:9px; background:#fbfdfb; outline:none; font-size:13px; color:#183329; transition:border-color .18s,box-shadow .18s; }
      #view-registrations .registrations-search input:focus { border-color:#5c9c76; box-shadow:0 0 0 3px rgba(7,91,53,.09); }
      #view-registrations .registrations-export { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:40px; padding:0 15px; border-radius:9px; background:#075b35; color:#fff; font-size:12px; font-weight:700; text-decoration:none; white-space:nowrap; transition:background .18s,transform .18s; }
      #view-registrations .registrations-export:hover { background:#043f26; transform:translateY(-1px); }
      #view-registrations .registrations-list { padding:0; }
      #view-registrations .registration-row { display:flex; align-items:center; gap:14px; padding:16px 20px; border-bottom:1px solid #edf1ee; transition:background .16s; }
      #view-registrations .registration-row:last-child { border-bottom:0; }
      #view-registrations .registration-row:hover { background:#fbfdfb; }
      #view-registrations .registration-avatar { flex:0 0 42px; width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#eaf5ee; color:#075b35; font-size:17px; }
      #view-registrations .registration-info { flex:1; min-width:0; }
      #view-registrations .registration-name { display:block; color:#183329; font-size:14px; line-height:1.35; font-weight:750; overflow-wrap:anywhere; }
      #view-registrations .registration-meta { display:flex; flex-wrap:wrap; align-items:center; gap:5px 9px; margin-top:6px; color:#718078; font-size:12px; line-height:1.5; }
      #view-registrations .registration-meta span { display:inline-flex; align-items:center; gap:5px; }
      #view-registrations .registration-meta i { color:#7c9a86; font-size:11px; }
      #view-registrations .registration-course { color:#276749; background:#f0f7f2; border:1px solid #e1eee5; border-radius:5px; padding:1px 6px; }
      #view-registrations .registration-actions { display:flex; align-items:center; gap:7px; flex-shrink:0; }
      #view-registrations .registration-action { display:inline-flex; align-items:center; justify-content:center; gap:7px; min-width:74px; height:35px; padding:0 11px; border:1px solid #075b35; border-radius:8px; background:#075b35; color:#fff; font-size:12px; font-weight:700; text-decoration:none; transition:background .16s,border-color .16s; }
      #view-registrations .registration-action:hover { background:#043f26; border-color:#043f26; }
      #view-registrations .registration-action.pdf { background:#fff; color:#075b35; border-color:#d4e5d9; }
      #view-registrations .registration-action.pdf:hover { background:#eaf5ee; border-color:#a9cdb5; }
      #view-registrations .registrations-empty { padding:48px 20px; text-align:center; color:#718078; }
      #view-registrations .registrations-empty i { display:block; margin-bottom:12px; font-size:30px; color:#a5b9aa; }
      #view-registrations .registrations-empty strong { display:block; margin-bottom:5px; color:#345744; font-size:14px; }
      #view-registrations .registrations-empty span { font-size:12px; }
      #view-registrations .registrations-pagination { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; padding:14px 20px; border-top:1px solid #e8eee9; background:#fcfdfc; }
      #view-registrations .registrations-pagination-note { color:#718078; font-size:12px; }
      #view-registrations .registrations-pagination .pagination-container { margin:0; }
      #view-registrations .registration-filter-empty { display:none; padding:28px 16px; text-align:center; color:#718078; font-size:13px; }
      @media(max-width:700px) {
        #view-registrations .registrations-heading h2 { font-size:23px; }
        #view-registrations .registrations-toolbar { align-items:stretch; padding:15px; }
        #view-registrations .registrations-tools { width:100%; }
        #view-registrations .registrations-search { min-width:0; flex:1; }
        #view-registrations .registrations-row, #view-registrations .registration-row { align-items:flex-start; padding:14px 13px; gap:10px; }
        #view-registrations .registration-avatar { flex-basis:36px; width:36px; height:36px; border-radius:10px; font-size:15px; }
        #view-registrations .registration-row { flex-wrap:wrap; }
        #view-registrations .registration-info { flex-basis:calc(100% - 50px); }
        #view-registrations .registration-actions { margin-left:46px; }
        #view-registrations .registration-action { min-width:68px; height:32px; }
        #view-registrations .registrations-pagination { padding:12px 14px; }
      }

      /* Courses page: 3 cards per row */
  #view-courses #coursesGrid {
      display: grid !important;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 18px;
      align-items: stretch;
      width: 100%;
      box-sizing: border-box;
  }

  #view-courses #coursesGrid > * {
      min-width: 0;
      box-sizing: border-box;
  }

  /* Two cards per row on tablets */
  @media (max-width: 1100px) {
      #view-courses #coursesGrid {
          grid-template-columns: repeat(2, minmax(0, 1fr));
      }
  }

  /* One card per row on mobile */
  @media (max-width: 650px) {
      #view-courses #coursesGrid {
          grid-template-columns: minmax(0, 1fr);
          gap: 14px;
      }
  }

      /* Executive overview refresh: LEDIPO green/yellow, scoped to overview only. */
      #view-overview .overview-intro {
        display:flex; align-items:flex-end; justify-content:space-between; gap:16px;
        flex-wrap:wrap; margin:0 0 17px; padding:2px 0 0;
      }
      #view-overview .overview-eyebrow {
        display:block; margin-bottom:7px; color:#6b7c73; font-size:10px;
        font-weight:800; letter-spacing:1.1px;
      }
      #view-overview .overview-intro h2 {
        margin:0; color:#075b35; font-size:25px; line-height:1.2;
        font-weight:800; letter-spacing:-.55px;
      }
      #view-overview .overview-intro p {
        margin:7px 0 0; color:#6b7c73; font-size:13px; line-height:1.55;
      }
      #view-overview .overview-live-note {
        display:inline-flex; align-items:center; gap:8px; padding:8px 11px;
        border:1px solid #dce9df; border-radius:8px; background:#fff;
        color:#52665b; font-size:11px; font-weight:700; white-space:nowrap;
      }
      #view-overview .overview-live-dot {
        width:7px; height:7px; border-radius:50%; background:#075b35;
        box-shadow:0 0 0 3px #eaf5ee;
      }
      #view-overview .overview-kpi-grid {
        display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:13px;
        margin:0 0 24px;
      }
      #view-overview .overview-kpi-card {
        min-width:0; padding:16px 17px 15px; background:#fff;
        border:1px solid #dce7df; border-radius:12px; position:relative;
        overflow:hidden; box-shadow:0 2px 8px rgba(4,63,38,.035);
      }
      #view-overview .overview-kpi-card::before {
        content:""; position:absolute; top:0; left:0; right:0; height:3px; background:#e1dc36;
      }
      #view-overview .overview-kpi-top {
        display:flex; justify-content:space-between; align-items:center; gap:8px;
        color:#52665b; font-size:11px; font-weight:750; line-height:1.4;
      }
      #view-overview .overview-kpi-mark {
        flex:0 0 30px; width:30px; height:30px; display:flex; align-items:center;
        justify-content:center; border-radius:8px; background:#eaf5ee; color:#075b35;
      }
      #view-overview .overview-kpi-card > strong {
        display:block; margin-top:12px; color:#075b35; font-size:27px;
        line-height:1.15; font-weight:850; letter-spacing:-.5px; overflow-wrap:anywhere;
      }
      #view-overview .overview-kpi-card > small {
        display:block; margin-top:7px; color:#849188; font-size:10px; line-height:1.4;
      }
      #view-overview .overview-chart-toolbar {
        display:flex; flex-direction:column; gap:13px; margin:0 0 15px;
      }
      #view-overview .overview-chart-toolbar h3 {
        margin:0; color:#075b35; font-size:17px; font-weight:800; letter-spacing:-.2px;
      }
      #view-overview .overview-chart-toolbar p {
        margin:5px 0 0; color:#718078; font-size:12px; line-height:1.5;
      }
      #view-overview .overview-filter-group {
        display:flex; flex-wrap:wrap; align-items:center; gap:7px;
      }
      #view-overview .overview-filter-btn {
        display:inline-flex; align-items:center; gap:9px; min-height:35px;
        padding:0 11px; border:1px solid #dce7df; border-radius:8px;
        background:#fff; color:#52665b; font:inherit; font-size:11px; font-weight:700; cursor:pointer;
        transition:background .16s,border-color .16s,color .16s;
      }
      #view-overview .overview-filter-btn span {
        display:inline-flex; align-items:center; justify-content:center;
        min-width:19px; height:19px; padding:0 4px; border-radius:5px;
        background:#f0f5f1; color:#52665b; font-size:10px;
      }
      #view-overview .overview-filter-btn:hover { border-color:#9dbda8; color:#075b35; }
      #view-overview .overview-filter-btn.is-active {
        border-color:#075b35; background:#075b35; color:#fff;
      }
      #view-overview .overview-filter-btn.is-active span { background:#e1dc36; color:#183329; }
      #view-overview .chart-card[hidden] { display:none !important; }
      @media(max-width:1150px) {
        #view-overview .overview-kpi-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
      }
      @media(max-width:680px) {
        #view-overview .overview-intro h2 { font-size:22px; }
        #view-overview .overview-kpi-grid { gap:9px; }
        #view-overview .overview-kpi-card { padding:14px 12px; }
        #view-overview .overview-kpi-card > strong { font-size:23px; }
        #view-overview .overview-kpi-top { align-items:flex-start; font-size:10px; }
        #view-overview .overview-kpi-mark { width:26px; height:26px; flex-basis:26px; }
        #view-overview .overview-filter-btn { flex:1 1 auto; justify-content:center; }
      }

      /* ==========================================================
        SYSTEM OVERVIEW — MINIMALIST LEDIPO GREEN & YELLOW THEME
        Scoped overrides: keeps existing charts, data and behavior.
        ========================================================== */
      #view-overview {
        width: 100%;
        min-width: 0;
        color: #183329;
      }

      #view-overview .overview-dashboard-layout {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) 300px !important;
        gap: 20px !important;
        align-items: start !important;
        width: 100% !important;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
      }

      #view-overview .charts-section {
        min-width: 0 !important;
        width: 100%;
      }

      #view-overview .charts-section > div {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 18px !important;
        width: 100%;
        min-width: 0;
        align-items: stretch;
        box-sizing: border-box;
      }

      #view-overview .chart-card {
        position: relative;
        min-width: 0 !important;
        width: 100%;
        box-sizing: border-box;
        padding: 18px !important;
        overflow: hidden;
        background: #ffffff !important;
        border: 1px solid #dce7df !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 8px rgba(4, 63, 38, 0.035) !important;
        transition: border-color .18s ease, box-shadow .18s ease;
      }

      #view-overview .chart-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 18px;
        width: 38px;
        height: 3px;
        background: #e1dc36;
        border-radius: 0 0 3px 3px;
      }

      #view-overview .chart-card:hover {
        border-color: #b8d2c0 !important;
        box-shadow: 0 4px 12px rgba(4, 63, 38, 0.06) !important;
      }

      #view-overview .chart-card { cursor: zoom-in; }
      #view-overview .chart-card:focus-visible { outline: 3px solid rgba(7,91,53,.25); outline-offset: 3px; }
      #view-overview .chart-card::after {
        content: "Click to expand";
        position: absolute; right: 14px; top: 12px;
        color: #718078; font-size: 10px; font-weight: 700;
        background: #f6faf7; border: 1px solid #e2ebe5;
        border-radius: 5px; padding: 4px 7px; opacity: 0;
        transition: opacity .18s ease; pointer-events: none;
      }
      #view-overview .chart-card:hover::after,
      #view-overview .chart-card:focus-visible::after { opacity: 1; }
      #expandedChartModal .expanded-kpi {
        min-width: 0; padding: 15px 14px; text-align: left;
        background: #f8faf8; border: 1px solid #dce7df;
        border-radius: 10px; border-top: 3px solid #e1dc36;
      }
      #expandedChartModal .expanded-kpi span {
        display:block; color:#111111; font-size:10px; line-height:1.4;
        font-weight:750; text-transform:uppercase; letter-spacing:.45px;
      }
      #expandedChartModal .expanded-kpi strong {
        display:block; margin-top:8px; color:#075b35; font-size:21px;
        line-height:1.25; font-weight:800; overflow-wrap:anywhere;
      }
      #expandedChartModal #expandedModalTitle { color:#075b35 !important; }
      #expandedChartModal #expandedCanvas { max-width:100%; }
      @media (max-width:680px) {
        #expandedChartModal .expanded-kpi { padding:12px; }
        #expandedChartModal .expanded-kpi strong { font-size:18px; }
      }

      #view-overview .chart-card h3 {
        margin: 0 0 5px !important;
        color: #075b35 !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        line-height: 1.4;
        letter-spacing: -0.15px;
      }

      #view-overview .chart-card p {
        margin: 0 0 12px !important;
        color: #718078 !important;
        font-size: 12px !important;
        line-height: 1.5 !important;
      }

      #view-overview .chart-card > div[style*="position:relative"] {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100%;
      }

      #view-overview .chart-card canvas {
        display: block;
        max-width: 100%;
      }

      #view-overview .updates-card {
        width: 100% !important;
        min-width: 0;
        box-sizing: border-box;
        border: 1px solid #dce7df !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 8px rgba(4, 63, 38, 0.035) !important;
        padding: 18px !important;
      }

      #view-overview .updates-card > h3 {
        color: #075b35 !important;
        padding-bottom: 12px;
        border-bottom: 1px solid #e8eee9;
      }

      #expandedChartModal {
        box-sizing: border-box;
        backdrop-filter: blur(2px);
      }

      #expandedChartModal > div {
        border: 1px solid #dce7df;
        border-radius: 14px !important;
      }

      #expandedChartModal #expandedModalTitle {
        color: #075b35 !important;
      }

      #expandedChartModal #modalKpiRow > div {
        background: #f8faf8 !important;
        border-color: #dce7df !important;
        border-radius: 9px !important;
      }

      #expandedChartModal #modalKpiRow strong {
        color: #075b35 !important;
      }

      @media (max-width: 1250px) {
        #view-overview .overview-dashboard-layout {
          grid-template-columns: minmax(0, 1fr) 270px !important;
          gap: 16px !important;
        }

        #view-overview .charts-section > div {
          gap: 14px !important;
        }

        #view-overview .chart-card {
          padding: 15px !important;
        }
      }

      @media (max-width: 1000px) {
        #view-overview .overview-dashboard-layout {
          grid-template-columns: minmax(0, 1fr) !important;
        }

        #view-overview .updates-card {
          margin-top: 2px;
        }
      }

      @media (max-width: 680px) {
        #view-overview .charts-section > div {
          grid-template-columns: minmax(0, 1fr) !important;
          gap: 14px !important;
        }

        #view-overview .chart-card {
          padding: 16px !important;
          border-radius: 10px !important;
        }

        #view-overview .chart-card > div[style*="position:relative"] {
          height: 250px !important;
        }

        #expandedChartModal {
          padding: 10px !important;
        }

        #expandedChartModal > div {
          padding: 16px !important;
          max-height: 96vh !important;
        }

        #expandedChartModal #expandedCanvas {
          max-height: 55vh;
        }
      }

      @page {
    size: A4 landscape;
    margin: 6mm;
}

@media print {
    html,
    body {
        width: 285mm !important;
        height: 198mm !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
        background: #fff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body * {
        visibility: hidden !important;
    }

    #expandedChartModal,
    #expandedChartModal * {
        visibility: visible !important;
    }

    #expandedChartModal {
        display: block !important;
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 285mm !important;
        height: 198mm !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
        background: #fff !important;
        z-index: 999999 !important;
    }

    #expandedChartModal > div {
        position: absolute !important;
        inset: 0 !important;
        width: 100% !important;
        max-width: none !important;
        height: 100% !important;
        max-height: none !important;
        margin: 0 !important;
        padding: 5mm !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
        border: 1px solid #dce7df !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: #fff !important;
    }

    #expandedChartModal button {
        display: none !important;
    }

    #expandedChartModal #expandedModalTitle {
        margin: 0 0 3mm !important;
        font-size: 16pt !important;
        color: #075b35 !important;
    }

    #expandedChartModal #modalKpiRow {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 3mm !important;
        margin: 0 0 3mm !important;
        break-inside: avoid !important;
    }

    #expandedChartModal #modalKpiRow > div {
        padding: 2mm 3mm !important;
        min-width: 0 !important;
        box-sizing: border-box !important;
        border: 1px solid #dce7df !important;
        border-top: 2px solid #e1dc36 !important;
        background: #f8faf8 !important;
        break-inside: avoid !important;
    }

    #expandedChartModal #modalKpiRow strong {
        font-size: 13pt !important;
        color: #075b35 !important;
    }

    #expandedChartModal #modalKpiRow span {
        font-size: 8pt !important;
        color: #111 !important;
    }

    #expandedChartModal > div > div:has(> #expandedCanvas) {
        height: 105mm !important;
        min-height: 0 !important;
        max-height: 105mm !important;
        width: 100% !important;
        margin: 0 0 2mm !important;
        overflow: hidden !important;
        break-inside: avoid !important;
    }

    #expandedChartModal #expandedCanvas {
        max-width: 100% !important;
        max-height: 105mm !important;
    }

    #expandedChartModal #expandedChartInsightPanel {
        margin: 2mm 0 0 !important;
        padding: 2mm 3mm !important;
        break-inside: avoid !important;
    }

    #expandedChartModal #expandedChartInsight {
        font-size: 8pt !important;
        line-height: 1.25 !important;
    }
}

/* Course filter — match the Registrations toolbar */
#view-registrations .registration-course-filter {
    height: 40px;
    min-width: 164px;
    max-width: 220px;
    box-sizing: border-box;
    padding: 0 32px 0 12px;

    border: 1px solid #dce7df;
    border-radius: 9px;
    background-color: #fbfdfb;
    color: #183329;

    font-family: inherit;
    font-size: 13px;
    font-weight: 500;

    cursor: pointer;
    outline: none;
    transition: border-color 0.18s ease,
                box-shadow 0.18s ease;
}

#view-registrations .registration-course-filter:hover {
    border-color: #9dbda8;
}

#view-registrations .registration-course-filter:focus {
    border-color: #5c9c76;
    box-shadow: 0 0 0 3px rgba(7, 91, 53, 0.09);
}

@media (max-width: 700px) {
    #view-registrations .registration-course-filter {
        flex: 1 1 140px;
        min-width: 0;
        max-width: none;
    }
}



    
      /* Super Admin uses the Admin dashboard design with a read-only presentation. */
      .superadmin-readonly-badge { display:inline-flex; align-items:center; gap:7px; padding:7px 10px; border:1px solid #d6e9dc; border-radius:8px; background:#eaf5ee; color:#075b35; font-size:10px; font-weight:800; letter-spacing:.5px; white-space:nowrap; }
      body.superadmin-readonly .mutation-control { display:none !important; }
      body.superadmin-readonly .readonly-field { background:#f6f8f6 !important; color:#65736a !important; cursor:not-allowed !important; }
      body.superadmin-readonly .readonly-notice { display:flex; align-items:center; gap:9px; margin:0 0 18px; padding:11px 14px; border:1px solid #d6e9dc; border-left:4px solid #e1dc36; border-radius:9px; background:#f7fbf8; color:#075b35; font-size:12px; font-weight:650; }
      @media(max-width:600px) { .superadmin-readonly-badge { font-size:9px; padding:6px 7px; } }

/* SUPER ADMIN SETTINGS */
.settings-page {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.settings-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  margin-bottom: 2px;
}

.settings-header h2 {
  margin: 0 0 6px;
  color: #075b35;
  font-size: 24px;
  font-weight: 750;
}

.settings-header p,
.settings-section-heading p {
  margin: 0;
  color: #6b7280;
  font-size: 13px;
  line-height: 1.5;
}

.settings-role {
  display: inline-flex;
  align-items: center;
  padding: 7px 11px;
  border-radius: 6px;
  background: #f4f3cf;
  color: #075b35;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .6px;
}

.settings-card {
  padding: 24px;
  border: 1px solid #e6ebe7;
  border-radius: 12px;
  background: #fff;
  box-shadow: 0 3px 12px rgba(0, 40, 20, .035);
}

.settings-section-heading {
  display: flex;
  align-items: center;
  gap: 13px;
  margin-bottom: 12px;
}

.settings-section-heading h3 {
  margin: 0 0 5px;
  color: #075b35;
  font-size: 16px;
  font-weight: 750;
}

.settings-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 0 0 42px;
  width: 42px;
  height: 42px;
  border-radius: 9px;
  background: #edf5ef;
  color: #075b35;
  font-size: 17px;
}

.settings-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  padding: 17px 0;
}

.settings-info {
  min-width: 0;
}

.settings-info strong {
  display: block;
  margin-bottom: 5px;
  color: #202923;
  font-size: 13px;
  font-weight: 700;
}

.settings-info p {
  margin: 0;
  color: #737b75;
  font-size: 12px;
  line-height: 1.6;
}

.settings-input {
  width: min(100%, 340px);
  min-width: 180px;
  padding: 10px 12px;
  border: 1px solid #dfe5e0;
  border-radius: 7px;
  outline: none;
  background: #f9fbf9;
  color: #374139;
  font-size: 12px;
}

.settings-input[readonly] {
  cursor: default;
}

.settings-value {
  flex-shrink: 0;
  padding: 7px 12px;
  border: 1px solid #dce9df;
  border-radius: 7px;
  background: #f2f8f3;
  color: #075b35;
  font-size: 12px;
  font-weight: 750;
}

.settings-divider {
  height: 1px;
  margin: 0;
  border: 0;
  background: #edf0ed;
}

.settings-card .btn-view,
.settings-card .btn-all {
  flex-shrink: 0;
  border-radius: 7px;
  font-size: 12px;
  font-weight: 650;
  cursor: pointer;
}

.settings-card .btn-view {
  padding: 10px 14px;
  border: 1px solid #075b35;
  background: #fff;
  color: #075b35;
}

.settings-card .btn-view:hover {
  background: #edf5ef;
}

.settings-card .btn-all {
  border: 1px solid #075b35;
  background: #075b35;
  color: #fff;
}

.settings-card .btn-all:hover {
  background: #064729;
}

.settings-note {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  margin: 14px 0 0;
  padding: 12px;
  border-radius: 7px;
  background: #f8f9f1;
  color: #60685f;
  font-size: 11px;
  line-height: 1.6;
}

.settings-note i {
  margin-top: 2px;
  color: #075b35;
}

.settings-info-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
  margin-top: 18px;
}

.settings-info-item {
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding: 15px;
  border: 1px solid #e7ece8;
  border-radius: 8px;
  background: #fbfcfb;
}

.settings-info-item span {
  color: #737b75;
  font-size: 11px;
}

.settings-info-item strong {
  color: #26352b;
  font-size: 13px;
  overflow-wrap: anywhere;
}

@media (max-width: 700px) {
  .settings-card {
    padding: 17px;
  }

  .settings-row {
    align-items: flex-start;
    flex-direction: column;
    gap: 12px;
  }

  .settings-input {
    width: 100%;
    min-width: 0;
  }

  .settings-info-grid {
    grid-template-columns: 1fr;
  }

  .settings-card .btn-view,
  .settings-card .btn-all {
    max-width: 100%;
  }
}

</style>

  </head>

  <body class="superadmin-readonly">

    <!-- ========================================================== -->
    <!-- TOPBAR                                                     -->
    <!-- ========================================================== -->
    <nav class="topbar">
      <div class="topbar-left">
        <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
          <span></span>
          <span></span>
          <span></span>
        </button>
        <a href="{{ url()->current() }}" class="topbar-brand">
          <img src="{{ asset('images/logo.png') }}" alt="logo"
            class="topbar-logo">
          <span>LEDIPO</span>
        </a>
      </div>

      <div class="topbar-right">
        <button class="avatar-btn" id="avatarBtn"
          aria-label="Open profile menu">SA</button>

        <div class="dropdown" id="dropdown">
          <div class="dropdown-header">
            <div class="dh-name">Super Administrator</div>
            <div class="dh-role">View-Only Access</div>
          </div>

          <div class="dd-divider"></div>
          <a href="#" class="dd-item dd-logout"
            onclick="event.preventDefault(); openLogoutModal();">
            <i class="fa fa-right-from-bracket dd-icon"></i>
            Log out
          </a>
          <form id="logout-form" action="{{ route('Logout') }}" method="POST"
            style="display:none;">
            @csrf
          </form>
        </div>
      </div>
    </nav>

    <!-- Custom Alert Modal (replaces native alert()) -->
    <div id="mcAlertModal" class="modal" style="display:none;">
      <div class="modal-content" style="max-width:380px;">
        <div class="modal-header">
          <h3><i class="fa-solid fa-circle-info"></i> Notice</h3>
          <span class="close-modal" onclick="closeAlertModal()">&times;</span>
        </div>
        <div style="padding:24px; text-align:center;">
          <p id="mcAlertMessage" style="font-size:14px; color:#333; margin:0;">
          </p>
        </div>
        <div class="modal-actions-centered"
          style="text-align:center; padding-bottom:16px;">
          <button onclick="closeAlertModal()"
            style="background:#025628;color:#fff;border:none;border-radius:8px;padding:9px 24px;font-size:13px;font-weight:700;cursor:pointer;">
            OK
          </button>
        </div>
      </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer"
      style="position:fixed; top:20px; right:20px; z-index:5000; display:flex; flex-direction:column; gap:10px; max-width:360px;">
    </div>

    <!-- Custom Confirm Modal (replaces native confirm()) -->
    <div id="mcConfirmModal" class="modal" style="display:none;">
      <div class="modal-content" style="max-width:380px;">
        <div class="modal-header">
          <h3><i class="fa-solid fa-triangle-exclamation"></i> Confirm</h3>
          <span class="close-modal" onclick="closeConfirmModal()">&times;</span>
        </div>
        <div style="padding:24px; text-align:center;">
          <p id="mcConfirmMessage" style="font-size:14px; color:#333; margin:0;">
          </p>
        </div>
        <div class="modal-actions-centered"
          style="text-align:center; padding-bottom:16px; display:flex; justify-content:center; gap:10px;">
          <button onclick="closeConfirmModal()"
            style="background:#fff;color:#777;border:1px solid #ddd;border-radius:8px;padding:9px 20px;font-size:13px;font-weight:700;cursor:pointer;">
            Cancel
          </button>
          <button onclick="_confirmYes()"
            style="background:#A32D2D;color:#fff;border:none;border-radius:8px;padding:9px 20px;font-size:13px;font-weight:700;cursor:pointer;">
            Yes, Remove
          </button>
        </div>
      </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="modal" style="display:none;">
      <div class="modal-content">
        <p>Are you sure you want to log out?</p>
        <div class="modal-actions-centered">
          <button onclick="confirmLogout()" class="btn-modal-yes">Yes</button>
          <button onclick="closeLogoutModal()"
            class="btn-modal-no">Cancel</button>
        </div>
      </div>
    </div>

    <!-- ========================================================== -->
    <!-- APP BODY & SIDEBAR                                         -->
    <!-- ========================================================== -->
    <div class="app-body">
      <div class="sidebar-overlay" id="overlay"></div>

      <aside class="sidebar" id="sidebar">
        <div class="sidebar-section-label">Menu</div>

        <a href="?view=overview"
          class="nav-item <?= !isset($_GET['view']) || $_GET['view'] === 'overview' ? 'active' : '' ?>"
          id="nav-overview"
          onclick="showView('overview'); setActive(this); return false;">
          <i class="fa fa-gauge-high nav-icon"></i>
          <span>Overview</span>
        </a>

        <div class="sidebar-section-label">Manage</div>

        <a href="?view=all-trainees"
          class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'all-trainees' ? 'active' : '' ?>"
          id="nav-trainees"
          onclick="showView('all-trainees'); setActive(this); return false;">
          <i class="fa fa-user-graduate nav-icon"></i>
          <span>Trainees</span>
        </a>

        <a href="?view=all-trainers"
          class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'all-trainers' ? 'active' : '' ?>"
          id="nav-trainers"
          onclick="showView('all-trainers'); setActive(this); return false;">
          <i class="fa fa-chalkboard-user nav-icon"></i>
          <span>Trainers</span>
        </a>

        <a href="?view=registrations"
          class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'registrations' ? 'active' : '' ?>"
          id="nav-registrations"
          onclick="showView('registrations'); setActive(this); return false;">
          <i class="fa fa-clipboard-list nav-icon"></i>
          <span>Registrations</span>
        </a>

        <a href="?view=courses"
          class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'courses' ? 'active' : '' ?>"
          id="nav-courses"
          onclick="showView('courses'); setActive(this); return false;">
          <i class="fa fa-book nav-icon"></i>
          <span>Courses</span>
        </a>

        <a href="?view=facilities"
          class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'facilities' ? 'active' : '' ?>"
          id="nav-facilities"
          onclick="showView('facilities'); setActive(this); return false;">
          <i class="fa fa-building nav-icon"></i>
          <span>Facilities</span>
        </a>

        <div class="sidebar-section-label">System</div>

        <a href="?view=announcements"
          class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'announcements' ? 'active' : '' ?>"
          id="nav-announcements"
          onclick="showView('announcements'); setActive(this); return false;">
          <i class="fa fa-bell nav-icon"></i>
          <span>Announcements</span>
        </a>

        <a href="?view=settings"
          class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'settings' ? 'active' : '' ?>"
          id="nav-settings"
          onclick="showView('settings'); setActive(this); return false;">
          <i class="fa fa-gear nav-icon"></i>
          <span>Settings</span>
        </a>

        <a href="?view=certificate"
          class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'certificate' ? 'active' : '' ?>"
          id="nav-certificate"
          onclick="showView('certificate'); setActive(this); return false;">
          <i class="fa fa-award nav-icon"></i>
          <span>Certificate</span>
        </a>
      </aside>

      <!-- ========================================================== -->
      <!-- MAIN CONTENT AREA                                          -->
      <!-- ========================================================== -->
      <main class="admin-main">
        <div class="readonly-notice"><i class="fa-solid fa-shield-halved"></i><span>Super Admin read-only mode — review system records, analytics, and reports using the same dashboard design as Admin. Create, edit, delete, approve, release, and other record-changing actions are unavailable.</span></div>
        <nav class="breadcrumb">
          <a href="#"
            onclick="showView('overview'); return false;">Home</a> /
          <span id="breadcrumb-current">System Overview</span>
        </nav>
        <h1 class="page-title" id="main-title">System Overview</h1>

        <!-- 1. OVERVIEW VIEW CONTAINER -->
        <div id="view-overview" style="width: 100%;">

          <!-- Executive summary: values are populated from the same live chart datasets below. -->
          <section class="overview-intro" aria-label="System overview summary">
            <div class="overview-intro-copy">
            </div>
          </section>

          <section class="overview-kpi-grid" aria-label="Overview key performance indicators">
            <article class="overview-kpi-card">
              <div class="overview-kpi-top"><span>Total Applicants</span><span class="overview-kpi-mark"><i class="fa-solid fa-users"></i></span></div>
              <strong id="overviewKpiApplicants">—</strong>
              <small>Registered applicants</small>
            </article>
            <article class="overview-kpi-card">
              <div class="overview-kpi-top"><span>Active Enrollments</span><span class="overview-kpi-mark"><i class="fa-solid fa-user-check"></i></span></div>
              <strong id="overviewKpiEnrollments">—</strong>
              <small>Enrollments represented in course data</small>
            </article>
            <article class="overview-kpi-card">
              <div class="overview-kpi-top"><span>Courses Tracked</span><span class="overview-kpi-mark"><i class="fa-solid fa-book-open"></i></span></div>
              <strong id="overviewKpiCourses">—</strong>
              <small>Courses represented in enrollment data</small>
            </article>
            <article class="overview-kpi-card">
              <div class="overview-kpi-top"><span>Competency Rate</span><span class="overview-kpi-mark"><i class="fa-solid fa-award"></i></span></div>
              <strong id="overviewKpiCompetency">—</strong>
              <small>Competent trainees among eligible records</small>
            </article>
          </section>

          <section class="overview-chart-toolbar" aria-label="Chart filters">
            <div>
              <h3>System analytics</h3>
              <p>Choose a category or select any chart to open its expanded view and KPIs.</p>
            </div>
            <div class="overview-filter-group" role="group" aria-label="Filter charts by category">
              <button type="button" class="overview-filter-btn is-active" data-chart-filter="all" aria-pressed="true">All charts <span>10</span></button>
              <button type="button" class="overview-filter-btn" data-chart-filter="enrollment" aria-pressed="false">Enrollment &amp; Demand <span>2</span></button>
              <button type="button" class="overview-filter-btn" data-chart-filter="training" aria-pressed="false">Training Performance <span>4</span></button>
              <button type="button" class="overview-filter-btn" data-chart-filter="demographics" aria-pressed="false">Applicant Demographics <span>4</span></button>
            </div>
          </section>

          <!-- 2-COLUMN MAIN DASHBOARD GRID -->
          <div class="overview-dashboard-layout"
            style="display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; width: 100%;">

            <!-- DASHBOARD CHARTS: SIX LIVE DATASETS -->
            <div class="charts-section" style="min-width:0;">
              <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;">
                <section class="card chart-card" data-category="enrollment" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;box-shadow:0 4px 14px rgba(4,63,38,.05);">
                  <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:8px;">
                    <div style="min-width:0;">
                      <h3 style="margin:0;color:#075b35;font-size:15px;font-weight:750;">Enrollment by Course</h3>
                      <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;line-height:1.45;">Number of enrolled trainees for each course.</p>
                    </div>
                  </div>
                  <div style="position:relative;height:270px;width:100%;min-width:0;">
                    <canvas id="chartEnrollmentCourse" aria-label="Enrollment by Course" role="img"></canvas>
                  </div>
                </section>
                <section class="card chart-card" data-category="enrollment" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;box-shadow:0 4px 14px rgba(4,63,38,.05);">
                  <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:8px;">
                    <div style="min-width:0;">
                      <h3 style="margin:0;color:#075b35;font-size:15px;font-weight:750;">Monthly Enrollment Trend</h3>
                      <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;line-height:1.45;">Enrollment activity across the last six months.</p>
                    </div>
                  </div>
                  <div style="position:relative;height:270px;width:100%;min-width:0;">
                    <canvas id="chartMonthlyEnrollment" aria-label="Monthly Enrollment Trend" role="img"></canvas>
                  </div>
                </section>
                <section class="card chart-card" data-category="training" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;box-shadow:0 4px 14px rgba(4,63,38,.05);">
                  <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:8px;">
                    <div style="min-width:0;">
                      <h3 style="margin:0;color:#075b35;font-size:15px;font-weight:750;">Quiz Performance by Course</h3>
                      <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;line-height:1.45;">Average quiz score percentage for each course.</p>
                    </div>
                  </div>
                  <div style="position:relative;height:270px;width:100%;min-width:0;">
                    <canvas id="chartQuizPerformance" aria-label="Quiz Performance by Course" role="img"></canvas>
                  </div>
                </section>
                <section class="card chart-card" data-category="training" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;box-shadow:0 4px 14px rgba(4,63,38,.05);">
                  <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:8px;">
                    <div style="min-width:0;">
                      <h3 style="margin:0;color:#075b35;font-size:15px;font-weight:750;">Quiz vs. Onsite Performance</h3>
                      <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;line-height:1.45;">Compare quiz and onsite assessment averages.</p>
                    </div>
                  </div>
                  <div style="position:relative;height:270px;width:100%;min-width:0;">
                    <canvas id="chartQuizVsOnsite" aria-label="Quiz vs. Onsite Performance" role="img"></canvas>
                  </div>
                </section>
                <section class="card chart-card" data-category="training" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;box-shadow:0 4px 14px rgba(4,63,38,.05);">
                  <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:8px;">
                    <div style="min-width:0;">
                      <h3 style="margin:0;color:#075b35;font-size:15px;font-weight:750;">Competency Distribution</h3>
                      <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;line-height:1.45;">Students grouped by competency assessment status.</p>
                    </div>
                  </div>
                  <div style="position:relative;height:270px;width:100%;min-width:0;">
                    <canvas id="chartCompetency" aria-label="Competency Distribution" role="img"></canvas>
                  </div>
                </section>
                <section class="card chart-card" data-category="training" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;box-shadow:0 4px 14px rgba(4,63,38,.05);">
                  <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:8px;">
                    <div style="min-width:0;">
                      <h3 style="margin:0;color:#075b35;font-size:15px;font-weight:750;">Competency Rate by Course</h3>
                      <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;line-height:1.45;">Percentage of eligible trainees who achieved a combined quiz and onsite score of at least 75%.</p>
                    </div>
                  </div>
                  <div style="position:relative;height:270px;width:100%;min-width:0;">
                    <canvas id="chartCompletion" aria-label="Competency Rate by Course" role="img"></canvas>
                  </div>
                </section>
                <section class="card chart-card" data-category="demographics" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;box-shadow:0 4px 14px rgba(4,63,38,.05);">
    <h3 style="margin:0 0 5px;color:#075b35;font-size:15px;">Registrations by Barangay</h3>
    <p style="margin:5px 0 12px;color:#6b7c73;font-size:12px;">Number of applicants from each barangay.</p>
    <div style="position:relative;height:270px;width:100%;"><canvas id="chartBarangay"></canvas></div>
  </section>
  <section class="card chart-card" data-category="demographics" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;box-shadow:0 4px 14px rgba(4,63,38,.05);">
    <h3 style="margin:0 0 5px;color:#075b35;font-size:15px;">Applicant Employment Status</h3>
    <p style="margin:5px 0 12px;color:#6b7c73;font-size:12px;">Employment status of registered applicants.</p>
    <div style="position:relative;height:270px;width:100%;"><canvas id="chartEmployment"></canvas></div>
  </section>
  <section class="card chart-card" data-category="demographics" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;box-shadow:0 4px 14px rgba(4,63,38,.05);">
    <h3 style="margin:0 0 5px;color:#075b35;font-size:15px;">Registrations by Sex</h3>
    <p style="margin:5px 0 12px;color:#6b7c73;font-size:12px;">Distribution of applicants by sex.</p>
    <div style="position:relative;height:270px;width:100%;"><canvas id="chartSex"></canvas></div>
  </section>
  <section class="card chart-card" data-category="demographics" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;box-shadow:0 4px 14px rgba(4,63,38,.05);">
    <h3 style="margin:0 0 5px;color:#075b35;font-size:15px;">Applicant Age Group Distribution</h3>
    <p style="margin:5px 0 12px;color:#6b7c73;font-size:12px;">Number of applicants in each age group.</p>
    <div style="position:relative;height:270px;width:100%;"><canvas id="chartAgeGroups"></canvas></div>
  </section>
              </div>
            </div>

            <!-- RIGHT COLUMN: Updates & Calendar Sidebar (Isolated Container) -->
            <div class="card updates-card"
              style="background: #ffffff; border-radius: 10px; border: 1px solid #dcdcdc; padding: 18px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); width: 100%; box-sizing: border-box;">

              <h3
                style="margin-top: 0; margin-bottom: 14px; font-size: 15px; font-weight: 700; color: #004d26; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-bell"></i> Updates
              </h3>

              <!-- Primary 3 Announcements -->
              <ul class="update-list" id="updateList"
                style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                @forelse($announcements->take(3) as $ann)
                  @php
                    $icon = match ($ann->type) {
                        'urgent' => 'fa-circle-exclamation',
                        'notice' => 'fa-bullhorn',
                        default => 'fa-bell',
                    };
                    $badgeColor = match ($ann->type) {
                        'urgent' => '#A32D2D',
                        'notice' => '#854F0B',
                        default => '#025628',
                    };
                    $bgColor = match ($ann->type) {
                        'urgent' => '#FCEBEB',
                        'notice' => '#FFF8E1',
                        default => '#E8F5E9',
                    };
                  @endphp
                  <li
                    style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; background: #fff; border: 1px solid #f0f0f0; border-radius: 8px;">
                    <div
                      style="width: 32px; height: 32px; border-radius: 50%; background: {{ $bgColor }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                      <i class="fa-solid {{ $icon }}"
                        style="color: {{ $badgeColor }}; font-size: 13px;"></i>
                    </div>

                    <div style="flex: 1; min-width: 0;">
                      <strong
                        style="font-size: 13px; color: #1a1a1a; display: block; margin-bottom: 2px;">{{ $ann->title }}</strong>
                      <small
                        style="color: #666; font-size: 12px; display: block; line-height: 1.4; margin-bottom: 4px;">{{ $ann->message }}</small>
                      <small
                        style="color: #aaa; font-size: 10px; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fa-regular fa-clock"
                          style="font-size: 9px;"></i>
                        {{ $ann->created_at->format('M j, Y h:i A') }}
                      </small>
                    </div>
                  </li>
                @empty
                  <li
                    style="text-align: center; color: #aaa; padding: 20px 0; font-size: 13px;">
                    <i class="fa-solid fa-bell-slash"
                      style="font-size: 20px; display: block; margin-bottom: 6px; color: #ccc;"></i>
                    No recent updates or announcements.
                  </li>
                @endforelse
              </ul>

              <!-- Collapsible Extra Announcements -->
              @if ($announcements->count() > 3)
                <div id="extra-updates"
                  style="display: none; margin-top: 12px;">
                  <ul
                    style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                    @foreach ($announcements->slice(3) as $ann)
                      @php
                        $icon = match ($ann->type) {
                            'urgent' => 'fa-circle-exclamation',
                            'notice' => 'fa-bullhorn',
                            default => 'fa-bell',
                        };
                        $badgeColor = match ($ann->type) {
                            'urgent' => '#A32D2D',
                            'notice' => '#854F0B',
                            default => '#025628',
                        };
                        $bgColor = match ($ann->type) {
                            'urgent' => '#FCEBEB',
                            'notice' => '#FFF8E1',
                            default => '#E8F5E9',
                        };
                      @endphp
                      <li
                        style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; background: #fff; border: 1px solid #f0f0f0; border-radius: 8px;">
                        <div
                          style="width: 32px; height: 32px; border-radius: 50%; background: {{ $bgColor }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                          <i class="fa-solid {{ $icon }}"
                            style="color: {{ $badgeColor }}; font-size: 13px;"></i>
                        </div>

                        <div style="flex: 1; min-width: 0;">
                          <strong
                            style="font-size: 13px; color: #1a1a1a; display: block; margin-bottom: 2px;">{{ $ann->title }}</strong>
                          <small
                            style="color: #666; font-size: 12px; display: block; line-height: 1.4; margin-bottom: 4px;">{{ $ann->message }}</small>
                          <small
                            style="color: #aaa; font-size: 10px;">{{ $ann->created_at->diffForHumans() }}</small>
                        </div>
                      </li>
                    @endforeach
                  </ul>
                </div>

                <div style="text-align: center; margin-top: 15px;">
                  <button class="view-more-btn" id="viewMoreBtn"
                    onclick="toggleUpdates()"
                    style="background: none; border: none; color: #004d26; font-weight: 700; font-size: 12px; cursor: pointer;">
                    View More <i class="fa-solid fa-chevron-down"></i>
                  </button>
                </div>
              @endif

              <!-- Sidebar Calendar Component -->
              <div class="sidebar-calendar" style="margin-top: 20px;">
                <div id="calendar"></div>
              </div>

            </div>

          </div>

        </div>

        <!-- EXPANDED CHART MODAL (PLACED OUTSIDE OVERVIEW VIEW TO PREVENT Z-INDEX OVERLAPS) -->
        <div id="expandedChartModal"
          style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
          <div
            style="background: #ffffff; width: 100%; max-width: 1180px; border-radius: 14px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; max-height: 92vh; overflow-y: auto;">

            <!-- Modal Header -->
            <div
              style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #ececec; padding-bottom: 12px;">
              <h3 id="expandedModalTitle"
                style="margin: 0; color: #004d26; font-size: 18px; font-weight: 700;">
                Analytics View
              </h3>
              <button onclick="closeExpandedChartModal()"
                style="background: none; border: none; font-size: 20px; color: #888888; cursor: pointer; padding: 4px;">
                <i class="fa-solid fa-xmark"></i>
              </button>
            </div>

            <!-- Dynamic KPI Cards Row -->
            <div id="modalKpiRow"
              style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
              <div
                style="background: #f8faf8; border: 1px solid #e0eae2; border-radius: 8px; padding: 12px; text-align: center;">
                <span id="kpiLabel1"
                  style="font-size: 11px; color: #666666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">Total
                  Trainees</span>
                <strong id="kpiVal1"
                  style="display: block; font-size: 20px; color: #004d26; font-weight: 800; margin-top: 2px;">0</strong>
              </div>

              <div
                style="background: #f8faf8; border: 1px solid #e0eae2; border-radius: 8px; padding: 12px; text-align: center;">
                <span id="kpiLabel2"
                  style="font-size: 11px; color: #666666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">Top
                  Performing Course</span>
                <strong id="kpiVal2"
                  style="display: block; font-size: 20px; color: #004d26; font-weight: 800; margin-top: 2px;">N/A</strong>
              </div>

              <div
                style="background: #f8faf8; border: 1px solid #e0eae2; border-radius: 8px; padding: 12px; text-align: center;">
                <span id="kpiLabel3"
                  style="font-size: 11px; color: #666666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">Active
                  Modules</span>
                <strong id="kpiVal3"
                  style="display: block; font-size: 20px; color: #004d26; font-weight: 800; margin-top: 2px;">0
                  / 0</strong>
              </div>
            </div>

            <!-- Scaled Chart Canvas Container -->
            <div
              style="position: relative; height: 520px; width: 100%; display: flex; justify-content: center; align-items: center; margin-bottom: 16px;">
              <canvas id="expandedCanvas"></canvas>

              <!-- Centered Doughnut Text Overlay -->
              <div id="doughnutCenterText"
                style="position: absolute; top: 43%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none; display: none;">
                <span
                  style="font-size: 42px; font-weight: 800; color: #004d26; display: block; line-height: 1;">33%</span>
                <span
                  style="font-size: 13px; font-weight: 700; color: #666666; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; display: block;">Completed</span>
              </div>
            </div>

            <div id="expandedChartInsightPanel" style="display:flex;align-items:flex-start;gap:10px;padding:13px 15px;margin:0 0 16px;background:#f8faf8;border:1px solid #dce7df;border-left:3px solid #e1dc36;border-radius:9px;">
              <i class="fa-solid fa-lightbulb" style="color:#075b35;margin-top:2px;"></i>
              <div><strong style="display:block;color:#075b35;font-size:12px;margin-bottom:4px;">Key insight</strong><p id="expandedChartInsight" style="margin:0;color:#183329;font-size:13px;line-height:1.55;">Review the values to identify patterns and areas for follow-up.</p></div>
            </div>

            <!-- Bottom Action Bar -->
            <div
              style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #ececec; padding-top: 14px; flex-wrap: wrap; gap: 10px;">
              <span style="font-size: 12px; color: #666666;">
                <i class="fa-solid fa-circle-info"
                  style="margin-right: 4px; color: #004d26;"></i> Metrics reflect
                real-time active enrollments and module completion data.
              </span>

              <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">

    <button type="button" onclick="printExpandedChartReport()"
        style="background: #ffffff; border: 1px solid #004d26; color: #004d26; padding: 9px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.15s ease;">
        <i class="fa-solid fa-print"></i> Print Report
    </button>

    <button type="button" onclick="exportExpandedChartToExcel()"
        style="background: #075b35; border: 1px solid #075b35; color: #fff; padding: 9px 16px; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.15s ease;">
        <i class="fa-solid fa-file-excel"></i> Export Excel
    </button>

</div>
            </div>

          </div>
        </div>
        <!-- 2. ANALYTICS VIEW -->
        <div id="view-analytics" style="display: none;">
          <div class="analytics-header-row">
            <h3><i class="fa-solid fa-chart-line"></i> Detailed System Analytics
            </h3>
            <button class="btn-cancel"
              onclick="showView('overview'); setActive(document.getElementById('nav-overview'));">Back
              to Overview</button>
          </div>
          <div class="analytics-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:18px;">
            <section class="card chart-card-full" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;">
              <div class="card-header" style="margin-bottom:12px;">
                <h4 style="margin:0;color:#075b35;font-size:15px;">Monthly Enrollment Trend</h4>
                <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;">Enrollment activity across the last six months.</p>
              </div>
              <div style="position:relative;height:340px;width:100%;"><canvas id="reportMonthlyEnrollment" aria-label="Monthly Enrollment Trend" role="img"></canvas></div>
            </section>
            <section class="card chart-card-full" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;">
              <div class="card-header" style="margin-bottom:12px;">
                <h4 style="margin:0;color:#075b35;font-size:15px;">Quiz Performance by Course</h4>
                <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;">Average quiz percentage grouped by course.</p>
              </div>
              <div style="position:relative;height:340px;width:100%;"><canvas id="reportQuizPerformance" aria-label="Quiz Performance by Course" role="img"></canvas></div>
            </section>
            <section class="card chart-card-full" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;">
              <div class="card-header" style="margin-bottom:12px;">
                <h4 style="margin:0;color:#075b35;font-size:15px;">Quiz vs. Onsite Performance</h4>
                <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;">Compare average quiz and onsite scores.</p>
              </div>
              <div style="position:relative;height:340px;width:100%;"><canvas id="reportQuizVsOnsite" aria-label="Quiz vs. Onsite Performance" role="img"></canvas></div>
            </section>
            <section class="card chart-card-full" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;">
              <div class="card-header" style="margin-bottom:12px;">
                <h4 style="margin:0;color:#075b35;font-size:15px;">Competency Distribution</h4>
                <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;">Distribution of competency assessment results.</p>
              </div>
              <div style="position:relative;height:340px;width:100%;"><canvas id="reportCompetency" aria-label="Competency Distribution" role="img"></canvas></div>
            </section>
            <section class="card chart-card-full" style="background:#fff;border:1px solid #e2ebe5;border-radius:14px;padding:18px;min-width:0;">
              <div class="card-header" style="margin-bottom:12px;">
                <h4 style="margin:0;color:#075b35;font-size:15px;">Competency Rate by Course</h4>
                <p style="margin:5px 0 0;color:#6b7c73;font-size:12px;">Percentage of eligible trainees with a combined quiz and onsite average of at least 75%.</p>
              </div>
              <div style="position:relative;height:340px;width:100%;"><canvas id="reportCompletion" aria-label="Competency Rate by Course" role="img"></canvas></div>
            </section>
          </div>
        </div>

        <!-- 3. TRAINEE MANAGEMENT VIEW -->
        <div id="view-trainee-list" style="display: none;">
          <!-- View A: Course Cards Grid -->
          <div id="course-cards-main-view">
            <!-- Top Filter & Search Control Bar -->
            <div
              style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; flex-wrap: wrap;">
              <h3
                style="margin: 0; font-size: 15px; font-weight: 700; color: #025628; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-chalkboard-user"
                  style="color: #025628;"></i>
                Courses & Enrolled Trainees
              </h3>

              <!-- Live Search Field -->
              <div style="position: relative; width: 280px; max-width: 100%;">
                <i class="fa-solid fa-magnifying-glass"
                  style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; font-size: 13px;"></i>
                <input type="text" id="traineeCourseSearch"
                  placeholder="Search course by title..."
                  onkeyup="filterTraineeCards()"
                  style="width: 100%; padding: 9px 12px 9px 34px; font-size: 12px; border: 1px solid #dcdcdc; border-radius: 8px; outline: none; box-sizing: border-box; background: #ffffff; transition: border-color 0.2s ease;">
              </div>
            </div>

            <!-- Course Cards Grid -->
            <div id="traineeCardsContainer"
              style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px;">
              <!-- Empty Filter State -->
              <div id="noTraineeCardResults"
                style="grid-column: 1 / -1; text-align: center; color: #888; padding: 40px 20px; font-size: 13px; display: none; background: #ffffff; border-radius: 10px; border: 1px solid #e0e0e0;">
                <i class="fa-solid fa-magnifying-glass"
                  style="font-size: 28px; display: block; margin-bottom: 10px; color: #ccc;"></i>
                No matching courses found.
              </div>

              @forelse($courses as $course)
                @php
                  /*
                  * Build a separate roster collection for this course.
                  * The enrollment ID and trainee user ID are selected separately.
                  */
                  $courseTrainees = collect();

                  $courseTrainees = \Illuminate\Support\Facades\DB::table(
                      'enrollment_tbls',
                  )
                      ->join(
                          'user_tbls',
                          'user_tbls.id',
                          '=',
                          'enrollment_tbls.user_id',
                      )
                      ->where('enrollment_tbls.course_id', $course->id)
                      ->select([
                          'user_tbls.id as user_id',
                          'enrollment_tbls.id as enrollment_id',
                          'user_tbls.firstname',
                          'user_tbls.lastname',
                          'user_tbls.email',
                          'user_tbls.contact',
                          'user_tbls.id_number',
                          'user_tbls.status',
                          'user_tbls.remarks',
                          'user_tbls.role',
                          'user_tbls.created_at as member_since',
                      ])
                      ->get();

                  $enrolledCount = $courseTrainees->count();
                  $slots = max(0, (int) ($course->slots ?? 0));
                  $remainingSlots = max(0, $slots - $enrolledCount);

                  $percent =
                      $slots > 0
                          ? min(100, (int) round(($enrolledCount / $slots) * 100))
                          : 0;

                  $barColor =
                      $percent >= 100
                          ? '#A32D2D'
                          : ($percent >= 80
                              ? '#854F0B'
                              : '#025628');

                  $assignedTrainer = $course->trainer ?? null;
                  $trainerName = $assignedTrainer
                      ? trim(
                          ($assignedTrainer->firstname ?? '') .
                              ' ' .
                              ($assignedTrainer->lastname ?? ''),
                      )
                      : 'Unassigned';
                  $hasTrainer = $assignedTrainer !== null;
                @endphp

                <div class="card trainee-course-card"
                  data-title="{{ strtolower($course->title) }}"
                  style="background: #ffffff; border-radius: 10px; border: 1px solid #e0e0e0; padding: 18px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; gap: 14px; transition: background-color 0.15s ease;">
                  <div>
                    <!-- Title & Enrollment Badge -->
                    <div
                      style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 12px;">
                      <h4
                        style="margin: 0; color: #111111; font-size: 14.5px; font-weight: 700; line-height: 1.3; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-book"
                          style="color: #025628; font-size: 14px;"></i>
                        {{ $course->title }}
                      </h4>
                      <span
                        style="font-size: 10.5px; background: #e8f5e9; color: #025628; padding: 3px 10px; border-radius: 12px; font-weight: 700; white-space: nowrap; border: 1px solid #c8e6c9;">
                        {{ $enrolledCount }} / {{ $course->slots }} Enrolled
                      </span>
                    </div>

                    {{-- Course Metadata --}}
                    <div
                      style="display: flex; flex-direction: column; gap: 7px; font-size: 11.5px; color: #555;">

                      {{-- Duration --}}
                      <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-calendar-day"
                          style="color: #888; width: 14px;"></i>
                        <span>
                          <strong>Duration:</strong>
                          {{ $course->duration }}
                          {{ \Illuminate\Support\Str::plural('Day', $course->duration) }}
                        </span>
                      </div>

                      {{-- Training Days --}}
                      @php
                        $trainingDays = $course->training_days ?? [];

                        if (is_string($trainingDays)) {
                            $trainingDays =
                                json_decode($trainingDays, true) ?? [];
                        }

                        if (!is_array($trainingDays)) {
                            $trainingDays = [];
                        }

                        $dayOrder = [
                            'Monday',
                            'Tuesday',
                            'Wednesday',
                            'Thursday',
                            'Friday',
                            'Saturday',
                            'Sunday',
                        ];

                        $trainingDays = array_values(
                            array_intersect($dayOrder, $trainingDays),
                        );
                      @endphp

                      <div
                        style="display: flex; align-items: flex-start; gap: 8px;">
                        <i class="fa-solid fa-calendar-week"
                          style="color: #888; width: 14px; margin-top: 2px;"></i>

                        <div style="flex: 1;">
                          <strong>Training Days:</strong>

                          @if (count($trainingDays))
                            <div
                              style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 5px;">
                              @foreach ($trainingDays as $day)
                                <span
                                  style="
                              background: #e8f5e9;
                              color: #025628;
                              border: 1px solid #c8e6c9;
                              padding: 3px 7px;
                              border-radius: 4px;
                              font-size: 10px;
                              font-weight: 600;
                          ">
                                  {{ substr($day, 0, 3) }}
                                </span>
                              @endforeach
                            </div>
                          @else
                            <span style="color: #999; margin-left: 3px;">
                              Not scheduled
                            </span>
                          @endif
                        </div>
                      </div>

                      {{-- Trainer --}}
                      <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-user-tie"
                          style="color: #888; width: 14px;"></i>

                        <span>
                          <strong>Trainer:</strong>
                          <span
                            style="
                  background: {{ $hasTrainer ? '#e8f5e9' : '#f0f0f0' }};
                  color: {{ $hasTrainer ? '#025628' : '#666' }};
                  padding: 2px 8px;
                  border-radius: 4px;
                  font-weight: 600;
                  font-size: 10.5px;
                  display: inline-block;
                  margin-left: 2px;
              ">
                            {{ $trainerName }}
                          </span>
                        </span>
                      </div>

                    </div>

                    <!-- Capacity Progress Bar -->
                    <div style="margin-top: 14px;">
                      <div
                        style="display: flex; justify-content: space-between; font-size: 10.5px; color: #666; font-weight: 600; margin-bottom: 5px;">
                        <span>Capacity Utilization</span>
                        <span
                          style="color: {{ $barColor }}; font-weight: 700;">{{ $percent }}%</span>
                      </div>
                      <div
                        style="background: #eeeeee; height: 6px; border-radius: 10px; overflow: hidden;">
                        <div
                          style="width: {{ $percent }}%; background: {{ $barColor }}; height: 100%; transition: width 0.3s ease;">
                        </div>
                      </div>
                    </div>
                  </div>

                  <hr
                    style="border: none; border-top: 1px solid #f2f2f2; margin: 0;">

                  <!-- Action Button -->
                  <button class="btn-all"
                    style="width: 100%; padding: 8px 14px; font-size: 12px; font-weight: 600; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;"
                    onclick="openFullCourseRoster('{{ addslashes($course->title) }}', {{ $courseTrainees->toJson() }})">
                    <i class="fa-solid fa-users"></i> View Trainees
                  </button>
                </div>
              @empty
                <div
                  style="grid-column: 1 / -1; text-align: center; color: #888; padding: 40px 20px; font-size: 13px; background: #ffffff; border-radius: 10px; border: 1px solid #e0e0e0;">
                  <i class="fa-solid fa-book-open"
                    style="font-size: 28px; display: block; margin-bottom: 10px; color: #ccc;"></i>
                  No active courses found.
                </div>
              @endforelse
            </div>
          </div>

          <!-- View B: Full Page Roster View -->
          <div id="full-course-roster-view"
            style="display: none; background: #ffffff; border-radius: 10px; border: 1px solid #e0e0e0; padding: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.02); overflow: hidden;">
            <div
              style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-bottom: 1px solid #ececec; background: #fafafa; flex-wrap: wrap; gap: 12px;">
              <div style="display: flex; align-items: center; gap: 12px;">
                <button onclick="backToCourseCards()"
                  style="background: #ffffff; border: 1px solid #dcdcdc; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; color: #444; display: flex; align-items: center; gap: 6px; transition: background 0.15s ease;">
                  <i class="fa-solid fa-arrow-left"></i> Back to Courses
                </button>
                <h3 id="rosterCourseTitle"
                  style="margin: 0; font-size: 15px; font-weight: 700; color: #025628;">
                  Course Roster
                </h3>
              </div>

              <div style="display: flex; align-items: center; gap: 10px;">
                <span id="rosterCountBadge"
                  style="font-size: 11px; background: #e8f5e9; color: #025628; padding: 4px 12px; border-radius: 20px; font-weight: 700; border: 1px solid #c8e6c9;">
                  0 Enrolled
                </span>
              </div>
            </div>

            <!-- Container for Trainee Roster Items -->
            <div id="fullRosterContainer"
              style="padding: 14px 18px; display: flex; flex-direction: column; gap: 8px; max-height: 550px; overflow-y: auto;">
              <!-- Populated via JavaScript -->
            </div>
          </div>
        </div>

        <!-- 4. TRAINER LIST VIEW -->
        <div id="view-trainer-list" style="display: none; width: 100%;">

          <!-- Header Controls Bar -->
          <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; flex-wrap: wrap;">
            <!-- Search Input -->
            <div style="position: relative; width: 280px; max-width: 100%;">
              <i class="fa-solid fa-magnifying-glass"
                style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #888; font-size: 13px;"></i>
              <input type="text" id="trainerSearchInput"
                onkeyup="filterTrainerList()"
                placeholder="Search trainer name, ID, or email..."
                style="width: 100%; padding: 8px 12px 8px 34px; font-size: 12.5px; border: 1px solid #dcdcdc; border-radius: 6px; outline: none; box-sizing: border-box; background: #ffffff; transition: border-color 0.2s ease;">
            </div>

            <!-- Add Trainer Button -->
            <button class="btn-save-main" onclick="openAddTrainerModal()"
              style="background: #025628; color: #ffffff; padding: 8px 16px; font-size: 12.5px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; white-space: nowrap;">
              <i class="fa-solid fa-user-plus"></i> Add Trainer
            </button>
          </div>

          <!-- Main Card Container -->
          <div class="card list-card"
            style="background: #ffffff; border-radius: 10px; border: 1px solid #dcdcdc; padding: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.02); overflow: hidden;">

            <!-- Card Header -->
            <div class="card-header"
              style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-bottom: 1px solid #ececec; background: #ffffff;">
              <h3
                style="margin: 0; font-size: 15px; font-weight: 700; color: #025628; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-chalkboard-user"></i> Trainer Directory
              </h3>
              <span
                style="font-size: 11px; background: #e8f5e9; color: #025628; padding: 3px 12px; border-radius: 12px; font-weight: 700;">
                Total: {{ count($trainersList) }}
              </span>
            </div>

            <!-- List Body -->
            <div class="user-list-body" id="trainer-list-content"
              style="display: flex; flex-direction: column;">
              @forelse($trainersList as $trainer)
                @php
                  $assignedCourse = isset($courses)
                      ? $courses->firstWhere('trainer_id', $trainer->id)
                      : null;
                  $courseTitle = $assignedCourse
                      ? $assignedCourse->title
                      : 'No course assigned';
                  $status = $trainer->status ?? 'Active';
                  $isInactive = strtolower($status) === 'inactive';
                  $fullName = trim(
                      ($trainer->firstname ?? '') .
                          ' ' .
                          ($trainer->lastname ?? ''),
                  );
                @endphp

                <!-- Single Trainer Row -->
                <div class="user-item" data-user-id="{{ $trainer->id }}"
                  style="display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; border-bottom: 1px solid #f2f2f2; gap: 16px; background: #ffffff; transition: background-color 0.15s ease;">

                  <div
                    style="display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0;">
                    <!-- Avatar Icon -->
                    <div
                      style="width: 38px; height: 38px; border-radius: 50%; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: #025628; flex-shrink: 0;">
                      <i class="fa-solid fa-user-tie"
                        style="font-size: 15px;"></i>
                    </div>

                    <div class="user-info" style="min-width: 0; flex: 1;">
                      <!-- Primary Row: Name & Status Badge -->
                      <div
                        style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <strong class="user-name-text"
                          style="font-size: 13.5px; font-weight: 700; color: #111111; letter-spacing: 0.2px;">
                          {{ strtoupper($fullName) }}
                        </strong>

                        <!-- Status Badge -->
                        <span class="roster-status-badge"
                          data-email="{{ $trainer->email }}"
                          style="font-size: 10.5px; font-weight: 700; padding: 2px 9px; border-radius: 12px; background: {{ $isInactive ? '#FCEBEB' : '#e8f5e9' }}; color: {{ $isInactive ? '#A32D2D' : '#025628' }}; display: inline-block;">
                          {{ $status }}
                        </span>
                      </div>

                      <!-- Secondary Details Bar -->
                      <div
                        style="display: flex; align-items: center; gap: 16px; margin-top: 4px; font-size: 11.5px; color: #666666; flex-wrap: wrap;">
                        <!-- ID Number -->
                        <span class="user-id-text"
                          style="color: #025628; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                          <i class="fa-solid fa-id-card"></i>
                          <span
                            class="user-id-value">{{ $trainer->id_number ?? 'N/A' }}</span>
                        </span>

                        <!-- Email -->
                        <span class="user-email-text"
                          style="display: inline-flex; align-items: center; gap: 6px; color: #666666;">
                          <i class="fa-regular fa-envelope"></i>
                          <span
                            class="user-email-value">{{ $trainer->email }}</span>
                        </span>

                        <!-- Assigned Course -->
                        <span class="user-course-text"
                          style="color: #025628; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                          <i class="fa-solid fa-book-open"></i>
                          <span
                            class="user-course-value">{{ $courseTitle }}</span>
                        </span>

                        <!-- Contact Number -->
                        <span class="user-contact-text"
                          style="color: #888888; display: inline-flex; align-items: center; gap: 6px;">
                          <i class="fa-solid fa-phone"></i>
                          <span
                            class="user-contact-value">{{ $trainer->contact ?? 'Not provided' }}</span>
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- View Profile Action Button -->
                  <button class="btn-view"
                    onclick="openUserModal(
              '{{ $trainer->id }}',
              '{{ addslashes(e($fullName)) }}',
              '{{ addslashes(e($trainer->email)) }}',
              'trainer',
              '{{ addslashes(e($status)) }}',
              '{{ addslashes(e($courseTitle)) }}',
              '{{ addslashes(e($trainer->contact ?? '')) }}',
              '{{ addslashes(e($trainer->id_number ?? '')) }}',
              '{{ \Carbon\Carbon::parse($trainer->created_at ?? now())->format('F Y') }}',
              '{{ addslashes(e($trainer->remarks ?? '')) }}'
            )"
                    style="background: #025628; color: #ffffff; border: none; padding: 7px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: background-color 0.15s ease; white-space: nowrap; flex-shrink: 0;">
                    View Profile
                  </button>

                </div>
              @empty
                <div
                  style="text-align: center; color: #888888; padding: 40px 20px; font-size: 13px;">
                  <i class="fa-solid fa-user-slash"
                    style="font-size: 26px; display: block; margin-bottom: 8px; color: #cccccc;"></i>
                  No trainers registered yet.
                </div>
              @endforelse
            </div>

          </div>
        </div>

        <!-- 5. REGISTRATIONS VIEW -->
  <div id="view-registrations" style="display: none;">
      <div class="registrations-heading">
          <div></div>

          <div class="registrations-total">
              <i class="fa-solid fa-users"></i>
              <span>{{ number_format($registrations->total()) }} total submissions</span>
          </div>
      </div>

     
@if (session('registration_import_summary'))
    @php
        $summary = session('registration_import_summary');
    @endphp

    <div style="margin-bottom:14px;padding:16px 18px;background:#eaf5ee;border:1px solid #cfe5d6;border-left:4px solid #075b35;border-radius:10px;color:#075b35;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
            <i class="fa-solid fa-circle-check"></i>
            <strong>Registration Import Summary</strong>
        </div>

        <div style="display:flex;flex-wrap:wrap;gap:10px 24px;">
            <span>
                <strong>Imported:</strong>
                {{ $summary['imported'] ?? 0 }}
            </span>

            <span>
                <strong>Duplicates Skipped:</strong>
                {{ $summary['duplicates'] ?? 0 }}
            </span>

            <span>
                <strong>Invalid Rows:</strong>
                {{ $summary['error_count'] ?? 0 }}
            </span>
        </div>

        @if (!empty($summary['errors']))
            <details style="margin-top:12px;padding-top:10px;border-top:1px solid #cfe5d6;">
                <summary style="cursor:pointer;font-weight:600;color:#075b35;">
                    View Invalid Rows
                </summary>

                <ul style="margin:10px 0 0;padding-left:22px;color:#654f00;">
                    @foreach ($summary['errors'] as $error)
                        <li style="margin-bottom:5px;">
                            {{ is_array($error) ? json_encode($error) : $error }}
                        </li>
                    @endforeach
                </ul>

                @if (($summary['error_count'] ?? 0) > count($summary['errors']))
                    <p style="margin-top:8px;font-size:12px;color:#654f00;">
                        Showing the first {{ count($summary['errors']) }}
                        errors out of {{ $summary['error_count'] }} total.
                    </p>
                @endif
            </details>
        @endif
    </div>
@endif

@if (session('import_summary'))
    <div style="margin-bottom:14px;padding:12px 16px;background:#eaf5ee;border:1px solid #cfe5d6;border-radius:10px;color:#075b35;">
        {{ session('import_summary') }}
    </div>
@endif

@if (session('import_errors'))
    <div style="margin-bottom:14px;padding:14px 16px;background:#fff8e1;border:1px solid #eadf9a;border-radius:10px;color:#654f00;">
        <strong>Rows that need attention:</strong>
        <ul style="margin:8px 0 0;padding-left:20px;">
            @foreach (session('import_errors') as $error)
                <li>{{ is_array($error) ? json_encode($error) : $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($errors->has('file'))
    <div style="margin-bottom:14px;padding:12px 16px;background:#fcebea;border:1px solid #efc5c2;border-radius:10px;color:#a32d2d;">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ $errors->first('file') }}
    </div>
@endif


      <div class="registrations-card">
          <div class="registrations-toolbar">
              <div>
                  <h3>Submitted Registrations</h3>
                  <span class="subtext">
                      Search registrations on this page by name, ULI, course, or date.
                  </span>
              </div>

              <div class="registrations-tools">
                  <label class="registrations-search" for="registration-search-input">
                      <i class="fa-solid fa-magnifying-glass"></i>
                      <input
                          type="search"
                          id="registration-search-input"
                          placeholder="Search registrations..."
                          autocomplete="off"
                          aria-label="Search registrations on this page"
                      >
                  </label>

                  <select
    id="registration-course-filter"
    class="registration-course-filter"
    aria-label="Filter registrations by course"
>
    <option value="">All Courses</option>

    @foreach ($registrationCourses as $course)
        <option value="{{ strtolower(trim($course)) }}">
            {{ $course }}
        </option>
    @endforeach
</select>



                  

                  <!-- Import Excel -->
<form
    action="{{ route('admin.registrations.import') }}"
    method="POST"
    enctype="multipart/form-data"
    style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;"
>
    @csrf

    <input
        type="file"
        name="file"
        id="registrations-import-file"
        accept=".xlsx,.xls,.csv"
        required
        style="display:none;"
        onchange="this.form.requestSubmit();"
    >

    <button
        type="button"
        class="registrations-export"
        onclick="document.getElementById('registrations-import-file').click();"
        style="background:#e1dc36;color:#183329;border:1px solid #d2cd28;cursor:pointer;"
    >
        <i class="fa-solid fa-file-import"></i>
        <span>Import Excel</span>
    </button>
</form>

<!-- Download Excel Template -->

                  
              </div>
          </div>

          <div class="registrations-list" id="registrations-list-content">
              @forelse ($registrations as $reg)
                  @php
                      $registrationName = trim(
                          ($reg->last_name ?? '') . ', ' .
                          ($reg->first_name ?? '') . ' ' .
                          ($reg->middle_name ?? '')
                      );

                      $registrationName = trim($registrationName, " ,\t\n\r\0\x0B");

                      $registrationDate = $reg->created_at
                          ? $reg->created_at->format('M j, Y g:i A')
                          : 'Date unavailable';

                      $registrationSearchText = strtolower(
                          $registrationName . ' ' .
                          ($reg->uli_number ?? '') . ' ' .
                          ($reg->course_name ?? '') . ' ' .
                          $registrationDate
                      );
                  @endphp

                  <div
    class="registration-row"
    data-registration-search="{{ $registrationSearchText }}"
    data-registration-course="{{ strtolower(trim($reg->course_name ?? '')) }}"
>
                      <div class="registration-avatar" aria-hidden="true">
                          <i class="fa-solid fa-id-card"></i>
                      </div>

                      <div class="registration-info">
                          <span class="registration-name">
                              {{ $registrationName !== '' ? $registrationName : 'Unnamed learner' }}
                          </span>

                          <div class="registration-meta">
                              <span>
                                  <i class="fa-solid fa-fingerprint"></i>
                                  ULI: {{ $reg->uli_number ?: '—' }}
                              </span>

                              <span class="registration-course">
                                  <i class="fa-solid fa-book-open"></i>
                                  {{ $reg->course_name ?: 'Course not specified' }}
                              </span>

                              <span>
                                  <i class="fa-regular fa-calendar"></i>
                                  {{ $registrationDate }}
                              </span>
                          </div>
                      </div>

                      <div class="registration-actions">
                          <a
                              href="{{ route('admin.registrations.show', $reg->id) }}"
                              target="_blank"
                              rel="noopener noreferrer"
                              class="registration-action"
                              aria-label="View registration {{ $reg->id }}"
                          >
                              <i class="fa-solid fa-eye"></i>
                              <span>View</span>
                          </a>

                          <a
                              href="{{ route('admin.registrations.pdf', $reg->id) }}"
                              class="registration-action pdf"
                              aria-label="Download PDF for registration {{ $reg->id }}"
                          >
                              <i class="fa-solid fa-file-pdf"></i>
                              <span>PDF</span>
                          </a>
                      </div>
                  </div>
              @empty
                  <div class="registrations-empty">
                      <i class="fa-regular fa-folder-open"></i>
                      <strong>No registrations yet</strong>
                      <span>Submitted learner registrations will appear here.</span>
                  </div>
              @endforelse

              <div
                  class="registration-filter-empty"
                  id="registration-filter-empty"
                  style="display: none;"
              >
                  No registrations on this page match your search.
              </div>
          </div>

          {{-- Registration pagination --}}
          @if ($registrations->hasPages())
              <div class="registrations-pagination">
                  <span class="registrations-pagination-note">
                      Showing
                      {{ number_format($registrations->firstItem() ?? 0) }}–{{ number_format($registrations->lastItem() ?? 0) }}
                      of
                      {{ number_format($registrations->total()) }}
                      registrations
                  </span>

                  <div class="pagination-container">
                      {{-- Previous page --}}
                      @if ($registrations->onFirstPage())
                          <button
                              type="button"
                              class="page-btn"
                              disabled
                              aria-label="Previous page"
                          >
                              <i class="fa-solid fa-chevron-left"></i>
                          </button>
                      @else
                          <a
                              href="{{ $registrations->appends(['view' => 'registrations'])->previousPageUrl() }}"
                              class="page-btn"
                              aria-label="Previous page"
                          >
                              <i class="fa-solid fa-chevron-left"></i>
                          </a>
                      @endif

                      {{-- Page numbers --}}
                      <div class="page-numbers">
                          @for ($i = 1; $i <= $registrations->lastPage(); $i++)
                              @if ($i === $registrations->currentPage())
                                  <button
                                      type="button"
                                      class="page-btn active"
                                      aria-current="page"
                                      aria-label="Page {{ $i }}, current page"
                                      disabled
                                  >
                                      {{ $i }}
                                  </button>
                              @else
                                  <a
                                      href="{{ $registrations->appends(['view' => 'registrations'])->url($i) }}"
                                      class="page-btn"
                                      aria-label="Go to page {{ $i }}"
                                  >
                                      {{ $i }}
                                  </a>
                              @endif
                          @endfor
                      </div>

                      {{-- Next page --}}
                      @if ($registrations->hasMorePages())
                          <a
                              href="{{ $registrations->appends(['view' => 'registrations'])->nextPageUrl() }}"
                              class="page-btn"
                              aria-label="Next page"
                          >
                              <i class="fa-solid fa-chevron-right"></i>
                          </a>
                      @else
                          <button
                              type="button"
                              class="page-btn"
                              disabled
                              aria-label="Next page"
                          >
                              <i class="fa-solid fa-chevron-right"></i>
                          </button>
                      @endif
                  </div>
              </div>
          @endif
      </div>
  </div>

        <!-- 6. FACILITIES VIEW -->
        <div id="view-facilities" style="display: none;">

          <!-- Top Control Bar (Search & Action Button) -->
          <div
            style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 20px; gap: 12px; flex-wrap: wrap;">
            <div class="input-wrapper"
              style="width: 320px; background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 6px 12px; display: flex; align-items: center;">
              <i class="fa-solid fa-magnifying-glass"
                style="color: #888; margin-right: 8px;"></i>
              <input type="text" id="searchFacilityInput"
                placeholder="Search facility name or location..."
                onkeyup="filterFacilities()"
                style="border: none; outline: none; width: 100%; font-size: 13px;">
            </div>

            <button class="btn-save-main" onclick="openAddFacilityModal()"
              style="width: auto; padding: 9px 18px; font-size: 13px; white-space: nowrap;">
              <i class="fa-solid fa-plus"></i> Add New Facility
            </button>
          </div>

          <!-- Facility Grid -->
          <div class="facility-grid" id="facilityGrid"
            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 16px;">

            @forelse ($facilities as $facility)
              <div class="card facility-card"
                data-name="{{ strtolower($facility->name) }}"
                data-location="{{ strtolower($facility->address) }}"
                style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; padding: 20px; background: #fff;">
                <div>
                  <!-- Header & Status Badge -->
                  <div
                    style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div style="display: flex; gap: 12px; align-items: center;">
                      <div
                        style="width: 42px; height: 42px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: #025628; font-size: 18px; flex-shrink: 0;">
                        <i class="fa-solid fa-building-circle-check"></i>
                      </div>
                      <div>
                        <strong
                          style="font-size: 15px; color: #1a1a1a; display: block;">{{ $facility->name }}</strong>
                        <small style="color: #666; font-size: 12px;">
                          <i class="fa-solid fa-location-dot"></i>
                          {{ $facility->address }}
                        </small>
                      </div>
                    </div>
                  </div>

                  <hr
                    style="border: none; border-top: 1px solid #f0f0f0; margin: 12px 0;">

                  <!-- Dynamic Multi-Course Badges -->
                  <div style="margin-bottom: 16px;">
                    <div
                      style="font-size: 12px; color: #555; margin-bottom: 6px;">
                      <i class="fa-solid fa-book-open"
                        style="color: #854F0B;"></i>
                      <strong>Assigned Courses
                        ({{ $facility->courses->count() }})
                        :</strong>
                    </div>

                    @if ($facility->courses->isNotEmpty())
                      <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        @foreach ($facility->courses as $course)
                          <span
                            style="font-size: 11px; font-weight: 600; background: #e8f5e9; color: #025628; padding: 3px 10px; border-radius: 12px; border: 1px solid #c8e6c9;">
                            {{ $course->title }}
                          </span>
                        @endforeach
                      </div>
                    @else
                      <span
                        style="font-size: 11px; color: #9ca3af; font-style: italic;">No
                        courses assigned</span>
                    @endif
                  </div>
                </div>

                <!-- Button with proper parameter alignment -->
                <button class="btn-all"
                  onclick="openFacilityModal(
            {{ $facility->id }}, 
            '{{ addslashes($facility->name) }}', 
            '{{ addslashes($facility->address) }}', 
            {{ json_encode($facility->courses->pluck('id')) }}
          )"
                  style="width: 100%;">
                   View Facility
                </button>
              </div>
            @empty
              <div
                style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #888;">
                <i class="fa-solid fa-building-circle-xmark"
                  style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                No facilities found. Click "Add New Facility" to create one.
              </div>
            @endforelse

          </div>

          <!-- Pagination -->
          <div class="pagination-container" style="margin-top: 20px;">
            <button class="page-btn prev" disabled><i
                class="fa-solid fa-chevron-left"></i></button>
            <div class="page-numbers">
              <button class="page-btn active">1</button>
            </div>
            <button class="page-btn next" disabled><i
                class="fa-solid fa-chevron-right"></i></button>
          </div>

        </div>

        <!-- 7. COURSES VIEW -->

        <div id="view-courses" style="display: none;">

          <!-- Top Control Bar -->
          <div
            style="display:flex;justify-content:flex-end;align-items:center;margin-bottom:20px;gap:12px;flex-wrap:wrap;">

            <div class="input-wrapper"
              style="width:320px;background:#fff;border:1px solid #d1d5db;border-radius:8px;padding:7px 12px;display:flex;align-items:center;box-shadow:0 1px 2px rgba(0,0,0,0.05);">

              <i class="fa-solid fa-magnifying-glass"
                style="color:#9ca3af;margin-right:8px;font-size:13px;"></i>

              <input type="text" id="searchCourseInput"
                placeholder="Search course title or code..."
                onkeyup="filterCourses()"
                style="border:none;outline:none;width:100%;font-size:13px;color:#1f2937;">
            </div>

            <button class="btn-save-main" onclick="openAddCourseModal()"
              style="width:auto;padding:9px 18px;font-size:13px;white-space:nowrap;border-radius:8px;cursor:pointer;font-weight:600;">

              <i class="fa-solid fa-plus"></i> Add New Course
            </button>
          </div>

          <!-- Courses Grid -->
          <div class="courses-grid" id="coursesGrid">

            <!-- Live Search No Results -->
            <div id="noFilterResults"
              style="grid-column:1/-1;text-align:center;color:#9ca3af;padding:50px 0;font-size:13px;display:none;">

              <i class="fa-solid fa-magnifying-glass"
                style="font-size:32px;display:block;margin-bottom:10px;color:#d1d5db;"></i>

              No courses found.
            </div>

            @forelse($courses as $course)

              @php
                // Trainer Information
                $trainerName = $course->trainer
                    ? trim(
                        $course->trainer->firstname .
                            ' ' .
                            $course->trainer->lastname,
                    )
                    : null;

                // Facility Information
                $facilityName = $course->facility
                    ? $course->facility->name
                    : null;

                // Enrollment Calculations
                $enrolledCount =
                    $course->enrolled_count ?? ($course->enrollments_count ?? 0);

                $totalSlots = max(0, (int) ($course->slots ?? 0));

                $remainingSlots = max(0, $totalSlots - $enrolledCount);

                // Module Count
                $moduleCount =
                    $course->modules_count ??
                    \Illuminate\Support\Facades\DB::table('modules')
                        ->where('course_id', $course->id)
                        ->count();

                // Quiz Count
                $quizCount =
                    $course->quizzes_count ??
                    \Illuminate\Support\Facades\DB::table('quizzes')
                        ->where('course_id', $course->id)
                        ->count();

                // Enrollment Progress
                $percent =
                    $totalSlots > 0
                        ? min(100, round(($enrolledCount / $totalSlots) * 100))
                        : 0;

                $barColor =
                    $percent >= 100
                        ? '#dc2626'
                        : ($percent >= 80
                            ? '#d97706'
                            : '#025628');

                // Normalize Training Days
                $trainingDays = $course->training_days ?? [];

                if (is_string($trainingDays)) {
                    $decodedTrainingDays = json_decode($trainingDays, true);

                    $trainingDays = is_array($decodedTrainingDays)
                        ? $decodedTrainingDays
                        : [];
                }

                if (!is_array($trainingDays)) {
                    $trainingDays = [];
                }

                // Weekday Labels and Order
                $dayLabels = [
                    'monday' => 'MON',
                    'tuesday' => 'TUE',
                    'wednesday' => 'WED',
                    'thursday' => 'THU',
                    'friday' => 'FRI',
                    'saturday' => 'SAT',
                    'sunday' => 'SUN',
                ];

                $normalizedTrainingDays = collect($trainingDays)
                    ->map(fn($day) => strtolower(trim((string) $day)))
                    ->filter(fn($day) => array_key_exists($day, $dayLabels))
                    ->unique()
                    ->values()
                    ->all();

                $orderedTrainingDays = array_values(
                    array_filter(
                        array_keys($dayLabels),
                        fn($day) => in_array($day, $normalizedTrainingDays, true),
                    ),
                );
              @endphp

              <!-- Course Card -->
              <div class="card course-card"
                style="background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;padding:18px;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 2px 8px rgba(0,0,0,0.03);text-align:left;"
                data-title="{{ strtolower($course->title ?? '') }}"
                data-code="{{ strtolower($course->course_code ?? '') }}">

                <div>

                  <!-- Course Code and Status -->
                  <div
                    style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:8px;">

                    <span
                      style="font-size:11px;font-weight:700;color:#4b5563;background:#f3f4f6;padding:4px 9px;border-radius:6px;">

                      <i class="fa-solid fa-barcode"
                        style="color:#6b7280;margin-right:4px;"></i>

                      {{ $course->course_code ?? 'CRS-000' }}
                    </span>

                    <div
                      class="course-badge {{ strtolower($course->status ?? '') === 'active' ? 'active' : 'inactive' }}"
                      style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:12px;">

                      {{ ucfirst($course->status ?? 'Inactive') }}
                    </div>
                  </div>

                  <!-- Course Title and Icon -->
                  <div
                    style="display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;text-align:left;">

                    <div
                      style="background:#e8f5e9;color:#025628;border-radius:10px;width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">

                      <i class="fa-solid fa-book" style="font-size:18px;"></i>
                    </div>

                    <div style="flex-grow:1;text-align:left;min-width:0;">

                      <h4
                        style="margin:0 0 4px;font-size:15px;font-weight:700;color:#111827;line-height:1.35;text-align:left;">

                        {{ $course->title }}
                      </h4>

                      <span
                        style="font-size:12px;color:#6b7280;font-weight:500;display:inline-flex;align-items:center;gap:5px;">

                        <i class="fa-regular fa-clock"
                          style="color:#9ca3af;"></i>

                        {{ $course->duration }} Days Duration
                      </span>
                    </div>
                  </div>

                  <!-- Training Days -->
                  <div
                    style="display:flex;align-items:flex-start;gap:8px;margin:-4px 0 14px 56px;font-size:12px;color:#4b5563;">

                    <i class="fa-solid fa-calendar-week"
                      style="color:#025628;width:14px;margin-top:2px;flex-shrink:0;"></i>

                    <div style="flex:1;min-width:0;">

                      <div style="font-weight:600;color:#374151;">
                        Training Days
                      </div>

                      @if (count($orderedTrainingDays) > 0)
                        <div
                          style="display:flex;flex-wrap:wrap;gap:5px;margin-top:6px;">

                          @foreach ($orderedTrainingDays as $day)
                            <span
                              style="background:#e8f5e9;color:#025628;border:1px solid #c8e6c9;border-radius:5px;padding:3px 7px;font-size:10px;font-weight:700;letter-spacing:.2px;">

                              {{ $dayLabels[$day] }}

                            </span>
                          @endforeach

                        </div>
                      @else
                        <div
                          style="color:#9ca3af;font-size:11px;margin-top:4px;">
                          No training days set
                        </div>
                      @endif
                    </div>
                  </div>

                  <!-- Trainer and Facility -->
                  <div
                    style="background:#f9fafb;border:1px solid #f3f4f6;border-radius:10px;padding:10px 12px;margin-bottom:12px;display:flex;flex-direction:column;gap:6px;font-size:12px;text-align:left;">

                    <!-- Trainer -->
                    <div style="display:flex;align-items:flex-start;gap:8px;">

                      <i class="fa-solid fa-chalkboard-user"
                        style="color:#025628;width:14px;text-align:center;margin-top:2px;"></i>

                      <span style="color:#6b7280;font-weight:500;flex-shrink:0;">
                        Trainer:
                      </span>

                      <strong
                        style="color:{{ $trainerName ? '#111827' : '#9ca3af' }};font-weight:600;overflow-wrap:anywhere;">

                        {{ $trainerName ?? 'No trainer assigned' }}
                      </strong>
                    </div>

                    <!-- Facility -->
                    <div style="display:flex;align-items:flex-start;gap:8px;">

                      <i class="fa-solid fa-building"
                        style="color:#025628;width:14px;text-align:center;margin-top:2px;"></i>

                      <span style="color:#6b7280;font-weight:500;flex-shrink:0;">
                        Facility:
                      </span>

                      <strong
                        style="color:{{ $facilityName ? '#111827' : '#9ca3af' }};font-weight:600;overflow-wrap:anywhere;">

                        {{ $facilityName ?? 'No facility assigned' }}
                      </strong>
                    </div>
                  </div>

                  <!-- Modules and Quizzes -->
                  <div
                    style="display:flex;align-items:center;gap:8px;margin-bottom:16px;flex-wrap:wrap;">

                    <span
                      style="background:#f3f4f6;color:#374151;font-size:11px;font-weight:600;padding:4px 9px;border-radius:6px;display:inline-flex;align-items:center;gap:5px;">

                      <i class="fa-solid fa-cubes" style="color:#025628;"></i>

                      <span id="course-module-count-{{ $course->id }}">
                        {{ $moduleCount }}
                      </span>

                      <span id="course-module-label-{{ $course->id }}">
                        {{ \Illuminate\Support\Str::plural('Module', $moduleCount) }}
                      </span>
                    </span>

                    <span
                      style="background:#fef3c7;color:#92400e;font-size:11px;font-weight:600;padding:4px 9px;border-radius:6px;display:inline-flex;align-items:center;gap:5px;">

                      <i class="fa-solid fa-clipboard-question"
                        style="color:#b45309;"></i>

                      <span id="course-quiz-count-{{ $course->id }}">
                        {{ $quizCount }}
                      </span>

                      <span id="course-quiz-label-{{ $course->id }}">
                        {{ \Illuminate\Support\Str::plural('Quiz', $quizCount) }}
                      </span>
                    </span>
                  </div>
                </div>

                <div>

                  <!-- Enrollment Capacity -->
                  <div style="margin-bottom:14px;text-align:left;">

                    <div
                      style="display:flex;justify-content:space-between;align-items:center;font-size:11px;font-weight:600;color:#4b5563;margin-bottom:6px;gap:8px;">

                      <span>Enrolled Capacity</span>

                      <span
                        style="color:{{ $barColor }};font-weight:700;white-space:nowrap;">

                        {{ $enrolledCount }} / {{ $totalSlots }} Enrolled
                      </span>
                    </div>

                    <div class="progress-container"
                      style="background:#e5e7eb;height:6px;border-radius:10px;overflow:hidden;">

                      <div class="progress-bar"
                        style="width:{{ $percent }}%;background:{{ $barColor }};height:100%;border-radius:10px;transition:width .3s ease;">
                      </div>
                    </div>

                    <div style="font-size:11px;color:#6b7280;margin-top:5px;">
                      {{ $remainingSlots }}
                      {{ \Illuminate\Support\Str::plural('slot', $remainingSlots) }}
                      remaining
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  
  <!-- Action Buttons -->
  <div style="display:flex;gap:8px;flex-wrap:wrap;">

      <button type="button" class="btn-all"
          style="flex:1;min-width:90px;border:1px solid #d1d5db;background:#ffffff;color:#374151;font-weight:600;padding:8px;border-radius:8px;font-size:12px;cursor:pointer;"
          onclick="openCourseModal(
              {{ $course->id }},
              {{ \Illuminate\Support\Js::from($course->course_code ?? '') }},
              {{ \Illuminate\Support\Js::from($course->title ?? '') }},
              {{ \Illuminate\Support\Js::from($course->duration ?? '') }},
              {{ $course->slots ?? 0 }},
              {{ $course->trainer_id ?? 'null' }},
              {{ \Illuminate\Support\Js::from($trainerName ?? '') }},
              {{ \Illuminate\Support\Js::from($course->status ?? 'Inactive') }},
              {{ \Illuminate\Support\Js::from($course->start_date?->format('Y-m-d') ?? '') }},
              {{ \Illuminate\Support\Js::from($course->end_date?->format('Y-m-d') ?? '') }},
              {{ \Illuminate\Support\Js::from($trainingDays) }},
              {{ \Illuminate\Support\Js::from($course->start_time ? substr((string) $course->start_time, 0, 5) : '') }},
              {{ \Illuminate\Support\Js::from($course->end_time ? substr((string) $course->end_time, 0, 5) : '') }}
          )">
          Course Details
      </button>

  </div>

                </div>
              </div>

            @empty

              <div
                style="grid-column:1/-1;text-align:center;color:#9ca3af;padding:50px 0;font-size:13px;">

                <i class="fa-solid fa-book-open"
                  style="font-size:32px;display:block;margin-bottom:10px;color:#d1d5db;"></i>

                No courses found.
              </div>

            @endforelse
          </div>

          <!-- Pagination -->
          @if (
              $courses instanceof \Illuminate\Pagination\LengthAwarePaginator &&
                  $courses->hasPages())

            <div class="pagination-container"
              style="margin-top:24px;display:flex;justify-content:center;align-items:center;gap:6px;">

              <!-- Previous Page -->
              @if ($courses->onFirstPage())
                <button class="page-btn" disabled
                  style="opacity:.5;cursor:not-allowed;">
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
              @else
                <a href="{{ $courses->previousPageUrl() }}&view=courses"
                  onclick="setActive(document.getElementById('nav-courses'))"
                  class="page-btn">

                  <i class="fa-solid fa-chevron-left"></i>
                </a>
              @endif

              <!-- Page Numbers -->
              <div class="page-numbers" style="display:flex;gap:4px;">

                @for ($i = 1; $i <= $courses->lastPage(); $i++)
                  @if ($i == $courses->currentPage())
                    <button class="page-btn active">
                      {{ $i }}
                    </button>
                  @else
                    <a href="{{ $courses->url($i) }}&view=courses"
                      onclick="setActive(document.getElementById('nav-courses'))"
                      class="page-btn">

                      {{ $i }}
                    </a>
                  @endif
                @endfor
              </div>

              <!-- Next Page -->
              @if ($courses->hasMorePages())
                <a href="{{ $courses->nextPageUrl() }}&view=courses"
                  onclick="setActive(document.getElementById('nav-courses'))"
                  class="page-btn">

                  <i class="fa-solid fa-chevron-right"></i>
                </a>
              @else
                <button class="page-btn" disabled
                  style="opacity:.5;cursor:not-allowed;">
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
              @endif
            </div>
          @endif

        </div>

        <!-- 8. ANNOUNCEMENTS VIEW -->
        <div id="view-announcements" style="display: none;">
          <!-- Header Bar -->
          <div class="view-header"
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
              <span style="font-size: 13px; color: #666;">Manage system updates,
                reminders, and public notices.</span>
            </div>

            <button class="btn-save-main" onclick="openAnnouncementModal()"
              style="width: auto; padding: 8px 16px; font-weight: 600;">
              <i class="fa-solid fa-plus"></i> Add Announcement
            </button>
          </div>

          <!-- Filter & Search Toolbar -->
          <div class="filter-toolbar"
            style="display: flex; align-items: center; justify-content: space-between; gap: 12px; background: #fff; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 16px;">
            <!-- Search Input -->
            <div style="position: relative; flex: 1; max-width: 320px;">
              <i class="fa-solid fa-magnifying-glass"
                style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
              <input type="text" id="annSearchInput"
                placeholder="Search announcements..."
                onkeyup="filterAnnouncements()"
                style="width: 100%; padding: 8px 12px 8px 36px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; outline: none; background: #f8fafc;">
            </div>

            <!-- Dropdown Filters -->
            <div style="display: flex; align-items: center; gap: 12px;">
              <div style="display: flex; align-items: center; gap: 6px;">
                <label for="annTypeFilter"
                  style="font-size: 12px; font-weight: 600; color: #64748b;">Type:</label>
                <select id="annTypeFilter" onchange="filterAnnouncements()"
                  style="padding: 7px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; color: #1e293b; background: #fff; cursor: pointer; outline: none;">
                  <option value="">All Types</option>
                  <option value="urgent">Urgent</option>
                  <option value="notice">Notice</option>
                  <option value="reminder">Reminder</option>
                </select>
              </div>

              <div style="display: flex; align-items: center; gap: 6px;">
                <label for="annStatusFilter"
                  style="font-size: 12px; font-weight: 600; color: #64748b;">Status:</label>
                <select id="annStatusFilter" onchange="filterAnnouncements()"
                  style="padding: 7px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; color: #1e293b; background: #fff; cursor: pointer; outline: none;">
                  <option value="">All Statuses</option>
                  <option value="active">Active</option>
                  <option value="draft">Draft</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Announcement List Card -->
          <div class="card list-card"
            style="display: flex; flex-direction: column; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            @forelse($announcements as $ann)
              @php
                $typeConfig = match ($ann->type) {
                    'urgent' => [
                        'badge' => '#A32D2D',
                        'bg' => '#FCEBEB',
                        'icon' => 'fa-triangle-exclamation',
                    ],
                    'notice' => [
                        'badge' => '#854F0B',
                        'bg' => '#FFF8E1',
                        'icon' => 'fa-circle-info',
                    ],
                    default => [
                        'badge' => '#025628',
                        'bg' => '#E8F5E9',
                        'icon' => 'fa-bell',
                    ],
                };
              @endphp

              <div class="user-item" data-type="{{ strtolower($ann->type) }}"
                data-status="{{ $ann->is_active ? 'active' : 'draft' }}"
                style="display: flex; align-items: flex-start; gap: 14px; padding: 16px; border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">

                <!-- Icon -->
                <div
                  style="width: 38px; height: 38px; border-radius: 50%; background: {{ $typeConfig['bg'] }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                  <i class="fa-solid {{ $typeConfig['icon'] }}"
                    style="color: {{ $typeConfig['badge'] }}; font-size: 14px;"></i>
                </div>

                <!-- Content Body -->
                <div class="user-info" style="flex: 1; min-width: 0;">
                  <div
                    style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px;">
                    <strong class="ann-title-text"
                      style="font-size: 14px; font-weight: 600; color: #0f172a;">{{ $ann->title }}</strong>

                    <span
                      style="padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 700; background: {{ $typeConfig['bg'] }}; color: {{ $typeConfig['badge'] }};">
                      {{ ucfirst($ann->type) }}
                    </span>

                    @if ($ann->is_active)
                      <span
                        style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 12px; background: #e6f4ea; color: #137333;">
                        Active
                      </span>
                    @else
                      <span
                        style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 12px; background: #f1f3f4; color: #5f6368;">
                        Draft
                      </span>
                    @endif
                  </div>

                  <p class="ann-msg-text"
                    style="margin: 0 0 10px 0; font-size: 13px; color: #475569; line-height: 1.5;">
                    {{ $ann->message }}
                  </p>

                  <!-- Timestamps Metadata Tags -->
                  <div
                    style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; font-size: 11px; color: #64748b;">
                    <span title="Created At">
                      <i class="fa-regular fa-clock"
                        style="color: #94a3b8;"></i>
                      <strong>Created:</strong>
                      {{ $ann->created_at->format('M j, Y h:i A') }}
                    </span>

                    <span title="Publish Date">
                      <i class="fa-solid fa-calendar-check"
                        style="color: {{ $ann->publish_at ? '#025628' : '#94a3b8' }};"></i>
                      <strong>Publishes:</strong>
                      {{ $ann->publish_at ? $ann->publish_at->format('M j, Y h:i A') : 'Immediately' }}
                    </span>

                    <span title="Expiration Date">
                      <i class="fa-solid fa-calendar-xmark"
                        style="color: {{ $ann->expires_at ? '#A32D2D' : '#94a3b8' }};"></i>
                      <strong>Expires:</strong>
                      {{ $ann->expires_at ? $ann->expires_at->format('M j, Y h:i A') : 'Never' }}
                    </span>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div
                  style="display: flex; gap: 8px; flex-shrink: 0; align-self: center;">
                  <button class="btn-view" data-id="{{ $ann->id }}"
                    data-title="{{ $ann->title }}"
                    data-message="{{ $ann->message }}"
                    data-type="{{ $ann->type }}"
                    data-active="{{ $ann->is_active ? '1' : '0' }}"
                    data-publish-at="{{ $ann->publish_at ? $ann->publish_at->format('Y-m-d H:i:s') : '' }}"
                    data-expires-at="{{ $ann->expires_at ? $ann->expires_at->format('Y-m-d H:i:s') : '' }}"
                    onclick="handleEditAnnouncement(this)"
                    style="padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid #025628; background: #025628; color: #fff;">
                    Edit
                  </button>

                  <button
                    onclick="deleteAnnouncement({{ $ann->id }}, this)"
                    style="padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid #fecdd3; background: #fff1f2; color: #9f1239;">
                    Delete
                  </button>
                </div>
              </div>
            @empty
              <div
                style="text-align: center; color: #64748b; padding: 48px 20px; font-size: 13px;">
                <i class="fa-solid fa-bell-slash"
                  style="font-size: 28px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                <strong>No announcements found.</strong>
                <p style="margin: 4px 0 0 0; color: #94a3b8; font-size: 12px;">
                  Try adjusting your filters or click "+ Add Announcement" to
                  create one.</p>
              </div>
            @endforelse
          </div>

          @if ($announcements->total() > 0)
            <div class="pagination-container"
              style="margin-top: 20px; display: flex; justify-content: center; gap: 4px;">
              @if ($announcements->onFirstPage())
                <button class="page-btn" disabled><i
                    class="fa-solid fa-chevron-left"></i></button>
              @else
                <a href="{{ $announcements->previousPageUrl() }}&view=announcements"
                  class="page-btn"><i class="fa-solid fa-chevron-left"></i></a>
              @endif

              <div class="page-numbers" style="display: flex; gap: 4px;">
                @for ($i = 1; $i <= $announcements->lastPage(); $i++)
                  @if ($i == $announcements->currentPage())
                    <button
                      class="page-btn active">{{ $i }}</button>
                  @else
                    <a href="{{ $announcements->url($i) }}&view=announcements"
                      class="page-btn">{{ $i }}</a>
                  @endif
                @endfor
              </div>

              @if ($announcements->hasMorePages())
                <a href="{{ $announcements->nextPageUrl() }}&view=announcements"
                  class="page-btn"><i class="fa-solid fa-chevron-right"></i></a>
              @else
                <button class="page-btn" disabled><i
                    class="fa-solid fa-chevron-right"></i></button>
              @endif
            </div>
          @endif
        </div>

<!-- 9. SETTINGS VIEW -->
<div id="view-settings" style="display: none;">
  <div class="settings-page">

    <div class="settings-header">
      <div>
        <h2>System Settings</h2>
        <p>Manage system-wide configuration, security, and maintenance.</p>
      </div>
      <span class="settings-role">SUPER ADMIN</span>
    </div>

    <!-- SYSTEM CONFIGURATION -->
    <div class="card settings-card">
      <div class="settings-section-heading">
        <div class="settings-icon"><i class="fa-solid fa-sliders"></i></div>
        <div>
          <h3>System Configuration</h3>
          <p>General information about the training management system.</p>
        </div>
      </div>

      <div class="settings-row">
        <div class="settings-info">
          <strong>System Name</strong>
          <p>The name displayed across the application.</p>
        </div>
        <input type="text"
          value="WEB-BASED TRAINING MANAGEMENT FOR
 LOCAL ECONOMIC DEVELOPMENT AND INVESTMENT PROMOTIONS
OFFICER (LEDIPO) DASMARIÑAS"
          class="settings-input" readonly>
      </div>

      <hr class="settings-divider">

      <div class="settings-row">
        <div class="settings-info">
          <strong>Organization</strong>
          <p>Government office managing the training programs.</p>
        </div>
        <input type="text" value="LEDIPO - City of Dasmariñas"
          class="settings-input" readonly>
      </div>

      <hr class="settings-divider">

      <div class="settings-row">
        <div class="settings-info">
          <strong>Passing Score</strong>
          <p>Reference passing percentage for competency assessments.</p>
        </div>
        <span class="settings-value">75%</span>
      </div>

      <hr class="settings-divider">

      <div class="settings-row">
        <div class="settings-info">
          <strong>Default Quiz Attempts</strong>
          <p>Default attempt limit for newly configured quizzes.</p>
        </div>
        <span class="settings-value">2 attempts</span>
      </div>
    </div>

    <!-- ADMINISTRATOR ACCESS -->
    <div class="card settings-card">
      <div class="settings-section-heading">
        <div class="settings-icon"><i class="fa-solid fa-user-shield"></i></div>
        <div>
          <h3>Administrator Access</h3>
          <p>Review administrator roles and access management.</p>
        </div>
      </div>

      <div class="settings-row">
        <div class="settings-info">
          <strong>Admin Accounts</strong>
          <p>Manage authorized administrators and their account status.</p>
        </div>
        <button type="button" class="btn-view"
          onclick="showView('admin-management')">
          <i class="fa-solid fa-users-gear"></i> Manage Accounts
        </button>
      </div>

      <hr class="settings-divider">

      <div class="settings-row">
        <div class="settings-info">
          <strong>Role Permissions</strong>
          <p>Review access boundaries for Super Admin, Admin, and Trainer roles.</p>
        </div>
        <button type="button" class="btn-view"
          onclick="showView('role-management')">
          <i class="fa-solid fa-key"></i> View Permissions
        </button>
      </div>
    </div>

    <!-- SECURITY -->
    <div class="card settings-card">
      <div class="settings-section-heading">
        <div class="settings-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <div>
          <h3>Security</h3>
          <p>Protect administrator accounts and monitor access.</p>
        </div>
      </div>

      <div class="settings-row">
        <div class="settings-info">
          <strong>My Password</strong>
          <p>Update the password for your current account.</p>
        </div>
        <button type="button" class="btn-view"
          onclick="showView('profile')">
          <i class="fa-solid fa-lock"></i> Update Password
        </button>
      </div>

      <hr class="settings-divider">

      <div class="settings-row">
        <div class="settings-info">
          <strong>Login Activity</strong>
          <p>Review successful logins, failed attempts, and logout events.</p>
        </div>
        <button type="button" class="btn-view"
          onclick="showView('login-activity')">
          <i class="fa-solid fa-clock-rotate-left"></i> View Activity
        </button>
      </div>

      <hr class="settings-divider">

      <div class="settings-row">
        <div class="settings-info">
          <strong>Audit Logs</strong>
          <p>Review important administrative actions and system changes.</p>
        </div>
        <button type="button" class="btn-view"
          onclick="showView('audit-logs')">
          <i class="fa-solid fa-clipboard-list"></i> View Audit Logs
        </button>
      </div>
    </div>

<!-- DATABASE BACKUP -->
<div class="card settings-card">
  <div class="settings-section-heading">
    <div class="settings-icon">
      <i class="fa-solid fa-database"></i>
    </div>
    <div>
      <h3>Database Backup</h3>
      <p>Protect training records and system data.</p>
    </div>
  </div>

  <div class="settings-row">
    <div class="settings-info">
      <strong>Manual Backup</strong>
      <p>Database backup functionality for system maintenance.</p>
    </div>

    <button type="button" class="btn-all"
      style="width:auto; padding:10px 20px;">
      <i class="fa-solid fa-download" aria-hidden="true"></i>
      Backup Now
    </button>
  </div>

  <hr class="settings-divider">

  <div class="settings-row">
    <div class="settings-info">
      <strong>Backup History</strong>
      <p>Review previous backup dates, status, and available files.</p>
    </div>

    <button type="button" class="btn-view">
      <i class="fa-solid fa-clock" aria-hidden="true"></i>
      View History
    </button>
  </div>

  <p class="settings-note">
    <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
    Store backup files securely and outside the public web directory.
    Test restoration procedures before relying on a backup.
  </p>
</div>


    <!-- SYSTEM INFORMATION -->
    <div class="card settings-card">
      <div class="settings-section-heading">
        <div class="settings-icon"><i class="fa-solid fa-circle-info"></i></div>
        <div>
          <h3>System Information</h3>
          <p>Technical details for maintenance and troubleshooting.</p>
        </div>
      </div>

      <div class="settings-info-grid">
        <div class="settings-info-item">
          <span>Application</span>
          <strong>Training Management System</strong>
        </div>
        <div class="settings-info-item">
          <span>Framework</span>
          <strong>Laravel</strong>
        </div>
        <div class="settings-info-item">
          <span>Environment</span>
          <strong>Check deployment configuration</strong>
        </div>
        <div class="settings-info-item">
          <span>Database</span>
          <strong>Check active connection</strong>
        </div>
      </div>

      <p class="settings-note">
        Version, environment, and database details should be populated from
        server-side configuration rather than hard-coded values.
      </p>
    </div>

  </div>
</div>
        <!-- 10. CERTIFICATE VIEW -->
        <div id="view-certificate" class="view-panel" style="display: none;">

          <!-- 1. Statistics Cards -->
          <section class="stats-grid" aria-label="Certificate Overview">
            <div class="stat-card">
              <h3 class="stat-number">
                {{ isset($certificates) ? str_pad($certificates->count(), 2, '0', STR_PAD_LEFT) : '00' }}
              </h3>
              <p class="stat-label">Certificates Issued</p>
            </div>
            <div class="stat-card">
              <h3 class="stat-number">
                {{ isset($certificates) ? str_pad($certificates->filter(fn($cert) => strtolower(trim((string) ($cert->status ?? ''))) === 'pending')->count(), 2, '0', STR_PAD_LEFT) : '00' }}
              </h3>
              <p class="stat-label">Pending Release</p>
            </div>
            <div class="stat-card">
              <h3 class="stat-number">
                {{ isset($certificates) ? str_pad($certificates->filter(fn($cert) => strtolower(trim((string) ($cert->status ?? ''))) === 'released')->count(), 2, '0', STR_PAD_LEFT) : '00' }}
              </h3>
              <p class="stat-label">Released</p>
            </div>
            <div class="stat-card urgent">
              <h3 class="stat-number">
                {{ isset($certificates) ? str_pad($certificates->filter(fn($cert) => strtolower(trim((string) ($cert->status ?? ''))) === 'claimed')->count(), 2, '0', STR_PAD_LEFT) : '00' }}
              </h3>
              <p class="stat-label">Claimed</p>
            </div>
          </section>

          <!-- 2. Search, Filters & Batch Selection -->
          <div class="filter-controls">
            <div class="dropdown-group">
              <!-- Live Search Bar -->
              <div class="input-wrapper"
                style="position: relative; min-width: 240px;">
                <i class="fas fa-search"
                  style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #888; font-size: 12px;"
                  aria-hidden="true"></i>
                <input type="text" id="certSearchInput"
                  placeholder="Search trainee or control no..."
                  onkeyup="filterCertTable()" class="filter-dropdown"
                  style="padding-left: 30px; width: 100%; box-sizing: border-box;">
              </div>

              <!-- Dropdown Filters -->
              <label for="filterCourse" class="sr-only">Filter by Course</label>
              <select id="filterCourse" class="filter-dropdown"
                onchange="filterCertTable()" aria-label="Filter by Course">
                <option value="">All Courses</option>
                @if (isset($allCourses))
                  @foreach ($allCourses as $c)
                    <option value="{{ strtolower($c->title) }}">
                      {{ $c->title }}</option>
                  @endforeach
                @endif
              </select>

              <label for="filterMonth" class="sr-only">Filter by Month</label>
              <select id="filterMonth" class="filter-dropdown"
                onchange="filterCertTable()" aria-label="Filter by Month">
                <option value="">All Months</option>
                <option value="january">January</option>
                <option value="february">February</option>
                <option value="march">March</option>
                <option value="april">April</option>
                <option value="may">May</option>
                <option value="june">June</option>
                <option value="july">July</option>
                <option value="august">August</option>
                <option value="september">September</option>
                <option value="october">October</option>
                <option value="november">November</option>
                <option value="december">December</option>
              </select>

              <label for="filterStatus" class="sr-only">Filter by Status</label>
              <select id="filterStatus" class="filter-dropdown"
                onchange="filterCertTable()" aria-label="Filter by Status">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="released">Released</option>
                <option value="claimed">Claimed</option>
              </select>
            </div>

            <div class="selection-group">
              <label class="custom-checkbox" for="toggleMultiple">
                <input type="checkbox" id="toggleMultiple"
                  onchange="toggleMultiSelectMode(this)">
                <span>Select Multiple</span>
              </label>

              <label class="custom-checkbox" for="selectAll">
                <input type="checkbox" id="selectAll"
                  onchange="toggleSelectAllRows(this)">
                <span>Select All</span>
              </label>

              <span id="selectedCertCount" aria-live="polite">
                0 selected
              </span>
            </div>

          </div>

          <!-- 3. Trainee Certificate Records Table -->
          <div class="table-responsive table-outline">
            <table class="trainee-data-table" id="certTable">
              <thead>
                <tr>
                  <th class="select-col hidden" scope="col">
                    <span class="sr-only">Batch Selection</span>
                    <i class="fas fa-check-square" aria-hidden="true"></i>
                  </th>
                  <th scope="col">Full Name</th>
                  <th scope="col">Course</th>
                  <th scope="col">Date Issued</th>
                  <th scope="col">Status</th>
                  <th scope="col" class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody id="certTableBody">
                @forelse(($certificates ?? collect()) as $cert)
                  @php
                    $fullName = ucwords(
                        strtolower(
                            trim(
                                ($cert->user->firstname ?? '') .
                                    ' ' .
                                    ($cert->user->lastname ?? ''),
                            ),
                        ),
                    );

                    if (trim($fullName) === '') {
                        $fullName = 'Registered Trainee';
                    }

                    $courseTitle = $cert->course->title ?? 'General Program';

                    $issueDate = $cert->issue_date
                        ? \Carbon\Carbon::parse($cert->issue_date)->format(
                            'F j, Y',
                        )
                        : 'Not specified';

                    $certificateStatus = strtolower(
                        trim((string) ($cert->status ?? 'pending')),
                    );

                    $certificateRemarks = strtolower(
                        trim((string) ($cert->remarks ?? '')),
                    );

                    // Find the matching enrollment for this trainee and course.
                    // $eligibleTrainees is populated from enrollment_tbls and includes progress.
                    $matchingEnrollment = collect($eligibleTrainees ?? [])->first(
                        fn($trainee) => (int) ($trainee->user_id ?? 0) ===
                            (int) ($cert->user_id ?? ($cert->user->id ?? 0)) &&
                            (int) ($trainee->course_id ?? 0) ===
                                (int) ($cert->course_id ??
                                    ($cert->course->id ?? 0)),
                    );

                    $rawGrade =
                        $matchingEnrollment->progress ??
                        ($matchingEnrollment->calculated_progress ??
                            ($matchingEnrollment->stored_progress ??
                                ($cert->progress ?? null)));

                    $grade = is_numeric($rawGrade) ? (float) $rawGrade : null;

                    $gradeDisplay =
                        $grade !== null
                            ? rtrim(
                                    rtrim(number_format($grade, 2, '.', ''), '0'),
                                    '.',
                                ) . '%'
                            : 'N/A';

                    $competencyStatus =
                        $grade !== null
                            ? ($grade >= 75
                                ? 'Competent'
                                : 'Not Yet Competent')
                            : 'Grade unavailable';

                    $isClaimed = $certificateStatus === 'claimed';

                    // Only competent trainees can receive a certificate.
                    $canReleaseCertificate =
                        $competencyStatus === 'Competent' &&
                        !in_array(
                            $certificateStatus,
                            ['released', 'claimed'],
                            true,
                        );
                  @endphp

                  <tr data-cert-id="{{ $cert->id }}"
                    data-cert-no="{{ $cert->certificate_no ?? '' }}"
                    data-cert-course="{{ strtolower($courseTitle) }}"
                    data-cert-date="{{ $cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('Y-m-d') : '' }}"
                    data-cert-status="{{ $certificateStatus }}">

                    <td class="select-col hidden">
                      <input type="checkbox" class="row-checkbox"
                        value="{{ $cert->id }}"
                        aria-label="Select {{ $fullName }}">
                    </td>

                    <td class="font-medium">{{ $fullName }}</td>
                    <td>{{ $courseTitle }}</td>
                    <td>{{ $issueDate }}</td>

                    <td>
                      <span
                        class="badge badge-toggle {{ $isClaimed ? 'badge-success' : 'badge-warning' }}"
                        onclick="toggleCertStatus(this, {{ $cert->id }})"
                        style="cursor: pointer;" title="Click to toggle status">
                        <i class="fas {{ $isClaimed ? 'fa-check-circle' : 'fa-clock' }}"
                          aria-hidden="true"></i>
                        <span
                          class="status-label">{{ ucfirst($certificateStatus) }}</span>
                      </span>
                    </td>

                    <td class="action-icons">
                      <!-- View Certificate -->
                      <button type="button" class="btn-icon"
                        title="View Certificate"
                        aria-label="View certificate for {{ $fullName }}"
                        onclick="openCertModal(
          {{ Js::from($fullName) }},
          {{ Js::from($courseTitle) }},
          {{ Js::from($cert->certificate_no ?? '') }},
          {{ Js::from($issueDate) }},
          {{ Js::from($competencyStatus) }},
          {{ Js::from($gradeDisplay) }},
          {{ Js::from($cert->document_type ?? 'completion') }},
          {{ Js::from($cert->training_id ?? '') }}
      )">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                        <span class="sr-only">View Certificate</span>
                      </button>

                      <!-- Release Certificate -->
                      <button type="button"
                        class="btn-icon certificate-release-btn"
                        title="{{ $canReleaseCertificate ? 'Release Certificate' : ($certificateRemarks !== 'competent' ? 'Only competent trainees can receive a certificate' : 'Certificate already released or claimed') }}"
                        aria-label="Release certificate for {{ $fullName }}"
                        data-remarks="{{ $certificateRemarks }}"
                        data-status="{{ $certificateStatus }}"
                        onclick="releaseCertificate(this, {{ $cert->id }})"
                        {{ $canReleaseCertificate ? '' : 'disabled' }}
                        style="color: #198754; {{ $canReleaseCertificate ? '' : 'opacity: .45; cursor: not-allowed;' }}">
                        <i class="fas fa-paper-plane" aria-hidden="true"></i>
                        <span class="sr-only">Release Certificate</span>
                      </button>

                      <!-- Delete Record -->
                      <button type="button" class="btn-icon btn-danger"
                        title="Delete Record"
                        aria-label="Delete certificate record for {{ $fullName }}"
                        onclick="deleteCert(this, {{ $cert->id }})">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                        <span class="sr-only">Delete Record</span>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr id="emptyCertRow">
                    <td colspan="6"
                      style="text-align: center; color: #888; padding: 36px 16px; font-size: 13px;">
                      <i class="fas fa-certificate"
                        style="font-size: 26px; display: block; margin-bottom: 8px; color: #ccc;"
                        aria-hidden="true"></i>
                      No certificates issued yet. Click "Issue New Certificate" to
                      create one.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>

            <!-- Empty Results Fallback -->
            <div id="noCertResults"
              style="display: none; text-align: center; color: #888; padding: 32px 16px; font-size: 13px;">
              <i class="fas fa-award"
                style="font-size: 24px; display: block; margin-bottom: 8px; color: #ccc;"
                aria-hidden="true"></i>
              No matching certificate records found.
            </div>
          </div>

          <!-- 4. Action Footer -->
          <footer class="action-footer">
            <button type="button" class="btn btn-primary"
              onclick="openAddModal()">
              <i class="fas fa-plus-square" aria-hidden="true"></i>
              <span>Issue New Certificate</span>
            </button>
          </footer>

        </div>
      </main>
    </div>

    <!-- ========================================================== -->
    <!-- MODALS SECTION                                             -->
    <!-- ========================================================== -->

    <!-- ==========================================
      1. CERTIFICATE VIEW MODAL (DYNAMIC DATA)
  ========================================== -->
    <div id="certificateModal" class="modal-overlay" role="dialog"
      aria-modal="true" aria-labelledby="viewModalTitle"
      style="display: none;">
      <div class="modal-box-fixed">
        <div class="modal-split">

          <!-- Left: Certificate Preview Frame -->
          <div class="split-left-preview">
            <h3 class="modal-section-header">Certificate Preview</h3>

            <div class="ui-cert-frame" id="printableCert">
              <div class="ui-cert-inner">
                <header class="cert-logos-header">
                  <img src="{{ asset('images/logo.png') }}"
                    alt="City Government Logo" class="cert-logo-img">
                  <img src="{{ asset('images/tesda.png') }}" alt="TESDA Logo"
                    class="cert-logo-img">
                  <img src="{{ asset('images/logo_ledipo.png') }}"
                    alt="LEDIPO Logo" class="cert-logo-img">
                </header>

                <p class="cert-authority-text">
                  TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY<br>
                  CITY GOVERNMENT OF DASMARIÑAS — LEDIPO
                </p>

                <h1 class="cert-title-primary" id="vDocTitle">CERTIFICATE OF
                  COMPLETION</h1>
                <p class="cert-certify-line">THIS CERTIFIES THAT</p>
                <h2 id="vName" class="cert-recipient-name">[Recipient Name]
                </h2>

                <p class="cert-training-msg">HAS SUCCESSFULLY COMPLETED THE
                  TRAINING IN</p>
                <h3 id="vCourse" class="cert-course-name">[Course Name]</h3>

                <div class="cert-signatures">
                  <div class="sig-item">
                    <p class="sig-name">HON. JENNIFER A. BARZAGA</p>
                    <p class="sig-rank">City Mayor</p>
                  </div>
                  <div class="sig-item">
                    <p class="sig-name">MR. CARLOS H. LEGASPI</p>
                    <p class="sig-rank">LEDIPO Head</p>
                  </div>
                </div>

                <footer class="cert-serial-footer">
                  <span id="vID">CERT. NO.: [ID]</span>
                  <span id="vTrainingID">TRAINING ID: —</span>
                </footer>
              </div>
            </div>
          </div>

          <!-- Right: Certificate Details & Actions -->
          <div class="split-right-info">
            <div
              style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
              <h2 id="viewModalTitle" class="modal-title" style="margin: 0;">
                Certificate Details</h2>
              <span id="vStatusBadge" class="badge badge-success">Pending</span>
            </div>

            <div class="info-block">
              <span class="info-label">Issue Date &amp; Record ID</span>
              <p class="info-value"
                style="font-size: 13px; margin: 0; color: #333;">
                <i class="fa-regular fa-calendar-check"
                  style="color: #025628; margin-right: 4px;"></i>
                <span id="vIssueDate">-</span> &bull; <strong
                  id="vControlNo">-</strong>
              </p>
            </div>

            <div class="info-block">
              <span class="info-label">Trainee Performance</span>
              <p id="vGrade" class="info-value">
                Select a trainee record
              </p>
            </div>

            <div class="info-block">
              <span class="info-label">Official Signatories</span>
              <ul class="sig-list">
                <li><i class="fas fa-check-circle" aria-hidden="true"></i> Hon.
                  Jennifer Austria-Barzaga</li>
                <li><i class="fas fa-check-circle" aria-hidden="true"></i> Mr.
                  Carlos H. Legaspi</li>
              </ul>
            </div>

            <div class="modal-actions-container"
              style="margin-top: auto; display: flex; flex-direction: column; gap: 8px;">
              <button type="button" class="btn btn-primary"
                onclick="handleDownload('printableCert')">
                <i class="fas fa-file-pdf" aria-hidden="true"></i> Download PDF
              </button>
              <button type="button" class="btn btn-print"
                onclick="handlePrint()">
                <i class="fas fa-print" aria-hidden="true"></i> Print
                Certificate
              </button>
              <button type="button" class="btn btn-secondary"
                onclick="closeCertModal()">
                Close View
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- ==========================================
      2. ISSUE NEW CERTIFICATE MODAL
  ========================================== -->
    <div id="addTraineeModal" class="modal-overlay" role="dialog"
      aria-modal="true" aria-labelledby="issueModalTitle"
      style="display: none;">
      <div class="modal-box-fixed">
        <div class="modal-split">

          <!-- Left: Issue Form -->
          <div class="split-right-info border-right">
            <h2 id="issueModalTitle" class="modal-title">Issue New Certificate
            </h2>

            <form id="issueForm" autocomplete="off"
              onsubmit="event.preventDefault(); submitIssueCertificate();">

              <!-- 1. Trainee Selection with Dynamic Database Course Filter -->
              <fieldset class="ui-form-group">
                <label class="form-label">1. Trainee Selection</label>

                <!-- Dynamic Course Filter Select -->
                <select class="form-control mb-2" id="modalCourseFilter"
                  onchange="filterModalTrainees(this.value)"
                  style="border-left: 3px solid #025628; background-color: #f8faf9;">
                  <option value="">-- All Courses (Show All Trainees) --
                  </option>
                  @if (isset($allCourses) && count($allCourses) > 0)
                    @foreach ($allCourses as $crs)
                      <option value="{{ $crs->title }}">
                        {{ $crs->title }}
                      </option>
                    @endforeach
                  @elseif (isset($courses) && count($courses) > 0)
                    @foreach ($courses as $crs)
                      <option value="{{ $crs->title }}">
                        {{ $crs->title }}
                      </option>
                    @endforeach
                  @endif
                </select>

                <!-- Dynamic Trainees Dropdown -->
                <select class="form-control" id="traineeSelect"
                  name="trainee_id"
                  onchange="updateLivePreview(); updateCompetencySummary()"
                  required>
                  <option value="" disabled selected>Search / Select
                    Trainee...</option>
                  @if (isset($eligibleTrainees) && count($eligibleTrainees) > 0)
                    @foreach ($eligibleTrainees as $trainee)
                      @php
                        $traineeName = trim(
                            ($trainee->firstname ?? '') .
                                ' ' .
                                ($trainee->lastname ?? ''),
                        );
                        $traineeCourse =
                            $trainee->course_title ?? 'General Training';
                      @endphp

                      @php
                        // Prefer explicit IDs from the controller query. Older query results may expose user_tbls.id as "id".
                        $traineeUserId =
                            $trainee->user_id ?? ($trainee->id ?? '');
                        $traineeEnrollmentId = $trainee->enrollment_id ?? '';
                      @endphp
                      <option value="{{ $traineeUserId }}"
                        data-enrollment-id="{{ $traineeEnrollmentId }}"
                        data-user-id="{{ $traineeUserId }}"
                        data-name="{{ trim(($trainee->firstname ?? '') . ' ' . ($trainee->lastname ?? '')) }}"
                        data-course="{{ $trainee->course_title ?? 'General Training' }}"
                        data-course-id="{{ $trainee->course_id }}"
                        data-training-id="{{ $trainee->training_id ?? '' }}"
                        data-progress="{{ $trainee->progress ?? ($trainee->stored_progress ?? 0) }}"
                        data-remarks="{{ $trainee->competency_remarks ?? 'Not Yet Competent' }}">
                        {{ $trainee->lastname ?? '' }},
                        {{ $trainee->firstname ?? '' }}
                        — {{ $trainee->course_title ?? 'General Training' }}
                        ({{ number_format((float) ($trainee->progress ?? ($trainee->stored_progress ?? 0)), 1) }}%
                        —
                        {{ $trainee->competency_remarks ?? 'Not Yet Competent' }})
                      </option>
                    @endforeach
                  @else
                    <option value="" disabled>No enrolled trainees found
                      in
                      database.</option>
                  @endif
                </select>
              </fieldset>

              <!-- 2. Record Details -->
              <fieldset class="ui-form-group">
                <label for="certIDInput" class="form-label">2. Record
                  Details</label>
                <input type="text" id="certIDInput" name="certificate_no"
                  class="form-control" autocomplete="off"
                  placeholder="Generated automatically by course" readonly
                  required>
                <input type="date" id="issueDateInput" name="issue_date"
                  class="form-control mt-2" value="{{ date('Y-m-d') }}">
              </fieldset>

              <!-- 3. Document Options -->
              <fieldset class="ui-form-group">
                <label for="docTypeSelect" class="form-label">3. Document
                  Options</label>
                <select class="form-control" id="docTypeSelect"
                  name="document_type" onchange="updateLivePreview()">
                  <option value="completion" selected>
                    Certificate of Completion
                  </option>
                </select>
                <!-- Competency is calculated from enrollment progress, not typed manually. -->
                <div id="competencySummary"
                  class="competency-summary is-empty" role="status"
                  aria-live="polite">
                  <div class="competency-summary-icon"><i
                      class="fa-solid fa-circle-info"></i></div>
                  <div class="competency-summary-content">
                    <strong id="competencyStatusText">Select a trainee to check
                      eligibility</strong>
                    <span id="competencyProgressText">Required progress:
                      75%</span>
                    <small id="competencyHelpText">Competency remarks are
                      assigned automatically.</small>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>

          <!-- Right: Live Preview Panel -->
          <div class="split-left-preview bg-preview">
            <h3 class="modal-section-header">Live Preview</h3>

            <div class="ui-cert-frame" id="livePreviewCert">
              <div class="ui-cert-inner">
                <header class="cert-logos-header">
                  <img src="{{ asset('images/logo.png') }}"
                    alt="City Government Logo" class="cert-logo-img">
                  <img src="{{ asset('images/tesda.png') }}"
                    alt="TESDA Logo" class="cert-logo-img">
                  <img src="{{ asset('images/logo_ledipo.png') }}"
                    alt="LEDIPO Logo" class="cert-logo-img">
                </header>

                <p class="cert-authority-text">
                  TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY<br>
                  CITY GOVERNMENT OF DASMARIÑAS — LEDIPO
                </p>

                <h1 class="cert-title-primary" id="pDocTitle">CERTIFICATE OF
                  COMPLETION</h1>
                <p class="cert-certify-line">THIS CERTIFIES THAT</p>
                <h2 id="pName" class="cert-recipient-name">[Recipient
                  Name]</h2>

                <p class="cert-training-msg">HAS SUCCESSFULLY COMPLETED THE
                  TRAINING IN</p>
                <h3 id="pCourse" class="cert-course-name">[Course Name]</h3>

                <div class="cert-signatures">
                  <div class="sig-item">
                    <p class="sig-name">HON. JENNIFER A. BARZAGA</p>
                    <p class="sig-rank">City Mayor</p>
                  </div>
                  <div class="sig-item">
                    <p class="sig-name">MR. CARLOS H. LEGASPI</p>
                    <p class="sig-rank">LEDIPO Head</p>
                  </div>
                </div>

                <footer class="cert-serial-footer">
                  <span id="pID">CERT. NO.: [ID]</span>
                  <span id="pTrainingID">TRAINING ID: —</span>
                </footer>
              </div>
            </div>

            <div class="modal-actions-container mt-4"
              style="display: flex; flex-direction: column; gap: 8px;">
              <button type="button" class="btn btn-primary w-full"
                onclick="submitIssueCertificate()">
                <i class="fas fa-check" aria-hidden="true"></i> Save &amp;
                Issue
              </button>
              <button type="button" class="btn btn-pdf w-full"
                onclick="handleDownload('livePreviewCert')">
                <i class="fas fa-file-pdf" aria-hidden="true"></i> Download
                PDF
              </button>
              <button type="button" class="btn btn-secondary w-full"
                onclick="closeAddModal()">
                Cancel
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Logout Modal Alternative Overlay -->
    <div id="logoutModalOverlay" class="modal-overlay"
      style="display:none;">
      <div class="modal-box">
        <p>Are you sure you want to log out?</p>
        <div class="modal-actions-centered">
          <a href="login.php" class="btn-modal-yes">Yes</a>
          <button type="button" class="btn-modal-cancel"
            onclick="hideLogoutModal()">Cancel</button>
        </div>
      </div>
    </div>

    <!-- Course Modal -->
    <div id="courseModal" class="modal">
      <div class="modal-content card">

        <!-- Modal Header -->
        <div class="modal-header">
          <h3>
            <i class="fa-solid fa-pen-to-square"></i>
            Manage Course
          </h3>

          <span class="close-modal" onclick="closeModal()">&times;</span>
        </div>

        <form id="courseForm" class="modal-body" method="POST"
          action="">
          @csrf

          <input type="hidden" id="editCourseId" name="id">
          <input type="hidden" id="editTrainerId" name="trainer_id">

          <!-- Row 1: Course Code and Status -->
          <div class="modal-row">
            <div class="input-field">
              <label for="editCourseCode">Course Code</label>

              <div class="input-wrapper">
                <i class="fa-solid fa-barcode"></i>

                <input type="text" id="editCourseCode" name="course_code"
                  placeholder="e.g. CRS-001" required>
              </div>
            </div>

            <div class="input-field">
              <label for="editStatus">Course Status</label>

              <div class="input-wrapper">
                <i class="fa-solid fa-circle-check"></i>

                <select id="editStatus" name="status"
                  class="modal-input-select">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Row 2: Course Name -->
          <div class="input-field">
            <label for="editCourseName">Course Name</label>

            <div class="input-wrapper">
              <i class="fa-solid fa-bookmark"></i>

              <input type="text" id="editCourseName" name="title"
                placeholder="e.g. Computer Literacy" required>
            </div>
          </div>

          <!-- Row 3: Duration and Slots -->
          <div class="modal-row">
            <div class="input-field">
              <label for="editDuration">Course Duration</label>

              <div class="input-wrapper input-with-suffix">
                <i class="fa-solid fa-calendar-day"></i>

                <input type="number" id="editDuration" name="duration"
                  min="1" max="365" placeholder="e.g. 15"
                  required>

                <span class="input-suffix">Days</span>
              </div>
            </div>

            <div class="input-field">
              <label for="editSlots">Available Slots</label>

              <div class="input-wrapper">
                <i class="fa-solid fa-user-graduate"></i>

                <input type="number" id="editSlots" name="slots"
                  min="0" placeholder="e.g. 30" required>
              </div>
            </div>
          </div>

          <!-- Row 4: Training Date Range -->
          <div class="modal-row">
            <div class="input-field">
              <label for="editStartDate">Training Start Date</label>

              <div class="input-wrapper">
                <i class="fa-solid fa-calendar-plus"></i>

                <input type="date" id="editStartDate" name="start_date">
              </div>
            </div>

            <div class="input-field">
              <label for="editEndDate">Training End Date</label>

              <div class="input-wrapper">
                <i class="fa-solid fa-calendar-check"></i>

                <input type="date" id="editEndDate" name="end_date">
              </div>
            </div>
          </div>

          <!-- Row 5: Training Days -->
          <div class="input-field training-days-field">
            <label>Training Days</label>

            <small class="training-days-hint">
              Select the weekdays when the onsite training takes place.
            </small>

            <div class="training-days-grid">
              <label class="training-day-option">
                <input type="checkbox" name="training_days[]"
                  value="Monday">
                <span>Mon</span>
              </label>

              <label class="training-day-option">
                <input type="checkbox" name="training_days[]"
                  value="Tuesday">
                <span>Tue</span>
              </label>

              <label class="training-day-option">
                <input type="checkbox" name="training_days[]"
                  value="Wednesday">
                <span>Wed</span>
              </label>

              <label class="training-day-option">
                <input type="checkbox" name="training_days[]"
                  value="Thursday">
                <span>Thu</span>
              </label>

              <label class="training-day-option">
                <input type="checkbox" name="training_days[]"
                  value="Friday">
                <span>Fri</span>
              </label>

              <label class="training-day-option">
                <input type="checkbox" name="training_days[]"
                  value="Saturday">
                <span>Sat</span>
              </label>

              <label class="training-day-option">
                <input type="checkbox" name="training_days[]"
                  value="Sunday">
                <span>Sun</span>
              </label>
            </div>
          </div>

          <!-- Row 6: Training Time -->
          <div class="training-time-section">

            <div class="training-time-heading">
              <div class="training-time-icon">
                <i class="fa-regular fa-clock"></i>
              </div>

              <div>
                <h4>Training Hours</h4>

                <p>
                  Set the daily start and end times for this course.
                </p>
              </div>
            </div>

            <div class="modal-row">
              <div class="input-field">
                <label for="editStartTime">Start Time</label>

                <div class="input-wrapper">
                  <i class="fa-solid fa-clock"></i>

                  <input type="time" id="editStartTime" name="start_time"
                    required>
                </div>
              </div>

              <div class="input-field">
                <label for="editEndTime">End Time</label>

                <div class="input-wrapper">
                  <i class="fa-solid fa-clock"></i>

                  <input type="time" id="editEndTime" name="end_time"
                    required>
                </div>
              </div>
            </div>

            <div class="training-time-note">
              <i class="fa-solid fa-circle-info"></i>

              <span>
                Example: 8:00 AM to 12:00 PM. These hours apply to
                the selected training days.
              </span>
            </div>
          </div>

          <!-- Row 7: Assign Trainer -->
          <div class="assign-trainer-section">
            <div class="assign-trainer-label">
              <i class="fa-solid fa-chalkboard-user"></i>
              Assign Trainer
            </div>

            <div class="assign-trainer-row">
              <select id="trainerDropdown">
                <option value="">— Select a trainer —</option>

                @foreach ($trainers as $trainer)
                  <option value="{{ $trainer->id }}">
                    {{ $trainer->firstname }} {{ $trainer->lastname }}
                  </option>
                @endforeach
              </select>

              <button type="button" class="btn-assign"
                onclick="assignTrainer()">
                <i class="fa-solid fa-check"></i>
                Assign
              </button>
            </div>

            <div id="currentTrainerBox" style="display:none;"
              class="current-trainer-box">
              <div class="trainer-avatar-sm" id="trainerInitials">JD</div>

              <div class="trainer-details">
                <div class="trainer-fullname" id="trainerFullName"></div>

                <div class="trainer-sub">
                  Currently assigned trainer
                </div>
              </div>

              <button type="button" class="btn-remove-trainer"
                onclick="removeTrainer()">
                <i class="fa-solid fa-xmark"></i>
                Remove
              </button>
            </div>

            <div id="noTrainerBox" class="no-trainer-box">
              <i class="fa-solid fa-circle-info"></i>
              No trainer assigned yet.
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn-delete-text"
              onclick="confirmDelete()">
              <i class="fa-solid fa-trash"></i>
              Delete Course
            </button>

            <div class="action-buttons">
              <button type="button" class="btn-cancel"
                onclick="closeModal()">
                Cancel
              </button>

              <button type="submit" class="btn-save-main">
                <i class="fa-solid fa-floppy-disk"></i>
                Save Changes
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>

    <!-- Announcement Modal -->
    <div id="announcementModal" class="modal" style="display: none;">
      <div class="modal-content card">
        <div class="modal-header">
          <h3 id="annModalTitle"><i class="fa-solid fa-bell"></i> Add
            Announcement</h3>
          <span class="close-modal"
            onclick="closeAnnouncementModal()">&times;</span>
        </div>

        <form id="announcementForm" class="modal-body">
          <input type="hidden" id="annId">

          <!-- Title Field -->
          <div class="form-group">
            <div class="label-row">
              <label for="annTitle">Title <span
                  class="required">*</span></label>
              <span class="char-counter" id="titleCounter">0/100</span>
            </div>
            <div class="input-container">
              <i class="fa-solid fa-pen input-icon"></i>
              <input type="text" id="annTitle" maxlength="100"
                placeholder="e.g., System Maintenance Schedule" required>
            </div>
          </div>

          <!-- Message Field -->
          <div class="form-group">
            <div class="label-row">
              <label for="annMessage">Message <span
                  class="required">*</span></label>
              <span class="char-counter" id="messageCounter">0/500</span>
            </div>
            <div class="input-container textarea-container">
              <i class="fa-solid fa-align-left input-icon textarea-icon"></i>
              <textarea id="annMessage" maxlength="500"
                placeholder="Enter detailed announcement message..." required></textarea>
            </div>
          </div>

          <!-- Row 1: Type & Status (Kill Switch) -->
          <div class="form-row">
            <div class="form-group flex-1">
              <label for="annType">Type <span
                  class="required">*</span></label>
              <div class="input-container">
                <i class="fa-solid fa-tag input-icon"></i>
                <select id="annType" class="modal-input-select">
                  <option value="reminder">Reminder</option>
                  <option value="notice">Notice</option>
                  <option value="urgent">Urgent</option>
                </select>
              </div>
            </div>

            <div class="form-group status-group">
              <label>Status Switch</label>
              <label class="checkbox-card">
                <input type="checkbox" id="annIsActive" checked>
                <span class="checkbox-text" id="statusLabel">Active</span>
              </label>
            </div>
          </div>

          <!-- Row 2: Schedule & Expiration Timestamps -->
          <div class="form-row">
            <div class="form-group flex-1">
              <div class="label-row">
                <label for="annPublishAt">Publish At <small
                    style="color:#888;">(Optional)</small></label>
              </div>
              <div class="input-container datetime-container">
                <input type="datetime-local" id="annPublishAt"
                  class="datetime-input">
              </div>
            </div>

            <div class="form-group flex-1">
              <div class="label-row">
                <label for="annExpiresAt">Expires At <small
                    style="color:#888;">(Optional)</small></label>
              </div>
              <div class="input-container datetime-container">
                <input type="datetime-local" id="annExpiresAt"
                  class="datetime-input">
              </div>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn-cancel"
              onclick="closeAnnouncementModal()">Cancel</button>
            <button type="submit" id="btnSubmitAnn"
              class="btn-save-main">Save</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Add Trainer Modal -->
    <div id="addTrainerModal" class="modal">

      <div class="modal-content card">
        <div class="modal-header">
          <h3><i class="fa-solid fa-user-plus" style="margin-right: 6px;"></i>
            Register New Trainer</h3>
          <span class="close-modal"
            onclick="closeAddTrainerModal()">&times;</span>
        </div>
        <form id="addTrainerForm" class="modal-body">
          <div class="input-field">
            <label>Full Name</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-signature"></i>
              <input type="text" id="newTrainerName"
                placeholder="e.g. Juan Dela Cruz" required>
            </div>
          </div>

          <div class="modal-row">
            <div class="input-field">
              <label>Email Address</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="newTrainerEmail"
                  placeholder="trainer@example.com" required>
              </div>
            </div>
            <div class="input-field">
              <label>Contact Number (Optional)</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-phone"></i>
                <input type="text" id="newTrainerContact"
                  placeholder="0912 345 6789">
              </div>
            </div>
          </div>

          <div class="modal-row">
            <div class="input-field">
              <label>Temporary Password</label>
              <div style="position: relative; width: 100%;">
                <i class="fa-solid fa-key"
                  style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #6b7280; z-index: 3; pointer-events: none;"></i>
                <input type="password" id="newTrainerPass"
                  placeholder="e.g. Welcome2026"
                  style="width: 100%; height: 42px; padding-left: 38px; padding-right: 42px; border: 1px solid #d1d5db !important; border-radius: 8px !important; background-color: #ffffff !important; box-sizing: border-box; font-family: inherit; font-size: 13px;"
                  required>
                <i class="fa-solid fa-eye" id="togglePasswordIcon"
                  onclick="togglePassword()"
                  style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6b7280; z-index: 3;"></i>
              </div>
            </div>
            <div class="input-field">
              <label>Reference / ID Number</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-id-card"></i>
                <input type="text" id="newTrainerIdNum"
                  placeholder="e.g. TR-2026-001">
              </div>
            </div>
          </div>

          <div class="input-field">
            <label>Assigned Course</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-book-open"></i>
              <select id="newTrainerCourse" class="modal-input-select">
                <option value="">— Select a course —</option>
                @foreach ($allCourses as $course)
                  <option value="{{ $course->id }}">{{ $course->title }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="input-field" style="margin-top: 4px;">
            <label>Admin Remarks / Internal Notes</label>
            <textarea id="newTrainerRemarks"
              placeholder="Add confidential notes or operational remarks regarding this profile..."
              rows="2"
              style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; font-size:13px; font-family:inherit; resize:vertical;"></textarea>
          </div>

          <div class="modal-footer"
            style="margin-top: 16px; padding-bottom: 5px;">
            <button type="button" class="btn-cancel"
              onclick="closeAddTrainerModal(); return false;">Cancel</button>
            <button type="submit" class="btn-save-main">Create
              Account</button>
          </div>
        </form>
      </div>
    </div>

    <!-- User Profile Modal -->
    <div id="userModal" class="modal">

      <div class="modal-content card" style="max-width: 600px; width: 95%;">
        <div class="modal-header">
          <h3><i class="fa-solid fa-user-gear"></i> Manage User Profile</h3>
          <span class="close-modal" onclick="closeUserModal()">&times;</span>
        </div>

        <form id="userForm" class="modal-body">
          <!-- Hidden User ID Field (Critical for deleteUser & update AJAX) -->
          <input type="hidden" id="editUserId" name="id"
            value="">

          <!-- Full Name -->
          <div class="input-field">
            <label for="editUserName">Full Name</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-signature"></i>
              <input type="text" id="editUserName" name="name"
                placeholder="Full Name" required>
            </div>
          </div>

          <!-- Email & Member Since -->
          <div class="modal-row">
            <div class="input-field">
              <label for="editUserEmail">Email Address</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="editUserEmail" name="email"
                  readonly class="readonly-input">
              </div>
            </div>

            <div class="input-field">
              <label for="editUserCreated">Member Since</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-calendar-days"></i>
                <input type="text" id="editUserCreated" readonly
                  class="readonly-input" style="background: #f9f9f9;">
              </div>
            </div>
          </div>

          <!-- Contact & Reference / ID Number -->
          <div id="trainerFieldsContainer" style="display: none;">
            <div class="modal-row">
              <div class="input-field">
                <label for="editUserContact">Contact Number</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-phone"></i>
                  <input type="text" id="editUserContact" name="contact"
                    maxlength="11"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                    placeholder="09XXXXXXXXX">
                </div>
              </div>

              <div class="input-field">
                <label for="editUserIdNum">Reference / ID Number</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-id-card"></i>
                  <input type="text" id="editUserIdNum" name="id_number"
                    placeholder="N/A">
                </div>
              </div>
            </div>
          </div>

          <!-- Assigned Course -->
          <div class="input-field" id="trainerCourseField"
            style="display: none;">
            <label for="editTrainerCourse">Assigned Course (Teaching)</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-book-open"></i>
              <input type="text" id="editTrainerCourse" readonly
                class="readonly-input" style="background: #f9f9f9;">
            </div>
          </div>

          <!-- Role & Status -->
          <div class="modal-row">
            <div class="input-field">
              <label for="editUserRole">Account Role</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-user-tag"></i>
                <select id="editUserRole" class="modal-input-select" disabled
                  style="background: #f5f5f5; cursor: not-allowed;">
                  <option value="student">Trainee</option>
                  <option value="trainer">Trainer</option>
                  <option value="admin">Admin</option>
                </select>
                <input type="hidden" name="role" id="hiddenUserRole"
                  value="">
              </div>
            </div>

            <div class="input-field">
              <label for="editUserStatus">Status</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-circle-check"></i>
                <select id="editUserStatus" name="status"
                  class="modal-input-select">
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                  <option value="Pending">Pending</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Admin Remarks -->
          <div class="input-field" style="margin-top: 4px;">
            <label for="editUserRemarks">Admin Remarks / Internal Notes</label>
            <textarea id="editUserRemarks" name="remarks"
              placeholder="Add confidential notes or operational remarks regarding this profile..."
              rows="2"
              style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px; font-family: inherit; resize: vertical; box-sizing: border-box;"></textarea>
          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn-delete-text"
              onclick="deleteUser()">
              <i class="fa-solid fa-user-slash"></i> Remove User
            </button>
            <div class="action-buttons">
              <button type="button" class="btn-cancel"
                onclick="closeUserModal();">Cancel</button>
              <button type="submit" class="btn-save-main">Update
                User</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Facility Modal -->
    <div id="facilityModal" class="modal"
      style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px;">

      <div class="modal-content card"
        style="background: #ffffff; width: 100%; max-width: 460px; max-height: 88vh; border-radius: 16px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">

        <!-- Header -->
        <div class="modal-header"
          style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0f0f0; padding: 14px 20px; background: #fff; flex-shrink: 0;">
          <h3 id="facilityModalTitle"
            style="margin: 0; font-size: 17px; color: #025628; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-building-circle-gear"></i> Manage Facility
          </h3>
          <span class="close-modal" onclick="closeFacilityModal()"
            style="font-size: 20px; cursor: pointer; color: #888; line-height: 1;">&times;</span>
        </div>

        <!-- Body Form -->
        <form id="facilityForm" class="modal-body"
          style="overflow-y: auto; padding: 16px 20px; display: flex; flex-direction: column; gap: 12px; flex-grow: 1;">

          <input type="hidden" id="editFacId" name="id"
            value="">

          <div class="input-field" style="margin: 0;">
            <label
              style="display: block; font-size: 11px; font-weight: 600; color: #4b5563; text-align: left; margin-bottom: 4px;">
              Facility / Center Name
            </label>
            <div class="input-wrapper"
              style="display: flex; align-items: center; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; background: #fff;">
              <i class="fa-solid fa-hotel"
                style="color: #6b9e7c; margin-right: 8px; font-size: 13px;"></i>
              <input type="text" id="editFacName" required
                placeholder="e.g. LEDIPO Main"
                style="border: none; outline: none; width: 100%; font-size: 13px; background: transparent;">
            </div>
          </div>

          <div class="input-field" style="margin: 0;">
            <label
              style="display: block; font-size: 11px; font-weight: 600; color: #4b5563; text-align: left; margin-bottom: 4px;">
              Full Address
            </label>
            <div class="input-wrapper"
              style="display: flex; align-items: center; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; background: #fff;">
              <i class="fa-solid fa-location-dot"
                style="color: #6b9e7c; margin-right: 8px; font-size: 13px;"></i>
              <input type="text" id="editFacAddress" required
                placeholder="Zone 4, Dasmariñas, Cavite"
                style="border: none; outline: none; width: 100%; font-size: 13px; background: transparent;">
            </div>
          </div>

          <div class="input-field" style="margin: 0;">
            <div
              style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
              <label
                style="font-size: 11px; font-weight: 600; color: #1f2937; display: flex; align-items: center; gap: 6px; margin: 0;">
                <i class="fa-solid fa-book-open-reader"
                  style="color: #025628;"></i> Assigned Courses
                <span id="selectedCourseBadge"
                  style="background: #e8f5e9; color: #025628; font-size: 10px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">
                  0 Selected
                </span>
              </label>
              <button type="button" onclick="toggleSelectAllCourses()"
                style="background: none; border: none; color: #025628; font-size: 11px; font-weight: 600; cursor: pointer; text-decoration: underline; padding: 0;">
                Select All
              </button>
            </div>

            <div id="facilityCoursesContainer"
              style="max-height: 120px; overflow-y: auto; border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 10px; background: #ffffff; display: flex; flex-direction: column; gap: 6px;">
              @foreach ($allCourses as $course)
                <label
                  style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #1f2937; cursor: pointer; user-select: none;">
                  <input type="checkbox" name="courses[]"
                    value="{{ $course->id }}" class="facility-course-cb"
                    onchange="updateCourseBadgeCount()"
                    style="width: 15px; height: 15px; accent-color: #025628; cursor: pointer;">
                  <span>{{ $course->title }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <div class="modal-footer"
            style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f0f0f0; padding-top: 12px; margin-top: 4px; flex-shrink: 0;">
            <button type="button" class="btn-delete-text"
              onclick="deleteFacility()"
              style="background: none; border: none; color: #dc2626; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px; padding: 0;">
              <i class="fa-solid fa-trash-can"></i> Delete Facility
            </button>
            <div class="action-buttons" style="display: flex; gap: 8px;">
              <button type="button" onclick="closeFacilityModal()"
                style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; padding: 7px 14px; border-radius: 6px; font-weight: 600; font-size: 12px; cursor: pointer;">
                Cancel
              </button>
              <button type="submit" class="btn-save-main"
                style="background: #025628; color: #fff; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 600; font-size: 12px; cursor: pointer;">
                Save Changes
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>

    <!-- Course Content Modal (Modules & Quizzes) -->
    <div id="contentModal" class="modal">
      <div class="modal-content card" style="max-width:680px; width:95%;">
        <div class="modal-header">
          <h3><i class="fa-solid fa-layer-group"></i> Manage: <span
              id="contentModalCourseTitle"></span></h3>
          <span class="close-modal"
            onclick="closeContentModal()">&times;</span>
        </div>

        <div class="modal-body" style="padding-bottom:0;">
          <!-- Tab Navigation -->
          <div
            style="display:flex; gap:0; border-bottom:2px solid #e5e5e5; margin-bottom:16px;">
            <button id="tab-btn-modules" onclick="switchContentTab('modules')"
              style="flex:1; padding:10px; border:none; background:none; font-weight:700; font-size:13px; border-bottom:2px solid #025628; margin-bottom:-2px; color:#025628; cursor:pointer;">
              <i class="fa-solid fa-cubes"></i> Modules
            </button>
            <button id="tab-btn-quizzes" onclick="switchContentTab('quizzes')"
              style="flex:1; padding:10px; border:none; background:none; font-weight:600; font-size:13px; color:#aaa; cursor:pointer;">
              <i class="fa-solid fa-clipboard-question"></i> Quizzes
            </button>
          </div>

          <!-- MODULES TAB -->
          <div id="content-tab-modules">
            <div id="moduleAlert"
              style="display:none; padding:8px 12px; border-radius:6px; font-size:12px; margin-bottom:10px; font-weight:600;">
            </div>

            <div
              style="display:flex; flex-direction:column; gap:8px; margin-bottom:14px;">
              <div style="display:flex; gap:8px;">
                <input type="text" id="newModuleTitle"
                  placeholder="Module title" required
                  style="flex:1; border:1px solid #ddd; border-radius:8px; padding:8px 12px; font-size:13px; font-family:inherit;">
                <input type="text" id="newModuleDesc"
                  placeholder="Description (optional)"
                  style="flex:2; border:1px solid #ddd; border-radius:8px; padding:8px 12px; font-size:13px; font-family:inherit;">
              </div>
              <div style="display:flex; gap:8px; align-items:center;">
                <label
                  style="font-size:12px; color:#666; white-space:nowrap;">📎
                  PDF File:</label>
                <input type="file" id="newModuleFile"
                  accept=".pdf,.doc,.docx"
                  style="flex:1; border:1px solid #ddd; border-radius:8px; padding:6px 12px; font-size:13px; font-family:inherit; background:#fff;">
                <button type="button" onclick="addModule()"
                  style="background:#025628; color:#fff; border:none; border-radius:8px; padding:9px 16px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap; font-family:inherit;">
                  <i class="fa-solid fa-plus"></i> Add
                </button>
              </div>
            </div>

            <div id="moduleListContainer"
              style="display:flex; flex-direction:column; gap:8px; max-height:320px; overflow-y:auto;">
              <div
                style="text-align:center; color:#bbb; font-size:13px; padding:20px 0;"
                id="modulesEmptyState">
                <i class="fa-solid fa-inbox"
                  style="font-size:24px; display:block; margin-bottom:6px; color:#ccc;"></i>
                No modules created yet.
              </div>
            </div>
          </div>

          <!-- QUIZZES TAB -->
          <div id="content-tab-quizzes" style="display:none;">
            <div id="quizAlert"
              style="display:none; padding:8px 12px; border-radius:6px; font-size:12px; margin-bottom:10px; font-weight:600;">
            </div>

            <div
              style="background:#f9f9f9; border:1px solid #eee; border-radius:10px; padding:14px; margin-bottom:14px;">
              <div
                style="font-size:12px; font-weight:700; color:#025628; margin-bottom:10px; text-transform:uppercase; letter-spacing:.04em;">
                <i class="fa-solid fa-plus-circle"></i> New Quiz
              </div>
              <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <input type="text" id="newQuizTitle"
                  placeholder="Quiz title" required
                  style="flex:2; min-width:140px; border:1px solid #ddd; border-radius:8px; padding:8px 12px; font-size:13px; font-family:inherit;">
                <select id="newQuizModule"
                  style="flex:1.5; min-width:130px; border:1px solid #ddd; border-radius:8px; padding:8px 12px; font-size:13px; font-family:inherit; background:#fff;">
                  <option value="">— Link to module (optional) —</option>
                </select>
              </div>
              <div
                style="display:flex; gap:8px; margin-top:8px; flex-wrap:wrap; align-items:center;">
                <div style="flex:1; min-width:100px;">
                  <label
                    style="font-size:11px; color:#888; display:block; margin-bottom:2px;">Passing
                    score (%)</label>
                  <input type="number" id="newQuizPass" value="75"
                    min="1" max="100"
                    style="width:100%; border:1px solid #ddd; border-radius:8px; padding:8px 10px; font-size:13px; font-family:inherit;">
                </div>
                <div style="flex:1; min-width:100px;">
                  <label
                    style="font-size:11px; color:#888; display:block; margin-bottom:2px;">Time
                    limit (mins)</label>
                  <input type="number" id="newQuizTime" value="30"
                    min="1"
                    style="width:100%; border:1px solid #ddd; border-radius:8px; padding:8px 10px; font-size:13px; font-family:inherit;">
                </div>
                <button type="button" onclick="addQuiz()"
                  style="background:#025628; color:#fff; border:none; border-radius:8px; padding:9px 20px; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; margin-top:14px;">
                  <i class="fa-solid fa-plus"></i> Add Quiz
                </button>
              </div>
            </div>

            <div id="quizListContainer"
              style="display:flex; flex-direction:column; gap:8px; max-height:280px; overflow-y:auto;">
              <div
                style="text-align:center; color:#bbb; font-size:13px; padding:20px 0;"
                id="quizzesEmptyState">
                <i class="fa-solid fa-inbox"
                  style="font-size:24px; display:block; margin-bottom:6px; color:#ccc;"></i>
                No quizzes created yet.
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer"
          style="margin-top:20px; padding-top:12px; border-top:1px solid #eee; display:flex; justify-content:flex-end;">
          <button class="btn-cancel" onclick="closeContentModal()"
            style="padding:8px 20px; font-size:13px;">
            Close
          </button>
        </div>
      </div>
    </div>

    <!-- ========================================================== -->
    <!-- JAVASCRIPT SCRIPTS & HANDLERS                              -->
    <!-- ========================================================== -->
    <script src="js/logout.js"></script>
    <script>
      const urlParams = new URLSearchParams(window.location.search);

      function showAlert(message) {
        document.getElementById('mcAlertMessage').textContent = message;
        document.getElementById('mcAlertModal').style.display = 'block';
      }

      function closeAlertModal() {
        document.getElementById('mcAlertModal').style.display = 'none';
      }

      let _confirmCallback = null;

      function showConfirm(message, onYes) {
        document.getElementById('mcConfirmMessage').textContent = message;
        _confirmCallback = onYes;
        document.getElementById('mcConfirmModal').style.display = 'block';
      }

      function closeConfirmModal() {
        document.getElementById('mcConfirmModal').style.display = 'none';
        _confirmCallback = null;
      }

      function showToast(message, type = 'info') {
        const styles = {
          success: {
            bg: '#e8f5e9',
            border: '#025628',
            color: '#025628',
            icon: 'fa-circle-check'
          },
          error: {
            bg: '#FCEBEB',
            border: '#A32D2D',
            color: '#A32D2D',
            icon: 'fa-circle-xmark'
          },
          warning: {
            bg: '#FFF8E1',
            border: '#854F0B',
            color: '#854F0B',
            icon: 'fa-triangle-exclamation'
          },
          info: {
            bg: '#E3F2FD',
            border: '#0d47a1',
            color: '#0d47a1',
            icon: 'fa-circle-info'
          }
        };
        const s = styles[type] || styles.info;

        const toast = document.createElement('div');
        toast.style.cssText = `
      background:${s.bg}; border:1px solid ${s.border}; color:${s.color};
      padding:12px 16px; border-radius:8px; font-size:13px; font-weight:600;
      display:flex; align-items:center; gap:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1);
      animation: mcToastIn 0.25s ease;
    `;
        toast.innerHTML = `
      <i class="fa-solid ${s.icon}"></i>
      <span style="flex:1;">${message}</span>
      <span onclick="this.parentElement.remove()" style="cursor:pointer; font-weight:700; opacity:0.6;">&times;</span>
    `;

        document.getElementById('toastContainer').appendChild(toast);

        setTimeout(() => {
          toast.style.transition = 'opacity 0.3s ease';
          toast.style.opacity = '0';
          setTimeout(() => toast.remove(), 300);
        }, 4000);
      }

      function _confirmYes() {
        const cb = _confirmCallback;
        closeConfirmModal();
        if (cb) cb();
      }

      const csrfToken = document.querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

      let traineeHistoryInstance = null;
      let courseHistoryInstance = null;
      let currentCourseId = null;

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
        if (!e.target.closest('.topbar-right')) {
          dropdown.classList.remove('open');
        }
      });

      window.traineeCourseLabels = @json($traineeCourseLabels ?? []);
      window.traineeCourseCounts = @json($traineeCourseCounts ?? []);
      window.monthlyEnrollmentLabels = @json($monthlyEnrollmentLabels ?? []);
      window.monthlyEnrollmentCounts = @json($monthlyEnrollmentCounts ?? []);
      window.quizPerformanceLabels = @json($quizPerformanceLabels ?? []);
      window.quizPerformanceScores = @json($quizPerformanceScores ?? []);
      window.performanceCourseLabels = @json($performanceCourseLabels ?? []);
      window.courseQuizAverages = @json($courseQuizAverages ?? []);
      window.courseOnsiteAverages = @json($courseOnsiteAverages ?? []);
      window.competencyLabels = @json($competencyLabels ?? []);
      window.competencyStudentCounts = @json($competencyStudentCounts ?? []);
      window.completionCourseLabels = @json($completionCourseLabels ?? []);
      window.courseCompletionRates = @json($courseCompletionRates ?? []);
      window.courseCompletedCounts = @json($courseCompletedCounts ?? []);
      window.courseEligibleCounts = @json($courseEligibleCounts ?? []);
      window.competentCourseLabels = @json($competentCourseLabels ?? []);
      window.competentCourseCounts = @json($competentCourseCounts ?? []);

      let traineeChartInstance = null;

      function setActive(el) {
        if (!el) return;
        document.querySelectorAll('.sidebar .nav-item').forEach(i => i.classList
          .remove('active'));
        el.classList.add('active');
      }

      function initHistoryChart(traineeData = [], courseData = []) {
        // 1. TRAINEE HISTORY CHART
        const traineeCanvas = document.getElementById('traineeHistoryChart');
        if (traineeCanvas) {
          traineeCanvas.style.height = '500px';
          const traineeCtx = traineeCanvas.getContext('2d');

          if (traineeHistoryInstance) {
            traineeHistoryInstance.destroy();
          }

          traineeHistoryInstance = new Chart(traineeCtx, {
            type: 'bar',
            data: {
              labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug',
                'Sep', 'Oct', 'Nov', 'Dec'
              ],
              datasets: [{
                label: 'Trainees',
                data: traineeData,
                backgroundColor: '#7fb092',
                borderRadius: 5
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: true,
                  position: 'top'
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    precision: 0
                  }
                }
              }
            }
          });
        }

        // 2. COURSE HISTORY CHART
        const courseCanvas = document.getElementById('courseHistoryChart');
        if (courseCanvas) {
          courseCanvas.style.height = '500px';
          const courseCtx = courseCanvas.getContext('2d');

          if (courseHistoryInstance) {
            courseHistoryInstance.destroy();
          }

          courseHistoryInstance = new Chart(courseCtx, {
            type: 'line',
            data: {
              labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug',
                'Sep', 'Oct', 'Nov', 'Dec'
              ],
              datasets: [{
                label: 'Courses',
                data: courseData,
                borderColor: '#004d26',
                backgroundColor: 'rgba(0,77,38,0.1)',
                fill: true,
                tension: 0.4
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    precision: 0
                  }
                }
              }
            }
          });
        }
      }

      document.addEventListener('DOMContentLoaded', function() {
        // 1. Announcement Modal Character Counters & Status Toggle
        const titleInput = document.getElementById('annTitle');
        const messageInput = document.getElementById('annMessage');
        const titleCounter = document.getElementById('titleCounter');
        const messageCounter = document.getElementById('messageCounter');
        const isActiveCb = document.getElementById('annIsActive');
        const statusLabel = document.getElementById('statusLabel');

        if (titleInput && titleCounter) {
          titleInput.addEventListener('input', () => {
            titleCounter.textContent = `${titleInput.value.length}/100`;
          });
        }

        if (messageInput && messageCounter) {
          messageInput.addEventListener('input', () => {
            messageCounter.textContent = `${messageInput.value.length}/500`;
          });
        }

        if (isActiveCb && statusLabel) {
          isActiveCb.addEventListener('change', (e) => {
            statusLabel.textContent = e.target.checked ? 'Publish Now' :
              'Save as Draft';
          });
        }

        // 2. FullCalendar Sidebar Initialization
        const calendarEl = document.getElementById('calendar');
        if (calendarEl) {
          const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            fixedWeekCount: true,
            headerToolbar: {
              left: 'prev',
              center: 'title',
              right: 'next'
            },
            eventColor: '#004d26',
            height: 280,
            aspectRatio: 1.0,
            contentHeight: 'auto',
            handleWindowResize: true
          });
          calendar.render();
        }

        // 3. Trainees Enrolled Per Course Bar Chart (Overview View)
        const traineeCanvas = document.getElementById('traineeChart');
        if (traineeCanvas) {
          const ctxBar = traineeCanvas.getContext('2d');

          if (window.traineeChartInstance) {
            window.traineeChartInstance.destroy();
          }

          // Safely retrieve global variables initialized by Blade
          const labels = (typeof window.traineeCourseLabels !== 'undefined' &&
              window.traineeCourseLabels.length) ?
            window.traineeCourseLabels :
            (typeof traineeCourseLabels !== 'undefined' ? traineeCourseLabels :
              []);

          const dataCounts = (typeof window.traineeCourseCounts !==
              'undefined' && window.traineeCourseCounts.length) ?
            window.traineeCourseCounts :
            (typeof traineeCourseCounts !== 'undefined' ? traineeCourseCounts :
              []);

          window.traineeChartInstance = new Chart(ctxBar, {
            type: 'bar',
            data: {
              labels: labels,
              datasets: [{
                label: 'Enrolled Trainees',
                data: dataCounts,
                backgroundColor: '#004d26',
                borderRadius: 4,
                barPercentage: 0.65
              }]
            },
            options: {
              indexAxis: 'y', // Makes bars horizontal so long course names render cleanly
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: false
                },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      return `Trainees: ${context.raw}`;
                    }
                  }
                }
              },
              scales: {
                x: {
                  beginAtZero: true,
                  grid: {
                    color: '#f0f0f0'
                  },
                  ticks: {
                    precision: 0
                  }
                },
                y: {
                  grid: {
                    display: false
                  },
                  ticks: {
                    font: {
                      size: 11,
                      weight: '600'
                    },
                    color: '#333333'
                  }
                }
              },
              layout: {
                padding: {
                  left: 10,
                  right: 20,
                  top: 10,
                  bottom: 10
                }
              }
            }
          });
        }

        // 4. Trainee Status Ratio Pie Chart (Overview View)
        const courseCanvas = document.getElementById('courseChart');
        if (courseCanvas) {
          const ctxPie = courseCanvas.getContext('2d');

          // Destroy previous instance to prevent rendering overlap or chart type caching issues
          if (window.courseChartInstance) {
            window.courseChartInstance.destroy();
            window.courseChartInstance = null;
          }

          // Retrieve active and completed array data or fallback to safe defaults
          const activeData = (typeof window.courseActiveCounts !==
              'undefined' && window.courseActiveCounts.length) ?
            window.courseActiveCounts :
            (typeof courseActiveCounts !== 'undefined' ? courseActiveCounts : [
              10, 2, 3, 1, 4
            ]);

          const completedData = (typeof window.courseCompletedCounts !==
              'undefined' && window.courseCompletedCounts.length) ?
            window.courseCompletedCounts :
            (typeof courseCompletedCounts !== 'undefined' ?
              courseCompletedCounts : [4, 1, 2, 0, 3]);

          // Aggregate total active and completed counts
          const totalActive = activeData.reduce((a, b) => a + b, 0);
          const totalCompleted = completedData.reduce((a, b) => a + b, 0);

          window.courseChartInstance = new Chart(ctxPie, {
            type: 'pie',
            data: {
              labels: ['Active / Enrolled', 'Completed / Graduated'],
              datasets: [{
                data: [totalActive, totalCompleted],
                backgroundColor: ['#004d26',
                  '#eab308'
                ], // Dark Green for Active, Yellow for Completed
                borderWidth: 2,
                borderColor: '#ffffff'
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              onClick: function() {
                if (typeof openExpandedChartModal === 'function') {
                  openExpandedChartModal('courses');
                }
              },
              plugins: {
                legend: {
                  display: true,
                  position: 'bottom',
                  labels: {
                    boxWidth: 12,
                    padding: 14,
                    font: {
                      size: 11,
                      weight: '600'
                    },
                    color: '#333333'
                  }
                },
                tooltip: {
                  backgroundColor: '#1a1a1a',
                  titleColor: '#ffffff',
                  bodyColor: '#ffffff',
                  padding: 10,
                  cornerRadius: 6,
                  callbacks: {
                    label: (ctx) => {
                      const total = totalActive + totalCompleted;
                      const val = ctx.raw;
                      const pct = total > 0 ? Math.round((val / total) *
                        100) : 0;
                      return ` ${ctx.label}: ${val} (${pct}%)`;
                    }
                  }
                }
              },
              layout: {
                padding: {
                  top: 5,
                  bottom: 5
                }
              }
            }
          });
        }

        // 5. URL Parameter & LocalStorage View Restoration
        const urlParams = new URLSearchParams(window.location.search);
        const savedTab = localStorage.getItem('activeAdminTab');

        if (urlParams.get('view') === 'certificate' || savedTab ===
          'view-certificate') {
          showView('certificate');
          setActive(document.getElementById('nav-certificate'));
          localStorage.removeItem('activeAdminTab');
        } else if (urlParams.get('view') === 'facilities' || savedTab ===
          'view-facilities') {
          showView('facilities');
          setActive(document.getElementById('nav-facilities'));
          localStorage.removeItem('activeAdminTab');
        } else if (urlParams.get('view') === 'courses' || urlParams.get(
            'page')) {
          showView('courses');
          setActive(document.getElementById('nav-courses'));
        } else if (urlParams.get('trainee_page') || window.location.pathname
          .includes('trainees')) {
          showView('all-trainees');
          setActive(document.getElementById('nav-trainees'));
        } else if (urlParams.get('trainer_page') || urlParams.get('trainer') ||
          urlParams.has('trainer_page')) {
          showView('all-trainers');
          setActive(document.getElementById('nav-trainers'));
        } else if (urlParams.get('view') === 'announcements' || urlParams.get(
            'announcement_page')) {
          showView('announcements');
          setActive(document.getElementById('nav-announcements'));
        } else if (urlParams.get('view') === 'registrations' || urlParams.get(
            'registration_page')) {
          showView('registrations');
          setActive(document.getElementById('nav-registrations'));
        } else if (savedTab && savedTab !== 'view-overview') {
          showView(savedTab.replace('view-', ''));
          localStorage.removeItem('activeAdminTab');
        } else {
          showView('overview');
          setActive(document.getElementById('nav-overview'));
        }
      });

      function showView(viewName) {
        const allViews = [
          'view-overview', 'view-trainee-list', 'view-trainer-list',
          'view-facilities', 'view-courses', 'view-settings', 'view-analytics',
          'view-announcements', 'view-certificate', 'view-registrations'
        ];

        allViews.forEach(id => {
          const el = document.getElementById(id);
          if (el) el.style.display = 'none';
        });

        const title = document.getElementById('main-title');
        const breadcrumb = document.getElementById('breadcrumb-current');

        const map = {
          overview: ['view-overview', 'System Overview', 'System Overview'],
          analytics: ['view-analytics', 'Detailed Analytics',
            `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Analytics`
          ],
          'all-trainees': ['view-trainee-list', 'Trainee Management',
            `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Trainees`
          ],
          'all-trainers': ['view-trainer-list', 'Trainer Management',
            `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Trainers`
          ],
          facilities: ['view-facilities', 'Facilities',
            `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Facilities`
          ],
          courses: ['view-courses', 'Available Courses',
            `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Courses`
          ],
          settings: ['view-settings', '',
            `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Settings`
          ],
          announcements: ['view-announcements', 'Announcements',
            `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Announcements`
          ],
          certificate: ['view-certificate', 'Certificates',
            `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Certificates`
          ],
          registrations: ['view-registrations', 'Registrations',
            `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Registrations`
          ]
        };

        const entry = map[viewName] || map['overview'];
        const el = document.getElementById(entry[0]);
        if (el) el.style.display = 'block';
        if (title) title.innerText = entry[1];
        if (breadcrumb) breadcrumb.innerHTML = entry[2];

        // Save active tab state persistently
        localStorage.setItem('activeAdminTab', 'view-' + viewName);

        const currentParams = new URLSearchParams(window.location.search);
        if (currentParams.get('view') !== viewName) {
          const newUrl = window.location.pathname + '?view=' + viewName;
          window.history.pushState({
            view: viewName
          }, '', newUrl);
        }

        document.querySelectorAll('.sidebar li').forEach(li => li.classList.remove(
          'active'));
        const activeNav = document.getElementById(`nav-${viewName}`);
        if (activeNav) {
          activeNav.classList.add('active');
        }

        if (viewName === 'all-trainees' && typeof backToCourseCards ===
          'function') {
          backToCourseCards();
        }

        if (viewName === 'analytics' && typeof initHistoryChart === 'function') {
          setTimeout(initHistoryChart, 100);
        }

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (window.innerWidth <= 768 && sidebar && overlay) {
          sidebar.classList.remove('sidebar-open');
          overlay.classList.remove('show');
        }
      }

      window.addEventListener('popstate', function() {
        const params = new URLSearchParams(window.location.search);
        const view = params.get('view') || 'overview';
        showView(view);
      });

      function toggleUpdates() {
        const extra = document.getElementById("extra-updates");
        const btn = document.getElementById("viewMoreBtn");
        if (extra && btn) {
          if (extra.style.display === "none" || extra.style.display === "") {
            extra.style.display = "flex";
            btn.innerHTML = `View Less <i class="fa-solid fa-chevron-up"></i>`;
          } else {
            extra.style.display = "none";
            btn.innerHTML = `View More <i class="fa-solid fa-chevron-down"></i>`;
          }
        }
      }

      function openLogoutModal() {
        document.getElementById('logoutModal').style.display = 'block';
      }

      function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
      }

      function confirmLogout() {
        document.getElementById('logout-form').submit();
      }

      function openCourseModal(
        id,
        code,
        name,
        duration,
        slots,
        trainerId,
        trainerName,
        status = 'active',
        startDate = '',
        endDate = '',
        trainingDays = [],
        startTime = '',
        endTime = ''
      ) {
        currentCourseId = id;

        const modal = document.getElementById('courseModal');
        if (modal) {
          modal.style.display = 'block';
        }

        const modalTitle = document.querySelector('#courseModal h3');
        if (modalTitle) {
          modalTitle.innerHTML =
            '<i class="fa-solid fa-pen-to-square"></i> Manage Course';
        }

        const deleteButton = document.querySelector(
          '#courseModal .btn-delete-text'
        );

        if (deleteButton) {
          deleteButton.style.display = 'inline-block';
        }

        const trainerSection = document.querySelector(
          '.assign-trainer-section'
        );

        if (trainerSection) {
          trainerSection.style.display = 'block';
        }

        // Populate the existing course details.
        document.getElementById('editCourseId').value = id ?? '';
        document.getElementById('editCourseCode').value = code || '';
        document.getElementById('editCourseName').value = name || '';
        document.getElementById('editDuration').value = duration || '';
        document.getElementById('editSlots').value = slots ?? '';

        // Populate course status.
        const statusSelect = document.getElementById('editStatus');

        if (statusSelect) {
          statusSelect.value = (status || 'active').toLowerCase();
        }

        // Populate training dates.
        const startDateInput = document.getElementById('editStartDate');
        const endDateInput = document.getElementById('editEndDate');

        if (startDateInput) {
          startDateInput.value = startDate || '';
        }

        if (endDateInput) {
          endDateInput.value = endDate || '';
        }

        // Populate saved training hours.
        const startTimeInput = document.getElementById('editStartTime');
        const endTimeInput = document.getElementById('editEndTime');

        if (startTimeInput) {
          startTimeInput.value = startTime || '';
        }

        if (endTimeInput) {
          endTimeInput.value = endTime || '';
        }

        // Restore the selected training days.
        if (!Array.isArray(trainingDays)) {
          trainingDays = [];
        }

        document.querySelectorAll(
          '#courseForm input[name="training_days[]"]'
        ).forEach(checkbox => {
          checkbox.checked = trainingDays.includes(checkbox.value);
        });

        // Restore the assigned trainer.
        const trainerDropdown = document.getElementById('trainerDropdown');

        if (trainerDropdown) {
          trainerDropdown.value = trainerId || '';
        }

        if (trainerId && trainerName) {
          showCurrentTrainer(trainerName);
        } else {
          showNoTrainer();
        }
      }

      function openAddCourseModal() {
        currentCourseId = null;

        const modal = document.getElementById('courseModal');

        if (modal) {
          modal.style.display = 'block';
        }

        const modalTitle = document.querySelector('#courseModal h3');

        if (modalTitle) {
          modalTitle.innerHTML =
            '<i class="fa-solid fa-folder-plus"></i> Create New Course';
        }

        // Clear previous course details, dates, times, and selected days.
        const courseForm = document.getElementById('courseForm');

        if (courseForm) {
          courseForm.reset();
        }

        // Keep the hidden course ID empty for a new course.
        const courseIdInput = document.getElementById('editCourseId');

        if (courseIdInput) {
          courseIdInput.value = '';
        }

        const deleteButton = document.querySelector(
          '#courseModal .btn-delete-text'
        );

        if (deleteButton) {
          deleteButton.style.display = 'none';
        }

        // Trainer assignment is available after the course is created.
        const trainerSection = document.querySelector(
          '.assign-trainer-section'
        );

        if (trainerSection) {
          trainerSection.style.display = 'none';
        }

        showNoTrainer();
      }

      function closeModal() {
        document.getElementById('courseModal').style.display = 'none';
        currentCourseId = null;
        document.querySelector('.assign-trainer-section').style.display = 'block';
      }

      function showCurrentTrainer(name) {
        const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2)
          .toUpperCase();
        document.getElementById('trainerInitials').textContent = initials;
        document.getElementById('trainerFullName').textContent = name;
        document.getElementById('currentTrainerBox').style.display = 'flex';
        document.getElementById('noTrainerBox').style.display = 'none';
      }

      function showNoTrainer() {
        document.getElementById('currentTrainerBox').style.display = 'none';
        document.getElementById('noTrainerBox').style.display = 'block';
      }

      function assignTrainer() {
        const dropdown = document.getElementById('trainerDropdown');
        const trainerId = dropdown.value;
        const trainerName = dropdown.selectedOptions[0].text;

        if (!trainerId) {
          alert('Please select a trainer first.');
          return;
        }
        if (!currentCourseId) {
          alert('Please save the course first before assigning a trainer.');
          return;
        }

        fetch(`/admin/course/${currentCourseId}/assign-trainer`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              trainer_id: trainerId
            }),
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              showCurrentTrainer(trainerName);
              alert('Trainer assigned successfully!');
            }
          })
          .catch(() => alert('Something went wrong. Please try again.'));
      }

      async function duplicateCourse(courseId, courseTitle) {
      if (!confirm(
          `Duplicate "${courseTitle}" without its trainer or enrolled trainees?`
      )) {
          return;
      }

      try {
          const response = await fetch(
              `/admin/course/${courseId}/duplicate`,
              {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': csrfToken,
                      'Accept': 'application/json'
                  }
              }
          );

          const data = await response.json();

          if (!response.ok || !data.success) {
              throw new Error(
                  data.message || 'Failed to duplicate course.'
              );
          }

          alert(data.message || 'Course duplicated successfully.');

          window.location.href =
              window.location.pathname + '?view=courses';

      } catch (error) {
          console.error('Duplicate course error:', error);
          alert(error.message || 'An error occurred while duplicating.');
      }
  }

      function removeTrainer() {
        if (!currentCourseId) return;
        if (!confirm('Remove the assigned trainer from this course?')) return;

        fetch(`/admin/course/${currentCourseId}/remove-trainer`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              showNoTrainer();
              document.getElementById('trainerDropdown').value = '';
              alert('Trainer removed successfully!');
            }
          })
          .catch(() => alert('Something went wrong. Please try again.'));
      }

      function confirmDelete() {
        const courseId = document.getElementById('editCourseId').value;

        if (!courseId) {
          alert('No course selected to delete.');
          return;
        }

        if (!confirm(
            'Are you sure you want to delete this course? This action cannot be undone.'
          )) {
          return;
        }

        fetch(`/admin/course/${courseId}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            }
          })
          .then(async res => {
            const data = await res.json();
            if (data.success) {
              alert('Course deleted successfully!');
              closeModal();
              window.location.href = window.location.pathname + '?view=courses';
            } else {
              alert(data.message ||
                'Could not delete course. Make sure to unassign any active trainers or enrolled trainees first.'
              );
            }
          })
          .catch(err => {
            console.error(err);
            alert('An error occurred while attempting to delete the course.');
          });
      }

      function openUserModal(idOrUser, name, email, role, status, courseTitle = '',
        contact = '', idNum = '', created = '', remarks = '') {
        // 1. Support both Object payload and positional parameters
        const isObj = typeof idOrUser === 'object' && idOrUser !== null;
        const data = isObj ? idOrUser : {
          id: idOrUser,
          name,
          email,
          role,
          status,
          courseTitle,
          contact,
          idNum,
          created,
          remarks
        };

        // Helper function to sanitize input strings
        const sanitize = (val, fallback = '') => {
          if (val === null || val === undefined) return fallback;
          const str = String(val).trim();
          if (str === '' || str === 'null' || str === 'undefined')
            return fallback;
          return str;
        };

        // 2. Display Modal
        const modal = document.getElementById('userModal');
        if (modal) {
          modal.style.display = 'block';
          modal.style.setProperty('display', 'block', 'important');
        }

        // 3. Set Inputs
        const fields = {
          'editUserId': sanitize(data.id),
          'editUserName': sanitize(data.name),
          'editUserEmail': sanitize(data.email),
          'editUserContact': sanitize(data.contact),
          'editUserIdNum': sanitize(data.idNum || data.id_number),
          'editUserCreated': sanitize(data.created, 'August 2026'),
          'editUserRemarks': sanitize(data.remarks)
        };

        Object.entries(fields).forEach(([fieldId, value]) => {
          const el = document.getElementById(fieldId);
          if (el) el.value = value;
        });

        // 4. Normalize & Assign Role Dropdown
        const cleanRole = sanitize(data.role, 'student').toLowerCase();
        const userRoleEl = document.getElementById('editUserRole');

        if (userRoleEl) {
          const matchedOption = Array.from(userRoleEl.options).find(
            opt => opt.value.toLowerCase() === cleanRole
          );
          userRoleEl.value = matchedOption ? matchedOption.value : (userRoleEl
            .options[0]?.value || cleanRole);
        }

        const hiddenRoleEl = document.getElementById('hiddenUserRole');
        if (hiddenRoleEl) hiddenRoleEl.value = cleanRole;

        // 5. Normalize & Assign Status Dropdown (Role-Specific Defaulting)
        const isTraineeRole = ['student', 'trainee', 'trainees'].includes(
          cleanRole);
        const defaultStatusFallback = isTraineeRole ? 'Pending' : 'Active';

        const statusSelect = document.getElementById('editUserStatus');
        if (statusSelect) {
          const rawStatus = sanitize(data.status, defaultStatusFallback)
            .toLowerCase();
          let cleanStatus = defaultStatusFallback;

          if (rawStatus.includes('inactive')) {
            cleanStatus = 'Inactive';
          } else if (rawStatus.includes('pending')) {
            cleanStatus = 'Pending';
          } else if (rawStatus.includes('active')) {
            cleanStatus = 'Active';
          }

          const matchedStatus = Array.from(statusSelect.options).find(
            opt => opt.value.toLowerCase() === cleanStatus.toLowerCase()
          );
          statusSelect.value = matchedStatus ? matchedStatus.value : cleanStatus;
        }

        // 6. Dynamic Label Logic: Trainee vs. Trainer / Admin
        const idNumLabel = document.getElementById('idNumLabel') ||
          document.querySelector('label[for="editUserIdNum"]');

        if (idNumLabel) {
          idNumLabel.textContent = isTraineeRole ? 'Unique Learner Identifier' :
            'Reference / ID Number';
        }

        // 7. Toggle Course/Trainer Visibility Fields
        const courseFieldContainer = document.getElementById('trainerCourseField');
        const courseInput = document.getElementById('editTrainerCourse');
        const trainerFieldsContainer = document.getElementById(
          'trainerFieldsContainer');

        const isTrainer = cleanRole === 'trainer';

        if (courseFieldContainer) {
          courseFieldContainer.style.display = isTrainer ? 'block' : 'none';
        }

        if (courseInput) {
          courseInput.value = isTrainer ? sanitize(data.courseTitle,
            'No course assigned') : '';
        }

        if (trainerFieldsContainer) {
          trainerFieldsContainer.style.display = 'block';
        }
      }

      function openFacilityModal(id, name, address, courseIds = []) {
        const modal = document.getElementById('facilityModal');
        const title = document.getElementById('facilityModalTitle') || document
          .querySelector('#facilityModal h3');
        const deleteBtn = document.querySelector('#facilityModal .btn-delete-text');

        if (modal) modal.style.display = 'flex';

        if (title) {
          title.innerHTML =
            '<i class="fa-solid fa-building-circle-gear"></i> Manage Facility';
        }
        if (deleteBtn) {
          deleteBtn.style.display = 'flex';
        }

        const idInput = document.getElementById('editFacId');
        if (idInput) idInput.value = id || '';

        const nameInput = document.getElementById('editFacName');
        const addrInput = document.getElementById('editFacAddress');
        if (nameInput) nameInput.value = name || '';
        if (addrInput) addrInput.value = address || '';

        const targetIds = Array.isArray(courseIds) ?
          courseIds.map(String) :
          (courseIds ? [String(courseIds)] : []);

        document.querySelectorAll('.facility-course-cb').forEach(cb => {
          cb.checked = targetIds.includes(String(cb.value));
        });

        if (typeof updateCourseBadgeCount === 'function') {
          updateCourseBadgeCount();
        }
      }

      function openAddFacilityModal() {
        const modal = document.getElementById('facilityModal');
        const title = document.getElementById('facilityModalTitle') || document
          .querySelector('#facilityModal h3');
        const form = document.getElementById('facilityForm');
        const deleteBtn = document.querySelector('#facilityModal .btn-delete-text');

        if (modal) modal.style.display = 'flex';

        if (title) {
          title.innerHTML =
            '<i class="fa-solid fa-building-circle-plus"></i> Add New Facility';
        }

        if (form) form.reset();

        const idInput = document.getElementById('editFacId');
        if (idInput) idInput.value = '';

        document.querySelectorAll('.facility-course-cb').forEach(cb => {
          cb.checked = false;
        });

        if (deleteBtn) {
          deleteBtn.style.display = 'none';
        }

        if (typeof updateCourseBadgeCount === 'function') {
          updateCourseBadgeCount();
        }
      }

      function deleteFacility() {
        const id = document.getElementById('editFacId')?.value?.trim();

        if (!id) {
          showAlert('Cannot delete: Invalid or missing Facility ID.');
          return;
        }

        showConfirm(
          'Are you sure you want to delete this facility? Any assigned courses will be unlinked.',
          function() {
            const csrfToken = typeof getCsrfToken === 'function' ?
              getCsrfToken() :
              document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                'content');

            fetch('/admin/facility/delete', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken,
                  'Accept': 'application/json'
                },
                body: JSON.stringify({
                  id: id
                })
              })
              .then(async response => {
                const data = await response.json().catch(() => null);

                if (response.ok && data && data.success) {
                  showAlert(data.message || 'Facility deleted successfully!');
                  closeFacilityModal();

                  localStorage.setItem('activeAdminTab', 'view-facilities');
                  location.reload();
                } else {
                  showAlert((data && data.message) ? data.message :
                    'Failed to delete facility.');
                }
              })
              .catch(error => {
                console.error('Delete facility error:', error);
                showAlert('An error occurred while deleting the facility.');
              });
          });
      }

      function closeFacilityModal() {
        const modal = document.getElementById('facilityModal');
        const form = document.getElementById('facilityForm');

        if (modal) {
          modal.style.display = 'none';
        }

        if (form) form.reset();
      }

      function openAddTrainerModal() {
        const modal = document.getElementById('addTrainerModal');
        if (modal) {
          modal.style.display = 'block';
        }
      }

      function closeAddTrainerModal() {
        const modal = document.getElementById('addTrainerModal');
        if (modal) {
          modal.style.display = 'none';
          modal.style.setProperty('display', 'none', 'important');
        }
        const form = document.getElementById('addTrainerForm');
        if (form) {
          form.reset();
        }
      }

      window.onclick = function(event) {
        if (event.target && event.target.classList.contains('modal')) {
          if (typeof closeModal === 'function') closeModal();
          if (typeof closeUserModal === 'function') closeUserModal();
          if (typeof closeFacilityModal === 'function') closeFacilityModal();
          if (typeof closeAddTrainerModal === 'function') closeAddTrainerModal();
          if (typeof closeAnnouncementModal === 'function')
            closeAnnouncementModal();
        }
      };

      document.getElementById('addTrainerForm').onsubmit = function(e) {
        e.preventDefault();

        const name = document.getElementById('newTrainerName').value.trim();
        const email = document.getElementById('newTrainerEmail').value.trim();
        const password = document.getElementById('newTrainerPass').value.trim();

        // Grab all the optional/extra fields from the modal
        const contactNumber = document.getElementById('newTrainerContact')?.value
          .trim() || null;
        const referenceId = document.getElementById('newTrainerReference')?.value
          .trim() || null;
        const role = document.getElementById('newTrainerRole')?.value ||
          'trainer';
        const status = document.getElementById('newTrainerStatus')?.value ||
          'Active';
        const remarks = document.getElementById('newTrainerRemarks')?.value
          .trim() || null;
        const courseId = document.getElementById('newTrainerCourse')?.value ||
          null;

        if (!name || !email || !password) {
          alert('Please fill in all required fields.');
          return;
        }

        fetch('/admin/trainer/store', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              name: name,
              email: email,
              password: password,
              contact_number: contactNumber,
              reference_id: referenceId,
              role: role,
              status: status,
              remarks: remarks,
              course_id: courseId,
            })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              alert('Trainer account created successfully!');
              closeAddTrainerModal();
              location.reload();
            } else {
              alert(data.message || 'An error occurred. Please try again.');
            }
          })
          .catch((err) => {
            console.error(err);
            alert('An error occurred. Please try again.');
          });
      };

      document.getElementById('courseForm').onsubmit = async function(e) {
        e.preventDefault();

        const form = this;
        const submitButton = form.querySelector(
          'button[type="submit"], input[type="submit"]'
        );

        const courseId =
          document.getElementById('editCourseId')?.value?.trim() || '';

        const courseCode =
          document.getElementById('editCourseCode')?.value?.trim() || '';

        const title =
          document.getElementById('editCourseName')?.value?.trim() || '';

        const duration =
          document.getElementById('editDuration')?.value?.trim() || '';

        const slots =
          document.getElementById('editSlots')?.value ?? '';

        const status =
          document.getElementById('editStatus')?.value || '';

        const startDate =
          document.getElementById('editStartDate')?.value || '';

        const endDate =
          document.getElementById('editEndDate')?.value || '';

        const startTime =
          document.getElementById('editStartTime')?.value || '';

        const endTime =
          document.getElementById('editEndTime')?.value || '';

        const trainingDays = Array.from(
          form.querySelectorAll(
            'input[name="training_days[]"]:checked'
          )
        ).map(checkbox => checkbox.value);

        // Read training_id only if the form actually has this field.
        const trainingIdInput = document.getElementById('editTrainingId');
        const trainingId = trainingIdInput?.value?.trim() || '';

        // Basic validation.
        if (!courseCode || !title || !duration || slots === '') {
          alert('Please fill in all required course fields.');
          return;
        }

        if (!Number.isInteger(Number(duration)) || Number(duration) < 1) {
          alert('Duration must be a whole number greater than zero.');
          return;
        }

        if (!Number.isInteger(Number(slots)) || Number(slots) < 0) {
          alert('Slots must be a non-negative whole number.');
          return;
        }

        if (!['active', 'inactive'].includes(status.toLowerCase())) {
          alert('Please select a valid course status.');
          return;
        }

        if (startDate && endDate && endDate < startDate) {
          alert('The training end date cannot be before the start date.');
          return;
        }

        if (!startTime || !endTime) {
          alert('Please enter both the training start time and end time.');
          return;
        }

        // Supports schedules that cross midnight.
        if (startTime === endTime) {
          alert('The training start and end times cannot be the same.');
          return;
        }

        const isEdit = courseId !== '';

        const url = isEdit ?
          `/admin/course/${encodeURIComponent(courseId)}` :
          '/admin/course/store';

        const method = isEdit ? 'PUT' : 'POST';

        const payload = {
          course_code: courseCode,
          title: title,
          duration: Number(duration),
          slots: Number(slots),
          status: status.toLowerCase(),
          start_date: startDate || null,
          end_date: endDate || null,
          start_time: startTime,
          end_time: endTime,
          training_days: trainingDays
        };

        // Include training_id only if the form has a populated input.
        if (trainingId) {
          payload.training_id = trainingId;
        }

        const originalButtonText = submitButton ?
          (submitButton.tagName === 'INPUT' ?
            submitButton.value :
            submitButton.innerHTML) :
          '';

        try {
          if (submitButton) {
            submitButton.disabled = true;

            if (submitButton.tagName === 'INPUT') {
              submitButton.value = 'Saving...';
            } else {
              submitButton.textContent = 'Saving...';
            }
          }

          const response = await fetch(url, {
            method: method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          });

          const data = await response.json().catch(() => null);

          if (!response.ok) {
            if (response.status === 422 && data?.errors) {
              const messages = Object.values(data.errors).flat();
              throw new Error(messages.join('\n'));
            }

            throw new Error(
              data?.message ||
              `Unable to save course (HTTP ${response.status}).`
            );
          }

          if (!data || data.success !== true) {
            throw new Error(data?.message || 'Unable to save the course.');
          }

          alert(
            isEdit ?
            'Course updated successfully!' :
            'Course created successfully!'
          );

          closeModal();

          window.location.href =
            window.location.pathname + '?view=courses';

        } catch (error) {
          console.error('Course save error:', error);
          alert(error.message || 'An error occurred. Please try again.');

        } finally {
          if (submitButton) {
            submitButton.disabled = false;

            if (submitButton.tagName === 'INPUT') {
              submitButton.value = originalButtonText;
            } else {
              submitButton.innerHTML = originalButtonText;
            }
          }
        }
      };

      document.getElementById('facilityForm').onsubmit = function(e) {
        e.preventDefault();

        const idVal = document.getElementById('editFacId')?.value?.trim();
        const id = idVal ? idVal : null;

        const name = document.getElementById('editFacName').value.trim();
        const address = document.getElementById('editFacAddress').value.trim();

        const selectedCourseIds = Array.from(
          document.querySelectorAll('.facility-course-cb:checked')
        ).map(cb => cb.value);

        if (!name || !address) {
          showAlert('Please enter a facility name and address.');
          return;
        }

        const csrfToken = typeof getCsrfToken === 'function' ?
          getCsrfToken() :
          document.querySelector('meta[name="csrf-token"]')?.getAttribute(
            'content');

        fetch('/admin/facility/save', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              id: id,
              name: name,
              address: address,
              course_ids: selectedCourseIds
            })
          })
          .then(async response => {
            const data = await response.json().catch(() => null);

            if (response.ok && data && data.success) {
              showAlert(data.message ||
                'Facility details saved successfully!');
              closeFacilityModal();

              localStorage.setItem('activeAdminTab', 'view-facilities');
              location.reload();
            } else {
              let errorMsg = 'Failed to save facility details.';
              if (data && data.errors) {
                errorMsg = Object.values(data.errors).flat().join('\n');
              } else if (data && data.message) {
                errorMsg = data.message;
              }
              showAlert(errorMsg);
            }
          })
          .catch(error => {
            console.error('Facility save error:', error);
            showAlert('An error occurred while saving the facility.');
          });
      };

      /* ========================================================== */
      /* ANNOUNCEMENT MODAL & ACTIONS HANDLERS                      */
      /* ========================================================== */
      // Utility helper to format database ISO/SQL timestamps for datetime-local inputs (YYYY-MM-DDTHH:mm)
      function formatForDateTimeInput(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return '';

        const pad = (num) => String(num).padStart(2, '0');

        const year = date.getFullYear();
        const month = pad(date.getMonth() + 1);
        const day = pad(date.getDate());
        const hours = pad(date.getHours());
        const minutes = pad(date.getMinutes());

        return `${year}-${month}-${day}T${hours}:${minutes}`;
      }

      // Open Modal (New Announcement)
      function openAnnouncementModal() {
        document.getElementById('annId').value = '';
        document.getElementById('annTitle').value = '';
        document.getElementById('annMessage').value = '';
        document.getElementById('annType').value = 'reminder';

        const isActiveCb = document.getElementById('annIsActive');
        if (isActiveCb) isActiveCb.checked = true;

        document.getElementById('annPublishAt').value = '';
        document.getElementById('annExpiresAt').value = '';

        document.getElementById('titleCounter').textContent = '0/100';
        document.getElementById('messageCounter').textContent = '0/500';

        const statusLabel = document.getElementById('statusLabel');
        if (statusLabel) statusLabel.textContent = 'Active';

        document.getElementById('annModalTitle').innerHTML =
          '<i class="fa-solid fa-bell"></i> Add Announcement';
        document.getElementById('announcementModal').style.display = 'flex';
      }

      // Open Modal (Edit Announcement)
      function handleEditAnnouncement(button) {
        const id = button.getAttribute('data-id');
        const title = button.getAttribute('data-title') || '';
        const message = button.getAttribute('data-message') || '';
        const type = button.getAttribute('data-type') || 'reminder';
        const active = button.getAttribute('data-active') === '1';
        const publishAt = button.getAttribute('data-publish-at') || '';
        const expiresAt = button.getAttribute('data-expires-at') || '';

        document.getElementById('annId').value = id;
        document.getElementById('annTitle').value = title;
        document.getElementById('annMessage').value = message;
        document.getElementById('annType').value = type;

        const isActiveCb = document.getElementById('annIsActive');
        if (isActiveCb) isActiveCb.checked = active;

        document.getElementById('annPublishAt').value = formatForDateTimeInput(
          publishAt);
        document.getElementById('annExpiresAt').value = formatForDateTimeInput(
          expiresAt);

        document.getElementById('titleCounter').textContent = `${title.length}/100`;
        document.getElementById('messageCounter').textContent =
          `${message.length}/500`;

        const statusLabel = document.getElementById('statusLabel');
        if (statusLabel) statusLabel.textContent = active ? 'Active' : 'Inactive';

        document.getElementById('annModalTitle').innerHTML =
          '<i class="fa-solid fa-pen-to-square"></i> Edit Announcement';
        document.getElementById('announcementModal').style.display = 'flex';
      }

      // Close Modal
      function closeAnnouncementModal() {
        const modal = document.getElementById('announcementModal');
        const form = document.getElementById('announcementForm');
        const btnSubmit = document.getElementById('btnSubmitAnn');

        if (modal) modal.style.display = 'none';
        if (form) form.reset();

        const titleCounter = document.getElementById('titleCounter');
        const messageCounter = document.getElementById('messageCounter');
        if (titleCounter) titleCounter.textContent = '0/100';
        if (messageCounter) messageCounter.textContent = '0/500';

        if (btnSubmit) {
          btnSubmit.disabled = false;
          btnSubmit.innerHTML = 'Save';
        }
      }

      // Submit Form Handler (Save/Update with Success & Error Feedback)
      document.getElementById('announcementForm').onsubmit = function(e) {
        e.preventDefault();

        const id = document.getElementById('annId')?.value || '';
        const title = document.getElementById('annTitle')?.value.trim() || '';
        const message = document.getElementById('annMessage')?.value.trim() || '';
        const type = document.getElementById('annType')?.value || 'reminder';
        const isActive = document.getElementById('annIsActive')?.checked ? 1 : 0;

        // Read SQL formatted datetime directly from input elements
        const publishAt = document.getElementById('annPublishAt')?.value || null;
        const expiresAt = document.getElementById('annExpiresAt')?.value || null;

        const btnSubmit = document.getElementById('btnSubmitAnn');
        const originalBtnText = btnSubmit ? btnSubmit.innerHTML : 'Save';

        if (btnSubmit) {
          btnSubmit.disabled = true;
          btnSubmit.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
        }

        const isEdit = id !== '' && id !== null;
        const url = isEdit ? `/admin/announcement/${id}` : '/admin/announcement';

        const token = typeof csrfToken !== 'undefined' ?
          csrfToken :
          document.querySelector('meta[name="csrf-token"]')?.getAttribute(
            'content') || '';

        const payload = {
          title: title,
          message: message,
          type: type,
          is_active: isActive,
          publish_at: publishAt ? publishAt : null,
          expires_at: expiresAt ? expiresAt : null
        };

        if (isEdit) {
          payload._method = 'PUT';
        }

        fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          })
          .then(async response => {
            const data = await response.json().catch(() => null);

            if (response.ok && data && data.success) {
              // Visual button feedback on success
              if (btnSubmit) {
                btnSubmit.style.backgroundColor = '#025628';
                btnSubmit.innerHTML = isEdit ?
                  '<i class="fa-solid fa-check"></i> Updated Successfully!' :
                  '<i class="fa-solid fa-check"></i> Created Successfully!';
              }

              setTimeout(() => {
                alert(data.message || (isEdit ?
                  'Announcement updated successfully!' :
                  'Announcement created successfully!'));
                closeAnnouncementModal();
                window.location.href = window.location.pathname +
                  '?view=announcements';
              }, 200);

            } else {
              let errorMsg = 'Validation error. Check your inputs.';
              if (data && data.errors) {
                errorMsg = Object.values(data.errors).flat().join('\n');
              } else if (data && data.message) {
                errorMsg = data.message;
              }

              alert('Error saving announcement:\n' + errorMsg);

              if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.style.backgroundColor = '';
                btnSubmit.innerHTML = originalBtnText;
              }
            }
          })
          .catch(err => {
            console.error('Save announcement error details:', err);
            alert(
              'An error occurred while saving. Check F12 Console for exact details.'
            );

            if (btnSubmit) {
              btnSubmit.disabled = false;
              btnSubmit.style.backgroundColor = '';
              btnSubmit.innerHTML = originalBtnText;
            }
          });
      };

      // Delete Announcement Handler
      function deleteAnnouncement(id, btn = null) {
        if (!confirm(
            'Are you sure you want to delete this announcement? This action cannot be undone.'
          )) {
          return;
        }

        if (btn) btn.disabled = true;

        const token = typeof csrfToken !== 'undefined' ?
          csrfToken :
          document.querySelector('meta[name="csrf-token"]')?.getAttribute(
            'content') || '';

        fetch(`/admin/announcement/${id}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            }
          })
          .then(async response => {
            const data = await response.json().catch(() => null);

            if (response.ok && data && data.success) {
              alert(data.message || 'Announcement deleted successfully!');
              window.location.href = window.location.pathname +
                '?view=announcements';
            } else {
              alert('Failed to delete announcement: ' + ((data && data
                .message) ? data.message : 'Server error'));
              if (btn) btn.disabled = false;
            }
          })
          .catch(err => {
            console.error('Delete announcement error:', err);
            alert('An error occurred while deleting the announcement.');
            if (btn) btn.disabled = false;
          });
      }

      // DOM Event Listeners Initialization
      document.addEventListener('DOMContentLoaded', function() {
        const isActiveCb = document.getElementById('annIsActive');
        const statusLabel = document.getElementById('statusLabel');
        const titleInput = document.getElementById('annTitle');
        const messageInput = document.getElementById('annMessage');
        const modal = document.getElementById('announcementModal');

        // Live Status Label Sync
        if (isActiveCb && statusLabel) {
          isActiveCb.addEventListener('change', function() {
            statusLabel.textContent = this.checked ? 'Active' : 'Inactive';
          });
        }

        // Live Character Counters
        if (titleInput) {
          titleInput.addEventListener('input', function() {
            document.getElementById('titleCounter').textContent =
              `${this.value.length}/100`;
          });
        }

        if (messageInput) {
          messageInput.addEventListener('input', function() {
            document.getElementById('messageCounter').textContent =
              `${this.value.length}/500`;
          });
        }

        // Close modal when clicking on dark backdrop
        window.addEventListener('click', function(e) {
          if (e.target === modal) {
            closeAnnouncementModal();
          }
        });
      });

      // Helper: Format raw database ISO string to "YYYY-MM-DDTHH:mm" for input[type="datetime-local"]
      function formatForDateTimeInput(dateStr) {
        if (!dateStr) return '';
        const formatted = dateStr.trim().replace(' ', 'T');
        if (formatted.length >= 16) {
          return formatted.substring(0, 16);
        }
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return '';
        const pad = (num) => String(num).padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
      }

      // Helper: Convert "YYYY-MM-DDTHH:mm" datetime-local value to SQL format "YYYY-MM-DD HH:mm:00" or null
      function cleanDateTimeInput(val) {
        if (!val || typeof val !== 'string' || val.trim() === '') return null;
        let formatted = val.trim().replace('T', ' ');
        if (formatted.length === 16) {
          formatted += ':00';
        }
        return formatted;
      }

      // Submit Form Handler
      document.addEventListener('DOMContentLoaded', function() {
        const annForm = document.getElementById('announcementForm');

        if (annForm) {
          // Clear any existing inline submit handlers to avoid duplicate triggers
          annForm.onsubmit = null;

          annForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e
              .stopImmediatePropagation(); // Block duplicate event listeners from executing

            const btnSubmit = document.getElementById('btnSubmitAnn');

            // Double-submit protection: ignore if a request is already in progress
            if (btnSubmit && btnSubmit.disabled) {
              return;
            }

            const id = document.getElementById('annId')?.value || '';
            const title = document.getElementById('annTitle')?.value
              .trim() || '';
            const message = document.getElementById('annMessage')?.value
              .trim() || '';
            const type = document.getElementById('annType')?.value ||
              'reminder';
            const isActive = document.getElementById('annIsActive')
              ?.checked ? 1 : 0;

            const rawPublish = document.getElementById('annPublishAt')
              ?.value || '';
            const rawExpires = document.getElementById('annExpiresAt')
              ?.value || '';

            const originalBtnText = btnSubmit ? btnSubmit.innerHTML :
              'Save';

            // Immediately disable button to prevent double-click submissions
            if (btnSubmit) {
              btnSubmit.disabled = true;
              btnSubmit.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
            }

            const isEdit = id !== '' && id !== null;
            const url = isEdit ? `/admin/announcement/${id}` :
              '/admin/announcement';

            const token = typeof csrfToken !== 'undefined' ?
              csrfToken :
              document.querySelector('meta[name="csrf-token"]')
              ?.getAttribute('content') || '';

            const payload = {
              title: title,
              message: message,
              type: type,
              is_active: isActive,
              publish_at: typeof cleanDateTimeInput === 'function' ?
                cleanDateTimeInput(rawPublish) : (rawPublish || null),
              expires_at: typeof cleanDateTimeInput === 'function' ?
                cleanDateTimeInput(rawExpires) : (rawExpires || null)
            };

            if (isEdit) {
              payload._method = 'PUT';
            }

            fetch(url, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': token,
                  'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
              })
              .then(async response => {
                const data = await response.json().catch(() => null);

                if (response.ok && data && data.success) {
                  // Immediate visual button feedback
                  if (btnSubmit) {
                    btnSubmit.style.backgroundColor = '#025628';
                    btnSubmit.innerHTML = isEdit ?
                      '<i class="fa-solid fa-check"></i> Updated Successfully!' :
                      '<i class="fa-solid fa-check"></i> Created Successfully!';
                  }

                  setTimeout(() => {
                    alert(data.message || (isEdit ?
                      'Announcement updated successfully!' :
                      'Announcement created successfully!'));
                    closeAnnouncementModal();
                    window.location.href = window.location
                      .pathname + '?view=announcements';
                  }, 200);

                } else {
                  let errorMsg =
                    'Validation error. Please check your form fields.';
                  if (data && data.errors) {
                    errorMsg = Object.values(data.errors).flat().join(
                      '\n');
                  } else if (data && data.message) {
                    errorMsg = data.message;
                  }

                  alert('Error saving announcement:\n' + errorMsg);

                  if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.style.backgroundColor = '';
                    btnSubmit.innerHTML = originalBtnText;
                  }
                }
              })
              .catch(err => {
                console.error('Save announcement error details:', err);
                alert(
                  'An error occurred while saving. Check Console (F12) for details.'
                );

                if (btnSubmit) {
                  btnSubmit.disabled = false;
                  btnSubmit.style.backgroundColor = '';
                  btnSubmit.innerHTML = originalBtnText;
                }
              });
          });
        }
      });

      function deleteAnn(id) {
        if (!confirm('Are you sure you want to delete this announcement?')) return;
        fetch(`/admin/announcement/${id}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            }
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) location.reload();
          })
          .catch(() => alert('An error occurred. Please try again.'));
      }

      let _contentCourseId = null;
      let _contentModules = [];
      let _contentQuizzes = [];

      function openContentModal(courseId, courseTitle) {
        _contentCourseId = courseId;

        const modal = document.getElementById('contentModal');
        const title = document.getElementById('contentModalCourseTitle');

        if (!modal || !title) {
          console.error('Course content modal elements are missing.');
          return;
        }

        title.textContent = courseTitle || 'Course';

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        switchContentTab('modules');
        fetchCourseContent(courseId);
      }

      function closeContentModal() {
        const modal = document.getElementById('contentModal');

        if (modal) {
          modal.style.display = 'none';
        }

        // Invalidate pending course-content requests
        if (typeof contentRequestId !== 'undefined') {
          contentRequestId++;
        }

        // Reset selected course and content
        _contentCourseId = null;
        _contentModules = [];
        _contentQuizzes = [];

        // Reset module form
        const moduleTitle = document.getElementById('newModuleTitle');
        const moduleDesc = document.getElementById('newModuleDesc');
        const moduleFile = document.getElementById('newModuleFile');

        if (moduleTitle) moduleTitle.value = '';
        if (moduleDesc) moduleDesc.value = '';
        if (moduleFile) moduleFile.value = '';

        // Reset quiz form
        const quizTitle = document.getElementById('newQuizTitle');
        const quizModule = document.getElementById('newQuizModule');
        const quizPass = document.getElementById('newQuizPass');
        const quizTime = document.getElementById('newQuizTime');

        if (quizTitle) quizTitle.value = '';
        if (quizModule) quizModule.value = '';
        if (quizPass) quizPass.value = '75';
        if (quizTime) quizTime.value = '30';

        // Return to the Modules tab
        if (
          document.getElementById('content-tab-modules') &&
          document.getElementById('content-tab-quizzes')
        ) {
          switchContentTab('modules');
        }

        document.body.style.overflow = '';
      }

      function switchContentTab(tab) {
        const isModules = tab === 'modules';

        const modulesTab = document.getElementById('content-tab-modules');
        const quizzesTab = document.getElementById('content-tab-quizzes');
        const modulesBtn = document.getElementById('tab-btn-modules');
        const quizzesBtn = document.getElementById('tab-btn-quizzes');

        if (!modulesTab || !quizzesTab || !modulesBtn || !quizzesBtn) {
          console.error('Modules/Quizzes modal elements are missing.');
          return;
        }

        modulesTab.style.display = isModules ? 'block' : 'none';
        quizzesTab.style.display = isModules ? 'none' : 'block';

        [modulesBtn, quizzesBtn].forEach((button, index) => {
          const active = index === (isModules ? 0 : 1);

          button.style.borderBottom = active ?
            '2px solid #025628' :
            '2px solid transparent';

          button.style.marginBottom = '-2px';
          button.style.color = active ? '#025628' : '#888';
          button.style.fontWeight = active ? '700' : '600';
        });

        if (!isModules) {
          populateQuizModuleDropdown();
        }
      }

      let contentRequestId = 0;

      async function fetchCourseContent(courseId) {
        const requestId = ++contentRequestId;

        try {
          const response = await fetch(
            `/admin/course/${encodeURIComponent(courseId)}/content`, {
              headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
              }
            }
          );

          const data = await response.json().catch(() => null);

          if (!response.ok || !data ||
            !Array.isArray(data.modules) ||
            !Array.isArray(data.quizzes)) {
            throw new Error(
              data?.message || `Failed to load content (${response.status}).`
            );
          }

          if (
            requestId !== contentRequestId ||
            String(courseId) !== String(_contentCourseId)
          ) {
            return;
          }

          _contentModules = data.modules;
          _contentQuizzes = data.quizzes;

          renderModules();
          renderQuizzes();
          populateQuizModuleDropdown();
          updateCourseCardCounts();

        } catch (error) {
          if (requestId !== contentRequestId) return;

          console.error('Course content loading error:', error);
          alert(error.message || 'Failed to load course content.');
        }
      }

      function renderModules() {
        const container = document.getElementById('moduleListContainer');
        if (!container) return;

        if (!_contentModules || !_contentModules.length) {
          container.innerHTML = `
        <div style="text-align:center; color:#bbb; font-size:13px; padding:20px 0;" id="modulesEmptyState">
          <i class="fa-solid fa-inbox" style="font-size:24px; display:block; margin-bottom:6px; color:#ccc;"></i>
          No modules created yet.
        </div>
      `;
          return;
        }

        container.innerHTML = _contentModules.map((m, i) => `
      <div id="module-card-${m.id}" style="display:flex; align-items:center; justify-content:space-between; gap:12px; background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:12px 16px; margin-bottom:8px; box-shadow:0 1px 2px rgba(0,0,0,0.03);">
        
        <div style="display:flex; align-items:center; gap:12px; flex:1; min-width:0;">
          <div style="width:30px; height:30px; border-radius:50%; background:#e8f5e9; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#025628; flex-shrink:0;">
            ${i + 1}
          </div>

          <div style="flex:1; min-width:0;">
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
              <strong style="font-size:14px; color:#1f2937;">${escHtml(m.title)}</strong>
              
              <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:12px; background:${(m.is_active !== false && m.is_published !== false) ? '#e8f5e9' : '#fff8e1'}; color:${(m.is_active !== false && m.is_published !== false) ? '#025628' : '#854F0B'};">
                ${(m.is_active !== false && m.is_published !== false) ? 'Published' : 'Draft'}
              </span>
            </div>
            
            <div style="font-size:12px; color:#6b7280; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
              ${m.description ? escHtml(m.description) : '<span style="color:#b0b0b0; font-style:italic;">No description</span>'}
            </div>
          </div>
        </div>

        <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
          ${m.file_path ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <a href="/admin/module/file/${m.id}/${encodeURIComponent(m.title)}.pdf" target="_blank" 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    style="font-size:11px; padding:6px 12px; border-radius:6px; background:#e8f5e9; color:#025628; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; transition: background 0.2s;">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <i class="fa-solid fa-file-pdf"></i> View File
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ` : `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <span style="font-size:11px; color:#9ca3af; padding:4px 8px; font-style:italic;">No PDF</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                `}

          <button type="button" onclick="deleteModule(${m.id})"
            style="font-size:11px; padding:6px 12px; border-radius:6px; background:#FCEBEB; color:#A32D2D; border:none; cursor:pointer; font-family:inherit; font-weight:700; white-space:nowrap; display:inline-flex; align-items:center; gap:4px;">
            <i class="fa-solid fa-trash"></i> Remove
          </button>
        </div>
      </div>
    `).join('');
      }

      function renderQuizzes() {
        const container = document.getElementById('quizListContainer');
        const empty = document.getElementById('quizzesEmptyState');

        const validQuizzes = (_contentQuizzes || []).filter(q => q && typeof q ===
          'object');

        if (!validQuizzes.length) {
          if (empty) empty.style.display = 'block';
          if (container) {
            container.innerHTML = '';
            if (empty) container.appendChild(empty);
          }
          return;
        }
        if (empty) empty.style.display = 'none';

        if (container) {
          container.innerHTML = validQuizzes.map(q => `
        <div style="display:flex; flex-direction:column; gap:0; background:#fff; border:1px solid #eee; border-radius:10px; overflow:hidden;">
          <div style="display:flex; align-items:center; gap:10px; padding:10px 14px;">
            
            <div style="flex:1; min-width:0;">
              <div style="font-size:13px; font-weight:700; color:#1a1a1a;">${escHtml(q.title)}</div>
              <div style="font-size:11px; color:#888;">
                ${q.module ? `<i class="fa-solid fa-cube"></i> ${escHtml(q.module.title)} &nbsp;·&nbsp;` : ''}
                <i class="fa-solid fa-clock"></i> ${q.time_limit || 30}m &nbsp;·&nbsp;
                <i class="fa-solid fa-star"></i> ${q.passing_score || 75}% passing
              </div>
            </div>
            <button onclick="toggleQuizQuestions(${q.id}, this)"
              style="font-size:11px; padding:4px 10px; border-radius:6px; background:#e8f5e9; color:#025628; border:none; cursor:pointer; font-family:inherit; font-weight:700; white-space:nowrap;">
              <i class="fa-solid fa-list"></i> Questions
            </button>
            <button onclick="deleteQuiz(${q.id})"
              style="font-size:11px; padding:4px 10px; border-radius:6px; background:#FCEBEB; color:#A32D2D; border:none; cursor:pointer; font-family:inherit; font-weight:700; white-space:nowrap;">
              <i class="fa-solid fa-trash"></i> Remove
            </button>
          </div>
          <div id="quiz-questions-${q.id}" style="display:none; border-top:1px solid #eee; padding:12px 14px; background:#fafafa;">
            <div id="qlist-${q.id}" style="display:flex; flex-direction:column; gap:6px; margin-bottom:10px;"></div>
            <div style="background:#fff; border:1px solid #eee; border-radius:8px; padding:12px;">
              <div style="font-size:12px; font-weight:700; color:#025628; margin-bottom:8px; text-transform:uppercase;">
                <i class="fa-solid fa-plus"></i> Add Question
              </div>
              <textarea id="qtext-${q.id}" placeholder="Question text..." rows="2"
                style="width:100%; border:1px solid #ddd; border-radius:8px; padding:8px; font-size:13px; font-family:inherit; margin-bottom:8px; resize:vertical;"></textarea>
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-bottom:8px;">
                <input type="text" id="qa-${q.id}" placeholder="A. Choice A" style="border:1px solid #ddd; border-radius:8px; padding:7px 10px; font-size:13px; font-family:inherit;">
                <input type="text" id="qb-${q.id}" placeholder="B. Choice B" style="border:1px solid #ddd; border-radius:8px; padding:7px 10px; font-size:13px; font-family:inherit;">
                <input type="text" id="qc-${q.id}" placeholder="C. Choice C" style="border:1px solid #ddd; border-radius:8px; padding:7px 10px; font-size:13px; font-family:inherit;">
                <input type="text" id="qd-${q.id}" placeholder="D. Choice D" style="border:1px solid #ddd; border-radius:8px; padding:7px 10px; font-size:13px; font-family:inherit;">
              </div>
              <div style="display:flex; gap:8px; align-items:center;">
                <label style="font-size:12px; color:#666;">Correct answer:</label>
                <select id="qans-${q.id}" style="border:1px solid #ddd; border-radius:8px; padding:6px 10px; font-size:13px; font-family:inherit; background:#fff;">
                  <option value="a">A</option>
                  <option value="b">B</option>
                  <option value="c">C</option>
                  <option value="d">D</option>
                </select>
                <button onclick="addQuestion(${q.id})"
                  style="background:#025628; color:#fff; border:none; border-radius:8px; padding:7px 16px; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; margin-left:auto;">
                  <i class="fa-solid fa-plus"></i> Add
                </button>
              </div>
            </div>
          </div>
        </div>
      `).join('');
        }
      }

      function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content ||
          (typeof csrfToken !== 'undefined' ? csrfToken : '');
      }

      function addModule(event) {
        if (event && typeof event.preventDefault === 'function') {
          event.preventDefault();
        }

        const titleInput = document.getElementById('newModuleTitle');
        const descInput = document.getElementById('newModuleDesc');
        const fileInput = document.getElementById('newModuleFile');

        const title = titleInput?.value.trim() || '';
        const desc = descInput?.value.trim() || '';
        const file = fileInput?.files[0];

        if (!title) {
          alert('Please enter a module title.');
          titleInput?.focus();
          return;
        }

        if (typeof _contentCourseId === 'undefined' || !_contentCourseId) {
          alert('No active course selected.');
          return;
        }

        const formData = new FormData();
        formData.append('course_id', _contentCourseId);
        formData.append('title', title);
        formData.append('description', desc);
        if (file) {
          formData.append('file', file);
        }

        fetch('/admin/module', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': getCsrfToken(),
              'Accept': 'application/json'
            },
            body: formData
          })
          .then(async response => {
            const data = await response.json().catch(() => null);

            if (response.ok && data && data.success) {
              if (!Array.isArray(_contentModules)) {
                _contentModules = [];
              }

              const rawModule = data.module || data.data || data;

              const newModule = {
                id: rawModule.id || Date.now(),
                title: rawModule.title || title,
                description: rawModule.description || desc,
                file_path: rawModule.file_path || null,
                is_active: rawModule.is_active ?? true
              };

              _contentModules.push(newModule);

              if (titleInput) titleInput.value = '';
              if (descInput) descInput.value = '';
              if (fileInput) fileInput.value = '';

              renderModules();
              updateCourseCardCounts();
              if (typeof populateQuizModuleDropdown === 'function') {
                populateQuizModuleDropdown();
              }
            } else {
              let errorMsg = 'Failed to create module.';
              if (data && data.errors) {
                errorMsg = Object.values(data.errors).flat().join('\n');
              } else if (data && data.message) {
                errorMsg = data.message;
              }
              alert(errorMsg);
            }
          })
          .catch(error => {
            console.error('Add module request error:', error);
            alert(
              'An error occurred while uploading the module. Please try again.');
          });
      }

      function deleteModule(id) {
        if (!confirm('Are you sure you want to remove this module?')) return;

        fetch(`/admin/module/${id}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': getCsrfToken(),
              'Accept': 'application/json'
            }
          })
          .then(async response => {
            const data = await response.json().catch(() => null);

            if (response.ok || response.status === 404) {
              if (Array.isArray(_contentModules)) {
                _contentModules = _contentModules.filter(m => m && String(m
                  .id) !== String(id));
              }

              try {
                renderModules();
              } catch (e) {
                console.error('Render modules error:', e);
              }
              try {
                updateCourseCardCounts();
              } catch (e) {
                console.error('Update counts error:', e);
              }
              try {
                if (typeof populateQuizModuleDropdown === 'function') {
                  populateQuizModuleDropdown();
                }
              } catch (e) {
                console.error('Update dropdown error:', e);
              }
            } else {
              const errorMsg = (data && data.message) ? data.message :
                `Server error (${response.status})`;
              alert(errorMsg);
            }
          })
          .catch(error => {
            console.error('Delete module fetch request failed:', error);
            if (Array.isArray(_contentModules)) {
              _contentModules = _contentModules.filter(m => m && String(m.id) !==
                String(id));
              try {
                renderModules();
              } catch (e) {}
              try {
                updateCourseCardCounts();
              } catch (e) {}
            }
            alert('Network error while attempting to delete the module.');
          });
      }

      function addQuiz(event) {
        if (event && typeof event.preventDefault === 'function') {
          event.preventDefault();
        }

        const titleInput = document.getElementById('newQuizTitle');
        const title = titleInput?.value.trim() || '';
        const moduleId = document.getElementById('newQuizModule')?.value || null;
        const passing = parseInt(document.getElementById('newQuizPass')?.value) ||
          75;
        const time = parseInt(document.getElementById('newQuizTime')?.value) || 30;

        if (!title) {
          alert('Please enter a quiz title.');
          titleInput?.focus();
          return;
        }

        fetch('/admin/quiz', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': getCsrfToken(),
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              course_id: _contentCourseId,
              module_id: moduleId,
              title: title,
              passing_score: passing,
              time_limit: time
            })
          })
          .then(async response => {
            const data = await response.json().catch(() => null);

            if (response.ok && data && data.success) {
              if (!Array.isArray(_contentQuizzes)) _contentQuizzes = [];
              const newQuiz = data.quiz || data.data || {
                id: Date.now(),
                title: title,
                time_limit: time,
                passing_score: passing
              };
              _contentQuizzes.push(newQuiz);

              if (titleInput) titleInput.value = '';

              renderQuizzes();
              updateCourseCardCounts();
            } else {
              alert((data && data.message) ? data.message :
                'Failed to create quiz.');
            }
          })
          .catch(error => {
            console.error('Add quiz request error:', error);
            alert('An error occurred while creating the quiz.');
          });
      }

      function deleteQuiz(id) {
        if (!confirm('Are you sure you want to remove this quiz?')) return;

        fetch(`/admin/quiz/${id}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': getCsrfToken(),
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              _method: 'DELETE'
            })
          })
          .then(async response => {
            const data = await response.json().catch(() => null);

            if (response.ok || response.status === 404) {
              if (Array.isArray(_contentQuizzes)) {
                _contentQuizzes = _contentQuizzes.filter(q => q && String(q
                  .id) !== String(id));
              }

              renderQuizzes();
              updateCourseCardCounts();
            } else {
              alert((data && data.message) ? data.message :
                `Server error (${response.status})`);
            }
          })
          .catch(error => {
            console.error('Delete quiz error:', error);
            if (Array.isArray(_contentQuizzes)) {
              _contentQuizzes = _contentQuizzes.filter(q => q && String(q.id) !==
                String(id));
              renderQuizzes();
              updateCourseCardCounts();
            }
          });
      }

      function populateQuizModuleDropdown() {
        const sel = document.getElementById('newQuizModule');
        if (!sel) return;
        sel.innerHTML = '<option value="">— Link to module (optional) —</option>';

        if (Array.isArray(_contentModules)) {
          _contentModules.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m.id;
            opt.textContent = m.title;
            sel.appendChild(opt);
          });
        }
      }

      function escHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str || ''));
        return div.innerHTML;
      }

      function toggleQuizQuestions(quizId, btn) {
        const panel = document.getElementById(`quiz-questions-${quizId}`);
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        if (!isOpen) loadQuizQuestions(quizId);
      }

      function loadQuizQuestions(quizId) {
        fetch(`/admin/quiz/${quizId}/questions`, {
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            }
          })
          .then(r => r.json())
          .then(data => renderQuizQuestions(quizId, data.questions || []))
          .catch(() => alert('Failed to load questions.'));
      }

      function renderQuizQuestions(quizId, questions) {
        const container = document.getElementById(`qlist-${quizId}`);
        if (!questions.length) {
          container.innerHTML =
            '<div style="font-size:12px; color:#aaa; text-align:center; padding:8px;">No questions created yet.</div>';
          return;
        }
        container.innerHTML = questions.map((q, i) => `
          <div style="display:flex; align-items:flex-start; gap:8px; background:#fff; border:1px solid #eee; border-radius:8px; padding:8px 12px;">
              <div style="width:22px; height:22px; border-radius:50%; background:#e8f5e9; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#025628; flex-shrink:0; margin-top:1px;">
                  ${i+1}
              </div>
              <div style="flex:1; min-width:0;">
                  <div style="font-size:13px; font-weight:600; color:#1a1a1a; margin-bottom:4px;">${escHtml(q.question)}</div>
                  <div style="font-size:11px; color:#555; display:grid; grid-template-columns:1fr 1fr; gap:2px;">
                      <span ${q.correct_answer==='a' ? 'style="color:#025628; font-weight:700;"' : ''}>A. ${escHtml(q.choice_a)}</span>
                      <span ${q.correct_answer==='b' ? 'style="color:#025628; font-weight:700;"' : ''}>B. ${escHtml(q.choice_b)}</span>
                      <span ${q.correct_answer==='c' ? 'style="color:#025628; font-weight:700;"' : ''}>C. ${escHtml(q.choice_c)}</span>
                      <span ${q.correct_answer==='d' ? 'style="color:#025628; font-weight:700;"' : ''}>D. ${escHtml(q.choice_d)}</span>
                  </div>
              </div>
              <button onclick="deleteQuestion(${q.id}, ${quizId})"
                  style="font-size:11px; padding:3px 8px; border-radius:6px; background:#FCEBEB; color:#A32D2D; border:none; cursor:pointer; font-family:inherit; font-weight:700; flex-shrink:0;">
                  ✕
              </button>
          </div>
        `).join('');
      }

      function addQuestion(quizId) {
        const question = document.getElementById(`qtext-${quizId}`).value.trim();
        const a = document.getElementById(`qa-${quizId}`).value.trim();
        const b = document.getElementById(`qb-${quizId}`).value.trim();
        const c = document.getElementById(`qc-${quizId}`).value.trim();
        const d = document.getElementById(`qd-${quizId}`).value.trim();
        const ans = document.getElementById(`qans-${quizId}`).value;

        if (!question || !a || !b || !c || !d) {
          alert('Please fill in all question fields.');
          return;
        }

        fetch('/admin/quiz-question', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              quiz_id: quizId,
              question,
              choice_a: a,
              choice_b: b,
              choice_c: c,
              choice_d: d,
              correct_answer: ans
            })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              document.getElementById(`qtext-${quizId}`).value = '';
              document.getElementById(`qa-${quizId}`).value = '';
              document.getElementById(`qb-${quizId}`).value = '';
              document.getElementById(`qc-${quizId}`).value = '';
              document.getElementById(`qd-${quizId}`).value = '';
              loadQuizQuestions(quizId);
            }
          })
          .catch(() => alert('An error occurred. Please try again.'));
      }

      function deleteQuestion(id, quizId) {
        if (!confirm('Remove this question?')) return;
        fetch(`/admin/quiz-question/${id}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              _method: 'DELETE'
            })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) loadQuizQuestions(quizId);
          })
          .catch(() => alert('An error occurred. Please try again.'));
      }

      function toggleSelectCol() {
        const show = document.getElementById('toggleMultiple').checked;
        document.querySelectorAll('.cert-select-col').forEach(el => {
          el.style.display = show ? '' : 'none';
        });
        if (!show) document.getElementById('selectAll').checked = false;
      }

      function toggleSelectAll(cb) {
        document.querySelectorAll('.row-checkbox').forEach(c => c.checked = cb
          .checked);
      }

      function deleteCertRow(btn) {
        if (!confirm('Delete this certificate record?')) return;
        btn.closest('tr').remove();
      }

      function openCertViewModal(name, course, certNo) {
        document.getElementById('certViewName').textContent = name;
        document.getElementById('certViewCourse').textContent = course
          .toUpperCase();
        document.getElementById('certViewNo').textContent = 'CERT. NO.: ' + certNo;
        document.getElementById('certViewModal').style.display = 'flex';
      }

      function closeCertViewModal() {
        document.getElementById('certViewModal').style.display = 'none';
      }

      function openIssueCertModal() {
        document.getElementById('issueCertModal').style.display = 'flex';
        updateCertPreview();
      }

      function closeIssueCertModal() {
        document.getElementById('issueCertModal').style.display = 'none';
      }

      function updateCertPreview() {
        const sel = document.getElementById('issueTraineeSelect');
        const nameEl = document.getElementById('previewName');
        const crsEl = document.getElementById('previewCourse');
        if (!sel || !nameEl) return;
        const rawName = sel.value ? sel.value.split(' (')[0] : '[NAME]';
        const course = sel.value && sel.selectedIndex > 0 ?
          sel.options[sel.selectedIndex].getAttribute('data-course') || '[COURSE]' :
          '[COURSE]';
        nameEl.textContent = rawName.toUpperCase();
        crsEl.textContent = course.toUpperCase();
      }

      function generateCertPDF({
        name,
        course,
        controlNumber,
        dateLabel,
        docType,
        remarks
      }) {
        const {
          jsPDF
        } = window.jspdf;
        const doc = new jsPDF({
          orientation: 'landscape',
          unit: 'mm',
          format: 'a4'
        });
        const W = 297,
          H = 210;

        doc.setFillColor(255, 255, 255);
        doc.rect(0, 0, W, H, 'F');

        doc.setDrawColor(2, 86, 40);
        doc.setLineWidth(4);
        doc.rect(8, 8, W - 16, H - 16);

        doc.setDrawColor(180, 150, 50);
        doc.setLineWidth(1);
        doc.rect(12, 12, W - 24, H - 24);

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8);
        doc.setTextColor(100, 100, 100);
        doc.text('TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY', W / 2,
          36, {
            align: 'center'
          });

        doc.setFontSize(9);
        doc.setTextColor(60, 60, 60);
        doc.text('CITY GOVERNMENT OF DASMARIÑAS – LEDIPO', W / 2, 43, {
          align: 'center'
        });

        doc.setDrawColor(180, 150, 50);
        doc.setLineWidth(0.8);
        doc.line(55, 47, W - 55, 47);

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(22);
        doc.setTextColor(2, 86, 40);
        doc.text(docType.toUpperCase(), W / 2, 63, {
          align: 'center'
        });

        doc.setFont('helvetica', 'italic');
        doc.setFontSize(10);
        doc.setTextColor(90, 90, 90);
        doc.text('This is to certify that', W / 2, 75, {
          align: 'center'
        });

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(26);
        doc.setTextColor(15, 15, 15);
        doc.text(name.toUpperCase(), W / 2, 91, {
          align: 'center'
        });

        const nameW = doc.getTextWidth(name.toUpperCase());
        doc.setDrawColor(2, 86, 40);
        doc.setLineWidth(0.5);
        doc.line(W / 2 - nameW / 2, 94, W / 2 + nameW / 2, 94);

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        doc.setTextColor(80, 80, 80);
        doc.text('has successfully completed the training in', W / 2, 105, {
          align: 'center'
        });

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(15);
        doc.setTextColor(2, 86, 40);
        doc.text(course.toUpperCase(), W / 2, 117, {
          align: 'center'
        });

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(9);
        doc.setTextColor(90, 90, 90);
        doc.text(`held on ${dateLabel}`, W / 2, 126, {
          align: 'center'
        });

        if (remarks && remarks.trim()) {
          doc.setFontSize(9);
          doc.setTextColor(110, 110, 110);
          doc.text(`Remarks: ${remarks}`, W / 2, 134, {
            align: 'center'
          });
        }

        if (controlNumber && controlNumber.trim()) {
          doc.setFontSize(8);
          doc.setTextColor(160, 160, 160);
          doc.text(`Control No.: ${controlNumber}`, W - 18, H - 15, {
            align: 'right'
          });
        }

        const sig1X = 80,
          sig2X = W - 80,
          sigY = H - 38;
        doc.setDrawColor(50, 50, 50);
        doc.setLineWidth(0.5);
        doc.line(sig1X - 35, sigY, sig1X + 35, sigY);
        doc.line(sig2X - 35, sigY, sig2X + 35, sigY);

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(9);
        doc.setTextColor(20, 20, 20);
        doc.text('HON. JENNIFER A. BARZAGA', sig1X, sigY + 6, {
          align: 'center'
        });
        doc.text('MR. CARLOS H. LEGASPI', sig2X, sigY + 6, {
          align: 'center'
        });

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8);
        doc.setTextColor(100, 100, 100);
        doc.text('City Mayor', sig1X, sigY + 11, {
          align: 'center'
        });
        doc.text('LEDIPO Head', sig2X, sigY + 11, {
          align: 'center'
        });

        return doc;
      }

      function saveAndIssueCert() {
        const sel = document.getElementById('issueTraineeSelect');
        const controlNum = document.getElementById('issueControlNum').value.trim();
        const dateInput = document.getElementById('issueDate').value;
        const docType = document.getElementById('issueDocType').value;
        const remarks = document.getElementById('issueRemarks').value.trim();

        if (!sel.value) {
          alert('Please select a trainee first.');
          return;
        }

        const name = sel.value.split(' (')[0];
        const course = sel.options[sel.selectedIndex].getAttribute('data-course') ||
          '';

        const dateLabel = dateInput ?
          new Date(dateInput + 'T12:00:00').toLocaleDateString('en-PH', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          }) :
          new Date().toLocaleDateString('en-PH', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          });

        const doc = generateCertPDF({
          name,
          course,
          controlNumber: controlNum,
          dateLabel,
          docType,
          remarks
        });
        const safeName = name.replace(/[^a-zA-Z0-9]/g, '_');
        doc.save(`LEDIPO_Certificate_${safeName}.pdf`);

        addCertTableRow(name, course, dateLabel, controlNum);
        closeIssueCertModal();
        alert('Certificate issued and downloaded successfully!');
      }

      function downloadExistingCert() {
        const name = document.getElementById('certViewName').textContent;
        const course = document.getElementById('certViewCourse').textContent;
        const certNo = document.getElementById('certViewNo').textContent.replace(
          'CERT. NO.: ', '');

        const dateLabel = new Date().toLocaleDateString('en-PH', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });

        const doc = generateCertPDF({
          name,
          course,
          controlNumber: certNo,
          dateLabel,
          docType: 'Certificate of Completion',
          remarks: ''
        });

        const safeName = name.replace(/[^a-zA-Z0-9]/g, '_');
        doc.save(`LEDIPO_Certificate_${safeName}.pdf`);
      }

      function addCertTableRow(name, course, dateLabel, controlNum) {
        const tbody = document.getElementById('certTableBody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td class="cert-select-col" style="display:none;"><input type="checkbox" class="row-checkbox"></td>
          <td>${name}</td>
          <td>${course}</td>
          <td>${dateLabel}</td>
          <td><span class="cert-badge claimed">Claimed</span></td>
          <td class="cert-action-icons">
              <i class="fa fa-eye" onclick="openCertViewModal('${name}','${course}','${controlNum || 'N/A'}')" title="View"></i>
              <i class="fa fa-trash-alt" onclick="deleteCertRow(this)" title="Delete"></i>
          </td>
        `;
        tbody.insertBefore(tr, tbody.firstChild);
      }

      function toggleCourseAccordion(courseId) {
        const body = document.getElementById(`accordion-body-${courseId}`);
        const chevron = document.getElementById(`chevron-${courseId}`);

        if (body.style.display === 'none' || body.style.display === '') {
          body.style.display = 'block';
          chevron.style.transform = 'rotate(180deg)';
        } else {
          body.style.display = 'none';
          chevron.style.transform = 'rotate(0deg)';
        }
      }

      function toggleCourseTrainees(courseId) {
        const listEl = document.getElementById('trainee-list-' + courseId);
        if (listEl) {
          if (listEl.style.display === 'none' || listEl.style.display === '') {
            listEl.style.display = 'flex';
          } else {
            listEl.style.display = 'none';
          }
        }
      }

      function openFullCourseRoster(courseTitle, trainees) {
        document.getElementById('course-cards-main-view').style.display = 'none';
        document.getElementById('full-course-roster-view').style.display = 'block';

        document.getElementById('rosterCourseTitle').textContent = courseTitle +
          " - Enrolled Trainees";
        document.getElementById('rosterCountBadge').textContent = (trainees ?
          trainees.length : 0) + " Enrolled";

        const container = document.getElementById('fullRosterContainer');

        if (!trainees || trainees.length === 0) {
          container.innerHTML = `
        <div style="text-align: center; color: #aaa; padding: 40px 0; font-size: 13px; font-style: italic;">
            <i class="fa-solid fa-users-slash" style="font-size: 28px; display: block; margin-bottom: 8px; color: #ccc;"></i>
            No trainees enrolled in this course yet.
        </div>`;
        } else {
          let html = `
        <div style="display: flex; align-items: center; justify-content: space-between; background: #e8f5e9; padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 700; color: #025628; margin-bottom: 4px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" id="selectAllTrainees" onclick="toggleSelectAllTrainees(this)" style="width: 16px; height: 16px; accent-color: #025628; cursor: pointer;">
                Select All Trainees
            </label>
            <span id="selectedCountLabel">0 selected</span>
        </div>
      `;

          html += trainees.map(t => {
            // Map all required attributes from DB payload
            const userId = t.user_id || t.id || '';
            const firstName = t.firstname || '';
            const lastName = t.lastname || '';
            const fullName = (firstName + ' ' + lastName).trim()
              .toUpperCase() || 'UNKNOWN TRAINEE';
            const initials = ((firstName[0] || '') + (lastName[0] || ''))
              .toUpperCase() || 'TR';
            const email = t.email || 'No email provided';
            const contact = t.contact || '';
            const idNum = t.id_number || 'N/A'; // Learner ID
            const status = t.status ||
              'Pending'; // Default to Pending for trainees
            const remarks = t.remarks || '';
            const role = t.role || 'student';

            // Safe Date Parsing
            let created = 'August 2026';
            if (t.member_since) {
              const parsedDate = new Date(t.member_since);
              if (!isNaN(parsedDate.getTime())) {
                created = parsedDate.toLocaleDateString('en-US', {
                  month: 'long',
                  year: 'numeric'
                });
              }
            } else if (t.created) {
              created = t.created;
            }

            // Role & Status Badge Color Logic
            const cleanStatus = status.toLowerCase();
            let statusBg = '#f0f0f0'; // Gray Background
            let statusColor = '#555555'; // Dark Gray Text

            if (cleanStatus === 'inactive') {
              statusBg = '#FCEBEB'; // Soft Red
              statusColor = '#A32D2D';
            } else if (cleanStatus === 'active') {
              statusBg = '#e8f5e9'; // Active Green
              statusColor = '#025628';
            }

            const safeEmailId = email.replace(/[^a-zA-Z0-9]/g, '_');

            // String escape helper for inline onclick attributes
            const escapeAttr = (str) => String(str || '').replace(/\\/g, '\\\\')
              .replace(/'/g, "\\'").replace(/\n/g, ' ');

            return `
          <div id="roster-row-${safeEmailId}" class="user-item" data-user-id="${userId}" style="display: flex; justify-content: space-between; align-items: center; background: #f9f9f9; padding: 12px 16px; border-radius: 8px; border: 1px solid #eee;">
              <div style="display: flex; align-items: center; gap: 12px;">
                  <input type="checkbox" class="roster-checkbox" onclick="updateSelectedCount()" style="width: 16px; height: 16px; accent-color: #025628; cursor: pointer;">
                  <div style="width: 36px; height: 36px; border-radius: 50%; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: #025628; font-weight: 700; font-size: 12px; flex-shrink: 0;">
                      ${initials}
                  </div>
                  <div>
                      <strong class="user-name-text" style="color: #1a1a1a; display: block; font-size: 13px;">${fullName}</strong>
                      <div style="display: flex; align-items: center; gap: 8px; margin-top: 2px;">
                          <small class="user-email-value" style="color: #888; font-size: 11.5px;">${email}</small>
                          <span style="color: #ccc; font-size: 10px;">•</span>
                          <small style="color: #025628; font-size: 11px; font-weight: 600; background: #e8f5e9; padding: 1px 6px; border-radius: 4px;">
                            ULI: <span class="user-id-value">${idNum}</span>
                          </small>
                      </div>
                      <span class="user-contact-value" style="display:none;">${contact}</span>
                  </div>
              </div>
              
              <div style="display: flex; align-items: center; gap: 10px;">
                  <span class="roster-status-badge" data-email="${email}" style="font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; background: ${statusBg}; color: ${statusColor};">
                      ${status.charAt(0).toUpperCase() + status.slice(1)}
                  </span>
                  <button class="btn-view" onclick="openUserModal(
                    '${escapeAttr(userId)}',
                    '${escapeAttr(fullName)}',
                    '${escapeAttr(email)}',
                    '${escapeAttr(role)}',
                    '${escapeAttr(status)}',
                    '',
                    '${escapeAttr(contact)}',
                    '${escapeAttr(idNum)}',
                    '${escapeAttr(created)}',
                    '${escapeAttr(remarks)}'
                  )">View Profile</button>  
              </div>
          </div>
        `;
          }).join('');

          container.innerHTML = html;
        }
      }

      function toggleSelectAllTrainees(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.roster-checkbox');
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        updateSelectedCount();
      }

      function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.roster-checkbox');
        const checkedCount = document.querySelectorAll('.roster-checkbox:checked')
          .length;
        const label = document.getElementById('selectedCountLabel');
        if (label) {
          label.textContent = checkedCount + " selected";
        }
        const master = document.getElementById('selectAllTrainees');
        if (master) {
          master.checked = checkboxes.length > 0 && checkedCount === checkboxes
            .length;
          master.indeterminate = checkedCount > 0 && checkedCount < checkboxes
            .length;
        }
      }

      function backToCourseCards() {
        document.getElementById('full-course-roster-view').style.display = 'none';
        document.getElementById('course-cards-main-view').style.display = 'block';
      }

      function addslashes(str) {
        return String(str).replace(/['"]/g, '\\$&');
      }
      /* ========================================================== */
      /* USER PROFILE: UPDATE & DELETE HANDLERS                     */
      /* ========================================================== */

      // 1. UPDATE USER FORM SUBMISSION
      document.getElementById('userForm').onsubmit = function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalSubmitHtml = submitBtn ? submitBtn.innerHTML :
          'Update User';

        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.style.opacity = '0.7';
          submitBtn.style.cursor = 'not-allowed';
          submitBtn.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';
        }

        const id = document.getElementById('editUserId')?.value || '';
        const name = document.getElementById('editUserName')?.value.trim() || '';
        const email = document.getElementById('editUserEmail')?.value.trim() ||
          '';
        const status = document.getElementById('editUserStatus')?.value ||
          'Pending';
        const remarks = document.getElementById('editUserRemarks')?.value
          .trim() || '';

        const rawContact = document.getElementById('editUserContact')?.value
          .trim() || '';
        const contact = rawContact.replace(/[^0-9]/g, '').slice(0, 11) || '';

        const rawIdNum = document.getElementById('editUserIdNum')?.value.trim() ||
          '';
        const id_number = (rawIdNum && rawIdNum !== 'N/A') ? rawIdNum : '';

        const role = document.getElementById('hiddenUserRole')?.value ||
          document.getElementById('editUserRole')?.value ||
          'student';

        const token = document.querySelector('meta[name="csrf-token"]')
          ?.getAttribute('content') ||
          (typeof csrfToken !== 'undefined' ? csrfToken : '');

        fetch('/admin/user/update', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              id,
              name,
              email,
              status,
              remarks,
              contact,
              id_number,
              role
            })
          })
          .then(async r => {
            const text = await r.text();
            let data;
            try {
              data = JSON.parse(text);
            } catch (err) {
              console.error("Server response was not JSON:", text);
              throw new Error("Server returned an invalid response.");
            }

            if (!r.ok) {
              throw new Error(data.message || `Server error (${r.status})`);
            }

            return data;
          })
          .then(data => {
            if (data && data.success) {
              const updatedUser = data.user || {};
              const freshName = updatedUser.fullname || name;
              const freshEmail = updatedUser.email || email;
              const freshStatus = updatedUser.status || status;
              const freshContact = updatedUser.contact || contact;
              const freshIdNum = updatedUser.id_number || id_number;
              const freshRemarks = updatedUser.remarks || remarks;
              const freshRole = updatedUser.role || role;
              const freshCourse = updatedUser.course_title || '';

              const row = document.querySelector(`[data-user-id="${id}"]`) ||
                document.querySelector(`.user-item[data-user-id="${id}"]`) ||
                document.querySelector(`.user-card[data-user-id="${id}"]`) ||
                document.querySelector(
                  `.user-item:has(.roster-status-badge[data-email="${email}"])`
                ) ||
                Array.from(document.querySelectorAll('button')).find(b => b
                  .getAttribute('onclick')?.includes(`'${id}'`))?.closest(
                  'div');

              const btn = (row ? row.querySelector('.btn-view') : null) ||
                Array.from(document.querySelectorAll('button')).find(b => b
                  .getAttribute('onclick')?.includes(`'${id}'`));

              if (row) {
                const nameEl = row.querySelector('.user-name-text') || row
                  .querySelector('strong');
                const emailVal = row.querySelector('.user-email-value');
                const contactVal = row.querySelector('.user-contact-value');
                const idNumVal = row.querySelector('.user-id-value');
                const badgeEl = row.querySelector('.roster-status-badge') || row
                  .querySelector('.badge');

                if (nameEl) nameEl.textContent = freshName.toUpperCase();
                if (emailVal) emailVal.textContent = freshEmail;
                if (contactVal) contactVal.textContent = freshContact || '';
                if (idNumVal) idNumVal.textContent = freshIdNum || '';

                // Live Badge Color Update Logic
                if (badgeEl) {
                  const cleanStatus = freshStatus.toLowerCase();
                  badgeEl.textContent = cleanStatus.charAt(0).toUpperCase() +
                    cleanStatus.slice(1);
                  badgeEl.setAttribute('data-email', freshEmail);

                  if (cleanStatus === 'pending') {
                    badgeEl.style.background = '#f0f0f0'; // Gray Background
                    badgeEl.style.color = '#555555'; // Dark Gray Text
                  } else if (cleanStatus === 'inactive') {
                    badgeEl.style.background = '#FCEBEB'; // Soft Red
                    badgeEl.style.color = '#A32D2D';
                  } else {
                    badgeEl.style.background = '#e8f5e9'; // Active Green
                    badgeEl.style.color = '#025628';
                  }
                }
              }

              if (btn) {
                const created = document.getElementById('editUserCreated')
                  ?.value || 'August 2026';

                btn.dataset.id = id;
                btn.dataset.name = freshName;
                btn.dataset.email = freshEmail;
                btn.dataset.role = freshRole;
                btn.dataset.status = freshStatus;
                btn.dataset.courseTitle = freshCourse;
                btn.dataset.contact = freshContact;
                btn.dataset.idNum = freshIdNum;
                btn.dataset.created = created;
                btn.dataset.remarks = freshRemarks;

                const escapeAttr = (str) => String(str || '').replace(/\\/g,
                  '\\\\').replace(/'/g, "\\'").replace(/\n/g, ' ');

                btn.setAttribute('onclick', `openUserModal(
            '${escapeAttr(id)}',
            '${escapeAttr(freshName)}',
            '${escapeAttr(freshEmail)}',
            '${escapeAttr(freshRole)}',
            '${escapeAttr(freshStatus)}',
            '${escapeAttr(freshCourse)}',
            '${escapeAttr(freshContact)}',
            '${escapeAttr(freshIdNum)}',
            '${escapeAttr(created)}',
            '${escapeAttr(freshRemarks)}'
          )`);
              }

              showToast(data.message || 'User profile updated successfully!',
                'success');
              if (typeof closeUserModal === 'function') closeUserModal();
            } else {
              showToast(data?.message || 'An error occurred while updating.',
                'error');
            }
          })
          .catch(err => {
            console.error("Update error:", err);
            showToast(err.message ||
              'An error occurred while updating. Please try again.', 'error');
          })
          .finally(() => {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.style.opacity = '1';
              submitBtn.style.cursor = 'pointer';
              submitBtn.innerHTML = originalSubmitHtml;
            }
          });
      };

      // 2. DELETE USER FUNCTION
      function deleteUser() {
        const idInput = document.getElementById('editUserId');
        const id = idInput ? idInput.value : null;

        if (!id) {
          alert('Error: Missing User ID.');
          return;
        }

        if (!confirm(
            'Are you sure you want to remove this user? This action cannot be undone.'
          )) {
          return;
        }

        const deleteBtn = document.querySelector('.btn-delete-text');
        const originalDeleteHtml = deleteBtn ? deleteBtn.innerHTML :
          '<i class="fa-solid fa-user-slash"></i> Remove User';

        if (deleteBtn) {
          deleteBtn.disabled = true;
          deleteBtn.style.opacity = '0.7';
          deleteBtn.style.cursor = 'not-allowed';
          deleteBtn.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin"></i> Removing...';
        }

        const token = document.querySelector('meta[name="csrf-token"]')
          ?.getAttribute('content') ||
          (typeof csrfToken !== 'undefined' ? csrfToken : '');

        fetch('/admin/user/delete', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              id: id
            })
          })
          .then(async r => {
            const text = await r.text();
            try {
              return JSON.parse(text);
            } catch (err) {
              console.error("Server response was not JSON:", text);
              throw new Error("Server returned an invalid response.");
            }
          })
          .then(data => {
            if (data.success) {
              const row = document.querySelector(
                  `.user-item[data-user-id="${id}"]`) ||
                document.querySelector(`[data-user-id="${id}"]`);
              if (row) {
                row.remove();
              }

              alert(data.message || 'User removed successfully!');
              if (typeof closeUserModal === 'function') closeUserModal();
            } else {
              alert(data.message || 'Failed to remove user.');
            }
          })
          .catch(err => {
            console.error(err);
            alert('Network error occurred while attempting to delete user.');
          })
          .finally(() => {
            if (deleteBtn) {
              deleteBtn.disabled = false;
              deleteBtn.style.opacity = '1';
              deleteBtn.style.cursor = 'pointer';
              deleteBtn.innerHTML = originalDeleteHtml;
            }
          });
      }

      // 2. DELETE USER FUNCTION
      function deleteUser() {
        const idInput = document.getElementById('editUserId');
        const id = idInput ? idInput.value : null;

        if (!id) {
          alert('Error: Missing User ID.');
          return;
        }

        if (!confirm(
            'Are you sure you want to remove this user? This action cannot be undone.'
          )) {
          return;
        }

        const deleteBtn = document.querySelector('.btn-delete-text');
        const originalDeleteHtml = deleteBtn ? deleteBtn.innerHTML :
          '<i class="fa-solid fa-user-slash"></i> Remove User';

        if (deleteBtn) {
          deleteBtn.disabled = true;
          deleteBtn.style.opacity = '0.7';
          deleteBtn.style.cursor = 'not-allowed';
          deleteBtn.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin"></i> Removing...';
        }

        const token = document.querySelector('meta[name="csrf-token"]')
          ?.getAttribute('content') ||
          (typeof csrfToken !== 'undefined' ? csrfToken : '');

        fetch('/admin/user/delete', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              id: id
            })
          })
          .then(async r => {
            const text = await r.text();
            try {
              return JSON.parse(text);
            } catch (err) {
              console.error("Server response was not JSON:", text);
              throw new Error("Server returned an invalid response.");
            }
          })
          .then(data => {
            if (data.success) {
              const row = document.querySelector(
                `.user-item[data-user-id="${id}"]`);
              if (row) {
                row.remove();
              }

              alert(data.message || 'User removed successfully!');
              closeUserModal();
            } else {
              alert(data.message || 'Failed to remove user.');
            }
          })
          .catch(err => {
            console.error(err);
            alert('Network error occurred while attempting to delete user.');
          })
          .finally(() => {
            if (deleteBtn) {
              deleteBtn.disabled = false;
              deleteBtn.style.opacity = '1';
              deleteBtn.style.cursor = 'pointer';
              deleteBtn.innerHTML = originalDeleteHtml;
            }
          });
      }

      function togglePassword() {
        const passwordInput = document.getElementById('newTrainerPass');
        const icon = document.getElementById('togglePasswordIcon');

        if (passwordInput && icon) {
          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          }
        }
      }

      function closeUserModal() {
        const modal = document.getElementById('userModal');
        if (modal) {
          modal.style.display = 'none';
          modal.style.setProperty('display', 'none', 'important');
        }
      }

      function filterCourses() {
        const searchVal = document.getElementById('searchCourseInput').value
          .toLowerCase().trim();
        const cards = document.querySelectorAll('#coursesGrid .course-card');
        let visibleCount = 0;

        cards.forEach(card => {
          const title = card.getAttribute('data-title') || '';
          const code = card.getAttribute('data-code') || '';

          const matchesSearch = title.includes(searchVal) || code.includes(
            searchVal);

          if (matchesSearch) {
            card.style.display = '';
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });

        const noResults = document.getElementById('noFilterResults');
        if (noResults) {
          noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }
      }

      function showModuleAlert(message, isError = false) {
        const alertEl = document.getElementById('moduleAlert');
        if (!alertEl) return;
        alertEl.style.display = 'block';
        alertEl.style.background = isError ? '#FCEBEB' : '#E8F5E9';
        alertEl.style.color = isError ? '#A32D2D' : '#025628';
        alertEl.style.border = isError ? '1px solid #F7C6C6' : '1px solid #C8E6C9';
        alertEl.textContent = message;
        setTimeout(() => {
          alertEl.style.display = 'none';
        }, 4000);
      }

      function updateCourseCardCounts() {
        if (typeof _contentCourseId === 'undefined' || !_contentCourseId) return;

        const validModules = Array.isArray(_contentModules) ? _contentModules
          .filter(Boolean) : [];
        const validQuizzes = Array.isArray(_contentQuizzes) ? _contentQuizzes
          .filter(Boolean) : [];

        const modCountEl = document.getElementById(
          `course-module-count-${_contentCourseId}`);
        const modLabelEl = document.getElementById(
          `course-module-label-${_contentCourseId}`);
        if (modCountEl) {
          modCountEl.textContent = validModules.length;
          if (modLabelEl) {
            modLabelEl.textContent = validModules.length === 1 ? 'Module' :
              'Modules';
          }
        }

        const quizCountEl = document.getElementById(
          `course-quiz-count-${_contentCourseId}`);
        const quizLabelEl = document.getElementById(
          `course-quiz-label-${_contentCourseId}`);
        if (quizCountEl) {
          quizCountEl.textContent = validQuizzes.length;
          if (quizLabelEl) {
            quizLabelEl.textContent = validQuizzes.length === 1 ? 'Quiz' :
              'Quizzes';
          }
        }
      }

      function filterFacilities() {
        const query = document.getElementById('searchFacilityInput').value
          .toLowerCase().trim();
        const cards = document.querySelectorAll('#facilityGrid .facility-card');

        cards.forEach(card => {
          const name = card.getAttribute('data-name') || '';
          const location = card.getAttribute('data-location') || '';
          const match = name.includes(query) || location.includes(query);
          card.style.display = match ? 'flex' : 'none';
        });
      }

      function updateCourseBadgeCount() {
        const selectedCount = document.querySelectorAll(
          '.facility-course-cb:checked').length;
        const badge = document.getElementById('selectedCourseBadge');
        if (badge) {
          badge.innerText = `${selectedCount} Selected`;
        }
      }

      function toggleSelectAllCourses() {
        const checkboxes = document.querySelectorAll('.facility-course-cb');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);

        checkboxes.forEach(cb => {
          cb.checked = !allChecked;
        });

        updateCourseBadgeCount();
      }

      function filterAnnouncements() {
        const searchVal = document.getElementById('annSearchInput')?.value
          .toLowerCase().trim() || '';
        const typeVal = document.getElementById('annTypeFilter')?.value
          .toLowerCase() || '';
        const statusVal = document.getElementById('annStatusFilter')?.value
          .toLowerCase() || '';

        const items = document.querySelectorAll('#view-announcements .user-item');

        items.forEach(item => {
          const titleText = item.querySelector('.ann-title-text')?.textContent
            .toLowerCase() || '';
          const msgText = item.querySelector('.ann-msg-text')?.textContent
            .toLowerCase() || '';

          const itemType = item.getAttribute('data-type') || '';
          const itemStatus = item.getAttribute('data-status') || '';

          const matchesSearch = searchVal === '' || titleText.includes(
            searchVal) || msgText.includes(searchVal);
          const matchesType = typeVal === '' || itemType === typeVal;
          const matchesStatus = statusVal === '' || itemStatus === statusVal;

          if (matchesSearch && matchesType && matchesStatus) {
            item.style.setProperty('display', 'flex', 'important');
          } else {
            item.style.setProperty('display', 'none', 'important');
          }
        });
      }

      function filterTraineeCards() {
        const searchVal = document.getElementById('traineeCourseSearch')?.value
          .toLowerCase().trim() || '';
        const cards = document.querySelectorAll('.trainee-course-card');
        let visibleCount = 0;

        cards.forEach(card => {
          const title = card.getAttribute('data-title') || '';
          if (searchVal === '' || title.includes(searchVal)) {
            card.style.display = 'flex';
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });

        const noResults = document.getElementById('noTraineeCardResults');
        if (noResults) {
          noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }
      }

      // Live search filter for Trainer Directory
      function filterTrainerList() {
        const input = document.getElementById('trainerSearchInput').value
          .toLowerCase();
        const items = document.querySelectorAll('#trainer-list-content .user-item');

        items.forEach(item => {
          const text = item.textContent.toLowerCase();
          item.style.display = text.includes(input) ? 'flex' : 'none';
        });
      }

      function filterTrainerList() {
        const input = document.getElementById('trainerSearchInput').value
          .toLowerCase();
        const items = document.querySelectorAll('#trainer-list-content .user-item');

        items.forEach(item => {
          const text = item.textContent.toLowerCase();
          item.style.display = text.includes(input) ? 'flex' : 'none';
        });
      }

      let expandedChartInstance = null;

      // Custom Chart.js plugin to render value labels next to horizontal bars (used for bar charts)
      const barValueLabelsPlugin = {
        id: 'barValueLabels',
        afterDatasetsDraw(chart) {
          if (chart.config.options.indexAxis !== 'y') return;
          const {
            ctx
          } = chart;
          ctx.save();
          ctx.font = '600 11px sans-serif';
          ctx.textAlign = 'left';
          ctx.textBaseline = 'middle';

          chart.data.datasets.forEach((dataset, datasetIdx) => {
            const meta = chart.getDatasetMeta(datasetIdx);
            meta.data.forEach((bar, index) => {
              const val = dataset.data[index];
              if (val > 0) {
                ctx.fillStyle = dataset.backgroundColor || '#004d26';
                ctx.fillText(val, bar.x + 6, bar.y);
              }
            });
          });
          ctx.restore();
        }
      };

      function printExpandedChartReport() {
    const modal = document.getElementById('expandedChartModal');
    const canvas = document.getElementById('expandedCanvas');

    if (!modal || !canvas || typeof expandedChartInstance === 'undefined'
        || !expandedChartInstance) {
        alert('Please open a chart before printing.');
        return;
    }

    // Ensure the expanded chart is rendered before printing.
    expandedChartInstance.resize();
    expandedChartInstance.update('none');

    // Give the browser time to apply print layout.
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            window.print();
        });
    });
}

function exportExpandedChartToExcel() {
    const canvas = document.getElementById('expandedCanvas');
    const titleEl = document.getElementById('expandedModalTitle');
    const kpiRow = document.getElementById('modalKpiRow');
    const insightEl = document.getElementById('expandedChartInsight');

    if (typeof XLSX === 'undefined') {
        alert('Excel library not loaded. Check your internet connection.');
        return;
    }

    if (!canvas || !titleEl || !kpiRow) {
        alert('Please open an expanded chart first.');
        return;
    }

    const chart = Chart.getChart(canvas);

    if (!chart) {
        alert('The chart is not ready. Please reopen it.');
        return;
    }

    const title = titleEl.textContent.trim();
    const workbook = XLSX.utils.book_new();

    // Overview worksheet
    const overview = [
        ['LEDIPO - SYSTEM ANALYTICS REPORT'],
        [title],
        ['Generated At', new Date().toLocaleString()],
        [],
        ['KPI', 'Value']
    ];

    Array.from(kpiRow.children).forEach(card => {
        overview.push([
            card.querySelector('span')?.textContent.trim() || 'KPI',
            card.querySelector('strong')?.textContent.trim() || ''
        ]);
    });

    overview.push([]);
    overview.push(['Key Insight']);
    overview.push([
        insightEl?.textContent.trim() || 'No insight available.'
    ]);

    const overviewSheet = XLSX.utils.aoa_to_sheet(overview);
    overviewSheet['!cols'] = [{ wch: 35 }, { wch: 35 }];

    XLSX.utils.book_append_sheet(
        workbook, overviewSheet, 'Overview'
    );

    // Chart Data worksheet
    const headers = [
        'Category',
        ...chart.data.datasets.map(ds => ds.label || 'Value')
    ];

    const rows = (chart.data.labels || []).map((label, i) => [
        label,
        ...chart.data.datasets.map(ds => {
            const value = ds.data[i];
            return value ?? '';
        })
    ]);

    const dataSheet = XLSX.utils.aoa_to_sheet([
        [title],
        [],
        headers,
        ...rows
    ]);

    dataSheet['!cols'] = [
        { wch: 35 },
        ...chart.data.datasets.map(() => ({ wch: 20 }))
    ];

    XLSX.utils.book_append_sheet(
        workbook, dataSheet, 'Chart Data'
    );

    const filename = title
        .replace(/[\\/:*?"<>|]/g, '')
        .trim()
        .replace(/\s+/g, '_') || 'Analytics_Report';

    XLSX.writeFile(workbook, `${filename}.xlsx`);
}

      function openExpandedChartModal(type) {
        const modal = document.getElementById('expandedChartModal');
        const canvas = document.getElementById('expandedCanvas');
        const title = document.getElementById('expandedModalTitle');
        const centerTextObj = document.getElementById('doughnutCenterText');
        const data = (window.__overviewChartData || {})[type];
        if (!modal || !canvas || !data) return;

        if (expandedChartInstance) {
          expandedChartInstance.destroy();
          expandedChartInstance = null;
        }

        const modalTitle = document.getElementById('expandedModalTitle');
        if (modalTitle) modalTitle.textContent = data.title;
        if (centerTextObj) centerTextObj.style.display = 'none';

        const labels = Array.isArray(data.labels) ? data.labels : [];
        const datasets = (data.datasets || []).map(ds => ({
          ...ds,
          data: (ds.data || []).map(value => Number(value) || 0)
        }));
        const allValues = datasets.flatMap(ds => ds.data);
        const total = values => values.reduce((sum, value) => sum + (Number(value) || 0), 0);
        const average = values => values.length ? total(values) / values.length : 0;
        const fmt = (value, suffix = '') => Number.isFinite(value)
          ? `${Number(value).toLocaleString(undefined, { maximumFractionDigits: 1 })}${suffix}` : 'N/A';
        const bestIndex = values => values.length ? values.indexOf(Math.max(...values)) : -1;
        const topIdx = bestIndex(allValues);
        const topLabel = topIdx >= 0 ? (labels[topIdx % Math.max(labels.length, 1)] || 'N/A') : 'N/A';
        const totalApplicants = total(allValues);
        const kpis = [];

        switch (type) {
          case 'chartEnrollmentCourse': {
            const counts = datasets[0]?.data || [];
            const i = bestIndex(counts);
            kpis.push(['Total Enrollments', fmt(total(counts))],
              ['Most Enrolled Course', i >= 0 ? (labels[i] || 'N/A') : 'N/A'],
              ['Average per Course', fmt(counts.length ? total(counts) / counts.length : 0)]);
            break;
          }
          case 'chartMonthlyEnrollment': {
            const counts = datasets[0]?.data || [];
            const current = counts.length ? counts[counts.length - 1] : 0;
            const previous = counts.length > 1 ? counts[counts.length - 2] : 0;
            const change = previous ? ((current - previous) / previous) * 100 : null;
            kpis.push(['Latest Month Enrollments', fmt(current)],
              ['Month-over-Month Change', change === null ? 'N/A' : `${change > 0 ? '+' : ''}${fmt(change, '%')}`],
              ['Six-Month Enrollments', fmt(total(counts))]);
            break;
          }
          case 'chartQuizPerformance': {
            const scores = datasets[0]?.data || [];
            const i = bestIndex(scores);
            kpis.push(['Overall Average Score', fmt(average(scores), '%')],
              ['Highest-Scoring Course', i >= 0 ? (labels[i] || 'N/A') : 'N/A'],
              ['Courses at 75%+', fmt(scores.filter(v => v >= 75).length)]);
            break;
          }
          case 'chartQuizVsOnsite': {
            const quiz = datasets[0]?.data || [];
            const onsite = datasets[1]?.data || [];
            const quizAvg = average(quiz), onsiteAvg = average(onsite);
            kpis.push(['Average Quiz Score', fmt(quizAvg, '%')],
              ['Average Onsite Score', fmt(onsiteAvg, '%')],
              ['Average Score Gap', fmt(Math.abs(quizAvg - onsiteAvg), ' pp')]);
            break;
          }
          case 'chartCompetency': {
            const competentIdx = labels.findIndex(label => /competent(?!.*not)/i.test(String(label)) && !/not yet|not competent/i.test(String(label)));
            const notCompetentIdx = labels.findIndex(label => /not yet competent|not competent|not competent/i.test(String(label)));
            const competent = competentIdx >= 0 ? (datasets[0]?.data[competentIdx] || 0) : 0;
            const notCompetent = notCompetentIdx >= 0 ? (datasets[0]?.data[notCompetentIdx] || 0) : 0;
            kpis.push(['Competent Trainees', fmt(competent)],
              ['Not Yet Competent', fmt(notCompetent)],
              ['Competency Rate', fmt(total(datasets[0]?.data || []) ? competent / total(datasets[0].data) * 100 : 0, '%')]);
            break;
          }
          case 'chartCompletion': {
            const rates = datasets[0]?.data || [];
            const eligible = data.eligibleCounts || [];
            const competent = data.competentCounts || [];
            const totalEligible = total(eligible), totalCompetent = total(competent);
            const i = bestIndex(rates);
            const nonZeroRates = rates.filter(v => v > 0);
            const lowest = nonZeroRates.length ? Math.min(...nonZeroRates) : 0;
            kpis.push(['Highest Competency Rate', fmt(i >= 0 ? rates[i] : 0, '%')],
              ['Lowest Competency Rate', fmt(lowest, '%')],
              ['Overall Competency Rate', fmt(totalEligible ? totalCompetent / totalEligible * 100 : 0, '%')]);
            break;
          }
          case 'chartBarangay': {
            const counts = datasets[0]?.data || [];
            const i = bestIndex(counts);
            const sum = total(counts);
            kpis.push(['Total Applicants', fmt(sum)],
              ['Top Barangay', i >= 0 ? (labels[i] || 'N/A') : 'N/A'],
              ['Top Barangay Share', fmt(sum && i >= 0 ? counts[i] / sum * 100 : 0, '%')]);
            break;
          }
          case 'chartEmployment': {
            const counts = datasets[0]?.data || [];
            const unemployedIdx = labels.findIndex(label => /unemploy|not employed/i.test(String(label)));
            const known = total(counts);
            kpis.push(['Total Applicants', fmt(known)],
              ['Employed Applicants', fmt(counts[labels.findIndex(label => /employ/i.test(String(label)) && !/unemploy|not employed/i.test(String(label)))] || 0)],
              ['Unemployed Share', fmt(known && unemployedIdx >= 0 ? counts[unemployedIdx] / known * 100 : 0, '%')]);
            break;
          }
          case 'chartSex': {
            const counts = datasets[0]?.data || [];
            const i = bestIndex(counts), sum = total(counts);
            kpis.push(['Total Applicants', fmt(sum)],
              ['Largest Group', i >= 0 ? (labels[i] || 'N/A') : 'N/A'],
              ['Largest Group Share', fmt(sum && i >= 0 ? counts[i] / sum * 100 : 0, '%')]);
            break;
          }
          case 'chartAgeGroups': {
            const counts = datasets[0]?.data || [];
            const i = bestIndex(counts), sum = total(counts);
            kpis.push(['Total Applicants', fmt(sum)],
              ['Largest Age Group', i >= 0 ? (labels[i] || 'N/A') : 'N/A'],
              ['Largest Group Share', fmt(sum && i >= 0 ? counts[i] / sum * 100 : 0, '%')]);
            break;
          }
        }

        const kpiRow = document.getElementById('modalKpiRow');
        if (kpiRow) {
          kpiRow.innerHTML = kpis.map(([label, value]) => `
            <div class="expanded-kpi">
              <span>${label}</span>
              <strong title="${String(value).replace(/"/g, '&quot;')}">${value}</strong>
            </div>`).join('');
        }
        const insight = document.getElementById('expandedChartInsight');
        if (insight) insight.textContent = data.insight || 'Review the values to identify patterns and areas for follow-up.';

        const chartDataLabelPlugin = {
          id: 'expandedValueLabels',
          afterDatasetsDraw(chart) {
            const { ctx } = chart;
            ctx.save();
            ctx.fillStyle = '#111111';
            ctx.font = '600 11px Arial, sans-serif';
            chart.data.datasets.forEach((dataset, datasetIndex) => {
              const meta = chart.getDatasetMeta(datasetIndex);
              if (meta.hidden) return;
              meta.data.forEach((element, index) => {
                const raw = dataset.data[index];
                if (raw === null || raw === undefined || !Number.isFinite(Number(raw))) return;
                const value = Number(raw);
                if (chart.config.type === 'doughnut' || chart.config.type === 'pie') {
                  const pos = element.tooltipPosition();
                  ctx.textAlign = 'center';
                  ctx.textBaseline = 'middle';
                  ctx.fillText(String(value), pos.x, pos.y);
                } else if (chart.options.indexAxis === 'y') {
                  const pos = element.tooltipPosition();
                  ctx.textAlign = 'left';
                  ctx.textBaseline = 'middle';
                  ctx.fillText(`${value}${data.percentage ? '%' : ''}`, pos.x + 7, pos.y);
                } else {
                  const pos = element.tooltipPosition();
                  ctx.textAlign = 'center';
                  ctx.textBaseline = 'bottom';
                  ctx.fillText(`${value}${data.percentage ? '%' : ''}`, pos.x, pos.y - 5);
                }
              });
            });
            ctx.restore();
          }
        };

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => {
          const chartType = data.type || 'bar';
          const isDoughnut = chartType === 'doughnut' || chartType === 'pie';
          const chartDatasets = datasets.map((ds, index) => ({
            ...ds,
            backgroundColor: ds.backgroundColor || (index % 2 ? '#e1dc36' : '#075b35'),
            borderColor: ds.borderColor || (chartType === 'line' ? '#075b35' : '#ffffff'),
            borderWidth: ds.borderWidth ?? (chartType === 'line' ? 2 : 1),
            borderRadius: chartType === 'bar' ? 5 : undefined,
            maxBarThickness: 42
          }));
          const scales = isDoughnut ? {} : {
            x: {
              beginAtZero: true,
              grid: { display: false, color: '#e8eee9' },
              ticks: { color: '#111111', autoSkip: false, maxRotation: 35, minRotation: 0 }
            },
            y: {
              beginAtZero: true,
              grid: { color: '#edf1ee' },
              ticks: { color: '#111111', precision: 0 },
              ...(data.percentage ? { max: 100, ticks: { color: '#111111', callback: value => `${value}%` } } : {})
            }
          };
          if (chartType === 'bar' && data.horizontal) {
            scales.x = {
              beginAtZero: true,
              grid: { color: '#edf1ee' },
              ticks: { color: '#111111', precision: data.percentage ? undefined : 0, callback: value => data.percentage ? `${value}%` : value }
            };
            if (data.percentage) scales.x.max = 100;
            scales.y = { grid: { display: false }, ticks: { color: '#111111', autoSkip: false } };
          }
          expandedChartInstance = new Chart(canvas.getContext('2d'), {
            type: chartType,
            plugins: [chartDataLabelPlugin],
            data: { labels, datasets: chartDatasets },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              indexAxis: data.horizontal ? 'y' : 'x',
              cutout: isDoughnut ? '58%' : undefined,
              layout: { padding: { top: 18, right: data.horizontal ? 48 : 12, bottom: 8, left: 4 } },
              plugins: {
                legend: {
                  display: chartDatasets.length > 1 || isDoughnut,
                  position: 'bottom',
                  labels: { color: '#111111', usePointStyle: true, padding: 16, font: { size: 12, weight: '600' } }
                },
                tooltip: {
                  backgroundColor: '#183329',
                  titleColor: '#ffffff',
                  bodyColor: '#ffffff',
                  callbacks: {
                    label: context => `${context.dataset.label || context.label}: ${fmt(Number(context.raw), data.percentage ? '%' : '')}`
                  }
                }
              },
              scales
            }
          });
        });
      }

      function closeExpandedChartModal() {
        const modal = document.getElementById('expandedChartModal');
        const centerTextObj = document.getElementById('doughnutCenterText');

        if (modal) modal.style.display = 'none';
        document.body.style.overflow = '';
        if (centerTextObj) centerTextObj.style.display = 'none';

        if (expandedChartInstance) {
          expandedChartInstance.destroy();
          expandedChartInstance = null;
        }
      }

      // Close when clicking outside the modal box
      document.getElementById('expandedChartModal')?.addEventListener('click',
        function(e) {
          if (e.target === this) {
            closeExpandedChartModal();
          }
        });

      document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
          const modal = document.getElementById('expandedChartModal');
          if (modal && modal.style.display !== 'none') closeExpandedChartModal();
        }
      });

      // ==========================================
      // 1. MODAL CONTROLLERS (OPEN / CLOSE)
      // ==========================================
      function openCertModal(name, course, certId, issueDate = 'April 3, 2026',
        status = 'Pending', grade = 'N/A', docType = 'completion', trainingId = ''
      ) {
        const modal = document.getElementById('certificateModal');
        if (!modal) return;

        document.getElementById('vName').textContent = name;
        document.getElementById('vCourse').textContent = course.toUpperCase();
        document.getElementById('vID').textContent = `CERT. NO.: ${certId}`;

        const trainingIdEl = document.getElementById('vTrainingID');
        if (trainingIdEl) {
          trainingIdEl.textContent = `TRAINING ID: ${trainingId || '—'}`;
        }

        const docTitle = document.getElementById('vDocTitle');
        if (docTitle) {
          docTitle.textContent = docType === 'participation' ?
            'CERTIFICATE OF PARTICIPATION' :
            'CERTIFICATE OF COMPLETION';
        }

        const statusBadge = document.getElementById('vStatusBadge');
        if (statusBadge) {
          const isClaimed = status.toLowerCase() === 'claimed';
          statusBadge.className =
            `badge ${isClaimed ? 'badge-success' : 'badge-warning'}`;
          statusBadge.textContent = status;
        }

        const issueDateEl = document.getElementById('vIssueDate');
        if (issueDateEl) issueDateEl.textContent = issueDate;

        const controlNoEl = document.getElementById('vControlNo');
        if (controlNoEl) controlNoEl.textContent = certId;

        const gradeEl = document.getElementById('vGrade');
        if (gradeEl) {
          const numericGrade = Number.parseFloat(String(grade).replace('%', ''));
          const performanceStatus = Number.isFinite(numericGrade) ?
            (numericGrade >= 75 ? 'Competent' : 'Not Yet Competent') :
            'Grade unavailable';
          gradeEl.textContent = `${grade} — ${performanceStatus}`;
        }

        modal.style.display = 'flex';
      }

      function closeCertModal() {
        document.getElementById('certificateModal').style.display = 'none';
      }

      function openAddModal() {
        const modal = document.getElementById('addTraineeModal');
        const courseFilter = document.getElementById('modalCourseFilter');

        if (courseFilter) {
          courseFilter.value = '';
          filterModalTrainees('');
        }

        if (modal) {
          modal.style.display = 'flex';
          updateLivePreview();
        }
      }

      function closeAddModal() {
        document.getElementById('addTraineeModal').style.display = 'none';
      }

      window.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal-overlay')) {
          e.target.style.display = 'none';
        }
      });

      // ==========================================
      // 2. LIVE PREVIEW SYNCHRONIZATION
      // ==========================================
      function updateLivePreview() {
        const traineeSelect = document.getElementById('traineeSelect');
        const certInput = document.getElementById('certIDInput');

        const pName = document.getElementById('pName');
        const pCourse = document.getElementById('pCourse');
        const pID = document.getElementById('pID');
        const pTrainingID = document.getElementById('pTrainingID');

        // Helper for Standard Title Case (Capitalizes the first letter of each word)
        function toTitleCase(str) {
          if (!str) return '';
          return str
            .toLowerCase()
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
        }

        if (traineeSelect && traineeSelect.selectedIndex > 0) {
          const selectedOption = traineeSelect.options[traineeSelect.selectedIndex];
          let rawText = selectedOption.getAttribute('data-name') || selectedOption
            .text.split(' (')[0].trim();
          const course = selectedOption.getAttribute('data-course') ||
            'TRAINING PROGRAM';

          // Applies proper Title Case instead of lowercase
          pName.textContent = toTitleCase(rawText);
          pCourse.textContent = course.toUpperCase();

          const trainingId = selectedOption.getAttribute('data-training-id') || '';
          if (pTrainingID) {
            pTrainingID.textContent = `TRAINING ID: ${trainingId || '—'}`;
          }
        } else {
          pName.textContent = '[Recipient Name]';
          pCourse.textContent = '[COURSE NAME]';
          if (pTrainingID) pTrainingID.textContent = 'TRAINING ID: —';
        }

        if (certInput && certInput.value.trim() !== '') {
          pID.textContent = `CERT. NO.: ${certInput.value.trim()}`;
        } else {
          pID.textContent = 'CERT. NO.: [ID]';
        }

        updateCompetencySummary();
      }

      // Display competency based on the selected enrollment's progress.
      // The server independently recalculates this value when saving/releasing.
      function updateCompetencySummary() {
        const traineeSelect = document.getElementById('traineeSelect');
        const card = document.getElementById('competencySummary');
        const statusText = document.getElementById('competencyStatusText');
        const progressText = document.getElementById('competencyProgressText');
        const helpText = document.getElementById('competencyHelpText');
        if (!traineeSelect || !card || !statusText || !progressText || !helpText)
          return;

        const option = traineeSelect.options[traineeSelect.selectedIndex];
        if (!option || !option.value) {
          card.className = 'competency-summary is-empty';
          card.querySelector('.competency-summary-icon i').className =
            'fa-solid fa-circle-info';
          statusText.textContent = 'Select a trainee to check eligibility';
          progressText.textContent = 'Required progress: 75%';
          helpText.textContent = 'Competency remarks are assigned automatically.';
          return;
        }

        const progress = Number.parseFloat(option.dataset.progress || '0') || 0;
        const competent = progress >= 75;
        card.className =
          `competency-summary ${competent ? 'is-eligible' : 'is-ineligible'}`;
        card.querySelector('.competency-summary-icon i').className = competent ?
          'fa-solid fa-circle-check' :
          'fa-solid fa-circle-exclamation';
        statusText.textContent = competent ? 'Eligible — Competent' :
          'Not yet eligible — Not Yet Competent';
        progressText.textContent = `Training progress: ${progress}% (75% required)`;
        helpText.textContent = competent ?
          'The certificate will be saved as Pending until an admin releases it.' :
          'This trainee cannot receive a released certificate until progress reaches 75%.';
      }

      // ==========================================
      // 3. TABLE ROW ACTIONS, INSERTION & FILTERS
      // ==========================================
      async function submitIssueCertificate() {
        const traineeSelect = document.getElementById('traineeSelect');
        const certInput = document.getElementById('certIDInput');
        const dateInput = document.getElementById('issueDateInput');
        const docTypeSelect = document.getElementById('docTypeSelect');

        if (!traineeSelect || traineeSelect.selectedIndex <= 0 || !traineeSelect
          .value) {
          alert('Please select a trainee from the dropdown.');
          traineeSelect?.focus();
          return;
        }

        const selectedOption = traineeSelect.options[traineeSelect.selectedIndex];
        const courseId = selectedOption.getAttribute('data-course-id') || null;
        const selectedProgress = Number.parseFloat(selectedOption.dataset
          .progress || '0') || 0;

        if (!courseId) {
          alert(
            'The selected trainee does not have a course linked. Please select a trainee with a valid course.'
          );
          return;
        }

        if (selectedProgress < 75) {
          const proceed = confirm(
            `This trainee has ${selectedProgress}% progress and will be marked Not Yet Competent. The certificate will remain Pending and cannot be released until progress reaches 75%. Continue saving this record?`
          );
          if (!proceed) return;
        }

        const payload = {
          trainee_id: traineeSelect.value,
          course_id: courseId,
          issue_date: dateInput?.value || new Date().toISOString().slice(0, 10),
          document_type: 'completion'
        };

        const token = document.querySelector('meta[name="csrf-token"]')
          ?.getAttribute('content') || '';

        try {
          const response = await fetch('/admin/certificate/store', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          });

          const data = await response.json();

          if (response.ok && data.success) {
            alert(data.message || 'Certificate saved successfully to database!');
            closeAddModal();
            localStorage.setItem('activeAdminTab', 'view-certificate');
            window.location.href = window.location.pathname + '?view=certificate';
          } else {
            let errorMsg = data.message || 'Failed to save certificate.';
            if (data.errors) {
              errorMsg = Object.values(data.errors).flat().join('\n');
            }
            alert('Error Saving:\n' + errorMsg);
          }
        } catch (error) {
          console.error('AJAX Error:', error);
          alert('Network error. Check Console (F12) for server details.');
        }
      }

      async function releaseCertificate(button, certId) {
        const remarks = String(button.dataset.remarks || '').trim().toLowerCase();
        const status = String(button.dataset.status || '').trim().toLowerCase();

        if (remarks !== 'competent') {
          alert('Only competent trainees can receive a certificate.');
          return;
        }
        if (['released', 'claimed'].includes(status)) {
          alert('This certificate has already been released or claimed.');
          return;
        }
        if (!certId) {
          alert('Certificate ID is missing. Refresh the page and try again.');
          return;
        }
        if (!confirm('Release this certificate to the trainee?')) return;

        const token = document.querySelector('meta[name="csrf-token"]')
          ?.content || '';
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.style.opacity = '0.6';
        button.style.cursor = 'wait';
        button.innerHTML =
          '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i>';

        try {
          const response = await fetch(
            `/admin/certificate/${encodeURIComponent(certId)}/release`, {
              method: 'PATCH',
              credentials: 'same-origin',
              headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
              }
            });

          const contentType = response.headers.get('content-type') || '';
          if (!contentType.includes('application/json')) {
            const message = response.status === 404 ?
              'Certificate release route was not found (404). Check routes/web.php.' :
              response.status === 419 ?
              'Your session or CSRF token expired. Refresh the page and try again.' :
              `Unexpected server response (HTTP ${response.status}). Check the Laravel route/controller and logs.`;
            throw new Error(message);
          }

          const data = await response.json();
          if (!response.ok || !data.success) {
            throw new Error(data.message ||
              `Unable to release certificate (HTTP ${response.status}).`);
          }

          button.dataset.status = 'released';
          button.disabled = true;
          button.title = 'Certificate already released or claimed';
          button.style.opacity = '0.45';
          button.style.cursor = 'not-allowed';

          const row = button.closest('tr');
          if (row) {
            row.dataset.certStatus = 'released';
            const badge = row.querySelector('.badge-toggle');
            if (badge) {
              badge.className = 'badge badge-toggle badge-warning';
              badge.innerHTML =
                '<i class="fas fa-paper-plane" aria-hidden="true"></i><span class="status-label">Released</span>';
            }
          }

          localStorage.setItem('activeAdminTab', 'view-certificate');
          alert(data.message || 'Certificate released successfully.');
          window.location.href = window.location.pathname + '?view=certificate';
        } catch (error) {
          console.error('Certificate release error:', error);
          alert(error.message ||
            'An error occurred while releasing the certificate.');
          button.disabled = false;
          button.removeAttribute('aria-busy');
          button.style.opacity = '';
          button.style.cursor = '';
          button.innerHTML = originalHTML;
        }
      }

      async function toggleCertStatus(badgeEl, certId) {
        const token = document.querySelector('meta[name="csrf-token"]')
          ?.getAttribute('content') || '';

        const currentStatus = badgeEl.querySelector('.status-label')
          ?.textContent.trim().toLowerCase() || '';

        // Prevent claiming a certificate that has not been released.
        if (currentStatus === 'pending') {
          alert(
            'This certificate must be released by the administrator before it can be marked as claimed.'
          );
          return;
        }

        if (!['released', 'claimed'].includes(currentStatus)) {
          alert('This certificate cannot be marked as claimed.');
          return;
        }

        try {
          badgeEl.style.pointerEvents = 'none';
          badgeEl.style.opacity = '0.6';

          const response = await fetch(
            `/admin/certificate/${certId}/toggle-status`, {
              method: 'PATCH',
              headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
              }
            }
          );

          const data = await response.json();

          if (!response.ok || !data.success) {
            alert(data.message || 'Unable to update certificate status.');
            return;
          }

          const newStatus = data.new_status;
          const normalizedStatus = newStatus.toLowerCase();
          const isClaimed = normalizedStatus === 'claimed';
          const isReleased = normalizedStatus === 'released';

          badgeEl.className = `badge badge-toggle ${
              isClaimed ? 'badge-success' : 'badge-warning'
          }`;

          badgeEl.innerHTML = `
              <i class="fas ${
                  isClaimed ? 'fa-check-circle' :
                  isReleased ? 'fa-paper-plane' : 'fa-clock'
              }"></i>
              <span class="status-label">${newStatus}</span>
          `;

          localStorage.setItem('activeAdminTab', 'view-certificate');
          window.location.href = window.location.pathname + '?view=certificate';

        } catch (error) {
          console.error('Toggle certificate status error:', error);
          alert('An error occurred while updating the certificate status.');
        } finally {
          badgeEl.style.pointerEvents = '';
          badgeEl.style.opacity = '';
        }
      }

      async function deleteCert(buttonElement, certId) {
        if (!confirm('Are you sure you want to delete this certificate record?'))
          return;
        const token = document.querySelector('meta[name="csrf-token"]')
          ?.getAttribute('content') || '';

        try {
          const response = await fetch(`/admin/certificate/${certId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            }
          });

          const data = await response.json();
          if (response.ok && data.success) {
            buttonElement.closest('tr').remove();
            localStorage.setItem('activeAdminTab', 'view-certificate');
            window.location.href = window.location.pathname + '?view=certificate';
          } else {
            alert(data.message || 'Could not delete record.');
          }
        } catch (err) {
          console.error('Delete error:', err);
        }
      }

      function openEditCertModal(certId) {
        alert(`Editing record for Certificate No: ${certId}`);
      }

      function filterCertTable() {
        const search = document.getElementById('certSearchInput')?.value
          .toLowerCase().trim() || '';
        const course = document.getElementById('filterCourse')?.value.toLowerCase()
          .trim() || '';
        const month = document.getElementById('filterMonth')?.value.toLowerCase()
          .trim() || '';
        const status = document.getElementById('filterStatus')?.value.toLowerCase()
          .trim() || '';

        const rows = document.querySelectorAll('#certTableBody tr');
        let matchCount = 0;

        rows.forEach(row => {
          if (row.id === 'emptyCertRow') return;

          const nameText = row.children[1]?.textContent.toLowerCase().trim() ||
            '';
          const courseText = row.children[2]?.textContent.toLowerCase()
            .trim() || '';
          const dateText = row.children[3]?.textContent.toLowerCase().trim() ||
            '';
          const statusText = (row.dataset.certStatus || row.children[4]
            ?.textContent || '').toLowerCase().trim();
          const certIdText = (row.getAttribute('data-cert-no') || '')
            .toLowerCase().trim();

          const matchSearch = !search || nameText.includes(search) || certIdText
            .includes(search);
          const matchCourse = !course || courseText.includes(course);
          const matchMonth = !month || dateText.includes(month);
          const matchStatus = !status || statusText.includes(status);

          if (matchSearch && matchCourse && matchMonth && matchStatus) {
            row.style.display = '';
            matchCount++;
          } else {
            row.style.display = 'none';
          }
        });

        const noResults = document.getElementById('noCertResults');
        if (noResults) {
          noResults.style.display = matchCount === 0 ? 'block' : 'none';
        }

        // Keep Select All synchronized when search or filters change.
        updateCertificateSelection();
      }

      // ==========================================
      // 4. BATCH CHECKBOX CONTROLS
      // ==========================================
      function toggleMultiSelectMode(checkbox) {
        const table = document.getElementById('certTable');
        const selectAll = document.getElementById('selectAll');

        if (!table) return;

        table.querySelectorAll('.select-col').forEach(col => {
          col.classList.toggle('hidden', !checkbox.checked);
        });

        if (!checkbox.checked) {
          table.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = false;
          });

          if (selectAll) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
          }
        }

        updateCertificateSelection();
      }

      function toggleSelectAllRows(masterCheckbox) {
        const table = document.getElementById('certTable');
        const multiSelect = document.getElementById('toggleMultiple');

        if (!table) return;

        // Selecting all automatically enables the checkbox column.
        if (masterCheckbox.checked && multiSelect && !multiSelect.checked) {
          multiSelect.checked = true;
          toggleMultiSelectMode(multiSelect);
        }

        // Select/deselect only rows currently visible after search and filters.
        table.querySelectorAll('#certTableBody tr').forEach(row => {
          if (row.id === 'emptyCertRow' || row.style.display === 'none') return;

          const cb = row.querySelector('.row-checkbox');
          if (cb) cb.checked = masterCheckbox.checked;
        });

        updateCertificateSelection();
      }

      function updateCertificateSelection() {
        const table = document.getElementById('certTable');
        const selectAll = document.getElementById('selectAll');
        const counter = document.getElementById('selectedCertCount');

        if (!table) return;

        const allCheckboxes = [
          ...table.querySelectorAll('#certTableBody .row-checkbox')
        ];

        const selected = allCheckboxes.filter(cb => cb.checked);
        const visibleCheckboxes = allCheckboxes.filter(cb => {
          const row = cb.closest('tr');
          return row && row.style.display !== 'none' && row.id !==
            'emptyCertRow';
        });
        const visibleSelected = visibleCheckboxes.filter(cb => cb.checked);

        if (counter) {
          counter.textContent = `${selected.length} selected`;
        }

        // Select All reflects only the currently visible rows.
        if (selectAll) {
          selectAll.checked =
            visibleCheckboxes.length > 0 &&
            visibleSelected.length === visibleCheckboxes.length;

          selectAll.indeterminate =
            visibleSelected.length > 0 &&
            visibleSelected.length < visibleCheckboxes.length;
        }
      }

      // Keep the count and Select All state synchronized with individual choices.
      document.addEventListener('change', function(event) {
        if (event.target.matches('#certTable .row-checkbox')) {
          updateCertificateSelection();
        }
      });

      function exportCertificates() {
        const selectedRows = document.querySelectorAll(
          '#certTable .row-checkbox:checked');
        if (selectedRows.length === 0) {
          alert('Please select at least one certificate to export.');
          return;
        }
        alert(`Exporting ${selectedRows.length} certificate(s)...`);
      }

      // ==========================================
      // 5. PRINT & DIRECT 1-PAGE SQUARE PDF EXPORT
      // ==========================================
      function handlePrint() {
        window.print();
      }

      async function handleDownload(targetElementId = 'printableCert') {
        const original = document.getElementById(targetElementId);
        if (!original) return;

        let recipientName = 'Certificate';
        const nameEl = targetElementId === 'printableCert' ?
          document.getElementById('vName') :
          document.getElementById('pName');

        if (nameEl && nameEl.textContent.trim() && !nameEl.textContent.includes(
            '[')) {
          recipientName = nameEl.textContent.trim();
        }

        const cleanFileName =
          `Certificate_${recipientName.replace(/[^a-zA-Z0-9_-]/g, '_')}.pdf`;

        const tempWrapper = document.createElement('div');
        Object.assign(tempWrapper.style, {
          position: 'fixed',
          top: '0',
          left: '0',
          width: '750px',
          height: '750px',
          zIndex: '999999',
          background: '#ffffff',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          overflow: 'hidden'
        });

        const clone = original.cloneNode(true);
        Object.assign(clone.style, {
          width: '750px',
          height: '750px',
          minWidth: '750px',
          minHeight: '750px',
          maxWidth: '750px',
          maxHeight: '750px',
          margin: '0',
          padding: '20px',
          border: '10px solid #025628',
          boxSizing: 'border-box',
          transform: 'none',
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'space-between',
          background: '#ffffff'
        });

        const inner = clone.querySelector('.ui-cert-inner');
        if (inner) {
          Object.assign(inner.style, {
            height: '100%',
            boxSizing: 'border-box',
            border: '1.5px dashed #025628',
            padding: '22px 18px',
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'space-between',
            textAlign: 'center',
            background: 'radial-gradient(circle at center, #ffffff 60%, #fafcf9 100%)'
          });
        }

        // Balanced Logo Dimensions for PDF Export
        const logoHeader = clone.querySelector('.cert-logos-header');
        if (logoHeader) {
          Object.assign(logoHeader.style, {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            gap: '24px',
            marginBottom: '8px',
            width: '100%'
          });

          logoHeader.querySelectorAll('.cert-logo-img').forEach(img => {
            Object.assign(img.style, {
              height: '60px',
              width: 'auto',
              maxHeight: '60px',
              maxWidth: '100px',
              objectFit: 'contain',
              display: 'inline-block'
            });
          });
        }

        // Harmonized Recipient Name Sizing for PDF Export
        const recipName = clone.querySelector('.cert-recipient-name');
        if (recipName) {
          recipName.style.fontSize = '70px';
          recipName.style.fontWeight = '700';
          recipName.style.textDecoration = 'underline';
          recipName.style.margin = '6px 0';
          recipName.style.color = '#1a1a1a';
          recipName.style.textTransform = 'capitalize';
        }

        tempWrapper.appendChild(clone);
        document.body.appendChild(tempWrapper);

        if (document.fonts) {
          await document.fonts.ready;
        }

        try {
          const canvas = await html2canvas(clone, {
            scale: 3,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            logging: false,
            scrollX: 0,
            scrollY: 0,
            windowWidth: 750,
            windowHeight: 750
          });

          document.body.removeChild(tempWrapper);

          const jsPDFConstructor = window.jspdf ? window.jspdf.jsPDF : (window
            .jsPDF || jsPDF);
          const pdf = new jsPDFConstructor({
            orientation: 'portrait',
            unit: 'mm',
            format: [200, 200]
          });

          const imgData = canvas.toDataURL('image/png', 1.0);
          pdf.addImage(imgData, 'PNG', 0, 0, 200, 200);
          pdf.save(cleanFileName);

        } catch (err) {
          console.error('PDF Generation Error:', err);
          if (document.body.contains(tempWrapper)) {
            document.body.removeChild(tempWrapper);
          }
          alert('An error occurred while generating the PDF.');
        }
      }

      function filterModalTrainees(selectedCourse) {
        const traineeSelect = document.getElementById('traineeSelect');
        if (!traineeSelect) return;

        const options = traineeSelect.querySelectorAll('option');
        const targetCourse = selectedCourse.toLowerCase().trim();

        options.forEach((opt, index) => {
          if (index === 0) {
            opt.style.display = '';
            return;
          }

          const optCourse = (opt.getAttribute('data-course') || '')
            .toLowerCase().trim();

          if (!targetCourse || optCourse.includes(targetCourse) || targetCourse
            .includes(optCourse)) {
            opt.style.display = '';
            opt.disabled = false;
          } else {
            opt.style.display = 'none';
            opt.disabled = true;
          }
        });

        traineeSelect.value = '';
        updateLivePreview();
      }
    </script>

  <script>
  (function () {
    const green = '#075b35';
    const greenSoft = 'rgba(7,91,53,0.14)';
    const yellow = '#e1dc36';
    const muted = '#6b7c73';
    const grid = '#e8eee9';

    const sharedOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { labels: { color: '#183329', usePointStyle: true, boxWidth: 9, padding: 14 } },
        tooltip: { backgroundColor: '#183329', padding: 10, cornerRadius: 8 }
      },
      scales: {
        x: { grid: { display: false }, ticks: { color: muted, maxRotation: 35, minRotation: 0 } },
        y: { beginAtZero: true, grid: { color: grid }, ticks: { color: muted } }
      }
    };

    function makeChart(id, config) {
      const canvas = document.getElementById(id);
      if (!canvas || typeof Chart === 'undefined') return;
      const existing = Chart.getChart(canvas);
      if (existing) existing.destroy();
      new Chart(canvas.getContext('2d'), config);
    }

    function barConfig(labels, data, label, color = green, horizontal = false, percentage = false) {
      const options = JSON.parse(JSON.stringify(sharedOptions));
      options.indexAxis = horizontal ? 'y' : 'x';
      options.scales.x.beginAtZero = true;
      options.scales.y.beginAtZero = true;
      if (percentage) {
        options.scales[horizontal ? 'x' : 'y'].max = 100;
        options.scales[horizontal ? 'x' : 'y'].ticks.callback = value => value + '%';
      }
      if (horizontal) options.scales.y.grid.display = false;
      options.plugins.legend.display = false;
      options.plugins.tooltip.callbacks = {
        label: context => `${label}: ${context.raw ?? 0}${percentage ? '%' : ''}`
      };
      return {
        type: 'bar',
        data: { labels, datasets: [{ label, data, backgroundColor: color, borderRadius: 6, maxBarThickness: 34 }] },
        options
      };
    }

    function renderDashboardCharts() {
      const enrollmentLabels = window.traineeCourseLabels || [];
      const enrollmentCounts = window.traineeCourseCounts || [];
      const monthLabels = window.monthlyEnrollmentLabels || [];
      const monthCounts = window.monthlyEnrollmentCounts || [];
      const quizLabels = window.quizPerformanceLabels || [];
      const quizScores = window.quizPerformanceScores || [];
      const performanceLabels = window.performanceCourseLabels || [];
      const quizAverages = window.courseQuizAverages || [];
      const onsiteAverages = window.courseOnsiteAverages || [];
      const competencyLabels = window.competencyLabels || [];
      const competencyCounts = window.competencyStudentCounts || [];
      const competentLabels = window.competentCourseLabels || [];
      const competentCounts = window.competentCourseCounts || [];
      const eligibleCounts = window.courseEligibleCounts || [];
      const competencyRates = competentCounts.map((count, index) => {
        const eligible = Number(eligibleCounts[index] || 0);
        return eligible > 0 ? Number(((Number(count || 0) / eligible) * 100).toFixed(1)) : 0;
      });
      const barangayLabels = @json($barangayLabels ?? []);
  const barangayCounts = @json($barangayCounts ?? []);
  const employmentLabels = @json($employmentLabels ?? []);
  const employmentCounts = @json($employmentCounts ?? []);
  const sexLabels = @json($sexLabels ?? []);
  const sexCounts = @json($sexCounts ?? []);
  const ageGroupLabels = @json($ageGroupLabels ?? []);
  const ageGroupCounts = @json($ageGroupCounts ?? []);

      // Shared live data for the expanded chart modal and its graph-specific KPIs.
      window.__overviewChartData = {
        chartEnrollmentCourse: { title: 'Enrollment by Course', type: 'bar', horizontal: true, labels: enrollmentLabels, datasets: [{ label: 'Enrolled trainees', data: enrollmentCounts, backgroundColor: green }], insight: 'Compare course demand to identify the training offerings attracting the most and fewest enrollments.' },
        chartMonthlyEnrollment: { title: 'Monthly Enrollment Trend', type: 'line', labels: monthLabels, datasets: [{ label: 'Enrollments', data: monthCounts, borderColor: green, backgroundColor: greenSoft, fill: true, tension: .3, pointRadius: 5, pointBackgroundColor: yellow }], insight: 'Use the latest month and month-over-month change to see whether enrollment is gaining momentum, declining, or fluctuating.' },
        chartQuizPerformance: { title: 'Quiz Performance by Course', type: 'bar', horizontal: true, percentage: true, labels: quizLabels, datasets: [{ label: 'Average quiz score', data: quizScores, backgroundColor: yellow }], insight: 'Courses below the 75% benchmark may need additional review activities or targeted learning support.' },
        chartQuizVsOnsite: { title: 'Quiz vs. Onsite Performance', type: 'bar', horizontal: true, percentage: true, labels: performanceLabels, datasets: [{ label: 'Quiz Average', data: quizAverages, backgroundColor: green }, { label: 'Onsite Average', data: onsiteAverages, backgroundColor: yellow }], insight: 'A large gap between quiz and onsite averages can indicate a difference between theoretical understanding and practical performance.' },
        chartCompetency: { title: 'Competency Distribution', type: 'doughnut', labels: competencyLabels, datasets: [{ label: 'Trainees', data: competencyCounts, backgroundColor: [green, '#c94c4c', '#cbd5ce'], borderColor: '#ffffff', borderWidth: 3 }], insight: 'Review the proportion of trainees meeting the competency standard and prioritize support for those not yet competent.' },
        chartCompletion: { title: 'Competency Rate by Course', type: 'bar', horizontal: true, percentage: true, labels: competentLabels, datasets: [{ label: 'Competency rate', data: competencyRates, backgroundColor: green }], competentCounts, eligibleCounts, insight: 'Compare course-level competency rates to identify strong outcomes and courses where trainees may need more coaching.' },
        chartBarangay: { title: 'Registrations by Barangay', type: 'bar', horizontal: true, labels: barangayLabels, datasets: [{ label: 'Applicants', data: barangayCounts, backgroundColor: green }], insight: 'The highest-registration barangays show where demand is strongest and where outreach or training access may be prioritized.' },
        chartEmployment: { title: 'Applicant Employment Status', type: 'doughnut', labels: employmentLabels, datasets: [{ label: 'Applicants', data: employmentCounts, backgroundColor: [green, yellow, '#79a98b', '#b7cbbd', '#cbd5ce'], borderColor: '#ffffff', borderWidth: 3 }], insight: 'Employment status can help the team understand whether training outreach is reaching applicants who may need livelihood opportunities.' },
        chartSex: { title: 'Registrations by Sex', type: 'doughnut', labels: sexLabels, datasets: [{ label: 'Applicants', data: sexCounts, backgroundColor: [green, yellow, '#79a98b', '#cbd5ce'], borderColor: '#ffffff', borderWidth: 3 }], insight: 'Use the recorded applicant distribution to review representation and assess whether outreach is reaching different groups.' },
        chartAgeGroups: { title: 'Applicant Age Group Distribution', type: 'bar', horizontal: true, labels: ageGroupLabels, datasets: [{ label: 'Applicants', data: ageGroupCounts, backgroundColor: yellow }], insight: 'The largest age groups can inform training schedules, course formats, and outreach channels that best fit the applicant population.' }
      };

      makeChart('chartEnrollmentCourse', barConfig(enrollmentLabels, enrollmentCounts, 'Enrolled trainees', green, true));
      makeChart('chartMonthlyEnrollment', {
        type: 'line',
        data: { labels: monthLabels, datasets: [{ label: 'Enrollments', data: monthCounts, borderColor: green, backgroundColor: greenSoft, fill: true, tension: .3, pointRadius: 4, pointBackgroundColor: yellow }] },
        options: { ...sharedOptions, plugins: { ...sharedOptions.plugins, legend: { display: false } } }
      });
      makeChart('chartQuizPerformance', barConfig(quizLabels, quizScores, 'Average quiz score', yellow, true, true));
      
  makeChart('chartQuizVsOnsite', {
      type: 'bar',
      data: {
          labels: performanceLabels,
          datasets: [
              {
                  label: 'Quiz Average',
                  data: quizAverages,
                  backgroundColor: '#075b35',
                  borderRadius: 5,
                  maxBarThickness: 24,
                  barPercentage: 0.8,
                  categoryPercentage: 0.72
              },
              {
                  label: 'Onsite Average',
                  data: onsiteAverages,
                  backgroundColor: '#e1dc36',
                  borderRadius: 5,
                  maxBarThickness: 24,
                  barPercentage: 0.8,
                  categoryPercentage: 0.72
              }
          ]
      },
      options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
              mode: 'index',
              intersect: false
          },
          plugins: {
              legend: {
                  display: true,
                  position: 'top',
                  align: 'center',
                  labels: {
                      color: '#183329',
                      usePointStyle: true,
                      pointStyle: 'circle',
                      padding: 18,
                      boxWidth: 8,
                      font: {
                          size: 12,
                          weight: '500'
                      }
                  }
              },
              tooltip: {
                  backgroundColor: '#183329',
                  padding: 12,
                  cornerRadius: 8,
                  callbacks: {
                      label: function(context) {
                          const value = context.raw;
                          return context.dataset.label + ': ' +
                              (value == null ? 'No data' :
                                  Number(value).toFixed(1) + '%');
                      }
                  }
              }
          },
          scales: {
              x: {
                  beginAtZero: true,
                  min: 0,
                  max: 100,
                  grid: {
                      color: '#e8eee9'
                  },
                  border: {
                      display: false
                  },
                  ticks: {
                      stepSize: 20,
                      color: '#6b7c73',
                      callback: value => value + '%'
                  }
              },
              y: {
                  grid: {
                      display: false
                  },
                  border: {
                      display: false
                  },
                  ticks: {
                      color: '#52665b',
                      font: {
                          size: 11
                      },
                      autoSkip: false
                  }
              }
          }
      }
  });

      makeChart('chartCompetency', {
        type: 'doughnut',
        data: { labels: competencyLabels, datasets: [{ data: competencyCounts, backgroundColor: [green, '#c94c4c', '#cbd5ce'], borderColor: '#fff', borderWidth: 3, hoverOffset: 7 }] },
        options: { responsive: true, maintainAspectRatio: false, cutout: '62%', plugins: { legend: { position: 'bottom', labels: { color: '#183329', padding: 14, usePointStyle: true } }, tooltip: sharedOptions.plugins.tooltip } }
      });
      makeChart('chartCompletion', barConfig(competentLabels, competencyRates, 'Competency rate among eligible enrollments', green, true, true));

      // Detailed Reports charts use the same live controller data.
      makeChart('reportMonthlyEnrollment', {
        type: 'line',
        data: { labels: monthLabels, datasets: [{ label: 'Enrollments', data: monthCounts, borderColor: green, backgroundColor: greenSoft, fill: true, tension: .3, pointRadius: 4, pointBackgroundColor: yellow }] },
        options: { ...sharedOptions, plugins: { ...sharedOptions.plugins, legend: { display: false } } }
      });
      makeChart('reportQuizPerformance', barConfig(quizLabels, quizScores, 'Average quiz score', yellow, true, true));
      makeChart('reportQuizVsOnsite', {
        type: 'bar',
        data: { labels: performanceLabels, datasets: [
          { label: 'Quiz Average', data: quizAverages, backgroundColor: green, borderRadius: 5 },
          { label: 'Onsite Average', data: onsiteAverages, backgroundColor: yellow, borderRadius: 5 }
        ] },
        options: { ...sharedOptions, scales: { ...sharedOptions.scales, y: { ...sharedOptions.scales.y, max: 100, ticks: { ...sharedOptions.scales.y.ticks, callback: value => value + '%' } } } }
      });
      makeChart('reportCompetency', {
        type: 'doughnut',
        data: { labels: competencyLabels, datasets: [{ data: competencyCounts, backgroundColor: [green, '#c94c4c', '#cbd5ce'], borderColor: '#fff', borderWidth: 3, hoverOffset: 7 }] },
        options: { responsive: true, maintainAspectRatio: false, cutout: '62%', plugins: { legend: { position: 'bottom', labels: { color: '#183329', padding: 14, usePointStyle: true } }, tooltip: sharedOptions.plugins.tooltip } }
      });
      makeChart('reportCompletion', barConfig(competentLabels, competencyRates, 'Competency rate among eligible enrollments', green, true, true));
      makeChart('chartBarangay', barConfig(barangayLabels, barangayCounts, 'Applicants', green, true));
  makeChart('chartEmployment', {
    type: 'doughnut',
    data: {
      labels: employmentLabels,
      datasets: [{
        data: employmentCounts,
        backgroundColor: [green, yellow, '#79a98b', '#b7cbbd', '#cbd5ce'],
        borderColor: '#fff',
        borderWidth: 3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom', labels: { color: '#183329', usePointStyle: true, padding: 12 } },
        tooltip: sharedOptions.plugins.tooltip
      }
    }
  });
  makeChart('chartSex', {
    type: 'doughnut',
    data: {
      labels: sexLabels,
      datasets: [{
        data: sexCounts,
        backgroundColor: [green, yellow, '#79a98b', '#cbd5ce'],
        borderColor: '#fff',
        borderWidth: 3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom', labels: { color: '#183329', usePointStyle: true, padding: 12 } },
        tooltip: sharedOptions.plugins.tooltip
      }
    }
  });
  makeChart('chartAgeGroups', barConfig(ageGroupLabels, ageGroupCounts, 'Applicants', yellow, true));

      // Populate the executive summary from the existing chart datasets (no hardcoded totals).
      const sumValues = values => (values || []).reduce((sum, value) => sum + (Number(value) || 0), 0);
      const setOverviewMetric = (id, value) => {
        const element = document.getElementById(id);
        if (element) element.textContent = value;
      };
      const applicantTotal = Math.max(sumValues(barangayCounts), sumValues(employmentCounts), sumValues(sexCounts), sumValues(ageGroupCounts));
      const enrollmentTotal = sumValues(enrollmentCounts);
      const eligibleTotal = sumValues(eligibleCounts);
      const competentTotal = sumValues(competentCounts);
      const overallCompetencyRate = eligibleTotal > 0 ? (competentTotal / eligibleTotal) * 100 : null;
      setOverviewMetric('overviewKpiApplicants', applicantTotal.toLocaleString());
      setOverviewMetric('overviewKpiEnrollments', enrollmentTotal.toLocaleString());
      setOverviewMetric('overviewKpiCourses', enrollmentLabels.length.toLocaleString());
      setOverviewMetric('overviewKpiCompetency', overallCompetencyRate === null ? 'N/A' : `${overallCompetencyRate.toFixed(1)}%`);

      // Category filters affect only the ten overview cards, never the separate reports view.
      const filterButtons = document.querySelectorAll('#view-overview [data-chart-filter]');
      const overviewCards = document.querySelectorAll('#view-overview .chart-card[data-category]');
      filterButtons.forEach(button => {
        if (button.dataset.filterBound === 'true') return;
        button.dataset.filterBound = 'true';
        button.addEventListener('click', () => {
          const selected = button.dataset.chartFilter || 'all';
          filterButtons.forEach(item => {
            const active = item === button;
            item.classList.toggle('is-active', active);
            item.setAttribute('aria-pressed', active ? 'true' : 'false');
          });
          overviewCards.forEach(card => {
            card.hidden = selected !== 'all' && card.dataset.category !== selected;
            if (!card.hidden) {
              const canvas = card.querySelector('canvas');
              const chart = canvas && typeof Chart !== 'undefined' ? Chart.getChart(canvas) : null;
              if (chart) requestAnimationFrame(() => chart.resize());
            }
          });
        });
      });

      // Every overview chart card opens the same expanded, data-driven view.
      Object.keys(window.__overviewChartData || {}).forEach(chartId => {
        const canvas = document.getElementById(chartId);
        const card = canvas ? canvas.closest('.chart-card') : null;
        if (!card || card.dataset.expansionBound === 'true') return;
        card.dataset.expansionBound = 'true';
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-label', `Expand ${window.__overviewChartData[chartId].title} with KPIs`);
        card.addEventListener('click', event => {
          if (event.target.closest('button, a, input, select, textarea')) return;
          openExpandedChartModal(chartId);
        });
        card.addEventListener('keydown', event => {
          if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            openExpandedChartModal(chartId);
          }
        });
      });
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', renderDashboardCharts);
    } else {
      renderDashboardCharts();
    }
  })();
  </script>


  <script>
(function () {
    const input = document.getElementById('registration-search-input');
    const courseFilter = document.getElementById('registration-course-filter');
    const list = document.getElementById('registrations-list-content');

    if (!list) return;

    const rows = Array.from(list.querySelectorAll('.registration-row'));
    const empty = document.getElementById('registration-filter-empty');

    function filterRegistrations() {
        const query = (input?.value || '').trim().toLowerCase();
        const selectedCourse = (courseFilter?.value || '').trim().toLowerCase();

        let visible = 0;

        rows.forEach(function (row) {
            const searchText = (
                row.dataset.registrationSearch || row.textContent
            ).toLowerCase();

            const rowCourse = (
                row.dataset.registrationCourse || ''
            ).trim().toLowerCase();

            const matchesSearch = searchText.includes(query);
            const matchesCourse =
                selectedCourse === '' || rowCourse === selectedCourse;

            const show = matchesSearch && matchesCourse;

            row.style.display = show ? '' : 'none';

            if (show) visible++;
        });

        if (empty) {
            empty.style.display =
                rows.length > 0 && visible === 0 ? 'block' : 'none';

            empty.textContent = 'No registrations match your filters.';
        }
    }

    input?.addEventListener('input', filterRegistrations);
    courseFilter?.addEventListener('change', filterRegistrations);
})();
</script>
  
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

  
<script>
/* Super Admin read-only UI guard. This is not a replacement for server-side authorization. */
document.addEventListener('DOMContentLoaded', function () {
  document.body.classList.add('superadmin-readonly');

  const allowedButtonWords = /\b(view|details|preview|print|export|download|search|filter|clear|close|cancel|back|next|previous|logout|log out|view more|expand|collapse|refresh|open|show)\b/i;
  const mutationWords = /\b(add|create|edit|update|save|submit|delete|remove|approve|reject|release|claim|assign|duplicate|restore|publish|unpublish|import|upload|issue certificate|mark claimed)\b/i;
  const mutationClasses = /\b(btn-add|btn-edit|btn-delete|btn-save|btn-update|btn-create|delete|edit|update|save|create|add-new|mutation-control)\b/i;

  document.querySelectorAll('button, input[type="submit"], input[type="button"], a.btn, a.button, .btn-add, .btn-edit, .btn-delete, .btn-save, .btn-update, .btn-create').forEach(function (el) {
    const label = ((el.innerText || el.value || el.getAttribute('aria-label') || el.title || '') + ' ' + (el.getAttribute('onclick') || '')).trim();
    const combined = label + ' ' + (el.className || '');
    const isLogout = /logout|log out|confirmLogout/i.test(combined);
    const isReadAction = allowedButtonWords.test(label) && !mutationWords.test(label);
    const isMutation = mutationClasses.test(String(el.className || '')) || (mutationWords.test(label) && !isReadAction);
    if (!isLogout && isMutation) {
      el.classList.add('mutation-control');
      el.setAttribute('aria-disabled', 'true');
      if (el.tagName === 'BUTTON' || el.matches('input')) el.disabled = true;
      else { el.removeAttribute('href'); el.style.pointerEvents = 'none'; }
    }
  });

  // Keep searches, filters, navigation, calendar controls, and logout usable.
  document.querySelectorAll('input:not([type="hidden"]), textarea, select').forEach(function (el) {
    const isLogout = !!el.closest('#logout-form');
    const isSearch = el.type === 'search' || el.matches('[data-search], .search-input, .registrations-search input, [name="search"], [id*="search" i]');
    const isFilter = el.matches('[id*="filter" i], [name*="filter" i], .overview-filter-btn, [data-chart-filter]') || !!el.closest('.overview-filter-group');
    if (isLogout || isSearch || isFilter) return;
    if (el.type === 'checkbox' || el.type === 'radio' || el.tagName === 'SELECT' || el.type === 'file') {
      el.disabled = true;
    } else {
      el.readOnly = true;
    }
    el.classList.add('readonly-field');
  });

  // Block submits from management forms, but preserve logout and read-only search/filter forms.
  document.querySelectorAll('form').forEach(function (form) {
    if (form.id === 'logout-form') return;
    const formText = ((form.getAttribute('action') || '') + ' ' + (form.id || '') + ' ' + (form.className || '')).toLowerCase();
    const readOnlyForm = /search|filter|pagination/.test(formText) || form.querySelector('[type="search"], [name="search"], [name*="filter" i]');
    if (readOnlyForm) return;
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      event.stopImmediatePropagation();
      if (typeof showAlert === 'function') showAlert('Super Admin has view-only access. Changes are not permitted.');
      else if (typeof openAlertModal === 'function') openAlertModal('Super Admin has view-only access. Changes are not permitted.');
      else window.alert('Super Admin has view-only access. Changes are not permitted.');
    }, true);
  });
});
</script>\n
</body>

  </html>

