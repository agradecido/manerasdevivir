{{-- resources/views/pages/articles/single-article.blade.php --}}
@extends('layouts.app')

@section('content')
  <article class="mx-auto">
    <div class="container">
      <header class="mb-6">
        <h1 class="text-3xl md:text-4xl font-sans font-bold leading-tight">
          {{ $title }}
        </h1>

        <div class="flex items-center gap-2 text-text-sub mt-2">
          <i data-feather="user"></i>
          <span>{{ $author }}</span>

          <i data-feather="clock"></i>
          <time datetime="{{ $isoDate ?? get_post_time('c', true) }}">
            {{ $pubDate }}
          </time>
        </div>
      </header>

      @if (!empty($thumb) || has_post_thumbnail())
        <figure class="mb-6">
          {!! $thumb !!}
        </figure>
      @endif

      <div class="prose prose-invert max-w-none">
        {!! $content !!}
      </div>

      <footer class="mt-8 pt-4 border-t border-border text-sm">
        @include('partials.tags', ['tags' => $tags])
      </footer>
    </div>
  </article>
@endsection
