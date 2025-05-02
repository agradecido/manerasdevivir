@php($items = mdv_breadcrumbs())
<nav aria-label="Breadcrumb" class="breadcrumbs">
  @foreach ($items as $index => $item)
    @if ($item['url'])
      <a href="{{ $item['url'] }}" class="no-underline">
        @if ( key_exists( 'icon', $item ) && $item['icon'] )
          <i data-feather="{{ $item['icon'] }}" class="h-4 w-4"></i>
        @else
          {{ $item['label'] }}
        @endif
      </a>
    @else
      <span>
        @if ( key_exists( 'icon', $item ) && $item['icon'] )
          <i data-feather="{{ $item['icon'] }}"></i>
        @else
          {{ $item['label'] }}
        @endif
      </span>
         
    @endif
    @if ($index < count($items) - 1 )
      <span>/</span>
    @endif
  @endforeach
</nav>