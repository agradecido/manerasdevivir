{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html {!! get_language_attributes() !!}>

<head>
    <meta charset="{{ bloginfo('charset') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ wp_get_document_title() }}</title>
    @php wp_head(); @endphp
    
</head>

<body @php body_class() @endphp>
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')
    
    @php wp_footer(); @endphp
</body>

</html>
