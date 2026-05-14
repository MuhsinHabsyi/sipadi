<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIPADI — {{ $title ?? 'Dashboard' }}</title>
    <meta name="description" content="Sistem Informasi Produksi Padi dan Penjualan - Kelompok Tani">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-width: 220px;
            --topbar-height: 64px;
            --color-primary:    #2D6A4F;
            --color-primary-lt: #52B788;
            --color-accent:     #1B4332;
            --color-bg:         #F4F6F9;
            --color-white:      #FFFFFF;
            --color-border:     #E8ECF0;
            --color-text:       #1A2332;
            --color-muted:      #6B7A8D;
            --color-success:    #2D6A4F;
            --color-warning:    #D4A017;
            --color-danger:     #C0392B;
            --color-info:       #2471A3;
            --radius-md:        10px;
            --radius-lg:        14px;
            --shadow-card:      0 2px 8px rgba(0,0,0,.06), 0 0 1px rgba(0,0,0,.04);
            --shadow-sidebar:   2px 0 12px rgba(0,0,0,.07);
            --transition:       all .2s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            min-height: 100vh;
            display: flex;
        }

        /* ────────────────────── SIDEBAR ────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--color-white);
            box-shadow: var(--shadow-sidebar);
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 0 20px;
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--color-border);
            flex-shrink: 0;
        }

        .sidebar-brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-lt));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand-icon i { color: #fff; font-size: 18px; }

        .sidebar-brand-text {
            font-size: 15px;
            font-weight: 700;
            color: var(--color-accent);
            letter-spacing: -0.3px;
        }

        .sidebar-brand-text span {
            display: block;
            font-size: 10px;
            font-weight: 400;
            color: var(--color-muted);
            letter-spacing: 0;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            scrollbar-width: thin;
            scrollbar-color: #D4EDE0 transparent;
        }
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background-color: #D4EDE0;
            border-radius: 10px;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--color-muted);
            padding: 12px 8px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: var(--color-muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
        }

        .nav-item i { font-size: 17px; flex-shrink: 0; }

        .nav-item:hover {
            background: #F0F7F4;
            color: var(--color-primary);
        }

        .nav-item.active {
            background: linear-gradient(135deg, #E8F5EE, #D4EDE0);
            color: var(--color-primary);
            font-weight: 600;
        }

        .nav-item.active i { color: var(--color-primary); }

        .sidebar-footer {
            border-top: 1px solid var(--color-border);
            padding: 14px 12px;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
        }

        .sidebar-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-lt));
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
        }

        .sidebar-user-info { flex: 1; overflow: hidden; }
        .sidebar-user-name { font-size: 13px; font-weight: 600; color: var(--color-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-role { font-size: 11px; color: var(--color-muted); }

        .sidebar-logout {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: var(--color-muted);
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            margin-top: 4px;
        }

        .sidebar-logout:hover { background: #FEF0EF; color: var(--color-danger); }
        .sidebar-logout i { font-size: 17px; }

        /* ────────────────────── TOPBAR ────────────────────── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: var(--color-white);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            justify-content: space-between;
            z-index: 90;
        }

        .topbar-left { display: flex; align-items: center; gap: 14px; }

        .topbar-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--color-text);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #E8F5EE;
            color: var(--color-primary);
        }

        .status-badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--color-primary-lt);
            display: inline-block;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .6; transform: scale(.8); }
        }

        .topbar-right { display: flex; align-items: center; gap: 20px; }

        .topbar-datetime {
            text-align: right;
        }

        .topbar-date { font-size: 13px; font-weight: 600; color: var(--color-text); }
        .topbar-time { font-size: 12px; color: var(--color-muted); }

        .topbar-divider { width: 1px; height: 24px; background: var(--color-border); }

        .topbar-icon-btn {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: var(--color-bg);
            border: 1px solid var(--color-border);
            cursor: pointer;
            transition: var(--transition);
            color: var(--color-muted);
            position: relative;
        }

        .topbar-icon-btn:hover { background: #E8F5EE; color: var(--color-primary); border-color: var(--color-primary-lt); }
        .topbar-icon-btn i { font-size: 17px; }

        .notif-dot {
            position: absolute; top: 6px; right: 6px;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--color-danger);
            border: 1.5px solid white;
        }

        /* ────────────────────── MAIN CONTENT ────────────────────── */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 24px 28px;
            min-height: calc(100vh - var(--topbar-height));
            flex: 1;
        }

        /* ────────────────────── CARDS ────────────────────── */
        .card {
            background: var(--color-white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            padding: 20px 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text);
        }

        /* ────────────────────── FLASH MESSAGES ────────────────────── */
        .flash-message {
            padding: 12px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 18px;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: slideDown .3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .flash-success { background: #E8F5EE; color: var(--color-success); border: 1px solid #A8D5B5; }
        .flash-error   { background: #FEF0EF; color: var(--color-danger);  border: 1px solid #F5B7B1; }

        /* ────────────────────── UTILITY ────────────────────── */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-4 { gap: 16px; }
        .gap-6 { gap: 24px; }
        .w-full { width: 100%; }
        .text-muted { color: var(--color-muted); font-size: 12px; }
    </style>

    @stack('styles')
</head>
<body>

    <!-- ══ SIDEBAR ══ -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="ph-bold ph-plant"></i>
            </div>
            <div class="sidebar-brand-text">
                SIPADI
                <span>Kelompok Tani</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu Utama</div>

            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               id="nav-dashboard">
                <i class="ph-bold ph-squares-four"></i>
                Dashboard
            </a>

            @php $role = session('pengguna_role'); @endphp

            @if($role === 'ketua')
            <div class="nav-section-label">Administrasi</div>
            <a href="{{ route('anggota.index') }}"
               class="nav-item {{ request()->routeIs('anggota.*') ? 'active' : '' }}"
               id="nav-anggota">
                <i class="ph-bold ph-user-list"></i>
                Data Anggota Petani
            </a>
            <a href="{{ route('pengguna.index') }}"
               class="nav-item {{ request()->routeIs('pengguna.*') ? 'active' : '' }}"
               id="nav-pengguna">
                <i class="ph-bold ph-users"></i>
                Hak Akses
            </a>
            @endif

            @if(in_array($role, ['ketua', 'staf_produksi']))
            <div class="nav-section-label">Produksi</div>
            <a href="{{ route('pertanian.index') }}"
               class="nav-item {{ request()->routeIs('pertanian.*') ? 'active' : '' }}"
               id="nav-pertanian">
                <i class="ph-bold ph-field"></i>
                Lahan & Bibit
            </a>
            <a href="{{ route('panen.index') }}"
               class="nav-item {{ request()->routeIs('panen.*') ? 'active' : '' }}"
               id="nav-panen">
                <i class="ph-bold ph-basket"></i>
                Data Panen
            </a>
            <a href="{{ route('cuaca.index') }}"
               class="nav-item {{ request()->routeIs('cuaca.*') ? 'active' : '' }}"
               id="nav-cuaca">
                <i class="ph-bold ph-cloud-sun"></i>
                Info Cuaca
            </a>
            @endif

            @if(in_array($role, ['ketua', 'staf_penjualan']))
            <div class="nav-section-label">Penjualan</div>
            <a href="{{ route('transaksi.index') }}"
               class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}"
               id="nav-transaksi">
                <i class="ph-bold ph-shopping-cart"></i>
                Transaksi
            </a>
            @endif

            @if(in_array($role, ['ketua', 'staf_keuangan']))
            <div class="nav-section-label">Keuangan</div>
            <a href="{{ route('keuangan.index') }}"
               class="nav-item {{ request()->routeIs('keuangan.*') ? 'active' : '' }}"
               id="nav-keuangan">
                <i class="ph-bold ph-money"></i>
                Arus Kas
            </a>
            <a href="{{ route('bagi-hasil.index') }}"
               class="nav-item {{ request()->routeIs('bagi-hasil.*') ? 'active' : '' }}"
               id="nav-bagi-hasil">
                <i class="ph-bold ph-chart-pie-slice"></i>
                Bagi Hasil
            </a>
            <a href="{{ route('laporan.index') }}"
               class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}"
               id="nav-laporan">
                <i class="ph-bold ph-file-text"></i>
                Laporan
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(session('pengguna_username', 'U'), 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ session('pengguna_username', 'Pengguna') }}</div>
                    <div class="sidebar-user-role">{{ ucfirst(str_replace('_', ' ', session('pengguna_role', ''))) }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('autentikasi.logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout" id="btn-logout">
                    <i class="ph-bold ph-sign-out"></i>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- ══ TOPBAR ══ -->
    <header class="topbar">
        <div class="topbar-left">
            <h1 class="topbar-title">{{ $title ?? 'Dashboard' }}</h1>
            <span class="status-badge">Sistem Aktif</span>
        </div>
        <div class="topbar-right">
            <div class="topbar-datetime">
                <div class="topbar-date" id="topbar-date">—</div>
                <div class="topbar-time" id="topbar-time">—</div>
            </div>
            <div class="topbar-divider"></div>
            <div class="topbar-icon-btn" title="Notifikasi" id="btn-notif">
                <i class="ph-bold ph-bell"></i>
                <span class="notif-dot"></span>
            </div>
        </div>
    </header>

    <!-- ══ MAIN CONTENT ══ -->
    <main class="main-content">

        @if(session('success'))
        <div class="flash-message flash-success">
            <i class="ph-bold ph-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="flash-message flash-error">
            <i class="ph-bold ph-x-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Live clock -->
    <script>
        function updateClock() {
            const now = new Date();
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            const day  = days[now.getDay()];
            const date = now.getDate();
            const mon  = months[now.getMonth()];
            const year = now.getFullYear();
            const h = String(now.getHours()).padStart(2,'0');
            const m = String(now.getMinutes()).padStart(2,'0');
            const s = String(now.getSeconds()).padStart(2,'0');
            document.getElementById('topbar-date').textContent = `${day}, ${date} ${mon} ${year}`;
            document.getElementById('topbar-time').textContent = `${h}:${m}:${s} WIB`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    @stack('scripts')
</body>
</html>
