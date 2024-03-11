<article @php(post_class('h-entry'))>
  <header>
    <h1 class="p-name">
      {!! $title !!}
    </h1>
    <div class="post-meta">
      @include('partials.post-meta')
    </div>
  </header>

  <div class="e-content">
    @php(the_content())
  </div>

  @if ($pagination)
    <footer>
      <nav class="page-nav" aria-label="Page">
        {!! $pagination !!}
      </nav>
    </footer>
  @endif

  @php(comments_template())
</article>
