@extends('layouts.app')

@section('content')
  <div class="flex flex-wrap -mx-2">
    <div class="w-full md:w-3/4 px-2">

      @include('partials.page-header')

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

      {!! get_the_posts_navigation() !!}

    </div>

    <div class="w-full md:w-1/4 px-2">
      @include('partials.sidebar.featured-posts', ['featuredPosts' => $featuredPosts])
      @include('sections.sidebar.events', ['events' => $events])
    </div>
  </div>
@endsection
