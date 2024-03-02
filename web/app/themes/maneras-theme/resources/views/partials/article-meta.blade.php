<time class="dt-published" datetime="{{ get_post_time('c', true) }}">
  {{ get_the_date() }}
</time>

<p>
  <span>Por <em>@php echo get_post_meta(get_the_ID(), 'sender_name', true); @endphp</em></span>
  <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}" class="p-author h-card">
    {{ get_the_author() }}
  </a>
</p>
