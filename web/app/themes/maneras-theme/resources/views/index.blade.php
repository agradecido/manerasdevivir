{{-- resources/views/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="grid gap-8 md:grid-cols-[70%_1fr] px-4 lg:px-0">
        <section class="space-y-10">
            <h2 class="text-2xl font-bold uppercase mb-0">Últimas noticias</h2>
        @if ($posts)
        @foreach ($posts as $post)
            @php
                setup_postdata($post);
                // Separamos contenido en main/extended
                $parts = get_extended($post->post_content);
                $link  = get_permalink($post);
            @endphp

            <article class="mb-8">
                {{-- Titular enlazado a canonical --}}
                <h3 class="text-2xl font-bold mb-2 first-of-type:mt-0">
                    <a href="{{ esc_url($link) }}" rel="noopener" class="text-links hover:underline no-underline">
                        {{ esc_html($post->post_title) }}
                    </a>
                </h3>

                {{-- Firma y fecha --}}
                <ul class="text-gray-500 text-text-sub text-sm">
                    <li><i data-feather="user"></i> <span>{{ esc_html(get_post_meta($post->ID, 'firma_sender', true)) }}</li>
                    <li><i data-feather="clock"></i> <span>{{ date_i18n('d.m.y', strtotime($post->post_date)) }}</li>
                </ul>

                {{-- Solo la parte antes del more --}}
                <div class="mb-2">
                    {!! apply_filters('the_content', $parts['main']) !!}
                </div>

                {{-- Leer más → canonical --}}
                @if (trim($parts['extended']))
                    <p>
                        <a href="{{ esc_url($link) }}" rel="noopener"
                            class="text-hs hover:underline font-medium no-underline">
                            Leer más
                        </a>
                    </p>
                @endif

            </article>
        @endforeach

        @php wp_reset_postdata(); @endphp
    @else
        <p>No hay noticias publicadas.</p>
    @endif
        </section>
        <aside class="space-y-10">
                <div class="home-sidebar__item">
                    <h2 class="text-2xl font-bold mb-4 uppercase">Destacados</h2>
                    <ul>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li>Quisque euismod, nisi vel consectetur interdum, nisl nisi.</li>
                        <li>Praesent euismod, nisi vel consectetur interdum, nisl nisi.</li>
                    </ul>                    
                </div>

                <div class="home-sidebar__item">
                    <h2 class="text-2xl font-bold mb-4 uppercase">Próximos conciertos</h2>
                    
                </div>
        </aside>
    </div>
</div> 
@endsection
