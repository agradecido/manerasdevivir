<div class="single-container flex flex-wrap">
  <div class="article w-full md:w-3/4 px-2">

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
  </div>
  <div class="sidebar w-full md:w-1/4 px-2 flex flex-col gap-y-8">
    @include('sections.sidebar.featured-posts', ['featuredPosts' => $featuredPosts])
    @include('sections.sidebar.events', ['events' => $events])
  </div>
</div>
