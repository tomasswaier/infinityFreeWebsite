@php
    $correctOptions=array();
    $optionIndex=0;
    $questionNumber=0;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>moew</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <!--cdn because hosting is php only... no vite sadly-->

        <!--<script src="https://cdn.tailwindcss.com"></script>-->
        <!-- Load Tailwind via CDN -->
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>


        <!-- Styles / Scripts -->
            <style>
                th{
                    background-color: #e0e4e5;
                    color:#A33155;
                    border-radius: 5px 5px 0px 0px
                }
                .color-separator:nth-child(even){
                    background-color: #e0e4e5;
                }
            </style>
    </head>
    <body class="" onload="oldLinkChecker()">
        <header class="" >
            <div class="">
                    <div class="bg-[#A33155] h-30 text-white text-8xl">
                            <a href="{{url('/')}}" class="logo">FillerText</a>
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
            <div class="text-red-600 my-2 bg-[#e0e4e5]" >
                    <b class="f20">
                    website
                    </b><span style="font-size:10px;">Now running laravel !!!</span>

            </div>
        </header>
        <main class="md:p-10 sm:p-0">
        @if(isset($data))
            <form id='testForm'>
            <h1 class="">Test Site</h1>
            @if($displayCorrectAnswers==true)
                <h1>corect answers are</h1>
            @endif

            <table>

                <tr class=" text-[#A33155]">
                <th class='max-w-12 text-xs'><span>question num</span></th><th class="p-2 flex justify-start">question text</th></tr>
            @foreach($data as $question)

                    <tr><td class="grid grid-flow-row justify-center"><span>{{++$questionNumber}}.</span></td>
                        <td class="py-2">
                            <div>
                            <span>{{$question->question_text}}</span>
                            <div>
                            @foreach($question->image as $image)
                                <img src="{{asset('storage/test_images/'.$image->image_name)}}" alt="" class="md:max-w-2xl max-w-80 sm:max-w-160 w-full ">
                            @endforeach
                            </div>
                            <br>
                                @foreach($question->options as $option)
                                    @if(isset($option->preceding_text))
                                        <span>{{$option->preceding_text}}</span>
                                    @endif
                                    @if($option->option_type =='boolean_choice')
                                        <table  >
                                        <thead><tr><th>true</th><th>false</th><th class="px-2">option text</th></tr></thead>
                                        <tbody >

                                        @foreach($option->data as $boolean_option)

                                            <tr class="color-separator"><td ><input class="" type="radio" name="{{$optionIndex}}"  value="1"
                                            @if( $displayCorrectAnswers==true && $boolean_option['is_correct']==true)
                                                checked
                                            @endif
                                            ></td><td >
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
                                        </tbody>
                                        </table>
                                    @elseif($option->option_type =='write_in')
                                            <span>
                                            <input type="text" class="rounded-lg focus:border-none focus:ring-2  focus:ring-[#A33155]" name="{{$optionIndex}}"
                                            @if( $displayCorrectAnswers==true )
                                                value="{{$option['data']['correct_answer']}}"
                                            @endif
                                            ></span>
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
                                            <tr >
                                            <thead>

                                            @foreach($option->data['column_names'] as $colname)
                                                <th class="min-w-10"><span>{{$colname}}</span></th>
                                                @php
                                                $i++;
                                                @endphp
                                            @endforeach
                                            <td class='max-w-80'></td>
                                            </tr>
                                            </thead>
                                            @php
                                                $rowArrayIndex=0;
                                            @endphp
                                            <tbody>

                                            @foreach($option->data['row_array'] as $rowData)
                                                <tr class="color-separator">
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
                                            </tbody>

                                        </table>
                                    @elseif($option->option_type =='open_answer')
                                    <br>
                                    <div>
                                        <textarea rows="4" cols="40" class="w-60 md:w-160" placeholder="this type of question has no answer checking and serves only as practice for what might be on an actual exam"></textarea>
                                    </div>
                                    @elseif($option->option_type =='boolean_choice_one_correct')
                                        <table>
                                        <tr><th>true</th><th class="px-2">option text</th></tr>
                                        @for($i=0;$i<sizeof($option->data['option_array']);$i++)
                                            <tr><td><input type="radio" name="{{$optionIndex}}"  value="{{$i}}"
                                            @if( $displayCorrectAnswers==true && $option->data['correct_index']==$i)
                                                checked
                                            @endif
                                            ></td><td>
                                                <span>{{$option->data['option_array'][$i]}}</span>
                                                </td>
                                            </tr>
                                        @endfor
                                        @php
                                                $correctOptions[$optionIndex]=$option->data['correct_index'];
                                                $optionIndex++;
                                        @endphp
                                        </table>
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
	        <form id='main_form' action="{{url('test/')}}" method="post" enctype="multipart/form-data" >
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
            <span id='result_info'></span>
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
                correctOptions[{{$number}}]=@json($val);
            @endforeach
            console.log(correctOptions);
        </script>
    </body>
</html>
<script src="{{ asset('js/testEvaluator.js') }}"></script>
<script src="{{ asset('js/oldLinkRedirect.js') }}"></script>
