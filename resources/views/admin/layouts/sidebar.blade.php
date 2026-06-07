<aside class="admin-sidebar">

    <div class="admin-brand">
        <i class="fa-solid fa-newspaper"></i>
        News CMS
    </div>

    <div class="admin-user">
        <div>
            <i class="fa-solid fa-user-circle me-1"></i>
            {{ auth()->user()->name }}
        </div>

        <small>
            {{ auth()->user()->role?->name ?? 'Chưa có vai trò' }}
        </small>
    </div>

    <nav class="admin-menu">
        <ul class="nav flex-column">

            <li class="nav-item">
    <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}"
       href="{{ route('admin.dashboard') }}">
        <i class="fa-solid fa-gauge"></i>
        Dashboard
    </a>
</li>

            @if(auth()->user()->hasPermission('category.manage'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}"
                       href="{{ route('categories.index') }}">
                        <i class="fa-solid fa-folder-tree"></i>
                        Danh mục
                    </a>
                </li>
            @endif

            @if(auth()->user()->hasPermission('post.create'))
                <li class="nav-item">
                   <a class="nav-link {{ request()->is('admin/posts') || request()->is('admin/posts/*') ? 'active' : '' }}"
   href="{{ route('posts.index') }}">
                        <i class="fa-solid fa-file-lines"></i>
                        Bài viết
                    </a>
                </li>
            @endif
@if(auth()->user()->hasPermission('post.publish'))
    <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/posts-pending*') ? 'active' : '' }}"
           href="{{ route('posts.pending') }}">
            <i class="fa-solid fa-clock"></i>
            Bài chờ duyệt

            @php
                $pendingCount = \App\Models\Post::where('status', 'pending')->count();
            @endphp

            @if($pendingCount > 0)
                <span class="badge bg-warning text-dark ms-auto">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>
    </li>
@endif
@if(auth()->user()->hasPermission('post.delete'))
    <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/posts-trash*') ? 'active' : '' }}"
           href="{{ route('posts.trash') }}">
            <i class="fa-solid fa-trash"></i>
            Thùng rác

            @php
                $trashCount = \App\Models\Post::onlyTrashed()->count();
            @endphp

            @if($trashCount > 0)
                <span class="badge bg-danger ms-auto">
                    {{ $trashCount }}
                </span>
            @endif
        </a>
    </li>
@endif
            @if(auth()->user()->hasPermission('media.manage'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/media*') ? 'active' : '' }}"
                       href="{{ route('media.index') }}">
                        <i class="fa-solid fa-photo-film"></i>
                        Media
                    </a>
                </li>
            @endif

            @if(auth()->user()->hasPermission('user.manage'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                       href="{{ route('users.index') }}">
                        <i class="fa-solid fa-users"></i>
                        Người dùng
                    </a>
                </li>
            @endif

            @if(auth()->user()->hasPermission('banner.manage'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/banners*') ? 'active' : '' }}"
                       href="{{ route('banners.index') }}">
                        <i class="fa-solid fa-image"></i>
                        Banner
                    </a>
                </li>
            @endif

            @if(auth()->user()->hasPermission('menu.manage'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/menus*') ? 'active' : '' }}"
                       href="{{ route('menus.index') }}">
                        <i class="fa-solid fa-bars"></i>
                        Menu
                    </a>
                </li>
            @endif

            @if(auth()->user()->hasPermission('page.manage'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/pages*') ? 'active' : '' }}"
                       href="{{ route('pages.index') }}">
                        <i class="fa-solid fa-file"></i>
                        Trang nội dung
                    </a>
                </li>
            @endif

            @if(auth()->user()->hasPermission('setting.manage'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}"
                       href="{{ route('settings.edit') }}">
                        <i class="fa-solid fa-gear"></i>
                        Cài đặt website
                    </a>
                </li>
            @endif
@if(auth()->user()->hasPermission('setting.manage'))
    <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/website-links*') ? 'active' : '' }}"
           href="{{ route('website-links.index') }}">
            <i class="fa-solid fa-link"></i>
            Liên kết website
        </a>
    </li>
@endif
            @if(auth()->user()->hasPermission('category.manage'))
    <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/tags*') ? 'active' : '' }}"
           href="{{ route('tags.index') }}">
            <i class="fa-solid fa-tags"></i>
            Tags
        </a>
    </li>
@endif
@if(auth()->user()->hasPermission('setting.manage'))
    <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/videos*') ? 'active' : '' }}"
           href="{{ route('videos.index') }}">
            <i class="fa-solid fa-video"></i>
            Video
        </a>
    </li>
@endif

        </ul>
    </nav>

</aside>