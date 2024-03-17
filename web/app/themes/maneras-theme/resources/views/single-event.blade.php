@extends('layouts.app')

@section('content')
    <div class="single-container flex flex-wrap">
        <div class="article w-full md:w-3/4 px-2">
            <article @php(post_class('h-entry'))>
                <header>
                    <h1 class="p-name">
                        {{ get_the_title() }}
                    </h1>
                    <div class="post-meta">
                        @include('partials.post-meta')
                    </div>
                </header>
                <div class="e-content">
                    @php(the_content())
                </div>
                @php(comments_template())
            </article>
        </div>
        <div class="sidebar w-full md:w-1/4 px-2 flex flex-col gap-y-8">
            @include('sections.sidebar')
        </div>
    </div>
@endsection
