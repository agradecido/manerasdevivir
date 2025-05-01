@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">{{ __('Tags', 'maneras') }}</h1>
        <ul class="list-disc pl-6 space-y-2">
            @foreach ($tags as $tag)
                <li>
                    <a
                        href="{{ esc_url(get_term_link($tag)) }}"
                        class="text-blue-600 hover:underline"
                    >
                        {{ esc_html($tag->name) }}
                    </a>
                    <span class="text-gray-500">({{ intval($tag->count) }})</span>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('footer')
@endsection
