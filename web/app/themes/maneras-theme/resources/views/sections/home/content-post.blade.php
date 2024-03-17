<article @php(post_class())>
    <header>
        <h3 class="entry-title">
            <a href="{{ get_permalink() }}">
                {{ $title }}
            </a>
        </h3>
    </header>

    <div class="entry-summary">
        @php(the_excerpt())
    </div>

    <footer>
        @include('post-meta')
    </footer>
</article>
