<div class="main-sidebar sidebar-style-2">
<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="/">Penggajian</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="/">GTC</a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="{{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}">
            {{-- <a href="{{ route('admin.dashboard.admin-dashboard') }}" class="nav-link">
                <i class="fas fa-fire"></i><span>Dashboard</span>
            </a> --}}
        </li>

        @php $role = auth()->user()->role->nama ?? ''; @endphp

        @if (in_array($role, ['super-admin', 'penilik']))
        <li class="menu-header">Manajemen User</li>
        <li class="nav-item dropdown {{ request()->routeIs('admin.user.*') || request()->routeIs('admin.role.*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i class="fas fa-users"></i><span>User & Role</span>
            </a>
            <ul class="dropdown-menu">
                <li class="{{ request()->routeIs('admin.user.user-list') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.user.list') }}">Daftar User</a>
                </li>
                <li class="{{ request()->routeIs('admin.role.list') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.role.view') }}">Daftar Role</a>
                </li>
            </ul>
        </li>

        <li class="menu-header">Penggajian</li>
        <li class="nav-item dropdown {{ request()->routeIs('admin.jabatan.*') || request()->routeIs('admin.bidang.*') || request()->routeIs('admin.golongan.*') || request()->routeIs('admin.pegawai.*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i class="fas fa-briefcase"></i><span>Kepegawaian</span>
            </a>
            <ul class="dropdown-menu">
                <li class="{{ request()->routeIs('admin.tutor-view') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.tutor-view') }}">Tutor</a>
                </li>
                <li class="{{ request()->routeIs('admin.kelas-view') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.kelas-view') }}">Kelas</a>
                </li>
                <li class="{{ request()->routeIs('admin.role-list') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.role-list') }}">Role</a>
                </li>
                <li class="{{ request()->routeIs('admin.user-list') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.user-list') }}">User</a>
                </li>
            </ul>
        </li>
        @endif

        {{-- @if (in_array($role, ['super-admin', 'sekretariat', 'admin', 'pegawai', 'kepala']))
        <li class="menu-header">Surat</li>
        <li class="nav-item dropdown {{ request()->routeIs('admin.kode-surat.*') || request()->routeIs('admin.surat-masuk.*') || request()->routeIs('admin.surat-keluar.*') || request()->routeIs('admin.disposisi.*') || request()->routeIs('admin.persetujuan-surat-keluar.*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i class="fas fa-envelope"></i><span>Surat</span>
            </a>
            <ul class="dropdown-menu">
                @if (in_array($role, ['super-admin', 'sekretariat']))
                <li class="{{ request()->routeIs('admin.kode-surat.list') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.kode-surat.list') }}">Kode Surat</a>
                </li>
                <li class="{{ request()->routeIs('admin.surat-masuk.list') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.surat-masuk.list') }}">Surat Masuk</a>
                </li>
                @endif
                @if (in_array($role, ['super-admin', 'sekretariat', 'pegawai']))
                <li class="{{ request()->routeIs('admin.surat-keluar.list') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.surat-keluar.list') }}">Surat Keluar</a>
                </li>
                @endif
                @if (in_array($role, ['super-admin', 'sekretariat', 'pegawai']))
                <li class="{{ request()->routeIs('admin.disposisi.list') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.disposisi.list') }}">Daftar Disposisi Saya</a>
                </li>
                @endif
                @if (in_array($role, ['super-admin', 'kepala']))
                <li class="{{ request()->routeIs('admin.disposisi.add') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.disposisi.add') }}">Tambah Disposisi</a>
                </li>
                <li class="{{ request()->routeIs('admin.persetujuan-surat-keluar.add') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.persetujuan-surat-keluar.add') }}">Persetujuan Surat</a>
                </li>
                @endif
            </ul>
        </li>
        @endif --}}
        
        {{-- @if (in_array($role, ['super-admin', 'sekretariat', 'admin', 'kepala']))
            <li class="menu-header">Laporan</li>
            <li class="nav-item dropdown {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-file"></i><span>Laporan</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('admin.laporan.laporan-surat') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.laporan.laporan-surat') }}">Laporan Surat</a>
                    </li>
                </ul>
            </li>
        @endif --}}

    </ul>
</aside>
</div>