<section id="sidebar">
    <a href="#" class="brand">
        <img src="https://img.icons8.com/?size=100&id=UUr7TPbn4dvp&format=png&color=000000" alt="" class="logo-img">
        <span class="text">Vicnex</span>
    </a>

    <ul class="side-menu top">
        {{-- Dashboard --}}
        <ul>
            <li class="has-submenu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
        </ul>

        {{-- Sản phẩm --}}
        <li class="has-submenu  {{ request()->is('admin/categories*') ? 'active' : '' }}">
            <a href="#">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">Danh mục sản phẩm</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.categories.index') }}"  class="{{ request()->routeIs('admin.categories.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách danh mục</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.create') }}"  class="{{ request()->routeIs('admin.categories.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm danh mục</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="has-submenu  {{ request()->is('admin/products*') || request()->is('admin/category-attribute*')? 'active' : '' }}">
            <a href="#"><i class='bx bx-package'></i><span class="text">Sản phẩm</span></a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.products.index') }}"  class="{{ request()->routeIs('admin.products.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách sản phẩm</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.create') }}"  class="{{ request()->routeIs('admin.products.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm sản phẩm</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="{{ route('admin.products.show', 1) }}" class="{{ request()->routeIs('admin.products.show') ? 'active1' : '' }}">
                        <i class='bx bx-show'></i>
                        <span class="text">Xem chi tiết sản phẩm</span>
                    </a>
                </li> -->
            </ul>
        </li>

        {{-- Bài viết --}}
        <li class="has-submenu  {{ request()->is('admin/post_categories*') ? 'active' : '' }}">
            <a href="#">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">Danh mục bài viết</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.post_categories.index') }}"  class="{{ request()->routeIs('admin.post_categories.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách danh mục</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.post_categories.create') }}"  class="{{ request()->routeIs('admin.post_categories.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm danh mục</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="has-submenu {{ request()->is('admin/posts*') ? 'active' : '' }}">
            <a href="#"><i class='bx bx-envelope'></i><span class="text">Bài viết</span></a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách bài viết</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.posts.create') }}" class="{{ request()->routeIs('admin.posts.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm bài viết</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Đơn hàng --}}
        <li class="{{ request()->is('admin/orders*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}">
                <i class='bx bx-cart'></i>
                <span class="text">Đơn hàng</span>
            </a>
        </li>

        {{-- Tài khoản --}}
        <li class="has-submenu  {{ request()->is('admin/users*') ? 'active' : '' }}">
            <a href="#"><i class='bx bxs-group'></i><span class="text">Tài khoản</span></a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.users.index') }}"  class="{{ request()->routeIs('admin.users.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách tài khoản</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.create') }}"  class="{{ request()->routeIs('admin.users.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm tài khoản</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="has-submenu  {{ request()->is('admin/roles*') ? 'active' : '' }}">
            <a href="#"><i class='bx bx-shield-quarter'></i><span class="text">Vai trò</span></a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.roles.index') }}"  class="{{ request()->routeIs('admin.roles.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách vai trò</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.roles.create') }}"  class="{{ request()->routeIs('admin.roles.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm vai trò</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Bình luận --}}
        <li class="has-submenu {{ request()->is('admin/comments*') ? 'active' : '' }}">
            <a href="#"><i class='bx bxs-message-dots'></i><span class="text">Bình luận</span></a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.comments.post.index') }}"
                    class="{{ request()->routeIs('admin.comments.post.*') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i><span class="text">Bình luận bài viết</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.comments.product.index') }}"
                    class="{{ request()->routeIs('admin.comments.product.*') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i><span class="text">Bình luận sản phẩm</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Mã giảm giá --}}
        <li class="has-submenu {{ request()->is('admin/vouchers*') ? 'active' : '' }}">
            <a href="#">
                <i class='bx bxs-discount'></i>
                <span class="text">Mã giảm giá</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.vouchers.index') }}" class="{{ request()->routeIs('admin.vouchers.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách Mã giảm giá</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.vouchers.create') }}" class="{{ request()->routeIs('admin.vouchers.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm Mã giảm giá</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Flash Sale --}}
        <li class="has-submenu {{ request()->is('admin/flash-sales*') ? 'active' : '' }}">
            <a href="#">
                <i class='bx bxs-hourglass'></i>
                <span class="text">Flash Sale</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.flash-sales.index') }}" class="{{ request()->routeIs('admin.flash-sales.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách Flash Sale</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.flash-sales.create') }}" class="{{ request()->routeIs('admin.flash-sales.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm Flash Sale</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Banner --}}
        <li class="has-submenu {{ request()->is('api/admin/banner*') ? 'active' : '' }}">
            <a href="#">
                <i class='bx bx-image'></i>
                <span class="text">Banner</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.banner.index') }}" class="{{ request()->routeIs('admin.banner.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách Banner</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.banner.create') }}" class="{{ request()->routeIs('admin.banner.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm Banner</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Popup Thông báo --}}
        <li class="has-submenu {{ request()->is('admin/popup*') ? 'active' : '' }}">
            <a href="#">
                <i class='bx bx-bell'></i>
                <span class="text">Popup Thông báo</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.popup.index') }}" class="{{ request()->routeIs('admin.popup.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách Popup</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.popup.create') }}" class="{{ request()->routeIs('admin.popup.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm Popup</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Quản lý sân --}}
        {{-- <li class="has-submenu  {{ request()->is('admin/courts*') || request()->is('admin/bookings*') ?  'active' : '' }}">
            <a href="#"><i class='bx bx-calendar-alt'></i><span class="text">Quản lí sân</span></a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.courts.index') }}"  class="{{ request()->routeIs('admin.courts.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i><span class="text">Danh sách sân</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bookings.index') }}"  class="{{ request()->routeIs('admin.bookings.index') ? 'active1' : '' }}">
                        <i class='bx bx-stopwatch'></i><span class="text">Lịch đặt sân</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.courts.create') }}"  class="{{ request()->routeIs('admin.courts.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i><span class="text">Thêm sân</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bookings.create') }}"  class="{{ request()->routeIs('admin.bookings.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus-circle'></i><span class="text">Tạo lịch đặt</span>
                    </a>
                </li>
            </ul>
        </li> --}}

        {{-- Quản lý địa điểm --}}
        {{-- <li class="has-submenu {{ request()->is('admin/locations*') ? 'active' : '' }}">
            <a href="#"><i class='bx bx-map'></i><span class="text">Quản lý địa điểm</span></a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.locations.index') }}" class="{{ request()->routeIs('admin.locations.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i><span class="text">Danh sách địa điểm</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.locations.create') }}" class="{{ request()->routeIs('admin.locations.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i><span class="text">Thêm địa điểm</span>
                    </a>
                </li>
            </ul>
        </li> --}}

        {{-- Thống kê --}}
        <li class="has-submenu  {{ request()->is('admin/statistics*') ? 'active' : '' }}">
            <a href="#">
                <i class='bx bx-bar-chart'></i>
                <span class="text">Thống kê</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.statistics.revenue') }}"  class="{{ request()->routeIs('admin.statistics.revenue*') ? 'active1' : '' }}">
                        <i class='bx bx-bar-chart'></i>
                        <span class="text">Thống kê doanh thu</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.statistics.order') }}"  class="{{ request()->routeIs('admin.statistics.order*') ? 'active1' : '' }}">
                        <i class='bx bx-bar-chart'></i>
                        <span class="text">Thống kê đơn hàng</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('admin.statistics.booking') }}"  class="{{ request()->routeIs('admin.statistics.booking*') ? 'active1' : '' }}">
                        <i class='bx bx-bar-chart'></i>
                        <span class="text">Thống kê lịch đặt sân</span>
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('admin.statistics.product') }}"  class="{{ request()->routeIs('admin.statistics.product*') ? 'active1' : '' }}">
                        <i class='bx bx-bar-chart'></i>
                        <span class="text">Thống kê sản phẩm</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Liên hệ --}}
        <li class="{{ request()->is('admin/contact*') ? 'active' : '' }}">
            <a href="{{ route('admin.contact.index') }}">
                <i class='bx bx-envelope'></i>
                <span class="text">Liên hệ</span>
            </a>
        </li>

        {{-- Quản lý chuyên gia --}}
        {{-- <li class="has-submenu {{ request()->is('admin/experts*') ? 'active' : '' }}">
            <a href="#">
                <i class='bx bx-user-pin'></i>
                <span class="text">Chuyên gia</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.experts.index') }}" class="{{ request()->routeIs('admin.experts.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách chuyên gia</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.experts.create') }}" class="{{ request()->routeIs('admin.experts.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm chuyên gia</span>
                    </a>
                </li>
            </ul>
        </li> --}}

        {{-- <li class="has-submenu {{ request()->is('admin/expert-reviews*') ? 'active' : '' }}">
            <a href="#">
                <i class='bx bx-comment-detail'></i>
                <span class="text">Nhận xét chuyên gia</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.expert-reviews.index') }}" class="{{ request()->routeIs('admin.expert-reviews.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách nhận xét</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.expert-reviews.create') }}" class="{{ request()->routeIs('admin.expert-reviews.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm nhận xét</span>
                    </a>
                </li>
            </ul>
        </li> --}}
    </ul>
</section>
