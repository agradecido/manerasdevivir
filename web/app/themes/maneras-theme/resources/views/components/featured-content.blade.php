@if (!empty($posts))
  <div class="featured-content bg-bg p-4 border border-links">
    <h2 class="text-hs text-2xl font-bold mb-4">Destacados</h2>
    <ul class="bg-[#1E2428] p-4">
      @foreach ($posts as $post)
        <li class="mb-6 last:mb-0">
          <div class="flex flex-col md:flex-row md:items-center text-text">
            @if (has_post_thumbnail($post->ID))
              <div class="w-full md:w-1/4 mb-4 md:mb-0">
                <a href="{{ get_permalink($post->ID) }}">
                  {{ the_post_thumbnail('medium', ['class' => 'w-full h-auto', 'alt' => get_the_title($post->ID)]) }}
                </a>
              </div>
            @endif
            <div class="w-full md:w-3/4 md:pl-4">
              <a href="{{ get_permalink($post->ID) }}" class="text-links text-lg font-semibold hover:text-hs">{{ $post->post_title }}</a>
              <p class="text-sm mt-1">
                Publicado el {{ get_the_date('', $post->ID) }} por {{ get_the_author_meta('display_name', $post->post_author) }}
              </p>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
  </div>
@else
  <p class="text-center text-text">No hay contenidos destacados para mostrar.</p>
@endif
