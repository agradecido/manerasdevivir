{{-- Featured posts for show in a column --}}
@php use App\Constants; @endphp
@if (!empty($featuredPosts))
  <div class="featured-posts">
    <h2 class="mb-2 text-2xl">@php _e('Destacados', Constants::TEXTDOMAIN) @endphp</h2>
    <div class="flex flex-col flex-wrap">
      <ul>
        @foreach ($featuredPosts as $post)
          <x-featured-post-card :post="$post"/>
        @endforeach
      </ul>
    </div>
  </div>
@endif
