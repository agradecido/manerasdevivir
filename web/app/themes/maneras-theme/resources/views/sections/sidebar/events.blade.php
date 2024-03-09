{{-- Events list for show in a column --}}

@if (!empty($events))
  <h2 class="mb-2">Próximos conciertos</h2>
  <div class="flex flex-col flex-wrap">
    @foreach ($events as $event)
      <x-event-card :event="$event" />
    @endforeach
  </div>
@endif
