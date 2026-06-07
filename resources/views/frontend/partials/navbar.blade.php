<nav class="main-menu">
    <ul>
        @php
            $mainMenus = \App\Models\Menu::with(['children', 'category'])
                ->whereNull('parent_id')
                ->where('status', true)
                ->orderBy('sort_order')
                ->get();
        @endphp

        @foreach($mainMenus as $menu)

            <li>
                <a href="{{ $menu->link }}"
                   target="{{ $menu->target }}">
                    {{ $menu->title }}
                </a>

                @if($menu->children->count() > 0)
                    <ul>
                        @foreach($menu->children as $child)
                            <li>
                                <a href="{{ $child->link }}"
                                   target="{{ $child->target }}">
                                    {{ $child->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>

        @endforeach
    </ul>
</nav>