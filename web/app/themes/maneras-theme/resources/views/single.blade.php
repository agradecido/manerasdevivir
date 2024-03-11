@extends('layouts.app')

<div class="single-container flex flex-wrap">
  <div class="article w-full md:w-3/4 px-2">
    @section('content')
      @while(have_posts())
        @php(the_post())
        @includeFirst(['partials.content-single-' . get_post_type(), 'partials.content-single'])
      @endwhile
    @endsection
  </div>
  <div class="sidebar w-full md:w-1/4 px-2 flex flex-col gap-y-8">
    @include('sections.sidebar.featured-posts', ['featuredPosts' => $featuredPosts])
    @include('sections.sidebar.events', ['events' => $events])
  </div>
</div>
