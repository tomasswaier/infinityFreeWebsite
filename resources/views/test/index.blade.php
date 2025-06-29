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
        {{--
        <header class="">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('admin') }}"
                            class=""
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class=""
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5  border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        --}}
        <header>
            <div class="">
                    <div class="bg-[#A33155] h-30 text-white text-8xl">
                            <span class="logo">Fiitsa joke</span>
                    </div>
                    <div id="ais_text" class="bg-[#A33155] text-white text-2xl text-right">text on right</div>
                    <div class="bg-[#A33155] mt-1 text-white text-right">|meow|meow|meow|meow|meow

                    </div>

            </div>
            <div class="text-red-600 my-2 bg-[#e7ebed]" >
                    <b class="f20">
                    website
                    </b><span style="font-size:10px;">Now running laravel !!!</span>

            </div>

        </header>
        <main class="p-10">
	        <form id='main_form' onkeydown="if(event.keycode === 13) {/*alert('you have pressed enter key, use submit button instead');*/ return false;}" >
                <span>Select Test</span>
                <select id="test_selector" class="rounded-md" name="test_selector">
                   @foreach($tests as $test)
                    <option value="{{$test->id}}">{{$test->test_name}}</option>
                   @endforeach


                </select>
                <br>
		        <span>number of questions:</span><input type="number" class="border w-20  rounded-md "id="number_of_questions" value='30'><br>
		        <button id="my_button" name="submit" class="border-black border p-2 rounded-md" ><u>Display test</u></button>
            </form>
        </main>
        {{--
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        --}}
    </body>
</html>
