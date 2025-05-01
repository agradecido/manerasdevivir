{{-- resources/views/index.blade.php --}}
@extends('layouts.app')

@section('content')
    @if ($posts)
        @foreach ($posts as $post)
            @php
                setup_postdata($post);

                // Separamos contenido en main/extended
                $parts = get_extended($post->post_content);

                $link = get_permalink($post);
            @endphp

            <article class="mb-8">
                {{-- Titular enlazado a canonical --}}
                <h2 class="text-2xl font-bold mb-2">
                    <a href="{{ esc_url($link) }}" rel="noopener" class="text-links hover:underline">
                        {{ esc_html($post->post_title) }}
                    </a>
                </h2>

                {{-- Solo la parte antes del more --}}
                <div class="prose mb-2">
                    {!! apply_filters('the_content', $parts['main']) !!}
                </div>

                {{-- Leer más → canonical --}}
                @if (trim($parts['extended']))
                    <p>
                        <a href="{{ esc_url($link) }}" rel="noopener"
                            class="text-hs hover:underline font-medium">
                            Leer la noticia completa.
                        </a>
                    </p>
                @endif

                {{-- Firma y fecha --}}
                <p class="text-gray-500 text-sm mt-2">
                    {{ esc_html(get_post_meta($post->ID, 'firma_sender', true)) }}
                    — {{ date_i18n('d.m.y', strtotime($post->post_date)) }}
                </p>
            </article>
        @endforeach

        @php wp_reset_postdata(); @endphp
    @else
        <p>No hay noticias publicadas.</p>
    @endif
@endsection
