{{-- Events list for show in a column --}}
@php use App\Constants; @endphp

@if (!empty($events))
  <div class="home-events">
    <h2 class="mb-2 text-2xl">@php _e('Próximos conciertos', Constants::TEXTDOMAIN) @endphp</h2>
    <div class="flex flex-col flex-wrap">
      <ul>
        @foreach ($events as $event)
          <x-event-card :event="$event"/>
        @endforeach
      </ul>
    </div>
  </div>
@endif
