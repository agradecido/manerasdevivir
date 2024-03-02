@extends('layouts.app')

@section('content')
  <header>
    <h1>Noticias</h1>
  </header>

  @if (!have_posts())
    <div>{{ __('No news found', 'sage') }}</div>
  @else
    @while(have_posts())
      @php(the_post())
      @include('partials.content-'.get_post_type())
    @endwhile

    {!! get_the_posts_navigation() !!}
  @endif
@endsection
