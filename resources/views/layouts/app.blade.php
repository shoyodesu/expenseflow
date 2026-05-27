<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ExpenseFlow') — ExpenseFlow</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
 
    <style>
        :root {
            --yellow: #F5C800;
            --yellow-light: #FFF8D6;
            --yellow-dark: #C9A200;
            --sidebar-bg: #1A1A2E;
            --sidebar-hover: rgba(245,200,0,.12);
            --sidebar-active: rgba(245,200,0,.18);
            --sidebar-w: 240px;
            --topbar-h: 60px;
        }
        * { box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: #F4F5F8; }
        h1,h2,h3,h4,h5,h6,.fw-bold,.sidebar-logo { font-family: 'Syne', sans-serif; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed; top: 0; left: 0; bottom: 0;
            display: flex; flex-direction: column;
            z-index: 1000;
            transition: transform .3s;
        }
        .sidebar-logo {
            padding: 18px 16px;
            font-size: 17px; font-weight: 800; color: var(--yellow);
            border-bottom: 1px solid rgba(255,255,255,.08);
            letter-spacing: .5px;
            white-space: nowrap; overflow: hidden;
        }
        .sidebar-logo span { color: #fff; }
        .nav-section-label {
            padding: 16px 22px 6px;
            font-size: 10px; font-weight: 700;
            color: rgba(255,255,255,.3); letter-spacing: 1.5px; text-transform: uppercase;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 22px;
            color: rgba(255,255,255,.55);
            text-decoration: none;
            font-size: 14px; font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .15s;
        }
        .sidebar-link:hover {
            background: var(--sidebar-hover); color: var(--yellow);
        }
        .sidebar-link.active {
            background: var(--sidebar-active); color: var(--yellow);
            border-left-color: var(--yellow);
        }
        .sidebar-link i { font-size: 16px; width: 20px; text-align: center; }
        .sidebar-footer {
            margin-top: auto;
            padding: 16px 22px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-user { display: flex; align-items: center; gap: 10px; }
        .sidebar-user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--yellow); color: var(--sidebar-bg);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 14px; overflow: hidden; flex-shrink: 0;
        }
        .sidebar-user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-user-name { font-size: 13px; font-weight: 600; color: #fff; }
        .sidebar-user-role { font-size: 11px; color: rgba(255,255,255,.4); }

        /* ── Main ── */
        .main-wrapper { margin-left: var(--sidebar-w); min-height: 100vh; }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #E8E8F0;
            height: var(--topbar-h);
            padding: 0 24px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 18px; color: #1A1A2E; }
        .page-content { padding: 24px; }

        /* ── Cards ── */
        .stat-card {
            background: #fff; border-radius: 12px;
            padding: 20px; border: 1px solid #E8E8F0;
            transition: box-shadow .2s;
        }
        .stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.06); }
        .stat-icon {
            width: 44px; height: 44px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin-bottom: 12px;
        }
        .stat-value {
            font-family: 'Syne', sans-serif; font-weight: 800;
            font-size: clamp(16px, 2vw, 22px);
            color: #1A1A2E;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .stat-label { font-size: 12px; font-weight: 600; color: #9090A8; text-transform: uppercase; letter-spacing: .5px; }

        /* ── Buttons ── */
        .btn-yellow { background: var(--yellow); color: #1A1A2E; font-weight: 700; border: none; }
        .btn-yellow:hover { background: var(--yellow-dark); color: #1A1A2E; }

        /* ── Table ── */
        .table-card { background: #fff; border-radius: 12px; border: 1px solid #E8E8F0; overflow: hidden; }
        .table thead th { background: #F7F7FB; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #9090A8; border-bottom: 1px solid #E8E8F0; padding: 12px 16px; }
        .table tbody td { padding: 12px 16px; vertical-align: middle; font-size: 14px; color: #2A2A3C; border-bottom: 1px solid #F0F0F6; }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: var(--yellow-light); }

        /* ── Badges ── */
        .badge-category { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 99px; }
        .cat-food { background: #FFF3CD; color: #856404; }
        .cat-transport { background: #D1ECF1; color: #0C5460; }
        .cat-utilities { background: #D4EDDA; color: #155724; }
        .cat-entertainment { background: #F8D7DA; color: #721C24; }
        .cat-health { background: #D6D8F8; color: #3730A3; }
        .cat-others { background: #E8E8F0; color: #6060A0; }
        .badge-paid { background: #D4EDDA; color: #155724; }
        .badge-pending { background: #FFF3CD; color: #856404; }
        .badge-admin { background: var(--yellow-light); color: var(--yellow-dark); }
        .badge-user { background: #E8E8F0; color: #6060A0; }

        /* ── Form ── */
        .form-control:focus, .form-select:focus {
            border-color: var(--yellow);
            box-shadow: 0 0 0 3px rgba(245,200,0,.2);
        }
        .form-label { font-size: 12px; font-weight: 700; color: #6060A0; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }

        /* ── Toast ── */
        .toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 9999; }
        .toast { border-radius: 10px; font-size: 14px; min-width: 280px; }
        .toast-success { background: #1A1A2E; border-left: 4px solid var(--yellow); color: #fff; }
        .toast-error   { background: #1A1A2E; border-left: 4px solid #E05050; color: #fff; }

        /* ── Mobile ── */
        @media (max-width: 767px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .topbar { padding: 0 16px; }
            .page-content { padding: 16px; }
            .stat-value { font-size: 20px; }
        }

        /* ── Overlay ── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.5); z-index: 999;
        }
        .sidebar-overlay.show { display: block; }
    </style>
    @stack('head')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">Expense<span>Flow</span></div>

    <div class="nav-section-label">Main</div>
    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('expenses.index') }}" class="sidebar-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i> My Expenses
    </a>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> Users
    </a>
    @endif
    <a href="{{ route('profile.show') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i> Profile
    </a>

    <div class="nav-section-label">Account</div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-link w-100 text-start" style="background:none;border:none;cursor:pointer">
            <i class="bi bi-box-arrow-left"></i> Logout
        </button>
    </form>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="">
                @endif
            </div>
            <div>
                <div class="sidebar-user-name">{{ Str::limit(Auth::user()->name, 18) }}</div>
                <div class="sidebar-user-role">{{ ucfirst(Auth::user()->role) }}</div>
            </div>
        </div>
    </div>
</aside>

<!-- Main -->
<div class="main-wrapper">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none border-0 p-1" onclick="openSidebar()" style="color:#1A1A2E">
                <i class="bi bi-list fs-4"></i>
            </button>
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            @yield('topbar-actions')
        </div>
    </header>

    <main class="page-content">
        @yield('content')
    </main>
</div>

<!-- Toast Container -->
<div class="toast-container">
    @if(session('toast_success'))
    <div class="toast toast-success show align-items-center border-0" role="alert" id="toastSuccess">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2" style="color:var(--yellow)"></i>
                {{ session('toast_success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if(session('toast_error'))
    <div class="toast toast-error show align-items-center border-0" role="alert" id="toastError">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-exclamation-circle-fill me-2" style="color:#E05050"></i>
                {{ session('toast_error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Auto-dismiss toasts after 4s
    document.querySelectorAll('.toast').forEach(el => {
        const t = new bootstrap.Toast(el, { delay: 4000 });
        t.show();
    });

    function openSidebar() {
        document.getElementById('sidebar').classList.add('show');
        document.getElementById('sidebarOverlay').classList.add('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    // Delete confirmation
    function confirmDelete(formId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F5C800',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, delete it!',
            color: '#1A1A2E',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@stack('scripts')
</body>
</html>
