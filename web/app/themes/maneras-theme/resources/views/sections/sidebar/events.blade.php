{{-- Events list for show in a column --}}
@php use App\Constants; @endphp

@if (!empty($events))
  <div class="home-events">

    @if(!empty($title))
      <h2 class="mb-2 text-2xl">{{ $title }}</h2>
    @else
      <h2 class="mb-2 text-2xl">@php _e('Próximos conciertos', Constants::TEXTDOMAIN) @endphp</h2>
    @endif

    <div class="flex flex-col flex-wrap">
      <ul>
        @foreach ($events as $event)
          <x-event-card :event="$event"/>
        @endforeach
      </ul>
    </div>
  </div>
@endif
