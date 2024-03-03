<header class="banner">
  {!! $siteLogo !!}
  @if (has_nav_menu('primary_navigation'))
    <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
    </nav>
  @endif
  <div class="w-full shadow-[rgba(0,_0,_0,_0.25)_0px_25px_50px_-12px]">
    <div class="ml-4 h-14 max-w-6xl">
      <ul class="relative mx-12 flex h-full flex-row items-center justify-between text-[16px] font-medium sm:text-[18px]">
        <li class="hover:cursor-pointer">Logo</li>
        <li class="hover:cursor-pointer">Home</li>
        <li class="hover:cursor-pointer">Services</li>
        <li class="hover:cursor-pointer">Contact</li>
      </ul>
    </div>
  </div>
</header>
