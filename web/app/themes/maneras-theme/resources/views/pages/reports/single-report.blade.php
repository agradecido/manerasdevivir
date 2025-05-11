{{-- resources/views/pages/reports/single-report.blade.php --}}
@extends('layouts.app')

@section('content')
  <article class="mx-auto px-4">
    <div class="container">
      <header class="mb-6">
        <h1 class="text-3xl md:text-4xl font-sans font-bold leading-tight">
          {{ $report->post_title }}
        </h1>

        <div class="flex items-center gap-2 text-text-sub mt-2">
          @php
            $autor = get_post_meta($report->ID, 'autor', true);
            $fecha = get_post_meta($report->ID, 'fecha_publicacion', true) ?: get_the_date('', $report->ID);
            $pubDate = get_the_date('', $report->ID);
          @endphp

          @if($autor)
            <i data-feather="user"></i>
            <span>{{ $autor }}</span>
          @endif

          <i data-feather="clock"></i>
          <time datetime="{{ get_post_time('c', true, $report->ID) }}">
            {{ $pubDate }}
          </time>
        </div>
      </header>

      @if(has_post_thumbnail($report->ID))
        <figure class="mb-6">
          {!! get_the_post_thumbnail($report->ID, 'large', ['class' => 'w-full h-auto rounded']) !!}
        </figure>
      @endif

      <div class="prose prose-invert max-w-none">
        {!! $report->post_content !!}
      </div>

      <footer class="mt-8 pt-4 border-t border-border text-sm">
        @php
            $tags = get_the_tags($report->ID);
        @endphp
        @if ($tags)
          <ul class="flex flex-wrap gap-2 p-0 m-0">
            @foreach ($tags as $tag)
              <li>
                <a href="{{ get_tag_link($tag->term_id) }}" 
                  class="inline-block bg-surface hover:bg-border px-3 py-1 rounded">
                  #{{ $tag->name }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </footer>
    </div>
  </article>
@endsection