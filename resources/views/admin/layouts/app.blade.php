<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1B2537">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>@yield('title', 'Dashboard') — Karang Taruna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <style>
        :root {
            --sidebar-bg: #1B2537;
            --sidebar-hover: #263348;
            --sidebar-active: #1E3A8A;
            --sidebar-text: #94A3B8;
            --sidebar-text-active: #ffffff;
            --sidebar-width: 260px;
            --topbar-h: 64px;
            --primary: #4154F1;
            --success: #2ECC71;
            --warning: #F39C12;
            --danger: #E74C3C;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #F1F5F9;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3B82F6, #7C3AED);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            color: white;
            line-height: 1.1;
        }

        .sidebar-brand-sub {
            font-size: 0.62rem;
            color: #64748B;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4154F1, #7C3AED);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .sidebar-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-user-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: white;
        }

        .sidebar-user-role {
            font-size: 0.73rem;
            color: #64748B;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 12px 0;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
        }

        .sidebar-section {
            padding: 16px 20px 6px;
            font-size: 0.68rem;
            font-weight: 600;
            color: #475569;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            margin: 2px 10px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar-link i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .sidebar-link.active {
            background: var(--sidebar-active);
            color: white;
            box-shadow: 0 4px 15px rgba(65, 84, 241, 0.3);
        }

        .sidebar-link[aria-expanded="true"] .toggle-arrow {
            transform: rotate(180deg);
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            background: rgba(231, 76, 60, 0.1);
            color: #FC8181;
            border: 1px solid rgba(231, 76, 60, 0.2);
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: rgba(231, 76, 60, 0.2);
            color: #FCA5A5;
        }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: white;
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1E293B;
        }

        .topbar-breadcrumb {
            font-size: 0.8rem;
            color: #94A3B8;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-icon-btn {
            width: 38px;
            height: 38px;
            background: #F1F5F9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748B;
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
        }

        .topbar-icon-btn:hover {
            background: #E2E8F0;
            color: #1E293B;
        }

        .topbar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #4154F1, #7C3AED);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            overflow: hidden;
        }

        .topbar-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .content-area {
            padding: 28px;
            flex: 1;
            overflow-x: hidden; /* prevent horizontal scroll on content */
            min-width: 0;
        }

        /* Cards */
        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .stat-card .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1E293B;
            font-family: 'Poppins', sans-serif;
        }

        .stat-card .stat-label {
            font-size: 0.85rem;
            color: #64748B;
            font-weight: 500;
        }

        .card-admin {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .card-admin .card-header {
            background: white;
            border-bottom: 1px solid #F1F5F9;
            padding: 18px 24px;
            border-radius: 16px 16px 0 0;
        }

        .card-admin .card-body {
            padding: 24px;
        }

        /* Tables */
        .table-admin {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-admin thead th {
            background: #F8FAFC;
            color: #64748B;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
            padding: 12px 16px;
        }

        .table-admin tbody td {
            border: none;
            border-bottom: 1px solid #F1F5F9;
            padding: 14px 16px;
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .table-admin tbody tr:hover td {
            background: #F8FAFC;
        }

        .table-admin tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .badge-publish {
            background: #ECFDF5;
            color: #059669;
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-draft {
            background: #FEF3C7;
            color: #D97706;
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-upcoming {
            background: #EFF6FF;
            color: #1D4ED8;
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-ongoing {
            background: #FFFBEB;
            color: #D97706;
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-completed {
            background: #F0FDF4;
            color: #16A34A;
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Alerts */
        .alert-success-modern {
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            color: #065F46;
            border-radius: 12px;
            padding: 14px 18px;
        }

        .alert-error-modern {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
            border-radius: 12px;
            padding: 14px 18px;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, #4154F1, #7C3AED);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 5px 20px rgba(65, 84, 241, 0.35);
            color: white;
        }

        .btn-edit {
            background: #EFF6FF;
            color: #1D4ED8;
            border: none;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.82rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-edit:hover {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .btn-delete {
            background: #FEF2F2;
            color: #DC2626;
            border: none;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.82rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-delete:hover {
            background: #FEE2E2;
            color: #B91C1C;
        }

        /* Forms */
        .form-control-admin,
        .form-select-admin {
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-control-admin:focus,
        .form-select-admin:focus {
            border-color: #4154F1;
            box-shadow: 0 0 0 3px rgba(65, 84, 241, 0.1);
            outline: none;
        }

        .form-label-admin {
            font-weight: 600;
            font-size: 0.85rem;
            color: #374151;
            margin-bottom: 6px;
        }

        /* Mobile sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                padding: 0 16px;
            }

            .content-area {
                padding: 16px;
            }
            /* Topbar: hide globe icon on xs to save space */
            .topbar-right a.topbar-icon-btn { display: none; }
        }

        @media (max-width: 575px) {
            .content-area { padding: 12px; }
            .topbar { padding: 0 12px; }
            /* Prevent long breadcrumb overflow */
            .topbar-breadcrumb { display: none; }
        }

        /* Pagination Styling */
        .pagination {
            margin-bottom: 0;
            gap: 4px;
        }
        .pagination .page-item .page-link {
            border-radius: 8px;
            border: 1px solid #E2E8F0;
            color: #475569;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 6px 12px;
        }
        .pagination .page-item.active .page-link {
            background: #4154F1;
            border-color: #4154F1;
            color: white;
        }
        .pagination svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
        }
        svg {
            max-width: 100%;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon"><i class="bi bi-star-fill text-white" style="font-size:1rem;"></i></div>
            <div>
                <div class="sidebar-brand-name">Karang Taruna</div>
                <div class="sidebar-brand-sub">Portal Admin</div>
            </div>
        </div>

        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                @if(auth()->user()->foto)
                    <img src="{{ Storage::url(auth()->user()->foto) }}" alt="Avatar">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">{{ auth()->user()->role->name ?? 'Admin' }}</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section">Menu Utama</div>

            {{-- Dashboard always visible --}}
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
            </a>

            @foreach($sidebarMenus->where('parent_id', null) as $menu)
                @if($menu->slug !== 'dashboard')
                    @php
                        $children = $sidebarMenus->where('parent_id', $menu->id);
                        $isParentActive = request()->is(ltrim($menu->url, '/') . '*') || $children->contains(fn($child) => request()->is(ltrim($child->url, '/') . '*'));
                    @endphp

                    @if($children->count() > 0)
                        <div class="dropdown-sidebar">
                            <a href="#menu_collapse_{{ $menu->id }}"
                                class="sidebar-link d-flex align-items-center justify-content-between {{ $isParentActive ? 'active' : '' }}"
                                data-bs-toggle="collapse" aria-expanded="{{ $isParentActive ? 'true' : 'false' }}">
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bi {{ $menu->icon ?: 'bi-folder' }}"></i>
                                    <span>{{ $menu->name }}</span>
                                </span>
                                <i class="bi bi-chevron-down toggle-arrow" style="font-size:0.75rem;transition:transform 0.2s;"></i>
                            </a>
                            <div class="collapse {{ $isParentActive ? 'show' : '' }} ms-3" id="menu_collapse_{{ $menu->id }}">
                                @foreach($children as $child)
                                    <a href="{{ $child->url }}"
                                        class="sidebar-link py-2 my-1 {{ request()->is(ltrim($child->url, '/') . '*') ? 'active' : '' }}"
                                        style="font-size:0.83rem;margin: 2px 10px 2px 14px;padding: 8px 16px;">
                                        <i class="bi {{ $child->icon }} me-1"
                                            style="font-size:0.85rem;"></i><span>{{ $child->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $menu->url }}"
                            class="sidebar-link {{ request()->is(ltrim($menu->url, '/') . '*') ? 'active' : '' }}">
                            <i class="bi {{ $menu->icon ?: 'bi-folder' }}"></i><span>{{ $menu->name }}</span>
                        </a>
                    @endif
                @endif
            @endforeach
        </nav>

        <div class="sidebar-footer">
            <a href="{{ url('/') }}" class="sidebar-link mb-2" style="margin:0;padding:10px 14px;">
                <i class="bi bi-globe"></i><span>Lihat Website</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout border-0 cursor-pointer">
                    <i class="bi bi-box-arrow-left"></i><span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn border-0 p-0 d-lg-none" onclick="toggleSidebar()" style="background:none;">
                    <i class="bi bi-list" style="font-size:1.5rem;color:#64748B;"></i>
                </button>
                <div>
                    <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                    <div class="topbar-breadcrumb">@yield('breadcrumb', 'Karang Taruna')</div>
                </div>
            </div>
            <div class="topbar-right">
                <a href="{{ url('/') }}" class="topbar-icon-btn" title="Lihat Website" target="_blank">
                    <i class="bi bi-globe" style="font-size:1rem;"></i>
                </a>
                <div class="dropdown">
                    <div class="topbar-avatar" role="button" data-bs-toggle="dropdown">
                        @if(auth()->user()->foto)
                            <img src="{{ Storage::url(auth()->user()->foto) }}" alt="Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                        style="border-radius:12px;min-width:180px;">
                        <li>
                            <div class="px-3 py-2 border-bottom">
                                <div style="font-weight:600;font-size:0.85rem;">{{ auth()->user()->name }}</div>
                                <div style="font-size:0.75rem;color:#94A3B8;">{{ auth()->user()->email }}</div>
                            </div>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color:#DC2626;font-size:0.85rem;">
                                    <i class="bi bi-box-arrow-left me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert-success-modern mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-error-modern mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>

</html>