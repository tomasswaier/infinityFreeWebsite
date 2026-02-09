<?php

namespace app\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Option;
use App\Models\School;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ImageController;
use App\Helpers\QuestionCreators\BooleanChoice;
use App\Helpers\QuestionCreators\BooleanChoiceOneCorrect;
use App\Helpers\QuestionCreators\FillInTable;
use App\Helpers\QuestionCreators\MultipleChoice;
use App\Helpers\QuestionCreators\OneFromMany;
use App\Helpers\QuestionCreators\OpenAnswer;
use App\Helpers\QuestionCreators\WriteIn;
use function App\AuthHelper;

//use app\Models\Question;

class TestController extends Controller
{
    public function show($school_id,Request $request)
    {
        if (!$school_id ||$school_id<1) {
            return redirect('test/redirect');
        }
        return view('test/index',[
            'tests'=>School::find($school_id)->tests()->get(),
            'school_id' =>$school_id,
        ]);
    }
    public function showTestQuestionNames($testId)
    {
        return view('admin/test/allQuestions',[
            'data'=>Question::select('id','question_text')->where('tests_id','=',$testId)->get()
        ]);
    }
    public function editQuestion(Request $request,$questionId)
    {
        return view('admin/test/editQuestion',[
            'question'=>Question::find($questionId),
            'options'=>Question::find($questionId)->options()->get(),
            'images'=>(new ImageController)->show($questionId)]);
    }
    public function deleteQuestion($questionId,Request $request){
        (new ImageController)->deleteAllQuestionImages($questionId);
        $testId=Question::select('tests_id')->where('id','=',$questionId)->get();
        Question::select('tests_id')->where('id','=',$questionId)->delete();
        return redirect('admin/questionDisplay/'.json_decode($testId,true)[0]['tests_id']);
    }
    public function loadTest($feelings,$test_id,$number_of_questions,Request $request){
        if (!is_numeric($test_id)||!is_numeric($number_of_questions)) {
            return view('test/index',['tests'=>Test::all()]);
        }
        /*if($test_id==1 && !Auth::user()){
            return redirect('test/redirect');
        }*/
        $test=Test::find($test_id);
        $data = $test->questions()->inRandomOrder()->limit($number_of_questions)->get();
        foreach($data as $question){
            $question['options']=Question::find($question->id)->options()->get();
            $question['image']=(new ImageController)->show($question->id);
        }
        //todo:change this to an optional parameter in the url... idk why i put it into the session
        $loadCorrectOptions=$request->session()->pull('displayCorrectAnswers',false);
        $school_id=$test->school_id;
        return view('test/index',[
            'tests'=>School::find($school_id)->tests()->get(),
            'data'=>$data,
            'school_id'=>$school_id,
            'displayCorrectAnswers'=>$loadCorrectOptions,
            'test_id'=>$test_id//sry for bad naming
        ]
        );
    }
    public function getTest(Request $request){
        if ($request['displayCorrectAnswers']==1) {
            session(['displayCorrectAnswers' => true]);
        }
        return redirect('test/idonlikeppi/'.$request['test_selector'].'/'.$request['number_of_questions']);
    }
    //
    public function createTest(Request $request) {
        $user=$request->user();
        $school_id=$request['school_id'];
        if (supervisesSchool($user,$school_id)) {
            try {
                $id=Test::create([
                    'test_name'=>$request['test_name'],
                    'school_id'=>$request['school_id'],
                    'test_author'=>Auth::user()->name,
                    'number_of_submits'=>0,
                ]);
            } catch (\Throwable $e) {
                Log::error('Test creation failed: '.$e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);

            }
            return redirect('admin')->with('success', 'owo created!');
        }else{
            return redirect('admin');
        }
    }
    public function updateQuestion(Request $request){
        $questionId=$request['question_id'];
        $originalOptions=Option::select('id')->where('questions_id','=',$questionId)->get();
        $question= Question::find($questionId);
        $question->question_text=$request['question_text'];
        $question->explanation_text=$request['question_explanation'];
        $question->save();
        $images=(new ImageController)->show($questionId);
        // this is a prototype function that works only for one image and should be changed .
        if (isset($request['user_image']) || $images!=[]) {
            $this->updateImages($request,$images);

        }
        $inputs=$request->except('_token','submit','question_id','question_explanation','user_image');
        $this->insertOptionsFromArray($inputs,$questionId);

        //iterating trough the array and finding all options which are no longer in the request array
        //chatgpt line... very likely a first one
        $optionIdsInRequest = collect($request->all())->filter(function ($value, $key) {return str_starts_with($key, 'option_id');})->values()->toArray();


        $optionsToRemove = collect($originalOptions)
            ->reject(function ($option) use ($optionIdsInRequest) {
                return in_array($option->id, $optionIdsInRequest);
            });

        $optionsToRemove->each(function ($option) {
            Option::destroy($option->id);
        });
        $test=Test::find($question['tests_id']);
        return redirect('admin/questionDisplay/'.$question['tests_id']);
    }
    private  function updateImages($request,$savedImages){
        // I am just deleting all and replacign them with new ones. this is not the best approach but really doesn't matter
        $questionId=$request['question_id'];
        $prevImage=$request['prev_image'];
        foreach($savedImages as $image){
            if ($image['image_name']!=$prevImage) {
                (new ImageController)->delete($image->image_name);
            }
        }
        if ($request['user_image']) {
            (new ImageController)->upload($request['user_image'],$questionId);
        }
    }
    public function addQuestion(Request $request){
        try {
            //Not sure why I put it in session instead of a hidden input... If anyone wants they can chanage it
            $test_id= $request->session()->get('test_id',-1);


            $input=$request->except('_token','submit','question_explanation');
            if ($test_id==-1) {
                Log::error('test id is invalid');
                exit;
            }
            $question= Question::create([
                'tests_id'=>$test_id,
                'question_text'=>$input['question_text'],
                'explanation_text'=>$request['question_explanation']
            ]);

            if (isset($request['user_image'])) {
                    $response=(new ImageController)->upload($request['user_image'],$question->id);
                    if ($response==1) {
                    }
                    else{
                        Log::error('Image did not get saved');
                    }
            }

            $questionId=$question['id'];
            $this->insertOptionsFromArray($input,$questionId);


            return redirect('admin/questionCreator/'.$test_id);
        } catch (\Exception $e) {
            Log::error('Something bad failed: '.$e->getMessage());
            return response()->json(['success' => false], 500);
        }

    }
    private function shiftAfterText($input){
        if (isset($input['after_text'])) {
          //assume it's on the front of the array because go sorts them automatically.
          $afterText=array_shift($input);
          $input["after_text"]=$afterText;

        }
        return $input;
    }

