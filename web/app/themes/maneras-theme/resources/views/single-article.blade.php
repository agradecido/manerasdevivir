{{-- resources/views/single-article.blade.php --}}
@extends('layouts.app')

@section('content')
  <article @php(post_class('single-article'))>
    <header class="mb-6">
      <h1 class="text-3xl font-bold leading-tight">
        {{ get_the_title() }}
      </h1>
      <div class="text-gray-600 text-sm mt-2">
        {{ get_the_date('d.m.Y') }}
      </div>
    </header>

    @if (has_post_thumbnail())
      <div class="mb-6">
        {!! get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-auto']) !!}
      </div>
    @endif

    <div class="prose max-w-none mb-8">
      {!! apply_filters('the_content', get_the_content()) !!}
    </div>

    @if ($tags = get_the_tags())
      <footer class="mt-8 pt-4 border-t text-sm">
        <div class="post-tags">
          <ul class="tag-list flex flex-wrap list-none p-0 m-0">
            @foreach ($tags as $tag)
              <li>
                <a href="{{ get_tag_link($tag->term_id) }}"
                  class="inline-block bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded">
                  #{{ $tag->name }}
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      </footer>
    @endif
  </article>
@endsection
