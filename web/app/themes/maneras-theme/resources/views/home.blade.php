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
        @includeFirst(['partials.content-home-post', 'partials.content'])
      @endwhile

      {!! get_the_posts_navigation() !!}

    </div>

    <div class="w-full md:w-1/4 px-2">
      @include('sections.sidebar.events', ['events' => $events])
      @include('partials.sidebar.featured-posts', ['featuredPosts' => $featuredPosts])
    </div>
  </div>
@endsection
