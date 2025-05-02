{{-- single-event.blade.php --}}
@extends('layouts.app')

@section('content')
  @php
    // Build JSON-LD schema for the event.
    if (strtotime($fields['end_date']) < strtotime($fields['start_date'])) {
        $fields['end_date'] = null;
    }
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => get_the_title($post->ID),
        'startDate' => isset($fields['start_date']) ? date('c', strtotime($fields['start_date'])) : null,
        'endDate' => isset($fields['end_date']) ? date('c', strtotime($fields['end_date'])) : null,
        'eventSchedule' => [
            '@type' => 'Schedule',
            'startDate' => isset($fields['start_date']) ? date('c', strtotime($fields['start_date'])) : null,
            'endDate' => isset($fields['end_date']) ? date('c', strtotime($fields['end_date'])) : null,
        ],
        'eventStatus' => ($schema['eventStatus'] = 'https://schema.org/EventScheduled'),
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'url' => get_permalink($post->ID),
        'image' => isset($fields['poster'])
            ? $fields['poster']
            : (has_post_thumbnail($post->ID)
                ? get_the_post_thumbnail_url($post->ID, 'large')
                : null),
        'description' => !empty($fields['comments']) ? $fields['comments'] : strip_tags(get_the_excerpt($post->ID)),
    ];

    // Location.
    if (!empty($fields['venue'])) {
        $location = [
            '@type' => 'Place',
            'name' => $fields['venue'],
        ];
        // Address.
        $address = [];
        if (!empty($fields['venue_address'])) {
            $address['streetAddress'] = $fields['venue_address'];
        }
        if (!empty($fields['event_city'])) {
            $address['addressLocality'] = $fields['event_city'];
        }
        if (!empty($fields['administrative_division'])) {
            $address['addressRegion'] = $fields['administrative_division'];
        }
        if (!empty($fields['event_country'])) {
            $address['addressCountry'] = $fields['event_country'];
        }
        if ($address) {
            $location['address'] = array_merge(['@type' => 'PostalAddress'], $address);
        }
        // Geo coordinates.
        if (!empty($fields['venue_lat']) && !empty($fields['venue_lon'])) {
            $location['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $fields['venue_lat'],
                'longitude' => $fields['venue_lon'],
            ];
        }
        $schema['location'] = $location;
    }

    // Performers.
    if (!empty($fields['artists'])) {
        $artists = explode(',', $fields['artists']);
        $performerItems = [];
        foreach ($artists as $artist) {
            $performerItems[] = [
                '@type' => 'MusicGroup',
                'name' => trim($artist),
            ];
        }
        $schema['performer'] = $performerItems;
    }

    // Offers
    if (!empty($fields['purchase_url']) || !empty($fields['price'])) {
        $offer = ['@type' => 'Offer'];
        if (!empty($fields['precio_anticipada'])) {
            $offer['price'] = $fields['precio_anticipada'];
            $offer['priceCurrency'] = 'EUR';
        } elseif (!empty($fields['price'])) {
            $offer['price'] = $fields['price'];
            $offer['priceCurrency'] = 'EUR';
        }
        if (!empty($fields['purchase_url'])) {
            $offer['url'] = $fields['purchase_url'];
        }
        $schema['offers'] = $offer;
    }
  @endphp
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>

  {{-- Section title --}}
  <div class="section-title">
    <p class="text-3xl font-bold mb-4"><a href="/conciertos">Agenda de conciertos</a></p>
    <p>Aquí tienes toda la información de la que disponemos sobre el concierto de
      <strong>{{ get_the_title($post->ID) }}</strong> en {{ $fields['administrative_division'] ?? '' }}:</p>
  </div>
  <div class="max-w-4xl mx-auto px-4 py-2">
    <article class="bg-white rounded-2xl shadow-xl overflow-hidden">
      {{-- Featured image --}}
      @if (has_post_thumbnail($post->ID))
        <div class="h-64 bg-cover bg-center"
          style="background-image:url('{{ get_the_post_thumbnail_url($post->ID, 'large') }}')"></div>
      @endif

      <div class="p-8 space-y-6">
        {{-- Event title --}}
        <h1 class="text-4xl font-extrabold leading-tight">{{ get_the_title($post->ID) }}</h1>

        @php
          // Mapping of allowed fields to Spanish labels
          $field_labels = [
              'start_date' => 'Fecha',
              'end_date' => 'Fecha de fin',
              //'festival_name' => 'Festival',
              'artists' => 'Artistas',
              'event_city' => 'Ciudad',
              'event_country' => 'País',
              'administrative_division' => 'Provincia',
              'venue' => 'Lugar',
              'venue_lat' => 'Latitud',
              'venue_lon' => 'Longitud',
              'venue_address' => 'Dirección',
              'price' => 'Precio',
              'precio_anticipada' => 'Precio anticipada',
              'precio_taquilla' => 'Precio taquilla',
              'purchase_url' => 'Enlace de compra',
              'poster' => 'Cartel',
              //'comments'                 => 'Comentarios',
          ];
        @endphp

        {{-- Loop through only allowed fields, display if exists --}}
        @foreach ($field_labels as $key => $label)
          @if (!empty($fields[$key]))
            @php $value = $fields[$key]; @endphp

            @if ($key === 'purchase_url')
              {{-- Purchase link --}}
              <div class="mt-4">
                <a href="{{ esc_url($value) }}"
                  class="inline-block px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                  {{ $label }}
                </a>
              </div>
            @elseif ($key === 'poster')
              {{-- Poster image --}}
              <div class="mt-4">
                <h4 class="text-lg font-medium">{{ $label }}</h4>
                <img src="{{ get_the_post_thumbnail_url($post->ID, 'large') }}" alt="{{ get_the_title($post->ID) }} poster"
                  class="w-full h-auto rounded-lg" />
              </div>
            @elseif (in_array($key, ['start_date', 'end_date']))
              {{-- Date formatting --}}
              <p class="text-gray-600"><strong>{{ $label }}:</strong> {{ date_i18n('j F, Y', strtotime($value)) }}
              </p>
            @else
              {{-- Default display --}}
              <p class="text-gray-600"><strong>{{ $label }}:</strong> {{ $value }}</p>
            @endif
          @endif
        @endforeach

        {{-- Main content --}}
        @if (trim(get_the_content($post->ID)))
          <div class="prose prose-lg">
            <h2>Más información sobre el concierto de {{ get_the_title($post->ID) }} en
              {{ $fields['administrative_division'] ?? '' }}</h2>
            {{-- Display the content of the post --}}
            {{-- Note: The content is filtered through 'the_content' filter to apply shortcodes and formatting --}}
            {{-- Use get_the_content() to retrieve the content without echoing it directly --}}
            {{-- This allows for better control over how the content is displayed --}}
            {{-- The post ID is passed to ensure the correct content is retrieved --}}
            {!! apply_filters('the_content', get_the_content($post->ID)) !!}
          </div>
        @endif
      </div>
    </article>
    <p class="text-gray-600 mt-8">
      <a href="/conciertos" class="text-indigo-600 hover:text-indigo-800">Volver a la agenda de conciertos</a>
    </p>
  </div>
@endsection
@section('footer')
@endsection