@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIPADI — {{ $title }}</title>
    <meta name="description" content="Sistem Informasi Produksi Padi dan Penjualan">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sw: 220px; --th: 64px;
            --primary: #2D6A4F; --primary-lt: #52B788; --accent: #1B4332;
            --bg: #F4F6F9; --white: #FFFFFF; --border: #E8ECF0;
            --text: #1A2332; --muted: #6B7A8D;
            --danger: #C0392B; --warning: #D4A017;
            --r: 10px; --rl: 14px;
            --shadow: 0 2px 8px rgba(0,0,0,.06), 0 0 1px rgba(0,0,0,.04);
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        /* SIDEBAR */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sw); height: 100vh;
            background: var(--white);
            box-shadow: 2px 0 12px rgba(0,0,0,.07);
            display: flex; flex-direction: column; z-index: 100;
        }
        .sb-brand {
            padding: 0 20px; height: var(--th);
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid var(--border); flex-shrink: 0;
        }
        .sb-brand-ico {
            width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), var(--primary-lt));
            display: flex; align-items: center; justify-content: center;
        }
        .sb-brand-ico i { color: #fff; font-size: 18px; }
        .sb-brand-txt { font-size: 15px; font-weight: 700; color: var(--accent); letter-spacing: -.3px; }
        .sb-brand-txt span { display: block; font-size: 10px; font-weight: 400; color: var(--muted); }
        .sb-nav { flex: 1; overflow-y: auto; padding: 14px 12px; display: flex; flex-direction: column; gap: 2px; }
        .nav-sec { font-size: 10px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); padding: 12px 8px 5px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px;
            border-radius: 8px; color: var(--muted); text-decoration: none;
            font-size: 13.5px; font-weight: 500; transition: all .2s;
        }
        .nav-item i { font-size: 17px; flex-shrink: 0; }
        .nav-item:hover { background: #F0F7F4; color: var(--primary); }
        .nav-item.active { background: linear-gradient(135deg,#E8F5EE,#D4EDE0); color: var(--primary); font-weight: 600; }
        .sb-footer { border-top: 1px solid var(--border); padding: 12px 12px 14px; }
        .sb-user { display: flex; align-items: center; gap: 10px; padding: 6px 10px; margin-bottom: 4px; }
        .sb-avatar {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), var(--primary-lt));
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #fff;
        }
        .sb-user-name { font-size: 13px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sb-user-role { font-size: 11px; color: var(--muted); }
        .sb-logout {
            display: flex; align-items: center; gap: 10px; width: 100%;
            padding: 9px 12px; border-radius: 8px; border: none; background: none;
            font-size: 13.5px; font-weight: 500; color: var(--muted); cursor: pointer;
            font-family: 'Inter', sans-serif; transition: all .2s; text-align: left;
        }
        .sb-logout:hover { background: #FEF0EF; color: var(--danger); }
        .sb-logout i { font-size: 17px; }

        /* TOPBAR */
        .topbar {
            position: fixed; top: 0; left: var(--sw); right: 0; height: var(--th);
            background: var(--white); border-bottom: 1px solid var(--border);
            display: flex; align-items: center; padding: 0 28px;
            justify-content: space-between; z-index: 90;
        }
        .tb-left { display: flex; align-items: center; gap: 14px; }
        .tb-title { font-size: 17px; font-weight: 700; color: var(--text); }
        .status-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600; background: #E8F5EE; color: var(--primary);
        }
        .status-badge::before {
            content: ''; width: 6px; height: 6px; border-radius: 50%;
            background: var(--primary-lt); display: inline-block;
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(.8)} }
        .tb-right { display: flex; align-items: center; gap: 18px; }
        .tb-dt-date { font-size: 13px; font-weight: 600; color: var(--text); text-align: right; }
        .tb-dt-time { font-size: 12px; color: var(--muted); text-align: right; }
        .tb-div { width: 1px; height: 24px; background: var(--border); }
        .tb-icon-btn {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: var(--bg); border: 1px solid var(--border);
            cursor: pointer; transition: all .2s; color: var(--muted); position: relative;
        }
        .tb-icon-btn:hover { background: #E8F5EE; color: var(--primary); border-color: var(--primary-lt); }
        .tb-icon-btn i { font-size: 17px; }
        .notif-dot { position: absolute; top: 6px; right: 6px; width: 7px; height: 7px; border-radius: 50%; background: var(--danger); border: 1.5px solid #fff; }

        /* MAIN */
        .main { margin-left: var(--sw); margin-top: var(--th); padding: 24px 28px; min-height: calc(100vh - var(--th)); flex: 1; }

        /* FLASH */
        .flash { padding: 12px 18px; border-radius: var(--r); margin-bottom: 18px; font-size: 13.5px; font-weight: 500; display: flex; align-items: center; gap: 8px; animation: slideDown .3s ease; }
        @keyframes slideDown { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
        .flash-ok  { background: #E8F5EE; color: var(--primary); border: 1px solid #A8D5B5; }
        .flash-err { background: #FEF0EF; color: var(--danger);  border: 1px solid #F5B7B1; }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sb-brand">
        <div class="sb-brand-ico"><i class="ph-bold ph-plant"></i></div>
        <div class="sb-brand-txt">SIPADI <span>Kelompok Tani</span></div>
    </div>

    <nav class="sb-nav">
        <div class="nav-sec">Menu Utama</div>
        <a href="{{ route('dashboard') }}" id="nav-dashboard"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="ph-bold ph-squares-four"></i> Dashboard
        </a>

        @php $role = session('pengguna_role'); @endphp

        @if(in_array($role, ['ketua','staf_produksi']))
        <div class="nav-sec">Produksi</div>
        <a href="{{ route('pertanian.index') }}" id="nav-pertanian"
           class="nav-item {{ request()->routeIs('pertanian.*') ? 'active' : '' }}">
            <i class="ph-bold ph-field"></i> Lahan &amp; Bibit
        </a>
        <a href="{{ route('pengadaan.index') }}" id="nav-pengadaan"
           class="nav-item {{ request()->routeIs('pengadaan.*') ? 'active' : '' }}">
            <i class="ph-bold ph-package"></i> Pengadaan
        </a>
        <a href="{{ route('panen.index') }}" id="nav-panen"
           class="nav-item {{ request()->routeIs('panen.*') ? 'active' : '' }}">
            <i class="ph-bold ph-basket"></i> Data Panen
        </a>
        <a href="{{ route('cuaca.index') }}" id="nav-cuaca"
           class="nav-item {{ request()->routeIs('cuaca.*') ? 'active' : '' }}">
            <i class="ph-bold ph-cloud-sun"></i> Info Cuaca
        </a>
        @endif

        @if(in_array($role, ['ketua','staf_penjualan']))
        <div class="nav-sec">Penjualan</div>
        <a href="{{ route('transaksi.index') }}" id="nav-transaksi"
           class="nav-item {{ request()->routeIs('transaksi.*') && !request()->routeIs('transaksi.konten.edit') ? 'active' : '' }}">
            <i class="ph-bold ph-shopping-cart"></i> Transaksi
        </a>
        <a href="{{ route('transaksi.konten.edit') }}" id="nav-konten-landing"
           class="nav-item {{ request()->routeIs('transaksi.konten.edit') ? 'active' : '' }}">
            <i class="ph-bold ph-gear"></i> Katalog & Landing
        </a>
        @endif

        @if(in_array($role, ['ketua','staf_keuangan']))
        <div class="nav-sec">Keuangan</div>
        <a href="{{ route('keuangan.index') }}" id="nav-keuangan"
           class="nav-item {{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
            <i class="ph-bold ph-money"></i> Arus Kas
        </a>
        <a href="{{ route('bagi-hasil.index') }}" id="nav-bagi-hasil"
           class="nav-item {{ request()->routeIs('bagi-hasil.*') ? 'active' : '' }}">
            <i class="ph-bold ph-chart-pie-slice"></i> Bagi Hasil
        </a>
        <a href="{{ route('laporan.index') }}" id="nav-laporan"
           class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="ph-bold ph-file-text"></i> Laporan
        </a>
        @endif

        @if($role === 'ketua')
        <div class="nav-sec">Administrasi</div>
        <a href="{{ route('anggota.index') }}" id="nav-anggota"
           class="nav-item {{ request()->routeIs('anggota.*') ? 'active' : '' }}">
            <i class="ph-bold ph-user-list"></i> Anggota Tani
        </a>
        <a href="{{ route('pengguna.index') }}" id="nav-pengguna"
           class="nav-item {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
            <i class="ph-bold ph-users"></i> Hak Akses
        </a>
        @endif
    </nav>

    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr(session('pengguna_username','U'),0,1)) }}</div>
            <div>
                <div class="sb-user-name">{{ session('pengguna_username','Pengguna') }}</div>
                <div class="sb-user-role">{{ ucfirst(str_replace('_',' ',session('pengguna_role',''))) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('autentikasi.logout') }}">
            @csrf
            <button type="submit" class="sb-logout" id="btn-logout">
                <i class="ph-bold ph-sign-out"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<header class="topbar">
    <div class="tb-left">
        <h1 class="tb-title">{{ $title }}</h1>
        <span class="status-badge">Sistem Aktif</span>
    </div>
    <div class="tb-right">
        <div>
            <div class="tb-dt-date" id="tb-date">—</div>
            <div class="tb-dt-time" id="tb-time">—</div>
        </div>
        <div class="tb-div"></div>
        <div class="tb-icon-btn" title="Notifikasi" id="btn-notif">
            <i class="ph-bold ph-bell"></i>
            <span class="notif-dot"></span>
        </div>
    </div>
</header>

<main class="main">
    @if(session('success'))
    <div class="flash flash-ok" id="flash-ok">
        <i class="ph-bold ph-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flash flash-err" id="flash-err">
        <i class="ph-bold ph-x-circle"></i> {{ session('error') }}
    </div>
    @endif

    {{ $slot }}
</main>

<script>
    function clock() {
        const n = new Date();
        const days  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const months= ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        document.getElementById('tb-date').textContent =
            `${days[n.getDay()]}, ${n.getDate()} ${months[n.getMonth()]} ${n.getFullYear()}`;
        document.getElementById('tb-time').textContent =
            `${String(n.getHours()).padStart(2,'0')}:${String(n.getMinutes()).padStart(2,'0')}:${String(n.getSeconds()).padStart(2,'0')} WIB`;
    }
    clock(); setInterval(clock, 1000);

    // Auto-dismiss flash
    setTimeout(() => {
        ['flash-ok','flash-err'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { el.style.transition='opacity .4s'; el.style.opacity='0'; setTimeout(()=>el.remove(),400); }
        });
    }, 4000);
</script>
@stack('scripts')
</body>
</html>
