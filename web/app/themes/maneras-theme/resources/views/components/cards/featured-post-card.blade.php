@props(['post'])

<li>
  <div class="card rounded-lg p-0 flex flex-col md:items-center text-text">
    <div class="w-full">
      <a href="{{ $post['permalink'] }}">
        {!! get_the_post_thumbnail($post['ID'], 'medium', ['class' => 'card-feat-content w-100', 'alt' => $post['title']]) !!}
      </a>
    </div>
    <div class="w-full mt-2 p-4 pt-1">
      <a href="{{ get_permalink($post['ID']) }}" class="text-links hover:text-hs">
        <h3 class="text-xl font-semibold">{{ $post['title'] }}</h3>
      </a>
      <p class="mt-2 mb-0">{!! wp_trim_words($post['excerpt'], 20) !!}</p>
      <ul class="text-sm mt-3 flex flex-row justify-between">
        <li><i class="fa fa-solid fa-user-astronaut"></i>{{ $post['author'] }}</li>
        <li>
          <time class="dt-published" datetime="{{ $post['date'] }}">
            {{ $post['date'] }}
          </time>
        </li>
      </ul>
    </div>
  </div>
</li>
