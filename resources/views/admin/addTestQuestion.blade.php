
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
        <script src="{{ asset('js/questionCreator.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
            </style>
        @endif
    </head>
    <body class=""  onload="load_input_field()">
        <header class="">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('admin') }}"
                            class=""
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
            <h1>Question Creator . Jajo I will not be doing ur thing bcs it's too hard(/timeconsuming and i no wanna)</h1>
            <form action="" id="user-list" method="post" enctype="multipart/form-data">

            </form ">

        </main>
        {{--
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        --}}
    </body>
</html>

