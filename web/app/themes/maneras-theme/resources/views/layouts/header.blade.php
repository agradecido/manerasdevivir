<header class="shadow-md relative z-50">
  <div class="container flex items-center justify-between py-4">
    {{-- Logo --}}
    <a href="{{ home_url('/') }}" class="flex items-center custom-logo-link">
      <img
        src="{{ vite_asset('resources/images/logo.png') }}"
        alt="{{ esc_attr(get_bloginfo('name')) }}"
      >
    </a>

    {{-- Menú inline para escritorio --}}
    <nav class="hidden lg:flex space-x-6 text-gray-700">
      @foreach (wp_get_nav_menu_items('main') ?? [] as $item)
        <a
          href="{{ esc_url($item->url) }}"
          class="hover:text-gray-900 transition-colors duration-200"
        >
          {{ esc_html($item->title) }}
        </a>
      @endforeach
    </nav>

    {{-- Botón hamburguesa (solo móvil) --}}
    <button
      id="mobile-menu-button"
      class="lg:hidden p-2 text-gray-700 hover:text-gray-900 focus:outline-none"
      aria-label="Open menu"
    >
      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  {{-- Overlay menú móvil (sólo <lg) --}}
  <nav
    id="mobile-menu"
    class="fixed inset-y-0 right-0 w-3/4 max-w-xs bg-white shadow-lg
           transform translate-x-full transition-transform duration-300 lg:hidden"
  >
    {{-- Botón cerrar --}}
    <button
      id="mobile-menu-close"
      class="absolute top-4 right-4 p-2 text-gray-700 hover:text-gray-900 focus:outline-none"
      aria-label="Close menu"
    >
      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <ul class="mt-16 flex flex-col space-y-4 p-6 text-gray-700">
      @foreach (wp_get_nav_menu_items('main') ?? [] as $item)
        <li>
          <a
            href="{{ esc_url($item->url) }}"
            class="block hover:text-gray-900 transition-colors duration-200"
          >
            {{ esc_html($item->title) }}
          </a>
        </li>
      @endforeach
    </ul>
  </nav>
</header>
