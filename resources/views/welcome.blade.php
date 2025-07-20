<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fiits ucks</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

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
                    <a href="{{Route('testPage')}}">TestPage</a>
                    @auth
                        <a
                            href="{{ url('admin') }}"
                            class=""
                        >
                            AdminPage
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5  border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @else
                        <a
                            href="{{ route('login') }}"
                            class=""
                        >
                            Log in
                        </a>
                    @endauth
                </nav>
            @endif
        </header>
        <main>
            <section>
                <h2>This is is a welcome page</h2>
                <span>Main page of my thingy</span>

            </section>
                <br>
            <section>
                <h3>help</h3>
                <span>it would be great if someone would redraw images used on my website. I've got them all from publicly available sources like github,google forms and such but I would prefer to have them redrawn.</span>
                <br>
                <br>
                <span>Tests have been reworked and so now they also contain fields like <u title="serves as indicator for the question or as another general block where you can put question description to give it a cleaner look ">"preceding text"</u> and <u title="as name implies this is a field to explain the reasoning behind the correct answer">"explanation"</u>. These fields have to be manually filled in. Since I do no particularly care any more about the already done tests I won't be doing it myself but if one of you is interested in making this website tiny bit better I can give you admin account)</span>
                <br><br>
                <span>will give anyone account who want's to make a test or contribute to existing test)</span>
                <br><br>
                <span>study guide page coming soon)</span>
            </section>
        </main>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
