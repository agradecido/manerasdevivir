@extends('layouts.app')

@section('content')
  <div class="archive-container flex flex-wrap">

    <div class="w-full lg:w-3/4 xl:w-2/3 lg:pr-8">

      @php
        $year      = get_query_var('year');
        $monthnum  = get_query_var('monthnum');
        $monthname = date("F", mktime(0, 0, 0, $monthnum, 10));
      @endphp

      <h1 class="text-2xl mb-2">Histórico de noticias de {{ $monthname }} de {{ $year }}</h1>

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
    <div class="w-full lg:w-1/4 xl:w-1/3 xl:pr-4">
          @include('sections.sidebar.featured-posts', ['featuredPosts' => $featuredPosts, 'title' => 'Actualidad'])
          @include('sections.sidebar.events', ['events' => $events, 'title' => 'Agenda'])
    </div>

  </div>
@endsection
