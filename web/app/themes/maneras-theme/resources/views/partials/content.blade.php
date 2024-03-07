<article @php(post_class())>
  <header>
    <h2 class="entry-title">
      <a href="{{ get_permalink() }}">
        {!! $title !!}
      </a>
    </h2>
  </header>

  <div class="entry-summary">
    @php(the_excerpt())
  </div>

  <footer>
    @include('partials/entry-meta')
  </footer>
</article>

<div class="test"><a href="#" class="btn-primary pt-2 m-2 mb-2">btn-primary</a></div>
