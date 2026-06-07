<header class="admin-navbar">

    <div>
        <span class="text-muted">
            Xin chào,
        </span>

        <strong>
            {{ auth()->user()->name }}
        </strong>
    </div>

    <div class="d-flex align-items-center gap-3">

        @php
            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();

            $latestNotifications = \App\Models\Notification::where('user_id', auth()->id())
                ->latest()
                ->take(5)
                ->get();
        @endphp

        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm position-relative"
                    data-bs-toggle="dropdown">
                <i class="fa-solid fa-bell"></i>

                <span id="notificationBadge"
                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $unreadCount > 0 ? '' : 'd-none' }}">
                    {{ $unreadCount }}
                </span>
            </button>

            <div class="dropdown-menu dropdown-menu-end" style="width: 330px;">
                <div class="dropdown-header">
                    Thông báo mới
                </div>

                <div id="notificationDropdownList">
                    @forelse($latestNotifications as $notification)
                        <a class="dropdown-item {{ !$notification->is_read ? 'fw-bold' : '' }}"
                           href="{{ route('notifications.read', $notification->id) }}">

                            <div>
                                {{ \Illuminate\Support\Str::limit($notification->title, 45) }}
                            </div>

                            <small class="text-muted">
                                {{ $notification->created_at?->format('d/m/Y H:i') }}
                            </small>
                        </a>
                    @empty
                        <span class="dropdown-item text-muted">
                            Chưa có thông báo.
                        </span>
                    @endforelse
                </div>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item text-center"
                   href="{{ route('notifications.index') }}">
                    Xem tất cả
                </a>
            </div>
        </div>

        <a href="{{ route('home') }}"
           target="_blank"
           class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-globe"></i>
            Xem website
        </a>

        <form method="POST"
              action="{{ route('logout') }}">

            @csrf

            <button class="btn btn-danger btn-sm">
                <i class="fa-solid fa-right-from-bracket"></i>
                Đăng xuất
            </button>

        </form>

    </div>

</header>

@push('scripts')
<script>
    function escapeHtml(text) {
        if (!text) {
            return '';
        }

        return text
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function limitText(text, maxLength) {
        if (!text) {
            return '';
        }

        return text.length > maxLength
            ? text.substring(0, maxLength) + '...'
            : text;
    }

    function loadNotifications() {
        fetch("{{ route('notifications.unread_data') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            const list = document.getElementById('notificationDropdownList');

            if (!badge || !list) {
                return;
            }

            if (data.unread_count > 0) {
                badge.classList.remove('d-none');
                badge.innerText = data.unread_count;
            } else {
                badge.classList.add('d-none');
                badge.innerText = '';
            }

            if (!data.notifications || data.notifications.length === 0) {
                list.innerHTML = `
                    <span class="dropdown-item text-muted">
                        Chưa có thông báo.
                    </span>
                `;
                return;
            }

            let html = '';

            data.notifications.forEach(item => {
                const title = escapeHtml(limitText(item.title, 45));
                const time = escapeHtml(item.created_at);
                const readClass = item.is_read ? '' : 'fw-bold';

                html += `
                    <a class="dropdown-item ${readClass}"
                       href="${item.read_url}">
                        <div>${title}</div>
                        <small class="text-muted">${time}</small>
                    </a>
                `;
            });

            list.innerHTML = html;
        })
        .catch(error => {
            console.error('Notification polling error:', error);
        });
    }

    setInterval(loadNotifications, 7000);

    document.addEventListener('DOMContentLoaded', loadNotifications);
</script>
@endpush