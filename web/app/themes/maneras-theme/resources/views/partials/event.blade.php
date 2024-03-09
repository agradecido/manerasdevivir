@php use App\Utils\Utils; @endphp

<li>
  <div class="event">

    <ul class="event__meta">
      <li>{{ $startDate }}</li>
      <li>{{ $city }}</li>
      <li>{{ $price }}</li>
    </ul>

    <div class="event__title">
      <h3 class="event__title">
        <a href="{{ $permalink }}">{{ $title }}</a>
      </h3>
    </div>

    <ul class="event__meta">
      @if (!empty($ticketsUrl))
        <li><a href="{{ $ticketsUrl }}">tickets</a></li>
      @endif
    </ul>

  </div>
</li>
