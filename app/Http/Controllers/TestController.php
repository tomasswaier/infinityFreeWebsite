<?php

namespace app\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
//use Illuminate\View\View;
use App\Models\Option;
use App\Models\Question;
use App\Models\Test;
//use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Boolean;

//use app\Models\Question;

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
            $test_id= $request->session()->get('test_id',-1);

            $myClass=null;
            $input=$request->except('_token','submit');
            Log::info($input);
            //Log::info('insert into db(testId,QuestionText,Explenation) values'.$test_id.$input['question_text'].$input['question_explenation']);
            if ($test_id==-1) {
                Log::error('test id is invalid');
                exit;
            }
            $question= Question::create([
                'tests_id'=>$test_id,
                'question_text'=>$input['question_text'],
                'explanation_text'=>$input['question_explanation']
            ]);
            Log::info($question);

            foreach ($input as $key => $value) {
                //will be building this with future possibility of multiple types of questions per one question
                if ($key=="question_text") {
                    continue;
                }
                if (str_contains($key,'preceding')) {
                    $questionId=$question['id'];
                    $myClass=$this->initQuestionTypeClass($key,$questionId,$value,$input);
                    if (!$myClass) {
                        Log::error("myClass doesn't exist.Exiting");
                        exit;
                    }else {
                        //Log::info($myClass);
                    }
                }elseif (str_contains($key,'explenation')) {
                    continue;
                }
                else{
                    if (!$myClass) {
                        Log::error('myClass not initiated. Most probable cause is that ');
                        break;
                    }
                    $myClass->readOption($key,$value);

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
    private function initQuestionTypeClass($key,$questionId,$precedingText,$inputs){
        $questionType=substr($key,0,strlen($key)-2);
        $questionType=substr($questionType,15,strlen($questionType)-15);
        switch ($questionType) {
            case "boolean_choice":
                $questionNumber=substr($key,30,strlen($key));
                return new BooleanChoice($questionNumber,$questionId,$precedingText,$inputs);
            case "write_in":
                $questionNumber=substr($key,24,strlen($key));
                return new WriteIn($questionNumber,$questionId,$precedingText,$inputs);
            case "multiple_choice":
                $questionNumber=substr($key,31,strlen($key));
                return new MultipleChoice($questionNumber,$questionId,$precedingText,$inputs);
            case "one_from_many":
                $questionNumber=substr($key,29,strlen($key));
                return new OneFromMany($questionNumber,$questionId,$precedingText,$inputs);
            default:
                Log::error("unknown question type:".$questionType);
                break;
        }

        return null;
    }

}

//move this somewhere ?
class QuestionType{
    //sorry for bad naming
    public $questionNumber;
    public $precedingText;
    public $questionId; // questionId is needed for inserting optionId
    public $optionId;//id of CREATED option
    public $optionNumber; // number read from the option_number_  key
    public $input; // input with all data from request
    public $data;

    function __construct($questionNumber,$questionId,$precedingText,$input){
        $this->questionNumber=$questionNumber;
        $this->precedingText=$precedingText;
        $this->questionId=$questionId;
        $this->optionId=null;
        $this->input=$input;
        $this->data=collect();
    }
    function _toString(){
        return "QuestionTypeClass";
    }
    function __destruct()
    {
        $this->optionId=$this->createQuestion('boolean-choice');
    }
    function createQuestion($option_type){
        $option = Option::create([
            'questions_id'=>$this->questionId,
            'preceding_text'=>$this->precedingText,
            'option_type'=>$option_type,
            'data'=>$this->data
        ]);
        return $option['id'];
    }
    function readOption($key,$val){


    }
}


class BooleanChoice extends QuestionType{
    public $specificOptioNumber;
    public function __toString(){
        return "BooleanChoiceClass";
    }
    private function getSpecificOptionNumber($key){
        return intval(substr($key,16,strlen($key)));
    }
    private function getOptionNumber($key){
        //we assume that there will be less than 11 boolean choice tables
        if (substr($key,0,19)=='option_text_number_') {
            return intval(substr($key,19,20));
        }
        return intval(substr($key,14,15));
    }
    public function readOption($key,$val){
        //Log::info('logging this optionId:'.$this->optionId.' new option num'.$this->getOptionNumber($key));
        if (str_contains($key,"option_number_")) {
            if (!isset($this->optionNumber) || $this->getOptionNumber($key)!=$this->optionNumber) {
                //Log::info($key);

                $this->optionNumber=$this->getOptionNumber($key);// By incrementing by 1 there could be errors
                //Log::info('I create a new Options table entry with Id:'.($this->optionId).' Precceding Text'.$this->precedingText);
                //Log::info('I create a new BooleanChoice table entry with FK:'.$this->optionId);
            }
            if ($val!='true' && $val!='false') {
                Log::error('Bad boolean choice input');
                exit;
            }
            $this->specificOptioNumber=$this->getSpecificOptionNumber($key);
            $this->data->push(['is_correct'=>($val=='true'? True :False),'option_text'=>$this->input['option_text_number_'.substr($key,14,strlen($key))]]);
            //Log::info($this->data);

            /*
            $response=BooleanChoice::create([
                'options_id'=>$this->optionId,
                'option_text'=>$this->input['option_text_number_'.substr($key,14,strlen($key))],
                'is_correct'=>boolval($val)
            ]);
             */
           //Log::info("inserting into BooleanChoiceOptions IsCorrect:".$val." Option Text:".$this->input['option_text_number_'.substr($key,14,strlen($key))]);

        }
        return;
    }

}
class WriteIn extends QuestionType{
    public function __toString(){
        return "WriteInClass";
    }
    public function readOption($key,$val){
        if (str_contains($key,"option_number_")) {
            Log::info('I create a new Options table entry with Id:imaginenumber Precceding Text'.$this->precedingText);
            Log::info("inserting into WriteIn FK:imaginenumber CorrectAnswer:".$val);
        }else {
            Log::error('$key does not contain option_number_ .Error in input key ');
        }
        return;
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
