<div class="post-meta flex flex-row justify-start mb-4">
  <div class="post-author mr-10">
    <i class="fa fa-solid fa-user-astronaut"></i>{{ get_post_meta( get_the_ID(), 'sender_name', true ) }}
  </div>
  <time class="dt-published" datetime="{{ get_post_time('c', true) }}">
    <i class="fa fa-clock"></i>{{ get_the_date() }}
  </time>
</div>
