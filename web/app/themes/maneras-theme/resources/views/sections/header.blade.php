<header class="main-header items-center pr-10">
  {!! $siteLogo !!}
  @if (has_nav_menu('primary_navigation'))
    <nav class="nav-primary flex flex-row " aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
    </nav>
  @endif
</header>

