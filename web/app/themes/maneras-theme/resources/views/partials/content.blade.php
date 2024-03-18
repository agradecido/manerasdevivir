<article @php(post_class('card text-sm pt-3'))>
  <header>
    <h2 class="entry-title mb-2 text-xl">
      <a href="{{ get_permalink() }}">
        {!! $title !!}
      </a>
    </h2>
  </header>

  <div class="entry-summary">
    @php(the_excerpt())
  </div>

  <footer class="mt-3">
    @include('sections.home.post-meta')
  </footer>
</article>
