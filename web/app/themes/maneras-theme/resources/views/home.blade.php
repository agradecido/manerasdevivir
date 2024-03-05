@extends('layouts.app')

@section('content')
  <div class="flex flex-wrap -mx-2">
    <div class="w-full md:w-2/3 px-2">
      @include('partials.page-header')

      @if (! have_posts())
        <x-alert type="warning">
          Algo ha ido mal...
        </x-alert>

        {!! get_search_form(false) !!}
      @endif

      @while(have_posts()) @php(the_post())
      @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
      @endwhile

      {!! get_the_posts_navigation() !!}
    </div>

    <div class="w-full md:w-1/3 px-2">
      <x-featured-content-component />
    </div>
  </div>
@endsection
