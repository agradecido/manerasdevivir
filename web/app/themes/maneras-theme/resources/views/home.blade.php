@extends('layouts.app')

@section('content')
  <div class="flex flex-wrap">

    <div class="w-full lg:w-3/4 xl:w-1/2 lg:pr-8">

      <h2 class="mb-2 text-2xl">Últimas noticias</h2>

      <div class="home-posts">
        @if (!have_posts())
          <x-alert type="warning">
            Algo ha ido mal...
          </x-alert>
          {!! get_search_form(false) !!}
        @endif
        <ul>
          @while(have_posts())
            @php(the_post())
            <li>
              <x-post-card :post="get_post()"/>
            </li>
          @endwhile
        </ul>
      </div>

      {!! get_the_posts_navigation() !!}

    </div>

    {{-- Sidebar --}}
    <div class="w-full lg:w-1/4 xl:w-1/2 xl:pr-4">
      <div class="flex flex-col xl:flex-row xl:-mx-4">
        <div class="xl:w-1/2 xl:px-4 flex flex-col">
          @include('sections.sidebar.featured-posts', ['featuredPosts' => $featuredPosts])
        </div>
        <div class="xl:w-1/2 xl:pl-4 flex flex-col">
          @include('sections.sidebar.events', ['events' => $events])
        </div>
      </div>
    </div>

  </div>
@endsection
