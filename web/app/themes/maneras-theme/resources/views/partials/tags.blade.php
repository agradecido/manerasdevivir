{{--
  Partial that displays post tags
  @param array $tags The tags array from get_the_tags()
--}}
@if ($tags)
  <ul class="flex flex-wrap gap-2 mt-3 p-0 m-0">
    @foreach ($tags as $tag)
      <li>
        <a href="{{ get_tag_link($tag->term_id) }}"
          class="inline-block bg-surface hover:bg-border px-2 py-1 text-xs rounded">
          #{{ $tag->name }}
        </a>
      </li>
    @endforeach
  </ul>
@endif
