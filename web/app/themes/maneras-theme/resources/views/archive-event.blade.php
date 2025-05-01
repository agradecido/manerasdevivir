{{-- archive-event.blade.php --}}
@extends('layouts.app')

@php
  use ManerasTheme\Controllers\EventController;
  $events = EventController::getEvents();
  $upcoming = $events['upcoming'];
  $past = $events['past'];

  $formatter = new \IntlDateFormatter(
	'es_ES',
	\IntlDateFormatter::LONG,
	\IntlDateFormatter::NONE,
	null,
	null,
	'd \'de\' MMMM \'de\' yyyy'
);
@endphp

@section('content')
  <div class="container mx-auto px-4 py-12">
    {{-- Section title --}}
    <h1 class="text-3xl font-bold mb-6">Conciertos</h1>

    {{-- Upcoming Events --}}
    @if ($upcoming->have_posts())
      <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @while ($upcoming->have_posts())
          @php
            $upcoming->the_post();
            $fields = function_exists('get_fields') ? get_fields(get_the_ID()) : get_post_meta(get_the_ID());
            $start_raw = get_post_meta(get_the_ID(), 'start_date', true);            
            $start = $formatter->format(strtotime($start_raw));
            $city = $fields['event_city'] ?? '';
          @endphp
          <a href="{{ get_permalink() }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition">
            @if (has_post_thumbnail())
              <div class="h-48 bg-cover bg-center rounded-t-lg"
                style="background-image:url('{{ get_the_post_thumbnail_url(get_the_ID(), 'large') }}')"></div>
            @endif
            <div class="p-4">
              <h2 class="text-xl font-semibold mb-2">{{ get_the_title() }}</h2>
              <p class="text-gray-600 text-sm mb-1"><strong>Fecha:</strong> {{ $start }}</p>
              @if ($city)
                <p class="text-gray-600 text-sm"><strong>Ciudad:</strong> {{ $city }}</p>
              @endif
            </div>
          </a>
        @endwhile
        @php wp_reset_postdata(); @endphp
      </section>
    @else
      <p class="text-gray-500 mb-12">No hay conciertos próximos.</p>
    @endif

    {{-- Past Events --}}
    @if ($past->have_posts())
      <hr class="my-8">
      <h2 class="text-2xl font-bold mb-4 text-gray-700">Conciertos pasados</h2>
      <ul class="space-y-3 text-gray-500">
        @while ($past->have_posts())
          @php
            $past->the_post();
            $fields = function_exists('get_fields') ? get_fields(get_the_ID()) : get_post_meta(get_the_ID());
            $start_raw = get_post_meta(get_the_ID(), 'start_date', true);
            $start = $formatter->format(strtotime($start_raw));
          @endphp
          <li>
            <a href="{{ get_permalink() }}" class="hover:text-indigo-600">
              {{ get_the_title() }} @if($start) ({{ $start }}) @endif
            </a>
          </li>
        @endwhile
        @php wp_reset_postdata(); @endphp
      </ul>
    @endif
  </div>
@endsection
@section('footer')
@endsection