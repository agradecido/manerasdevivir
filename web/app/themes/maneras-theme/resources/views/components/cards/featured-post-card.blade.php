@props(['post'])

<li class="mb-6 last:mb-0 bg-bg-secondary rounded-lg">
  <div class="card p-0 flex flex-col md:items-center text-text">

    <div class="w-full">
      <a href="{{ $post['permalink'] }}">
        {!! get_the_post_thumbnail($post['ID'], 'medium', ['class' => 'card-feat-content w-100', 'alt' => $post['title']]) !!}
      </a>
    </div>

    <div class="w-full mt-2 p-4 pt-1">
      <a href="{{ get_permalink($post['ID']) }}" class="text-links font-semibold hover:text-hs">
        <h3>{{ $post['title'] }}</h3></a>
      <ul class="text-sm mt-1 flex flex-row justify-between">
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
