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
        --}}
        <main class="p-10">
        @if(isset($data))
            <form>
            <h1>Test Site</h1>
            <table>
                <th>
                <tr>
                <td>question num</td><td class="p-2">question text</td></tr></th>
            @foreach($data as $question)
                    <tr><td>X.</td>
                        <td class="border py-2">
                            <div>
                            <span>{{$question->question_text}}</span>
                            <br>
                                @foreach($question->options as $option)
                                    @if(isset($option->preceding_text))
                                        <span>{{$option->preceding_text}}</span>
                                    @endif
                                    @if($option->option_type =='boolean_choice')
                                        <table>
                                        <th><tr><td>true</td><td>false</td><td class="px-2">question test</td></tr></th>
                                        @foreach($option->data as $boolean_option)
                                            <tr><td><input type="radio" name="somename[]" value="1"></td><td>
                                                <input type="radio" name="somename[]" value="0">
                                                </td>
                                                <td>
                                                <span>{{$boolean_option['option_text']}}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </table>
                                    @elseif($option->option_type =='write_in')
                                        <div>
                                            <input type="text" name="idkyet">
                                        </div>

                                    @elseif($option->option_type =='one_from_many')
                                        <select>
                                            @for($i=0;$i<count($option->data['option_array']);$i++)
                                            <option value="{{$i}}">{{$option->data['option_array'][$i]}}</option>
                                            @endfor
                                        </select>
                                    @elseif($option->option_type =='multiple_choice')
                                        <table>
                                            @php
                                            $i=0;
                                            @endphp
                                            <th>
                                            <tr>
                                            @foreach($option->data['column_names'] as $colname)
                                                <td class="min-w-10"><span>{{$colname}}</span></td>
                                                @php
                                                $i++;
                                                @endphp
                                            @endforeach
                                            <td class='w-20'></td>
                                            </tr>
                                            </th>

                                            @foreach($option->data['row_array'] as $rowData)
                                                <tr>
                                                @for($j=0;$j<$i;$j++)
                                                    <td><input type="radio" name="something[]" value="$j" ></td>
                                                @endfor
                                                <td>
                                                    <span>{{$rowData['row_name']}}</span>
                                                </td>
                                                </tr>
                                            @endforeach

                                        </table>
                                    @else
                                        <span>Something went very very very very wrong please contact .maryann</span>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                    </tr>
            @endforeach
            </table>
            <br><br><br>
            <button type="submit" class="border border-black p-2 rounded-md">submit</button>
            </form>
            <br><br>
        @endif
        @if(isset($tests))
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
        @endif
        </main>
        {{--
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        --}}
    </body>
</html>
