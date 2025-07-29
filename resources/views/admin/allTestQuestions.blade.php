@extends('layouts.app')
@section('mainContent')
        @if($data)
        <table class="m-10 border rounded-xl overflow-hidden">
            <thead class="bg-project-super-blue px-4 h-16 text-xl text-white ">

                <tr ><th class="w-10"></th><th class="p-4">id</th><th>question</th><th class="p-4">edit</th> <th>delete</th><th class="w-10">
                </tr>
            </thead>
            @foreach($data as $question)
            <tbody>
                <tr class="border">
                    <td></td>
                    <td align="center">{{$question['id']}}</td>
                    <td class="w-50"><span maxlength='30'>{{Str::limit($question['question_text'],'120','...')}}</span></td>
                    <td align="center"><a href="{{url('admin/questionEditor/'.$question['id'])}}"><img src="{{asset('storage/assets/edit_icon.png')}}" class="h-10 w-auto" alt="random edit icon"></a></td>
                    <td align="center"><a href="{{url('admin/questionDelete/'.$question['id'])}}" title="heh like im ever gonna implement that">x</a</td>
                    <td></td>
                </tr>
            </tbody>
            @endforeach
        </table>
        @endif
        </main>
        {{--
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        --}}
@endsection


