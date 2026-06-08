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
    $unreadCount = auth()->user()->hasPermission('post.publish')
        ? \App\Models\Post::where('status', 'pending')->count()
        : \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

    $latestNotifications = \App\Models\Notification::where('user_id', auth()->id())
        ->latest()
        ->take(5)
        ->get();
@endphp

        {{-- NOTIFICATION BELL --}}
        <div class="dropdown">

            <button type="button"
                    class="btn btn-outline-secondary btn-sm notification-bell"
                    data-bs-toggle="dropdown"
                    title="Thông báo">

                <i class="fa-solid fa-bell"></i>

             <span id="notificationBadge"
      class="notification-badge"
      style="{{ $unreadCount > 0 ? 'display:inline-flex;' : 'display:none;' }}">
    {{ $unreadCount > 0 ? $unreadCount : '' }}
</span>

            </button>

            <div class="dropdown-menu dropdown-menu-end notification-dropdown"
                 style="width: 340px;">

                <div class="dropdown-header fw-bold">
                    <i class="fa-solid fa-bell me-1"></i>
                    Thông báo mới
                </div>

                <div id="notificationDropdownList">

                    @forelse($latestNotifications as $notification)

                        <a class="dropdown-item {{ !$notification->is_read ? 'fw-bold' : '' }}"
                           href="{{ route('notifications.read', $notification->id) }}">

                            <div>
                                {{ \Illuminate\Support\Str::limit($notification->title, 50) }}
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

        {{-- VIEW WEBSITE --}}
        <a href="{{ route('home') }}"
           target="_blank"
           class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-globe"></i>
            Xem website
        </a>

        {{-- LOGOUT --}}
        <form method="POST"
              action="{{ route('logout') }}">

            @csrf

            <button type="submit"
                    class="btn btn-danger btn-sm">
                <i class="fa-solid fa-right-from-bracket"></i>
                Đăng xuất
            </button>

        </form>

    </div>

</header>

@push('scripts')
<script>
    let navbarCurrentUnreadCount = null;
    let navbarSoundEnabled = false;

    function enableNavbarSoundOnce() {
        navbarSoundEnabled = true;
    }

    document.addEventListener('click', enableNavbarSoundOnce, { once: true });

    function playNavbarNotificationSound() {
        if (!navbarSoundEnabled) {
            return;
        }

        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();

            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(900, audioContext.currentTime);

            gainNode.gain.setValueAtTime(0.28, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.45);

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.45);
        } catch (error) {
            console.log('Không thể phát âm thanh thông báo:', error);
        }
    }

    function showNavbarToast(message) {
        let toast = document.getElementById('realtimeToast');

        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'realtimeToast';
            toast.className = 'realtime-toast';
            document.body.appendChild(toast);
        }

        toast.innerHTML = '<i class="fa-solid fa-bell me-1"></i> ' + message;
        toast.style.display = 'block';

        setTimeout(function () {
            toast.style.display = 'none';
        }, 4000);
    }

    function escapeHtml(text) {
        if (!text) {
            return '';
        }

        return String(text)
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

    function updateNotificationBadge(count) {
        const badge = document.getElementById('notificationBadge');

        if (!badge) {
            return;
        }

        if (count > 0) {
            badge.innerText = count;
            badge.style.display = 'inline-flex';
        } else {
            badge.innerText = '';
            badge.style.display = 'none';
        }
    }

    function loadNavbarNotifications() {
        fetch("{{ route('notifications.unread_data') }}", {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                return response.json();
            })
            .then(function (data) {
                const unreadCount = parseInt(data.unread_count || 0);
                const list = document.getElementById('notificationDropdownList');

                updateNotificationBadge(unreadCount);

                if (
                    navbarCurrentUnreadCount !== null &&
                    unreadCount > navbarCurrentUnreadCount
                ) {
                    playNavbarNotificationSound();

                    showNavbarToast(
                        'Có ' + unreadCount + ' thông báo chưa đọc'
                    );
                }

                navbarCurrentUnreadCount = unreadCount;

                if (!list) {
                    return;
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

                data.notifications.forEach(function (item) {
                    const title = escapeHtml(limitText(item.title, 50));
                    const time = escapeHtml(item.created_at);
                    const readClass = item.is_read ? '' : 'fw-bold';
                    const readUrl = escapeHtml(item.read_url);

                    html += `
                        <a class="dropdown-item ${readClass}"
                           href="${readUrl}">
                            <div>${title}</div>
                            <small class="text-muted">${time}</small>
                        </a>
                    `;
                });

                list.innerHTML = html;
            })
            .catch(function (error) {
                console.log('Notification polling error:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadNavbarNotifications();

        setInterval(loadNavbarNotifications, 7000);
    });
</script>
@endpush