{{-- Events list for show in a column --}}
@if (!empty($featuredPosts))
  <div class="featured-posts">
    <h2 class="mb-2">Destacados</h2>
    <div class="flex flex-col flex-wrap">
      <ul>
        @foreach ($featuredPosts as $post)
          <x-featured-post-card :post="$post"/>
        @endforeach
      </ul>
    </div>
  </div>
@endif
