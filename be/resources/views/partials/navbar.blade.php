
@php
    use Illuminate\Support\Facades\Auth;
    $admin = Auth::user();
    $avatar = $admin && $admin->Avatar
        ? (filter_var($admin->Avatar, FILTER_VALIDATE_URL) ? $admin->Avatar : asset($admin->Avatar))
        : ($admin && $admin->Gender == 'female'
            ? asset('WebAdmin/img/avt/default-avatar-female.png')
            : asset('WebAdmin/img/avt/default-avatar-male.png'));
@endphp

<nav>
    <i class='bx bx-menu'></i>
    <a href="#" class="nav-link">Categories</a>
    <form action="{{ route('admin.products.index') }}" method="GET">
        <div class="form-input">
            <input type="search" name="keyword" placeholder="Search..." value="{{ request('keyword') }}">
            <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
        </div>
    </form>

    <input type="checkbox" id="switch-mode" hidden>
    <label for="switch-mode" class="switch-mode"></label>
    <a href="#" class="notification">
        <i class='bx bxs-bell'></i>
        <span class="num">0</span>
    </a>
    <ul class="side-menu">
        <li>
            <a href="#">
                <i class='bx bxs-cog'></i>
            </a>
        </li>
    </ul>
    <div style="display: flex; align-items: center; gap: 12px; margin-left: 16px;">
        <img src="{{ $avatar }}" alt="Avatar" style="width:38px; height:38px; border-radius:50%; object-fit:cover; border:2px solid #0154b9;">
        <span style="font-weight:600; color:#0154b9;">{{ $admin->Name ?? 'Admin' }}</span>
        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin:0;">
            @csrf
            <button type="submit"
                style="background:#e74c3c; color:#fff; border:none; border-radius:8px; padding:7px 16px; font-weight:600; font-size:15px; cursor:pointer; transition:background 0.2s;">
                <i class='bx bx-log-out'></i> Đăng xuất
            </button>
        </form>
    </div>
</nav>
