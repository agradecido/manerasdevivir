@extends('layouts.app')

@section('content')
  <div class="flex flex-wrap -mx-2">
    <div class="w-full md:w-3/4 px-2">

      <h2 class="mb-2">Últimas noticias</h2>

      <div class="home-posts p-2 pl-0">
        @if ( !have_posts())
          <x-alert type="warning">
            Algo ha ido mal...
          </x-alert>
          {!! get_search_form(false) !!}
        @endif

        @while(have_posts())
          @php the_post() @endphp
          @includeFirst(['partials.content', 'partials.home.content-post'])
        @endwhile

      </div>

      {!! get_the_posts_navigation() !!}

    </div>

    <div class="w-full md:w-1/4 px-2 flex flex-col gap-y-8">
      @include('sections.sidebar.featured-posts', ['featuredPosts' => $featuredPosts])
      @include('sections.sidebar.events', ['events' => $events])
    </div>
  </div>
@endsection
