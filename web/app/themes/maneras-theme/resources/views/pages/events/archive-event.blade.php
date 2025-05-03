{{-- archive-event.blade.php --}}
@extends('layouts.app')

@php
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
  <div class="container mx-auto py-2">
    {{-- Section title --}}
    <h1 class="text-3xl font-bold mb-6">Agenda de Conciertos</h1>

    {{-- Upcoming Events --}}
    @if ($upcoming && $upcoming->have_posts())
        <h2 class="text-2xl font-bold mb-4">Próximos conciertos y festivales</h2>
      <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @while ($upcoming->have_posts())
          @php
            $upcoming->the_post();
            $post_id = get_the_ID();
            
            // Obtener metadatos específicos (ya están precargados para rendimiento)
            $startRaw = get_post_meta($post_id, 'start_date', true);
            $endRaw = get_post_meta($post_id, 'end_date', true);
            $artists = get_post_meta($post_id, 'artists', true);
            $event_city = get_post_meta($post_id, 'event_city', true);
            $administrative_division = get_post_meta($post_id, 'administrative_division', true);
            $event_country = get_post_meta($post_id, 'event_country', true);
            $venue = get_post_meta($post_id, 'venue', true);
            $venue_address = get_post_meta($post_id, 'venue_address', true);
            $venue_lat = get_post_meta($post_id, 'venue_lat', true);
            $venue_lon = get_post_meta($post_id, 'venue_lon', true);
            $price = get_post_meta($post_id, 'price', true);
            $precio_anticipada = get_post_meta($post_id, 'precio_anticipada', true);
            $precio_taquilla = get_post_meta($post_id, 'precio_taquilla', true);
            $purchase_url = get_post_meta($post_id, 'purchase_url', true);
            $festival_name = get_post_meta($post_id, 'festival_name', true);
            $poster = get_post_meta($post_id, 'poster', true);

            if (!empty($poster) && !preg_match('/^https?:\/\//', $poster)) {
              $poster = 'https://contenidos.manerasdevivir.com/' . $poster;
            }

            $comments = get_post_meta($post_id, 'comments', true);
            
            // Formatear las fechas
            $startDT = !empty($startRaw) ? new \DateTimeImmutable($startRaw, new \DateTimeZone('Europe/Madrid')) : null;
            $start = $startDT ? $formatter->format($startDT) : 'Sin fecha';
            $endDT = !empty($endRaw) ? new \DateTimeImmutable($endRaw, new \DateTimeZone('Europe/Madrid')) : null;
            $end = $endDT ? $formatter->format($endDT) : '';
            
            // Limpiar el título del evento
            $clean_title = mdv_clean_event_title(get_the_title());
            
            // Para depuración: obtener TODOS los metadatos del post
            $all_meta = get_post_meta($post_id);
          @endphp
          <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
            <a href="{{ get_permalink() }}" class="block no-underline">
              @if (has_post_thumbnail())
                <div class="h-48 bg-cover bg-center rounded-t-lg"
                  style="background-image:url('{{ get_the_post_thumbnail_url($post_id, 'large') }}')"></div>
              @elseif ($poster)
                <div class="h-48 bg-cover bg-center rounded-t-lg"
                  style="background-image:url('{{ esc_url($poster) }}')"></div>
              @endif
              
              <div class="p-4">
                <h2 class="text-xl font-semibold mb-2 mt-0 text-primary">{{ $clean_title }}</h2>
                
                {{-- Fecha de inicio siempre visible --}}
                <p class="flex items-center text-gray-600 text-sm mb-2">
                  <i data-feather="calendar" class="w-4 h-4 mr-1"></i>
                  <strong>Fecha:</strong>&nbsp; {{ $start }}
                </p>
                
                {{-- Mostrar fecha fin si existe --}}
                @if ($end && $start !== $end)
                  <p class="flex items-center text-gray-600 text-sm mb-2">
                    <i data-feather="calendar" class="w-4 h-4 mr-1"></i>
                    <strong>Hasta:</strong>&nbsp; {{ $end }}
                  </p>
                @endif

                
                {{-- Mostrar ubicación (ciudad, provincia, país) si existe --}}
                @if ($event_city || $administrative_division || $venue)
                  <p class="flex items-center text-gray-600 text-sm mb-2">
                    <i data-feather="map-pin" class="w-4 h-4 mr-1"></i>
                    <strong>Lugar:</strong>&nbsp;
                    @if ($venue) {{ $venue }}@if($event_city || $administrative_division), @endif @endif
                    @if ($event_city) {{ $event_city }}@if($administrative_division), @endif @endif
                    @if ($administrative_division) {{ $administrative_division }}@endif
                    @if ($event_country && $event_country !== 'España') ({{ $event_country }})@endif
                  </p>
                @endif
                
                {{-- Mostrar dirección si existe --}}
                @if ($venue_address)
                  <p class="flex items-center text-gray-600 text-sm mb-2">
                    <i data-feather="navigation" class="w-4 h-4 mr-1"></i>
                    <strong>Dirección:</strong>&nbsp; {{ $venue_address }}
                  </p>
                @endif
                
                {{-- Mostrar precio (usar precio anticipada si existe, si no, precio normal) --}}
                @if ($precio_anticipada || $price)
                  <p class="flex items-center text-gray-600 text-sm mb-2">
                    <i data-feather="tag" class="w-4 h-4 mr-1"></i>
                    <strong>{{ $precio_anticipada ? 'Anticipada:' : 'Precio:' }}</strong> 
                    {{ $precio_anticipada ?? $price }}€
                  </p>
                @endif
                
                {{-- Mostrar precio taquilla si existe y es diferente del precio anticipada --}}
                @if ($precio_taquilla && $precio_taquilla !== $precio_anticipada)
                  <p class="flex items-center text-gray-600 text-sm mb-2">
                    <i data-feather="tag" class="w-4 h-4 mr-1"></i>
                    <strong>Taquilla:</strong>&nbsp; {{ $precio_taquilla }}€
                  </p>
                @endif
                
                {{-- Para depuración: mostrar todos los metadatos disponibles --}}
                @if (isset($_GET['debug']) && $_GET['debug'] === '1')
                  <details class="mt-2">
                    <summary class="text-xs text-gray-500 cursor-pointer">Ver todos los metadatos</summary>
                    <div class="text-xs text-gray-500 bg-gray-100 p-2 mt-1 rounded overflow-auto max-h-40">
                      <pre>{{ print_r($all_meta, true) }}</pre>
                    </div>
                  </details>
                @endif
              </div>
            </a>
            
            {{-- Botón de compra de entradas si existe URL --}}
            @if ($purchase_url)
              <div class="px-4 pb-4 mt-2">
                <a href="{{ esc_url($purchase_url) }}" target="_blank" rel="noopener" 
                  class="block w-full text-center bg-primary text-white py-2 rounded hover:bg-primary-h transition-colors">
                  <i data-feather="shopping-cart" class="w-4 h-4 mr-1 inline-block"></i>
                  Comprar entradas
                </a>
              </div>
            @endif
          </div>
        @endwhile
        @php wp_reset_postdata(); @endphp
      </section>

      {{-- Pagination --}}
      @if ($upcoming->max_num_pages > 1)
        <div class="pagination flex justify-center my-8">
          <?php
            $big = 999999999;
            echo paginate_links([
              'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
              'format' => '?paged=%#%',
              'current' => max(1, get_query_var('paged')),
              'total' => $upcoming->max_num_pages,
              'prev_text' => '&laquo; Anterior',
              'next_text' => 'Siguiente &raquo;',
            ]);
          ?>
        </div>
      @endif
    @else
      <p class="text-gray-500 mb-12">No hay conciertos próximos.</p>
    @endif

    {{-- Past Events --}}
    @if ($past && $past->have_posts())
      <hr class="my-8">
      <h2 class="text-2xl font-bold mb-4 text-gray-700">Conciertos pasados</h2>
      <ul class="space-y-3 text-gray-500">
        @while ($past->have_posts())
          @php
            $past->the_post();
            $post_id = get_the_ID();
            $start_raw = get_post_meta($post_id, 'start_date', true);
            $start = $formatter->format(strtotime($start_raw));
            $event_city = get_post_meta($post_id, 'event_city', true);
            // Limpiar el título del evento
            $clean_title = mdv_clean_event_title(get_the_title());
          @endphp
          <li>
            <a href="{{ get_permalink() }}" class="hover:text-indigo-600">
              {{ $clean_title }} 
              @if($start) <span class="text-gray-400">({{ $start }}@if($event_city), {{ $event_city }}@endif)</span> @endif
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