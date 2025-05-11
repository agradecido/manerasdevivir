{{-- resources/views/pages/reports/archive-report.blade.php --}}
@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Reportajes</h1>
    
    @php
      $reports = new WP_Query([
        'post_type' => 'report',
        'posts_per_page' => 10,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
      ]);
    @endphp
    
    @if ($reports && $reports->have_posts())
      @while ($reports->have_posts())
        @php
          $reports->the_post();
          $link = get_permalink();
          $autor = get_post_meta(get_the_ID(), 'autor', true);
        @endphp

        <article class="mb-12 pb-8 border-b border-border last:border-0">
          {{-- Titular enlazado al reportaje --}}
          <h3 class="text-2xl font-bold mb-2">
            <a href="{{ esc_url($link) }}" class="text-links hover:underline no-underline">
              {{ get_the_title() }}
            </a>
          </h3>

          {{-- Autor y fecha --}}
          <ul class="flex flex-wrap gap-4 text-text-sub text-sm mb-4">
            @if ($autor)
            <li class="flex items-center gap-1">
              <i data-feather="user"></i> 
              <span>{{ esc_html($autor) }}</span>
            </li>
            @endif
            <li class="flex items-center gap-1">
              <i data-feather="clock"></i> 
              <span>{{ get_the_date('d.m.Y') }}</span>
            </li>
          </ul>

          {{-- Imagen destacada --}}
          @if (has_post_thumbnail())
          <div class="mb-4">
            <a href="{{ esc_url($link) }}" class="block">
              {{ the_post_thumbnail('medium', ['class' => 'rounded']) }}
            </a>
          </div>
          @endif

          {{-- Extracto --}}
          <div class="mb-4">
            {{ the_excerpt() }}
          </div>

          {{-- Leer más --}}
          <p>
            <a href="{{ esc_url($link) }}" class="inline-flex items-center gap-1 text-links hover:underline font-medium">
              Leer reportaje completo
              <i data-feather="arrow-right" class="w-4 h-4"></i>
            </a>
          </p>
        </article>
      @endwhile

      {{-- Pagination --}}
      @if ($reports->max_num_pages > 1)
        <div class="pagination flex justify-center my-8">
          <?php
            $big = 999999999;
            echo paginate_links([
              'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
              'format' => '?paged=%#%',
              'current' => max(1, get_query_var('paged')),
              'total' => $reports->max_num_pages,
              'prev_text' => '&laquo; Anterior',
              'next_text' => 'Siguiente &raquo;',
            ]);
          ?>
        </div>
      @endif

      @php wp_reset_postdata(); @endphp
    @else
      <p class="text-text-sub">No hay reportajes disponibles.</p>
    @endif
  </div>
@endsection