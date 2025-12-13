<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="/">SIMAJIAN</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="/">GTC</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>

            {{-- Kelola User & Roles --}}
            <li class="nav-link {{ request()->routeIs('admin.user.*') || request()->routeIs('admin.role.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fa-solid fa-users"></i><span>User & Role</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('admin.user.list') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.user.list') }}">Daftar User</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.role.view') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.role.view') }}">Daftar Role</a>
                    </li>
                </ul>
            </li>

            {{-- Kelola Tutor --}}
            <li class="nav-link {{ request()->routeIs('admin.tutor.view') ? 'active' : '' }}">
                <a href="{{ route('admin.tutor.view') }}">
                    <i class="fa-solid fa-chalkboard-user"></i><span>Tutor</span>
                </a>
            </li>
            <li class="nav-link {{ request()->routeIs('admin.kelas.view') ? 'active' : '' }}">
                <a href="{{ route('admin.kelas.view') }}">
                    <i class="fas fa-th-large"></i><span>Kelas</span>
                </a>
            </li>

            {{-- Penggajian --}}
            <li class="nav-link {{ request()->routeIs('admin.penggajian.list') ? 'active' : '' }}">
                <a href="{{ route('admin.penggajian.list') }}">
                    <i class="fa-solid fa-dollar-sign"></i><span>Penggajian</span>
                </a>
            </li>

            {{-- Pertemuan --}}
            <li class="nav-link {{ request()->routeIs('admin.pertemuan.list') ? 'active' : '' }}">
                <a href="{{ route('admin.pertemuan.list') }}">
                    <i class="fa-solid fa-calendar"></i>
                    <span>Pertemuan</span>
                </a>
            </li>

            {{-- Presensi Tutor --}}
            <li class="nav-link {{ request()->routeIs('admin.presensi.view') ? 'active' : '' }}">
                <a href="{{ route('admin.presensi.view') }}">
                    <i class="fa-solid fa-person-chalkboard"></i>
                    <span>Presensi Tutor</span>
                </a>
            </li>
        </ul>
    </aside>
</div>