@php
    $correctOptions=array();
    $optionIndex=0;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fiits ucks</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <!--cdn because hosting is php only... no vite sadly-->

        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Load Tailwind via CDN -->
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>


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
            <div class="">
                    <div class="bg-[#A33155] h-30 text-white text-8xl">
                            <span class="logo">Fiitsa joke</span>
                    </div>
                    <div id="ais_text" class="bg-[#A33155] text-white text-2xl text-right">text on right</div>
                    <div class="bg-[#A33155] mt-1 text-white text-right">
                        <nav class="flex items-center justify-end ">
                    @if (Route::has('login'))
                            @if (Route::has('adminPage') && Auth::check())
                            <span>
                                <a
                                    href="{{ url('admin') }}"
                                    class=""
                                >meowboard</a></span>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class=""
                                >
                                    meow in
                                </a>

                            @endauth
                    @endif
                    <span>
                        |meow|meow|meow|meow|
                    </span>
                    @if(Auth::check())
                     <form method="post" action="{{route("logout")}}">
                         @csrf
                         <button type="submit" class="">meow out</button>
                     </form>
                    @else
                        <span>meow</span>
                    @endif

                        </nav>
                    </div>

            </div>
            <div class="text-red-600 my-2 bg-[#e7ebed]" >
                    <b class="f20">
                    website
                    </b><span style="font-size:10px;">Now running laravel !!!</span>

            </div>
        </header>
        <main class="p-10">
        @if(isset($data))
            <form id='testForm'>
            <h1 class="">Test Site</h1>
            @if($displayCorrectAnswers==true)
                <h1>corect answers are</h1>
            @endif

            <table>
                <th>
                <tr>
                <td>question num</td><td class="p-2">question text</td></tr></th>
            @foreach($data as $question)
                    <tr><td>X.</td>
                        <td class="border py-2">
                            <div>
                            <span>{{$question->question_text}}</span>
                            <div>
                            @foreach($question->image as $image)
                                <img src="{{asset('storage/test_images/'.$image->image_name)}}" alt="">
                            @endforeach
                            </div>
                            <br>
                                @foreach($question->options as $option)
                                    @if(isset($option->preceding_text))
                                        <span>{{$option->preceding_text}}</span>
                                    @endif
                                    @if($option->option_type =='boolean_choice')
                                        <table>
                                        <th><tr><td>true</td><td>false</td><td class="px-2">question test</td></tr></th>
                                        @foreach($option->data as $boolean_option)

                                            <tr><td><input type="radio" name="{{$optionIndex}}"  value="1"
                                            @if( $displayCorrectAnswers==true && $boolean_option['is_correct']==true)
                                                checked
                                            @endif
                                            ></td><td>
                                                <input type="radio"  name="{{$optionIndex}}" value="0"
                                            @if( $displayCorrectAnswers==true && $boolean_option['is_correct']==false)
                                                checked
                                            @endif
                                                >
                                                </td>
                                                <td>
                                                <span>{{$boolean_option['option_text']}}</span>
                                                </td>
                                            </tr>
                                            @php
                                                $correctOptions[$optionIndex]=$boolean_option['is_correct']==true?1:0;
                                                $optionIndex++;
                                            @endphp
                                        @endforeach
                                        </table>
                                    @elseif($option->option_type =='write_in')
                                        <div>
                                            <input type="text" name="{{$optionIndex}}"
                                            @if( $displayCorrectAnswers==true )
                                                value="{{$option['data']['correct_answer']}}"
                                            @endif
                                            >
                                        </div>
                                            @php
                                                $correctOptions[$optionIndex]=$option['data']['correct_answer'];
                                                $optionIndex++;
                                            @endphp
                                    @elseif($option->option_type =='one_from_many')
                                        <br>
                                        <div>

                                        <select class="appearance-auto bg-none">
                                            @for($i=0;$i<count($option->data['option_array']);$i++)
                                            <option value="{{$i}}" name="{{$optionIndex}}"
                                            @if( $displayCorrectAnswers==true && $option['data']['correct_option']==$i )
                                            selected
                                            @endif
                                            >{{$option->data['option_array'][$i]}}</option>
                                            @endfor
                                            @php
                                                $correctOptions[$optionIndex]=$option['data']['correct_option'];
                                                $optionIndex++;
                                            @endphp
                                        </select>
                                        </div>
                                    @elseif($option->option_type =='multiple_choice')
                                        <table>
                                            @php
                                            $i=0;
                                            @endphp
                                            <th >
                                            <tr >
                                            @foreach($option->data['column_names'] as $colname)
                                                <td class="min-w-10"><span>{{$colname}}</span></td>
                                                @php
                                                $i++;
                                                @endphp
                                            @endforeach
                                            <td class='w-20'></td>
                                            </tr>
                                            </th>
                                            @php
                                                $rowArrayIndex=0;
                                            @endphp

                                            @foreach($option->data['row_array'] as $rowData)
                                                <tr>
                                                @for($j=0;$j<$i;$j++)
                                                    <td><input type="radio" name="{{$optionIndex}}" value="{{$j}}"
                                                        @if( $displayCorrectAnswers==true && $rowData['correct_answer']==$j )
                                                            checked
                                                        @endif
                                                    ></td>
                                                @endfor
                                                @php
                                                    $correctOptions[$optionIndex]=$option['data']['row_array'][$rowArrayIndex]['correct_answer'];
                                                    $optionIndex++;
                                                    $rowArrayIndex++;
                                                @endphp
                                                <td>
                                                    <span>{{$rowData['row_name']}}</span>
                                                </td>
                                                </tr>
                                            @endforeach

                                        </table>
                                    @elseif($option->option_type =='open_answer')
                                    <br>
                                    <div>
                                        <textarea rows="4" cols="40" placeholder="this type of question has no answer checking and serves only as practice for what might be on an actual exam"></textarea>
                                    </div>
                                    @else
                                        <span>Something went very very very very wrong please contact .maryann</span>
                                    @endif
                                @endforeach
                            </div>
                            <div class="p-2" name='explanation'
                                @if( !$displayCorrectAnswers==true)
                                hidden
                                @endif
                            >
                                <span class="text-slate-500">
                                   {{$question['explanation_text']}}
                                </span>
                            </div>
                        </td>
                    </tr>
            @endforeach
            </table>
            <br><br><br>
            <input type="submit" id='testSubmitButton' class="border border-black p-2 rounded-md">submit todo:add js for it to mark the stuff and api to track number of submits</button>
            </form>
            <br><br>
        @endif
        @if(isset($tests))
	        <form id='main_form' action="{{route('displayTest')}}" method="post" enctype="multipart/form-data" >
                @csrf
                <span>Select Test</span>
                <select id="test_selector" class="rounded-md appearance-auto bg-none p-2" name="test_selector">
                   @foreach($tests as $test)
                    <option value="{{$test->id}}">{{$test->test_name}}</option>
                   @endforeach
                </select>
                <br>
		        <span>number of questions:</span><input name='number_of_questions' type="number" class="border w-20  rounded-md "id="number_of_questions" value='30'><br>
                <span>Display all correct options:<input type="checkbox" name="displayCorrectAnswers" value="1"> </span><br>
		        <button id="my_button" name="submit" class="border-black border p-2 rounded-md" ><u>Display test</u></button>
            </form>
        @endif
        </main>
        {{--
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        --}}
        <script>
        //it's intended for the user to have full access to correct asnwers ,. hell i'd give everyone access to db if ash wash here. I am doing rthis with js because it's easier than submitting everyhting with laravel trying to recreate the test ,build completely new blade file  for this shi ... I dont need to info of what the user had correct or didnt so why send it over :3
            var correctOptions={};
            @foreach($correctOptions as $number=>$val)
                correctOptions[{{$number}}]="{{$val}}";
            @endforeach
        </script>
    </body>
</html>
<script src="{{ asset('js/testEvaluator.js') }}"></script>
