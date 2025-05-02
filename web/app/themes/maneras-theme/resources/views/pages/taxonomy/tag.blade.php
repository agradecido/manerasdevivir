@extends('layouts.app')
@php
  $formatter = new \IntlDateFormatter(
	'es_ES',
	\IntlDateFormatter::LONG,
	\IntlDateFormatter::NONE,
	null,
	null,
	'd \'de\' MMMM \'de\' yyyy'
    );
@endphp
@section('content')
    <div class="container mx-auto px-4 py-8">
        <header class="mb-6">
            <h1 class="text-4xl font-bold">{{ esc_html($tag->name) }}</h1>
            @if (!empty($tag->description))
                <p class="text-gray-700 mt-2">{{ esc_html($tag->description) }}</p>
            @endif
        </header>

        @if ($posts->have_posts())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @while ($posts->have_posts())
                    @php($posts->the_post())
                    <article class="border rounded-lg p-4 shadow-sm">
                        <a href="{{ get_permalink() }}" class="block">
                            <h2 class="text-2xl font-semibold mb-2"><?php the_title(); ?></h2>
                        </a>
                        <div class="text-sm text-gray-500 mb-4">
                            {!! $formatter->format(get_post_time('U', true)) !!}
                        </div>
                        <div class="prose">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                @endwhile
            </div>
            @php(wp_reset_postdata())
        @else
            <p>{{ __('No posts found for this tag.', 'maneras') }}</p>
        @endif
    </div>
@endsection

@section('footer')
@endsection