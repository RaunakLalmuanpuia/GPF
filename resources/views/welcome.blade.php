<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GPF</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel=icon type=image/ico href="/public/favicon.ico"> 
        <!-- <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}"> -->

        @vite('resources/css/app.css') 
        @vite(['resources/js/app.js'])
       
    </head>
    <body>
        <div id="app">
         
            @vite('resources/js/app.js')
            @vite('resources/css/app.css')
        </div>
    </body>
</html>
<!-- Final build -->