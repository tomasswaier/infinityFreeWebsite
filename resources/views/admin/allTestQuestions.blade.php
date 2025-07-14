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
        @if($data)
        <table>
            <th><tr ><td class="p-4">id</td><td>question</td><td class="p-4">edit</td> <td>delete</td></tr></th>
            @foreach($data as $question)
            <div>
            <tr class="border">
                <td>{{$question['id']}}</td>
                <td class="w-50"><span maxlength='30'>{{$question['question_text']}}</span></td>
                <td><a href="{{url('admin/questionEditor/'.$question['id'])}}">edt</a></td>
                <td><a href="{{url('admin/questionDelete/'.$question['id'])}}" title="heh like im ever gonna implement that">del</a</td>
            </tr>
            @endforeach
        </table>
        @endif
        </main>
        {{--
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        --}}
    </body>
</html>



