{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="grid gap-8 md:grid-cols-[70%_1fr] px-4 lg:px-0">
        <section class="space-y-10">
            <h2 class="text-2xl font-bold uppercase mb-0">Últimas noticias</h2>
        @if ($articles && $articles->have_posts())
            @while ($articles->have_posts())
                @php
                    $articles->the_post();
                    // Separamos contenido en main/extended
                    $parts = get_extended(get_the_content());
                    $link = get_permalink();
                @endphp

                <article class="mb-8">
                    {{-- Titular enlazado a canonical --}}
                    <h3 class="text-2xl font-bold mb-2 first-of-type:mt-0">
                        <a href="{{ esc_url($link) }}" rel="noopener" class="text-links hover:underline no-underline">
                            {{ get_the_title() }}
                        </a>
                    </h3>

                    {{-- Firma y fecha --}}
                    <ul class="flex flex-wrap gap-4 text-text-sub text-sm">
                        <li class="flex items-center gap-1">
                            <i data-feather="user"></i>
                            <span>{{ esc_html(get_post_meta(get_the_ID(), 'firma_sender', true)) }}</span>
                        </li>
                        <li class="flex items-center gap-1">
                            <i data-feather="clock"></i>
                            <span>{{ get_the_date('d.m.y') }}</span>
                        </li>
                    </ul>

                    {{-- Solo la parte antes del more --}}
                    <div class="mb-2">
                        {!! apply_filters('the_content', $parts['main']) !!}
                    </div>

                    {{-- Leer más → canonical --}}
                    @if (trim($parts['extended']))
                        <p>
                            <a href="{{ esc_url($link) }}" rel="noopener"
                                class="inline-flex items-center gap-1 text-primary hover:underline font-medium no-underline">
                                Leer más
                                <i data-feather="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </p>
                    @endif

                </article>
            @endwhile

            @if ($articles->max_num_pages > 1)
                <div class="pagination flex justify-center my-8">
                    <a href="{{ get_post_type_archive_link('article') }}/page/2" rel="noopener"
                        class="inline-flex items-center gap-1 text-primary hover:underline font-medium no-underline">
                        Ver más
                        <i data-feather="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            @endif

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
