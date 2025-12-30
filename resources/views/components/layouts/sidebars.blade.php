<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="/">SIMAJI</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="/">GTC</a>
        </div>

        @php $role = auth()->user()->role->nama ?? ''; @endphp

        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>

            @if (in_array($role, ['tutor']))
                {{-- Dashboard Tutor --}}
                <li class="{{ request()->routeIs('admin.tutor.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.tutor.dashboard') }}" class="nav-link">
                        <i class="fa-solid fa-home"></i><span>Dashboard tutor</span>
                    </a>
                </li>
            @else
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="fa-solid fa-home"></i><span>Dashboard</span>
                    </a>
                </li>
            @endif

            @if (in_array($role, ['pemilik']))
                {{-- Kelola User & Roles --}}
                <li class="menu-header">Kelola User</li>
                <li
                    class="nav-item dropdown {{ request()->routeIs('admin.user.*') || request()->routeIs('admin.role.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fa-solid fa-users"></i><span>User & Role</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('admin.user.list') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.user.list') }}">Daftar User</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.role.list') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.role.list') }}">Daftar Role</a>
                        </li>
                    </ul>
                </li>

                {{-- Kelola Tarif --}}
                <li class="menu-header">Kelola Tarif</li>
                <li
                    class="nav-item dropdown {{ request()->routeIs('admin.kelas.*') || request()->routeIs('admin.jenjang.*') || request()->routeIs('admin.durasi.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fa-solid fa-bars"></i><span>Tarif</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('admin.kelas.list') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.kelas.list') }}">Kelas</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.jenjang.list') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.jenjang.list') }}">Jenjang</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.durasi.list') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.durasi.list') }}">Durasi Mengajar</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (in_array($role, ['admin']))
                {{-- Kelola Tutor --}}
                <li class="menu-header">Kelola Tutor</li>
                <li class="nav-item dropdown {{ request()->routeIs('admin.tutor.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.tutor.list') }}">
                        <i class="fa-solid fa-chalkboard-user"></i><span>Tutor</span>
                    </a>
                </li>
            @endif

            {{-- Pertemuan --}}
            {{-- <li class="menu-header">Pertemuan</li>
            <li class="nav-item dropdown {{ request()->routeIs('admin.pertemuan.list') ? 'active' : '' }}">
                <a href="{{ route('admin.pertemuan.list') }}">
                    <i class="fa-solid fa-calendar"></i>
                    <span>Pertemuan</span>
                </a>
            </li> --}}


            @if (in_array($role, ['pemilik', 'admin']))
                {{-- Penggajian --}}
                <li class="menu-header">Penggajian</li>
                <li
                    class="nav-item dropdown {{ request()->routeIs('admin.penggajian.*') || request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fa-solid fa-dollar-sign"></i><span>Penggajian</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('admin.penggajian.list') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.penggajian.list') }}">Daftar Gaji Tutor</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.laporan.laporan-gaji') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.laporan.laporan-gaji') }}">Laporan Penggajian</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (in_array($role, ['admin', 'tutor']))
                {{-- Presensi Tutor --}}
                <li class="nav-item dropdown {{ request()->routeIs('admin.presensi.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.presensi.list') }}">
                        <i class="fa-solid fa-person-chalkboard"></i>
                        <span>Presensi Tutor</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>