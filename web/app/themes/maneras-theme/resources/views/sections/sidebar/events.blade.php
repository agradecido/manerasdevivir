{{-- Events list for show in a column --}}

@if (!empty($events))
  <div class="home-events">
    <h2 class="mb-2">Próximos conciertos</h2>
    <div class="flex flex-col flex-wrap">
      <ul>
        @foreach ($events as $event)
          <x-event-card :event="$event"/>
        @endforeach
      </ul>
    </div>
  </div>
@endif
