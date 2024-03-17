<div class="post-meta flex flex-row justify-between w-full max-w-full min-w-full text-xs my-2">
  <div class="post-author">
    <i class="fa fa-solid fa-user-astronaut"></i>{{ $post['author'] }}
  </div>
  <div class="post-date">
    <time class="dt-published" datetime="{{ $post['date'] }}">
      <i class="fa fa-calendar-alt"></i>{{ $post['date']  }}
    </time>
  </div>
</div>