    public function mcpAddQuestion(array $request){
        try {
            if (!isset($request['test_id']) || !isset($request['question_text'])|| !isset($request['options']) ) {
                Log::error('Important parameter missing: test_id '.!isset($request['test_id']).'  missing: question_text '.!isset($request['question_text']).'  missing: options '.!isset($request['options']));
                return response()->json([
                    'success'=>false,
                    'message'=>'Important parameter missing: test_id '.var_export(!isset($request['test_id']),true).'  missing: question_text '.var_export(!isset($request['question_text']),true).'  missing: options '.var_export(!isset($request['options']),true)
                ]);
            }
            $testId=$request['test_id'];
            if(!Test::find($testId)){
                return response()->json([
                    'success'=>false,
                    'message'=>'test_id has an invalid value'
                ]);
            }
            $question= Question::create([
                'tests_id'=>$testId,
                'question_text'=>$request['question_text'],
                'explanation_text'=>$request['question_explanation']
            ]);
            //Just testing atm. Images not supported atm..
            /*if (isset($request['user_image'])) {
                $response=(new ImageController)->upload($request['user_image'],$question->id);
                if ($response==1) {
                }
                else{
                    Log::error('Image did not get saved');
                }
            }*/
            $input=$this->shiftAfterText($request['options']);
            $questionId=$question['id'];
            $this->insertOptionsFromArray($input,$questionId);

            return response()->json([
                'success'=>true,
                'message'=>'Question has been added to the test'
            ]);

            //return redirect('admin/questionCreator/'.$test_id);
        } catch (\Exception $e) {
            Log::error('Something bad failed: '.$e->getMessage());
            return response()->json(['success' => false, 'message'=>'Something. Check logs'], 500);
        }

    }
    private function insertOptionsFromArray($input,$questionId){
        Log::info($input);
            $myClass=null;
            $optionId=null;
            //would be better to save a number to compare it to but im in a hurry
            $prevOption=null;
            foreach ($input as $key => $value) {
                //will be building this with future possibility of multiple types of questions per one question
                if (substr($key,0,10)=='option_id_') {
                    $optionId=$value;
                    $prevOption=$key;
                    continue;
                }
                if ($key=="question_text"||$key=="prev_image") {
                    continue;
                }
                if (str_contains($key,'preceding')) {
                    //compare last 2 chars of the string . it can be either option_number_x or preceding_text_something_x this way it cna hold up to 99 options
                    if ($prevOption && explode('_',$prevOptionp)[count(explode('_',$prevOptionp))]!=explode('_',$key)[count(explode('_',$key))]) {
                        $prevOption=$key;
                        $optionId=null;
                    }
                    $myClass=$this->initQuestionTypeClass($key,$questionId,$value,$input,$optionId);
                    if (!$myClass) {
                        Log::error("myClass doesn't exist.Exiting");
                        exit;
                    }
                }elseif (str_contains($key,'explanation')) {
                    continue;
                }
                else{
                    //Temporary fix while I'm developing mcp
                    if (!$myClass ) {
                        Log::error('myClass not initiated. Most probable cause is that values came in an unexpected order');
                        continue;
                        //break;
                    }else{
                        $myClass->readOption($key,$value);
                    }

                }
            }
    }
    private function getQuestionType($key){
        $helperArr=explode('_',$key);
        $questionType=substr($key,0,strlen($key)-strlen(last($helperArr))-1);
        $questionType=substr($questionType,15,strlen($questionType)-15);

        return $questionType;
    }

