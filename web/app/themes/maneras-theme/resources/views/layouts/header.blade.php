<header class="shadow-md relative z-50 px-4 py-4">
  <div class="container flex items-center justify-between">
    {{-- Logo --}}
    <a href="{{ home_url('/') }}" class="custom-logo-link flex-shrink-0">
      <img src="{{ vite_asset('resources/images/logo.png') }}" alt="{{ esc_attr(get_bloginfo('name')) }}"
        class="max-w-[300px] h-auto sm:max-w-[400px] lg:max-w-[500px] xl:max-w-[600px]"
      >
    </a>

    {{-- Hamb Button (mobile) --}}
    <button id="mobile-menu-button" class="ml-auto lg:hidden p-2 text-text hover:text-primary focus:outline-none"
      aria-label="Open menu">
      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  {{-- Menu mobile --}}
  <nav id="mobile-menu"
    class="fixed inset-y-0 right-0 w-3/4 max-w-xs bg-surface shadow-lg
           transform translate-x-full transition-transform duration-300 lg:hidden">
    {{-- Close button --}}
    <button id="mobile-menu-close" class="absolute top-4 right-4 p-2 text-text hover:text-primary focus:outline-none"
      aria-label="Close menu">
      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <ul class="mt-16 flex flex-col gap-4 p-6 text-text">
      @foreach (wp_get_nav_menu_items('main') ?? [] as $item)
        <li>
          <a href="{{ esc_url($item->url) }}" class="block hover:text-primary transition-colors duration-150">
            {{ esc_html($item->title) }}
          </a>
        </li>
      @endforeach
    </ul>
  </nav>

  {{-- Menu desktop --}}
  <div class="hidden lg:block container mt-4">
    <nav class="hidden lg:flex gap-6 text-text-sub" >
      @foreach (wp_get_nav_menu_items('main') ?? [] as $item)
        <a href="{{ esc_url($item->url) }}" class="hover:underline transition-colors duration-150 no-underline">
          {{-- If the item is a parent, add an arrow --}}
          {{ esc_html($item->title) }}
        </a>
      @endforeach
    </nav>
  </div>
</header>

<div class="container breadcrumbs-quote px-4 lg:px-0 block md:flex align-items-center justify-between ">
  @php wp_reset_postdata(); @endphp
  @include('partials.breadcrumbs')
  {!! mdv_cita() !!}
</div>
