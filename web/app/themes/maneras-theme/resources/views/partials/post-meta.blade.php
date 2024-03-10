<div class="post-meta flex flex-row justify-between">
  <div class="post-author">
    <i class="fa fa-solid fa-user-astronaut"></i>{{ get_post_meta( get_the_ID(), 'sender_name', true ) }}
  </div>
  <time class="dt-published" datetime="{{ get_post_time('c', true) }}">
  {{ get_the_date() }}
  </time>
</div>
