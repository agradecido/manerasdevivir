@props (['event'])

<div class="event card mb-1">
  <h3>
    <a href="{{ $event['permalink'] }}" class="hover:text-blue-400 font-light">{{ $event['title'] }}</a>
  </h3>

  <ul class="meta-data">
    <li class="event-location mr-2">
      {{ $event['city'] }} ({{ $event['province'] }})
    </li>
    <li class="event-date">
      {{ $event['start_date'] }}
    </li>
  </ul>

  @if (!empty($event['purchase_url']))
    <div class="event-purchase mt-3">
      <a
        target="_blank"
        href="{{ $event['purchase_url'] }}"
        class="btn-medium"
      >
        Comprar Tickets
      </a>
    </div>
  @endif
</div>

