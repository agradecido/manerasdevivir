<span class="fa fa-clock"></span>
<time class="dt-published" datetime="{{ get_post_time('c', true) }}">
  {{ get_the_date() }}
</time>

<span class="fa fa-user"></span>{{ get_post_meta( get_the_ID(), 'sender_name', true ) }}

<!--<p>
  <span>{{ __('By', 'sage') }}</span>
  <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}" class="p-author h-card">
    {{ get_the_author() }}
  </a>
</p>-->
