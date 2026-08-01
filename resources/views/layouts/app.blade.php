<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NH FINANCIAL EVENT MANAGEMENT</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons & FontAwesome -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* Light Theme Variables (Neumorphism) */
            --bg-color: #e6e9ef;
            --surface-color: #e6e9ef;
            --surface-solid: #e6e9ef;
            --text-primary: #334155;
            --text-secondary: #64748b;
            --border-color: transparent;
            --primary-red: #DC2626;
            --primary-red-hover: #b91c1c;
            --primary-light: rgba(220, 38, 38, 0.1);
            --success: #10b981;
            --success-light: rgba(16, 185, 129, 0.1);
            --warning: #f59e0b;
            --warning-light: rgba(245, 158, 11, 0.1);
            
            /* Neumorphic Shadows */
            --shadow-sm: 5px 5px 10px #c4c6cc, -5px -5px 10px #ffffff;
            --shadow-md: 8px 8px 16px #c4c6cc, -8px -8px 16px #ffffff;
            --shadow-inset: inset 5px 5px 10px #c4c6cc, inset -5px -5px 10px #ffffff;
            --shadow-glass: var(--shadow-md);
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="dark"] {
            /* Dark Theme Variables (Neumorphism) */
            --bg-color: #212428;
            --surface-color: #212428;
            --surface-solid: #212428;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --border-color: transparent;
            
            /* Dark Neumorphic Shadows */
            --shadow-sm: 5px 5px 10px #191b1e, -5px -5px 10px #292d32;
            --shadow-md: 8px 8px 16px #191b1e, -8px -8px 16px #292d32;
            --shadow-inset: inset 5px 5px 10px #191b1e, inset -5px -5px 10px #292d32;
            --shadow-glass: var(--shadow-md);
            
            --primary-light: rgba(220, 38, 38, 0.15);
            --success-light: rgba(16, 185, 129, 0.15);
            --warning-light: rgba(245, 158, 11, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            transition: var(--transition);
            overflow-x: hidden;
        }

        /* Neumorphism Utilities */
        .glass {
            background: var(--surface-color);
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-glass);
        }

        .glass-panel {
            background: var(--surface-solid);
            border: none;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }

        .app-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            height: 100%;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: var(--transition);
            border-right: none;
            background: var(--surface-color);
            box-shadow: var(--shadow-md);
            z-index: 50;
        }

        .sidebar.collapsed {
            width: 80px;
            overflow-x: hidden;
        }

        .sidebar.collapsed .menu-text, 
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .menu-group-title,
        .sidebar.collapsed .sidebar-branding {
            display: none !important;
        }

        .sidebar.collapsed .brand {
            justify-content: center;
            padding: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 12px;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .brand-icon {
            background: var(--primary-red);
            color: white;
            padding: 8px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 4px 4px 10px rgba(220, 38, 38, 0.4), -4px -4px 10px rgba(255, 100, 100, 0.1);
        }

        .brand-text h1 {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .nav-menu {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-menu::-webkit-scrollbar {
            width: 4px;
        }
        .nav-menu::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        .menu-group-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-secondary);
            font-weight: 600;
            margin: 14px 0 6px 12px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            border: none;
        }

        .nav-item:hover {
            box-shadow: var(--shadow-sm);
            color: var(--text-primary);
        }

        .nav-item.active {
            box-shadow: var(--shadow-inset);
            color: var(--primary-red);
            font-weight: 600;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        /* Top Navbar */
        .topbar {
            height: 72px;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: none;
            background: var(--surface-color);
            box-shadow: var(--shadow-sm);
            z-index: 40;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--surface-color);
            border: none;
            box-shadow: var(--shadow-inset);
            padding: 8px 16px;
            border-radius: 20px;
            width: 300px;
            transition: var(--transition);
        }

        .search-bar:focus-within {
            box-shadow: var(--shadow-inset);
            outline: 2px solid var(--primary-red);
        }

        .search-bar input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            color: var(--text-primary);
            font-size: 14px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-sim-notif {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: var(--surface-color);
            box-shadow: var(--shadow-sm);
            color: var(--primary-red);
            border: none;
            font-size: 0.785rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }
        .btn-sim-notif:hover {
            box-shadow: var(--shadow-inset);
            transform: translateY(-1px);
        }

        .icon-btn {
            background: var(--surface-color);
            border: none;
            box-shadow: var(--shadow-sm);
            color: var(--text-secondary);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .icon-btn:hover {
            box-shadow: var(--shadow-inset);
            color: var(--primary-red);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--primary-red);
            color: white;
            font-size: 10px;
            font-weight: bold;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg-color);
        }

        .notif-dropdown {
            position: absolute;
            top: 60px;
            right: 32px;
            width: 360px;
            background: var(--surface-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 100;
        }
        .notif-dropdown.show { display: flex; }

        .notif-header {
            padding: 14px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .notif-list {
            max-height: 280px;
            overflow-y: auto;
        }

        .notif-item {
            padding: 12px 18px;
            display: flex;
            gap: 12px;
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .notif-item:hover { background: rgba(220, 38, 38, 0.05); }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 24px;
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
        }

        .profile-img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-red);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            box-shadow: var(--shadow-sm);
        }

        .profile-text {
            display: flex;
            flex-direction: column;
        }
        .profile-text .name { font-size: 14px; font-weight: 600; }
        .profile-text .role { font-size: 12px; color: var(--text-secondary); }

        .page-content {
            flex: 1;
            padding: 32px;
            overflow-y: auto;
            position: relative;
        }

        /* Section Header */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .section-title h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .section-title p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .btn-primary {
            background: var(--primary-red);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            box-shadow: 4px 4px 10px rgba(220, 38, 38, 0.3), -4px -4px 10px rgba(255, 100, 100, 0.1);
            text-decoration: none;
        }
        .btn-primary:hover {
            background: var(--primary-red-hover);
            transform: translateY(-2px);
            box-shadow: inset 4px 4px 10px rgba(0, 0, 0, 0.2), inset -4px -4px 10px rgba(255, 255, 255, 0.1);
        }

        .btn-secondary {
            background: var(--surface-solid);
            color: var(--text-primary);
            border: none;
            box-shadow: var(--shadow-sm);
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            text-decoration: none;
        }
        .btn-secondary:hover {
            box-shadow: var(--shadow-inset);
            color: var(--primary-red);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-inset);
        }
        .stat-icon.red { color: var(--primary-red); }
        .stat-icon.green { color: var(--success); }
        .stat-icon.orange { color: var(--warning); }
        .stat-icon.gray { color: var(--text-primary); }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .trend-up { color: var(--success); }
        .trend-down { color: var(--primary-red); }

        .progress-wrapper {
            width: 100%;
            margin-top: 8px;
        }
        .progress-bar {
            height: 8px;
            background: var(--bg-color);
            box-shadow: var(--shadow-inset);
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: var(--primary-red);
            border-radius: 4px;
            transition: width 1s ease-in-out;
        }

        /* Table Styles */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            padding: 16px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-secondary);
            border-bottom: none;
            box-shadow: 0 4px 4px -4px rgba(0,0,0,0.1);
            font-weight: 600;
        }

        td {
            padding: 16px;
            font-size: 14px;
            border-bottom: none;
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        
        tr:hover td {
            color: var(--primary-red);
            transition: var(--transition);
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge.success { background: var(--success-light); color: var(--success); }
        .badge.warning { background: var(--warning-light); color: var(--warning); }
        .badge.danger { background: var(--primary-light); color: var(--primary-red); }

        /* Modal Dialog */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            z-index: 500;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .modal-overlay.show { display: flex; }

        .modal-card {
            background: var(--surface-color);
            border-radius: 24px;
            width: 100%;
            max-width: 540px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .modal-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .modal-header h3 { font-size: 1.25rem; font-weight: 700; }

        .modal-body { padding: 1.5rem; max-height: 70vh; overflow-y: auto; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem; }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-md);
            border: none;
            background: var(--surface-color);
            box-shadow: var(--shadow-inset);
            color: var(--text-primary);
            font-size: 0.875rem;
            outline: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: 2px solid var(--primary-red);
        }
        .modal-footer {
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        /* Toast Alert */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast-card {
            background: var(--surface-color);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 320px;
            max-width: 420px;
            border-left: 4px solid var(--primary-red);
            animation: fadeIn 0.3s ease;
        }

        /* Loading Screen */
        #loader {
            position: fixed;
            inset: 0;
            background: var(--bg-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--border-color);
            border-top-color: var(--primary-red);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) {
            .sidebar { position: fixed; left: -100%; width: 260px; }
            .sidebar.mobile-open { left: 0; }
            .search-bar { display: none; }
            .page-content { padding: 20px; }
            .stats-grid { grid-template-columns: 1fr; }
            .topbar { padding: 0 20px; }
        }
    </style>
</head>
<body>

    <!-- Loading Screen -->
    <div id="loader">
        <div class="spinner"></div>
        <h2 style="margin-top: 20px; font-weight: 600; color: var(--text-primary);">Memuat ERP...</h2>
        
        <!-- Branding Loading Screen -->
        <div style="position: absolute; bottom: 50px; text-align: center;">
            <span style="font-size: 11px; color: var(--text-secondary); letter-spacing: 1.5px; font-weight: 600;">POWERED BY</span>
            <div style="font-size: 16px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px; justify-content: center; margin-top: 8px;">
                <div style="background: var(--surface-color); box-shadow: var(--shadow-sm); padding: 6px; border-radius: 8px; display: flex;">
                    <i data-lucide="laptop" style="width: 16px; height: 16px; color: var(--primary-red);"></i>
                </div>
                <span>nhmedia<span style="font-weight: 400; color: var(--text-secondary);">technology</span></span>
            </div>
        </div>
    </div>

    <div class="app-container">
        
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <a href="{{ route('dashboard') }}" class="brand">
                <div class="brand-icon">
                    <i data-lucide="flag"></i>
                </div>
                <div class="brand-text">
                    <h1 style="font-size: 15px;">NH FINANCIAL</h1>
                    <p style="font-size: 11px;">EVENT MANAGEMENT</p>
                </div>
            </a>

            <nav class="nav-menu">
                <div class="menu-group-title">Utama</div>
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i> <span class="menu-text">Dashboard</span>
                </a>
                <a href="{{ route('panduan.index') }}" class="nav-item {{ request()->routeIs('panduan.*') ? 'active' : '' }}">
                    <i data-lucide="book-open"></i> <span class="menu-text">Panduan Manual</span>
                </a>
                <a href="{{ route('struktur.index') }}" class="nav-item {{ request()->routeIs('struktur.*') ? 'active' : '' }}">
                    <i data-lucide="users"></i> <span class="menu-text">Struktur Panitia</span>
                </a>

                <div class="menu-group-title">Keuangan</div>
                <a href="{{ route('anggaran.index') }}" class="nav-item {{ request()->routeIs('anggaran.*') ? 'active' : '' }}">
                    <i data-lucide="pie-chart"></i> <span class="menu-text">Anggaran (RAB)</span>
                </a>
                <a href="{{ route('pemasukan.index') }}" class="nav-item {{ request()->routeIs('pemasukan.*') ? 'active' : '' }}">
                    <i data-lucide="arrow-down-circle"></i> <span class="menu-text">Pemasukan</span>
                </a>
                <a href="{{ route('pengeluaran.index') }}" class="nav-item {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
                    <i data-lucide="arrow-up-circle"></i> <span class="menu-text">Pengeluaran</span>
                </a>
                <a href="{{ route('keuangan.index') }}" class="nav-item {{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
                    <i data-lucide="wallet"></i> <span class="menu-text">Buku Kas Semua</span>
                </a>

                <div class="menu-group-title">Relasi & Aset</div>
                <a href="{{ route('sponsor.index') }}" class="nav-item {{ request()->routeIs('sponsor.*') ? 'active' : '' }}">
                    <i data-lucide="handshake"></i> <span class="menu-text">Sponsor</span>
                </a>
                <a href="{{ route('dokumen.index') }}" class="nav-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                    <i data-lucide="folder-open"></i> <span class="menu-text">Dokumen & Laporan</span>
                </a>
                <a href="{{ route('acara.index') }}" class="nav-item {{ request()->routeIs('acara.*') || request()->routeIs('jadwal.*') ? 'active' : '' }}">
                    <i data-lucide="calendar"></i> <span class="menu-text">Jadwal Event (Acara)</span>
                </a>

                @if(auth()->user() && auth()->user()->isSuperadmin())
                <div class="menu-group-title">Superadmin</div>
                <a href="{{ route('desa.index') }}" class="nav-item {{ request()->routeIs('desa.*') ? 'active' : '' }}">
                    <i data-lucide="map-pin"></i> <span class="menu-text">Data Nama Desa</span>
                </a>
                <a href="{{ route('user.index') }}" class="nav-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                    <i data-lucide="shield-check"></i> <span class="menu-text">Manajemen User</span>
                </a>
                @endif
            </nav>

            <div style="margin-top: auto;">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; color: var(--primary-red); background: transparent;">
                        <i data-lucide="log-out"></i> <span class="menu-text">Keluar Sistem</span>
                    </button>
                </form>
                
                <!-- Branding Sidebar -->
                <a href="https://harunarrasyid.vercel.app" target="_blank" rel="noopener noreferrer" class="sidebar-branding" style="margin-top: 16px; text-align: center; padding: 14px; background: var(--surface-color); border-radius: var(--radius-md); box-shadow: var(--shadow-inset); display: block; text-decoration: none; color: inherit; transition: var(--transition); cursor: pointer;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'" title="Kunjungi Website Developer: Harun Ar Rasyid">
                    <span style="font-size: 10px; color: var(--text-secondary); letter-spacing: 1px; font-weight: 600;">DEVELOPED BY</span>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 6px; justify-content: center; margin-top: 6px;">
                        <i data-lucide="code-2" style="width: 16px; height: 16px; color: var(--primary-red);"></i>
                        <span>nhmedia<span style="font-weight: 400; color: var(--text-secondary);">technology</span></span>
                        <i data-lucide="external-link" style="width: 12px; height: 12px; color: var(--text-secondary); margin-left: 2px;"></i>
                    </div>
                </a>
            </div>
        </aside>

        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button type="button" class="icon-btn" onclick="toggleSidebar()">
                        <i data-lucide="panel-left-close"></i>
                    </button>
                    <form action="{{ request()->url() }}" method="GET" class="search-bar">
                        <i data-lucide="search" style="color: var(--text-secondary); width: 18px;"></i>
                        <input type="text" name="search" placeholder="Cari transaksi, desa, atau event..." value="{{ request('search') }}">
                    </form>
                </div>

                <div class="topbar-right">
                    <!-- Simulasi Real-time Notif Trigger (Hanya Mode Local/Demo) -->
                    @if(app()->isLocal())
                    <button type="button" class="btn-sim-notif" onclick="triggerSimulatedNotif()">
                        <i data-lucide="zap" style="width: 16px;"></i>
                        <span>Simulasi Notifikasi Realtime</span>
                    </button>
                    @endif

                    <!-- Theme Toggle -->
                    <button type="button" class="icon-btn" onclick="toggleTheme()" title="Toggle Light/Dark Mode">
                        <i data-lucide="sun" id="themeIcon"></i>
                    </button>

                    <!-- Notification Bell -->
                    <div style="position: relative;">
                        <button type="button" class="icon-btn" onclick="toggleNotifDropdown()">
                            <i data-lucide="bell"></i>
                            <span class="notification-badge" id="notif-count" style="display: none;">0</span>
                        </button>
                        <div class="notif-dropdown" id="notif-dropdown">
                            <div class="notif-header">
                                <span>Pusat Notifikasi</span>
                                <button type="button" onclick="markAllNotificationsRead()" style="background: none; border: none; color: var(--primary-red); font-weight: 600; cursor: pointer;">Tandai Dibaca</button>
                            </div>
                            <div class="notif-list" id="notif-list"></div>
                        </div>
                    </div>

                    <!-- User Profile -->
                    <a href="{{ route('profile.edit') }}" class="profile-btn glass-panel" style="border:none; background:transparent;">
                        <div class="profile-text" style="text-align:right;">
                            <span class="name">{{ auth()->user()->name ?? 'Budi Santoso' }}</span>
                            <span class="role">{{ auth()->user()->role === 'superadmin' ? 'Superadmin Utama' : 'Bendahara Utama' }}</span>
                        </div>
                        <div class="profile-img">
                            {{ strtoupper(substr(auth()->user()->name ?? 'BS', 0, 2)) }}
                        </div>
                    </a>
                </div>
            </header>

            <div class="page-content" id="mainContent">
                @if(session('success'))
                <div style="padding: 1rem 1.25rem; border-radius: var(--radius-md); background: var(--success-light); color: var(--success); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                    <i data-lucide="check-circle" style="width: 20px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if ($errors->any())
                <div style="padding: 1rem 1.25rem; border-radius: var(--radius-md); background: var(--primary-light); color: var(--primary-red); margin-bottom: 1.5rem;">
                    <strong>Terjadi Kesalahan Input:</strong>
                    <ul style="margin-top: 0.5rem; margin-left: 1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- TOAST NOTIFICATION CONTAINER -->
    <div class="toast-container" id="toast-container"></div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (window.lucide) {
                lucide.createIcons();
            }

            // Hide Loader smoothly
            setTimeout(() => {
                const loader = document.getElementById("loader");
                if (loader) {
                    loader.style.opacity = "0";
                    setTimeout(() => loader.style.display = "none", 500);
                }
            }, 600);

            // Fetch Realtime Notifications
            fetchNotifications();
            setInterval(fetchNotifications, 15000);
        });

        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("collapsed");
            if (window.lucide) lucide.createIcons();
        }

        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute("data-theme");
            const newTheme = currentTheme === "dark" ? "light" : "dark";
            html.setAttribute("data-theme", newTheme);
            localStorage.setItem("theme", newTheme);

            const icon = document.getElementById("themeIcon");
            if (icon) {
                icon.setAttribute("data-lucide", newTheme === "dark" ? "sun" : "moon");
                if (window.lucide) lucide.createIcons();
            }
        }

        // Initialize saved theme
        const savedTheme = localStorage.getItem("theme") || "dark";
        document.documentElement.setAttribute("data-theme", savedTheme);

        function fetchNotifications() {
            fetch("{{ route('notifications.unread') }}", {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById("notif-count");
                if (data.count > 0) {
                    badge.style.display = "flex";
                    badge.innerText = data.count;
                } else {
                    badge.style.display = "none";
                }

                const list = document.getElementById("notif-list");
                list.innerHTML = "";

                if (data.notifications.length === 0) {
                    list.innerHTML = `<div style="padding: 1.5rem; text-align: center; color: var(--text-secondary); font-size: 0.825rem;">Belum ada notifikasi baru</div>`;
                    return;
                }

                data.notifications.forEach(notif => {
                    list.innerHTML += `
                        <a href="${notif.data.url || '#'}" class="notif-item">
                            <div style="flex:1;">
                                <p style="font-weight:600; font-size:13px; color:var(--text-primary);">${notif.data.title || 'Info'}</p>
                                <p style="font-size:12px; color:var(--text-secondary); margin-top:2px;">${notif.data.message || ''}</p>
                            </div>
                        </a>
                    `;
                });
                if (window.lucide) lucide.createIcons();
            })
            .catch(err => console.error("Notif fetch error:", err));
        }

        function toggleNotifDropdown() {
            document.getElementById("notif-dropdown").classList.toggle("show");
        }

        function markAllNotificationsRead() {
            fetch("{{ route('notifications.read-all') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("notif-count").style.display = "none";
                    showToast("Semua Dibaca", "Notifikasi telah ditandai dibaca.");
                }
            });
        }

        function triggerSimulatedNotif() {
            fetch("{{ route('notifications.simulate') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchNotifications();
                    showToast("Realtime Alert: " + data.title, data.message);
                }
            });
        }

        function showToast(title, message) {
            const container = document.getElementById("toast-container");
            const toast = document.createElement("div");
            toast.className = "toast-card";
            toast.innerHTML = `
                <div>
                    <div style="font-weight: 700; font-size: 14px; color: var(--text-primary);">${title}</div>
                    <div style="font-size: 13px; color: var(--text-secondary); margin-top: 2px;">${message}</div>
                </div>
            `;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = "0";
                toast.style.transform = "translateY(10px)";
                toast.style.transition = "all 0.3s ease";
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        function openModal(id) {
            document.getElementById(id).classList.add("show");
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove("show");
        }
    </script>
</body>
</html>
