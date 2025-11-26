<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel RBAC')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white vh-100 position-fixed" style="width: 280px;">
            <div class="p-4">
                <h4 class="text-center">Exam - Portals</h4>
            </div>
            <nav class="nav flex-column p-3">
                @if (auth()->user()->hasPermission('view-dashboard'))
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link text-white {{ request()->is('admin/dashboard') ? 'bg-primary rounded' : '' }} mb-2">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                @endif

                @if (auth()->user()->is_user)
                    @if (auth()->user()->hasPermission('view-users'))
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'bg-danger rounded' : '' }} mb-2">
                            <i class="bi bi-people-fill"></i> Manage Users
                        </a>
                    @endif
                    {{-- @if (auth()->user()->hasPermission('view-users')) --}}
                    <a href="{{ route('admin.exam.forms') }}"
                        class="nav-link text-white {{ request()->is('admin/exam/forms') ? 'bg-danger rounded' : '' }} mb-2">
                        <i class="bi bi-people-fill"></i> Exam Form
                    </a>
                    {{-- @endif --}}
                @endif

                @if (auth()->user()->is_admin)
                    <hr class="border-secondary">
                    <div class="text-info small px-3 fw-bold text-uppercase">Admin Panel</div>

                    @if (auth()->user()->hasPermission('view-users'))
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'bg-danger rounded' : '' }} mb-2">
                            <i class="bi bi-people-fill"></i> Manage Users
                        </a>
                    @endif

                    @if (auth()->user()->hasPermission('view-roles'))
                        <a href="{{ route('admin.roles.index') }}"
                            class="nav-link text-white {{ request()->routeIs('admin.roles.*') ? 'bg-danger rounded' : '' }} mb-2">
                            <i class="bi bi-person-lock"></i> Roles
                        </a>
                    @endif

                    @if (auth()->user()->hasPermission('view-permissions'))
                        <a href="{{ route('admin.permissions.index') }}"
                            class="nav-link text-white {{ request()->routeIs('admin.permissions.*') ? 'bg-danger rounded' : '' }} mb-2">
                            <i class="bi bi-shield-check"></i> Permissions
                        </a>
                    @endif
                @endif

                <hr class="border-secondary mt-4">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-white btn btn-link text-start w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1" style="margin-left: 280px;">
            <div class="p-4">
                <h2>@yield('title')</h2>
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
