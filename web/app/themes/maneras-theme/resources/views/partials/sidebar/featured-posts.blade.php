@if (!empty($featuredPosts))
  <h2 class="text-hs text-2xl font-bold mb-4">Destacados</h2>
  <div class="featured-content bg-bg">
    <ul>
      @foreach ($featuredPosts as $post)
        <li class="mb-6 last:mb-0 bg-bg-secondary rounded-lg">
          <div class="flex flex-col md:items-center text-text">
{{--            @if ($loop['index'] < 2 && has_post_thumbnail($post['ID']))--}}
{{--              <div class="w-full">--}}
{{--                <a href="{{ $post['permalink'] }}">--}}
{{--                  {{get_the_post_thumbnail($post['ID'], 'medium', ['class' => 'w-full h-auto rounded-t-lg opacity-80 hover:opacity-100', 'alt' => $post['tilte']])  }}--}}
{{--                </a>--}}
{{--              </div>--}}
{{--            @endif--}}
            <div class="w-full mt-2 px-2 pb-2">
              <a href="{{ get_permalink($post['ID']) }}" class="text-links font-semibold hover:text-hs"><h3>{!! $post['title'] !!}</h3></a>
              <ul class="text-sm mt-1 flex flex-row justify-between">
                <li><span class="fas fa-user text-text"></span>{{ get_post_meta( $post['ID'], 'sender_name', true ) }}</li>
                <li>
                  <time class="dt-published" datetime="{{ get_post_time('c', true) }}">
                    {{ get_the_date('d \d\e F \d\e Y', $post['ID']) }}
                  </time>
                </li>
              </ul>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
  </div>
@endif
