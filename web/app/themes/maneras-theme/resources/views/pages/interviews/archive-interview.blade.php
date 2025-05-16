{{-- resources/views/pages/interviews/archive-interview.blade.php --}}
@extends('layouts.app')

@section('content')
  <div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Entrevistas</h1>
    
    @php
      $interviews = new WP_Query([
        'post_type' => 'interview',
        'posts_per_page' => 10,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
      ]);
    @endphp
    
    @if ($interviews && $interviews->have_posts())
      @while ($interviews->have_posts())
        @php
          $interviews->the_post();
          $link = get_permalink();
          $entrevistado = get_post_meta(get_the_ID(), 'entrevistado', true);
          $entrevistador = get_post_meta(get_the_ID(), 'entrevistador', true);
        @endphp

        <article class="mb-12 pb-8 border-b border-border last:border-0">
          {{-- Titular enlazado a la entrevista --}}
          <h3 class="text-2xl font-bold mb-2">
            <a href="{{ esc_url($link) }}" class="text-links hover:underline no-underline">
              {{ get_the_title() }}
            </a>
          </h3>

          {{-- Entrevistador, entrevistado y fecha --}}
          <ul class="flex flex-wrap gap-4 text-text-sub text-sm mb-4">
            @if ($entrevistador)
            <li class="flex items-center gap-1">
              <i data-feather="user"></i> 
              <span>{{ esc_html($entrevistador) }}</span>
            </li>
            @endif
            <li class="flex items-center gap-1">
              <i data-feather="clock"></i> 
              <span>{{ get_the_date('d.m.Y') }}</span>
            </li>
            @if ($entrevistado)
            <li class="flex items-center gap-1">
              <i data-feather="mic"></i> 
              <span>{{ esc_html($entrevistado) }}</span>
            </li>
            @endif
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
              Leer entrevista completa
              <i data-feather="arrow-right" class="w-4 h-4"></i>
            </a>
          </p>
        </article>
      @endwhile

      {{-- Pagination --}}
      @if ($interviews->max_num_pages > 1)
        <div class="pagination flex justify-center my-8">
          <?php
            $big = 999999999;
            echo paginate_links([
              'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
              'format' => '?paged=%#%',
              'current' => max(1, get_query_var('paged')),
              'total' => $interviews->max_num_pages,
              'prev_text' => '&laquo; Anterior',
              'next_text' => 'Siguiente &raquo;',
            ]);
          ?>
        </div>
      @endif

      @php wp_reset_postdata(); @endphp
    @else
      <p class="text-text-sub">No hay entrevistas disponibles.</p>
    @endif
  </div>
@endsection