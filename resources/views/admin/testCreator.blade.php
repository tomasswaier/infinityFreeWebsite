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
            <h1>Test Creator Page!</h1>
            <span>You read it correctly! We no longer have the need to manually insert tests into database!! Be careful tho. You can NOT delete a test after it's creation so choose your insert options carefully</span>
            <br>
            <br>
            <h3>Create Test</h3>
            <br>
            <form action="{{route('testCreator.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <span>Test Name:</span><input type="text" name="test_name" value=""><span>(this can be anything but try to keep it understandabale like "PSI final 2024/2025" )</span>
                <br>
                <span>Test CREATOR Name:"{{ Auth::user()->name }}"</span><span>(This field is derived from your profile thingy)</span>
                <br>
                <button type="submit" class="border rounded-md p-2">Create TEST!</button>
            </form>
        </main>
        {{--
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        --}}
    </body>
</html>



