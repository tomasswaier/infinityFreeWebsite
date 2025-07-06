<?php

namespace app\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Test;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function show()
    {
        return view('test/index',['tests'=>Test::all()]);
    }
    //
    public function createTest(Request $request) {
        try {
            $id=Test::create([
                'test_name'=>$request['test_name'],
                'test_author'=>Auth::user()->name,
                'number_of_submits'=>0,
            ]);
        } catch (\Throwable $e) {
            Log::error('Test creation failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

        }
        return redirect('admin')->with('success', 'owo created!');
    }

    public function addQuestion(Request $request){
        try {
            //Marek is this how I should do it ? in routes set the test id to sessions and then here retreive ti ?
            $test_id= $request->session()->pull('test_id',0);
            //Log::info('test id:'.$test_id);
            $questionType="";
            $myClass;
            $input=$request->except('_token)');
            //Log::info($input);
            Log::info('insert into db(testId,QuestionText,Question)')
            foreach ($input as $key => $value) {
                //will be building this with future possibility of multiple types of questions per one question
                if (str_contains($key,'preceding')) {
                    $questionId=1;//todo : figure out how to get the id
                    $myClass=$this->initQuestionTypeClass($key,$questionId);
                    if (!$myClass) {
                        exit;
                    }else {
                        //Log::info("I am class:".$myClass." with:Id".$myClass->questionId." num:".$myClass->questionNumber);
                        $myClass.


                    }
                }else{


                }
                /*
                switch ($key) {
                    case "preceding_text_boolean_choice_0":
                        $questionType="boolean-choice";
                        Log::info('insert into Questions:question_text:'.$input['question_text']);
                        break;
                    default:
                        # code...
                        break;
                }
                 */
            }


            return redirect('admin/questionCreator/'.$test_id);
        } catch (\Exception $e) {
            Log::error('Something bad failed: '.$e->getMessage());
            return response()->json(['success' => false], 500);
        }

    }
    private function initQuestionTypeClass($key,$questionId){
        Log::info($questionId);
        $questionType=substr($key,0,strlen($key)-2);
        $questionType=substr($questionType,15,strlen($questionType)-15);
        Log::info($questionType);
        switch ($questionType) {
            case "boolean_choice":
                $questionNumber=substr($key,30,strlen($key));
                return new BooleanChoice($questionNumber,$questionId);
            case "write_in":
                $questionNumber=substr($key,24,strlen($key));
                return new WriteIn($questionNumber,$questionId);
            case "multiple_choice":
                $questionNumber=substr($key,31,strlen($key));
                return new MultipleChoice($questionNumber,$questionId);
            case "one_from_many":
                $questionNumber=substr($key,29,strlen($key));
                return new OneFromMany($questionNumber,$questionId);
            default:
                Log::error("unknown question type:".$questionType);
                break;
        }

        return null;
    }

}

//move this somewhere ?
class QuestionType{
    public $questionNumber;
    public $questionId;
    function __construct($questionNumber,$questionId){
        $this->questionNumber=$questionNumber;
        $this->questionId=$questionId;
    }
    function _toString(){
        return "QuestionTypeClass";
    }
    public function readOption
}


class BooleanChoice extends QuestionType{
    public function __toString(){
        return "BooleanChoiceClass";
    }
}
class WriteIn extends QuestionType{
    public function __toString(){
        return "WriteInClass";
    }
}
class MultipleChoice extends QuestionType{
    public function __toString(){
        return "MultipleChoiceClass";
    }
}
class OneFromMany extends QuestionType{
    public function __toString(){
        return "OneFromManyClass";
    }
}
