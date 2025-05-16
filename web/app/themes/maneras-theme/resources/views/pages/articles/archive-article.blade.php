{{-- resources/views/pages/archive-article.blade.php --}}
@extends('layouts.app')

@section('content')
  <div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Noticias</h1>

    @if ($articles && $articles->have_posts())
      @while ($articles->have_posts())
        @php
          $articles->the_post();
          // Separamos contenido en main/extended
          $parts = get_extended(get_the_content());
          $link = get_permalink();
        @endphp

        <article class="mb-12 pb-8 border-b border-border last:border-0">
          {{-- Titular enlazado a canonical --}}
          <h3 class="text-2xl font-bold mb-2">
            <a href="{{ esc_url($link) }}" class="text-links hover:underline no-underline">
              {{ get_the_title() }}
            </a>
          </h3>

          {{-- Firma, fecha y tags en la misma línea --}}
          <div class="flex flex-wrap items-center gap-4 mb-4">
            <ul class="flex flex-wrap gap-4 text-text-sub text-sm">
              <li class="flex items-center gap-1">
                <i data-feather="user"></i>
                <span>{{ esc_html(get_post_meta(get_the_ID(), 'firma_sender', true)) }}</span>
              </li>
              <li class="flex items-center gap-1">
                <i data-feather="clock"></i>
                <span>{{ get_the_date('d.m.y') }}</span>
              </li>
            </ul>

            {{-- Tags --}}
            @php $tags = get_the_tags( the_post() ) @endphp
            @include('partials.tags', ['tags' => $tags])
          </div>

          {{-- Solo la parte antes del more --}}
          <div class="mb-4">
            {!! apply_filters('the_content', $parts['main']) !!}
          </div>

          {{-- Leer más → canonical --}}
          @if (trim($parts['extended']))
            <p>
              <a href="{{ esc_url($link) }}"
                class="inline-flex items-center gap-1 text-links hover:underline font-medium">
                Leer más
                <i data-feather="arrow-right" class="w-4 h-4"></i>
              </a>
            </p>
          @endif

        </article>
      @endwhile

      {{-- Pagination --}}
      @if ($articles->max_num_pages > 1)
        <div class="pagination flex justify-center my-8">
          <?php
          $big = 999999999;
          echo paginate_links([
              'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
              'format' => '?paged=%#%',
              'current' => max(1, get_query_var('paged')),
              'total' => $articles->max_num_pages,
              'prev_text' => '&laquo; Anterior',
              'next_text' => 'Siguiente &raquo;',
          ]);
          ?>
        </div>
      @endif

      @php wp_reset_postdata(); @endphp
    @else
      <p class="text-text-sub">No hay artículos disponibles.</p>
    @endif
  </div>
@endsection
