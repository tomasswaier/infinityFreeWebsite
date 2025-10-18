@extends('layouts.app')
@section('mainContent')
<div class="border-t pt-10 border-black sm:p-10 sm:border-none  text-project-dark-blue">
    @php
        $correctOptions=array();
        $optionIndex=0;
        $questionNumber=0;
    @endphp
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    @if(Auth::user() && supervisesSchool(Auth::user(),$school_id))
    <div class="absolute right-0 bg-project-super-blue text-project-white p-3 rounded-md">
        <span>Admin Panel</span>
        <br><br>
        <span class=" bg-project-blue text-white rounded-sm p-2"><a href="{{url('admin/test/testCreator/'.$school_id)}}">create test</a></span>
    </div>
    @endif
    @if(isset($data))
        <form id='testForm' class="xl:w-1/2 w-full"> {{--data is never sent hence no need for all form parameters even tho html demands them--}}
        @if($displayCorrectAnswers==true)
            <h1>CORRECT ANSWERS ARE</h1>
        @endif

        <table class="bg-project-light-blue sm:rounded-md">
            <tr >
            <th class='max-w-12 text-xs'><span>question num</span></th><th class="p-2 flex justify-start">question text</th></tr>
        @foreach($data as $question)

                <tr><td class="grid grid-flow-row justify-center" align="center"><span>{{++$questionNumber}}.</span></td>
                    <td class="py-2 bg-project-white">
                        <div class="inline-block">
                            <pre  class="whitespace-pre-wrap break-words overflow-x-auto">{{$question->question_text}}</pre>
                        <div>
                        @foreach($question->image as $image)
                            <img src="{{asset('storage/test_images/'.$image->image_name)}}" alt="" class="md:max-w-2xl max-w-80 sm:max-w-160 w-full ">
                        @endforeach
                        </div>
                        <br>
                            @foreach($question->options as $option)
                                @if(isset($option->preceding_text))
                                    <pre class="">{{$option->preceding_text}}</pre>
                                @endif
                                @if($option->option_type =='boolean_choice')
                                    <table  >
                                    <thead><tr><th>true</th><th>false</th><th class="px-2">option text</th></tr></thead>
                                    <tbody >
                                    @php
                                        $shuffledArr=$option->data;
                                        shuffle($shuffledArr);
                                    @endphp
                                    @foreach($shuffledArr as $boolean_option)

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
                                            <span >{{$boolean_option['option_text']}}</span>
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
                                        <input type="text" class="rounded-lg border-none ring-2  ring-project-dark-blue focues:border-none" name="{{$optionIndex}}"
                                        @if( $displayCorrectAnswers==true )
                                            value="{{$option['data']['correct_answer']}}"
                                        @endif
                                        ></span>
                                        @isset($option['data']['after_text'])
                                            <span>{{$option['data']['after_text']}}</span>
                                        @endisset
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
                                                <td><input type="radio"  name="{{$optionIndex}}" value="{{$j}}"
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
                                @elseif($option->option_type =='fill_in_table')
                                    <br>
                                    <table>
                                        <tbody class="border-2 border-black">
                                            @foreach($option->data["row_array"] as $row)
                                              <tr>
                                              @foreach($row as $cell)
                                                <td align="middle" class="border border-black">
                                                @if($cell['isAnswer'])
                                                  <input type="text" class="border-none ring-2 ring-project-dark-blue focues:border-none" name="{{$optionIndex}}">
                                                @else
                                                    <span>{{$cell["cellText"]}}</span>
                                                @endif
                                                </td>
                                                @php
                                                        $correctOptions[$optionIndex]=$cell['cellText'];
                                                        $optionIndex++;
                                                @endphp
                                              @endforeach
                                              </tr>
                                            @endforeach
                                        </tbody>
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
        <input type="submit" id='testSubmitButton' class="border border-black p-2 rounded-md" value="Submit Test">
        </form>
        <br><br>
    @endif
    @if(isset($tests))
        <form id='main_form' action="{{url('test/')}}" method="post" enctype="multipart/form-data" >
            @csrf
            <span>Select Test</span>
            <select id="test_selector" class="rounded-md appearance-auto bg-none p-2" name="test_selector">
               @foreach($tests as $test)
                <option value="{{$test->id}}" @if(isset($test_id) && $test->id==$test_id) selected @endif>{{$test->test_name}}</option>
               @endforeach
            </select>
            <br>
            <span>number of questions:</span><input name='number_of_questions' type="number" class="border w-20  rounded-md "id="number_of_questions" value='@if($questionNumber!=0){{$questionNumber}}@else{{30}}@endif'><br>
            <span>Display all correct options:<input type="checkbox" name="displayCorrectAnswers" value="1"> </span><br>
            <button id="my_button" name="submit" class="border-black border p-2 rounded-md" ><u>Display test</u></button>
        </form>
        <span id='result_info'></span>
    @endif
</div>
<script>
//it's intended for the user to have full access to correct asnwers ,. hell i'd give everyone access to db if ash wasnt here. I am doing rthis with js because it's easier than submitting everyhting with laravel trying to recreate the test ,build completely new blade file  for this shi ... I dont need to info of what the user had correct or didnt so why send it over :3
    var correctOptions={};
    @foreach($correctOptions as $number=>$val)
        correctOptions[{{$number}}]=@json($val);
    @endforeach
    console.log(correctOptions);
    var test_id=@isset($test_id){{$test_id}}@endisset;
</script>
<script src="{{ asset('js/testEvaluator.js') }}"></script>
<script src="{{ asset('js/oldLinkRedirect.js') }}"></script>
@endsection
