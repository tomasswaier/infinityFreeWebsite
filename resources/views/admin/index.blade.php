<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fiits ucks</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
            </style>
        @endif
    </head>
    <body class="">
        <header class="">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ Route('testPage') }}"
                        >
                           TestPage
                        </a>
                        <a
                            href="{{ url('admin') }}"
                        >
                            MainPage
                        </a>
                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5  border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @else
                    @endauth
                </nav>
            @endif
        </header>
        <main class="p-10">
            <span>available tests</span>
            <a href="{{url('admin/testCreator')}}">create test</a>
            <br>
            <table class="border">
                <thead>
                <td class="w-60">Test name</td>
                <td class="w-20">id</td>
                <td class="w-40">edit button?</td>
                <td class="w-40">add question</td>
                <td class="w-40">delte text</td>
                </thead>
            @foreach($tests as $test)
                <tr>
                 <td>{{$test->test_name}}</td> <td>{{$test->id}}</td> <td>im a button</td>  <td><a  href="{{url('admin/questionCreator/'.$test->id)}}">|+|</a></span></td><td><button type="submit" title="heh lmao never gonna implement this">X</button></td>
                 <td></td>
                </tr>
            @endforeach
            </table>


        </main>
        {{--
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        --}}
    </body>
</html>

