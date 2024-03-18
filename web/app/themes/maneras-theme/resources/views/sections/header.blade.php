<header class="main-header mb-5">
  <div class="container flex flex-col">
    <div class="logo mb-3">
      {!! $siteLogo !!}
    </div>


    <!-- Botón de hamburguesa solo visible en móviles -->
    <div class="md:hidden flex items-center">
      <button id="hamburgerBtn" class="text-white focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
      </button>
    </div>

    <!-- Navegación principal -->
    @if (has_nav_menu('primary_navigation'))
    <nav class="nav-primary flex flex-row" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
    </nav>
    @endif
  </div>
</header>