    private function initQuestionTypeClass($key,$questionId,$precedingText,$inputs,$optionId){
        $questionType=$this->getQuestionType($key);
        switch ($questionType) {
            case "boolean_choice":
                $questionNumber=substr($key,30,strlen($key));
                return new BooleanChoice($questionNumber,$questionId,$precedingText,$inputs,$optionId);
            case "boolean_choice_one_correct":
                $questionNumber=substr($key,42,strlen($key));
                return new BooleanChoiceOneCorrect($questionNumber,$questionId,$precedingText,$inputs,$optionId);
            case "write_in":
                $questionNumber=substr($key,24,strlen($key));
                return new WriteIn($questionNumber,$questionId,$precedingText,$inputs,$optionId);
            case "multiple_choice":
                $questionNumber=substr($key,31,strlen($key));
                return new MultipleChoice($questionNumber,$questionId,$precedingText,$inputs,$optionId);
            case "one_from_many":
                $questionNumber=substr($key,29,strlen($key));
                return new OneFromMany($questionNumber,$questionId,$precedingText,$inputs,$optionId);
            case "open_answer":
                $questionNumber=substr($key,27,strlen($key));
                return new OpenAnswer($questionNumber,$questionId,$precedingText,$inputs,$optionId);
            case "fill_in_table":
                $questionNumber=substr($key,29,strlen($key));
                return new FillInTable($questionNumber,$questionId,$precedingText,$inputs,$optionId);
            default:
                Log::error("unknown question type:".$questionType);
                break;
        }

        return null;
    }
    public function incrementNumberOfSubmits($testId){
        if (intval($testId)>0 ) {
            $test=Test::find($testId);
            $test->number_of_submits=$test->number_of_submits+1;
            $test->save();
        }

    }

}




