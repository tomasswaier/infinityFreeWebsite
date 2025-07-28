<?php

namespace app\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Option;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ImageController;

use function App\supervisesClass;

//use app\Models\Question;

class TestController extends Controller
{
    public function show($school_id,Request $request)
    {
        if (!$school_id ||$school_id<1) {
            return redirect('/');
        }
        return view('test/index',[
            'tests'=>Test::where('school_id','=',$school_id)->get(),
            'school_id' =>$school_id,
        ]);
    }
    public function showTestQuestionNames($testId)
    {
        return view('admin/allTestQuestions',['data'=>Question::select('id','question_text')->where('tests_id','=',$testId)->get()]);
    }
    public function editQuestion(Request $request,$questionId)
    {
        return view('admin/editTestQuestion',[
            'question'=>Question::find($questionId),
            'options'=>Option::where('questions_id','=',$questionId)->get(),
            'images'=>(new ImageController)->show($questionId)]);
    }
    public function deleteQuestion($questionId,Request $request){
        //Log::info(json_decode($testId,true)[0]['tests_id']);
        (new ImageController)->deleteAllQuestionImages($questionId);
        $testId=Question::select('tests_id')->where('id','=',$questionId)->get();
        Question::select('tests_id')->where('id','=',$questionId)->delete();
        return redirect('admin/questionDisplay/'.json_decode($testId,true)[0]['tests_id']);
    }
    public function loadTest($feelings,$test_id,$number_of_questions,Request $request){
        if (!is_numeric($test_id)||!is_numeric($number_of_questions)) {
            return view('test/index',['tests'=>Test::all()]);
        }
        $data = Question::query()->where('tests_id','=',intval($test_id))->inRandomOrder()->limit($number_of_questions)->get();
        foreach($data as $question){
            $question['options']=Option::query()->where('questions_id','=',$question->id)->get();
            $question['image']=(new ImageController)->show($question->id);
        }
        $loadCorrectOptions=$request->session()->pull('displayCorrectAnswers',false);
        $tempModel=Test::find($test_id);
        $school_id=$tempModel->school_id;
        return view('test/index',[
            'tests'=>Test::all(),
            'data'=>$data,
            'school_id'=>$school_id,
            'displayCorrectAnswers'=>$loadCorrectOptions
        ]
        );
    }
    public function getTest(Request $request){
        //Log::info($request->all());
        if ($request['displayCorrectAnswers']==1) {
            session(['displayCorrectAnswers' => true]);
        }
        return redirect('test/idonlikeppi/'.$request['test_selector'].'/'.$request['number_of_questions']);
        //todo : learn routes
    }
    //
    public function createTest(Request $request) {
        $user=$request->user();
        $school_id=$request['school_id'];
        if (supervisesClass($user,$school_id)) {
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
        Log::info($request);
        Log::info($originalOptions);

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
        return redirect('admin')->with('success');
    }
    private  function updateImages($request,$savedImages){
        // I am just deleting all and replacign them with new ones. this is not the best approach but really doesn't matter
        $questionId=$request['question_id'];
        $prevImage=$request['prev_image'];
        foreach($savedImages as $image){
            if ($image['image_name']!=$prevImage) {
                Log::info('deleting image:');
                Log::info($image);
                (new ImageController)->delete($image->image_name);
            }
        }
        if ($request['user_image']) {
            (new ImageController)->upload($request['user_image'],$questionId);
        }
    }
    public function addQuestion(Request $request){
        try {
            //Marek is this how I should do it ? in routes set the test id to sessions and then here retreive ti ? would hidden input be better?
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
                            Log::info('Image saved');
                    }
                    else{
                        Log::info('Image did not get saved');
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
    private function insertOptionsFromArray($input,$questionId){
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
                if ($key=="question_text") {
                    continue;
                }
                if (str_contains($key,'preceding')) {
                    //compare last 2 chars of the string . it can be either option_number_x or preceding_text_something_x this way it cna hold up to 99 options
                    if ($prevOption && substr($prevOption,strlen($prevOption)-2,strlen($prevOption))!=substr($key,strlen($key)-2,strlen($key))) {
                        $prevOption=$key;
                        $optionId=null;
                    }
                    $myClass=$this->initQuestionTypeClass($key,$questionId,$value,$input,$optionId);
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
            }
    }


    private function initQuestionTypeClass($key,$questionId,$precedingText,$inputs,$optionId){
        //this could cause problems when entering more than 10 fields
        $questionType=substr($key,0,strlen($key)-2);
        $questionType=substr($questionType,15,strlen($questionType)-15);
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
    public $optionId;//id of CREATED option or of UPDATED option
    public $optionNumber; // number read from the option_number_  key
    public $input; // input with all data from request
    public $data;

    function __construct($questionNumber,$questionId,$precedingText,$input,$optionId=null){
        $this->questionNumber=$questionNumber;
        $this->precedingText=$precedingText;
        $this->questionId=$questionId;
        $this->optionId=$optionId;
        $this->input=$input;
        $this->data=collect();
    }
    function __toString(){
        return "question-type";
    }
    function __destruct()
    {
        $this->optionId=$this->createQuestion($this->__toString());
    }
    function createQuestion($option_type){
        $option = Option::find($this->optionId);
        if (!$option) {
            $option= new Option;
        }
        if ($this->optionId) {
            $option->id=$this->optionId;
        }
        $option->questions_id=$this->questionId;
        $option->preceding_text=$this->precedingText;
        $option->option_type=$option_type;
        $option->data=$this->data;
        $option->save();

        /*
        Option::create([
            'id'=>$this->optionId,
            'questions_id'=>$this->questionId,
            'preceding_text'=>$this->precedingText,
            'option_type'=>$option_type,
            'data'=>$this->data
        ]);
         */
        //return $option['id'];
    }
    function readOption($key,$val){


    }
}


class BooleanChoice extends QuestionType{
    public $specificOptioNumber;
    public function __toString(){
        return "boolean_choice";
    }
    private function getSpecificOptionNumber($key){
        return intval(substr($key,16,strlen($key)));
    }
    private function getOptionNumber($key){
        //we assume that there will be less than 11 boolean choice tables dunnu why the first if is here like it's def a option_numeber_ but wahtevs
        if (substr($key,0,19)=='option_text_number_') {
            return intval(substr($key,19,20));
        }
        return intval(substr($key,14,15));
    }
    public function readOption($key,$val){
        if (str_contains($key,"option_number_")) {
            if (!isset($this->optionNumber) || $this->getOptionNumber($key)!=$this->optionNumber) {

                $this->optionNumber=$this->getOptionNumber($key);// By incrementing by 1 there could be errors
            }
            if ($val!='true' && $val!='false') {
                Log::error('Bad boolean choice input');
                exit;
            }
            $this->specificOptioNumber=$this->getSpecificOptionNumber($key);
            $this->data->push(['is_correct'=>($val=='true'? True :False),'option_text'=>$this->input['option_text_number_'.substr($key,14,strlen($key))]]);

        }
        return;
    }

}
class BooleanChoiceOneCorrect extends QuestionType{
    public $specificOptioNumber;
    public function __toString(){
        return "boolean_choice_one_correct";
    }
    private function getSpecificOptionNumber($key){
        return intval(substr($key,16,strlen($key)));
    }
    private function getOptionNumber($key){
        //we assume that there will be less than 11 boolean choice tables dunnu why the first if is here like it's def a option_numeber_ but wahtevs
        if (substr($key,0,19)=='option_text_number_') {
            return intval(substr($key,19,20));
        }
        return intval(substr($key,14,15));
    }
    public function readOption($key,$val){
        //if (str_contains($key,"option_number_")) {
        //    if (!isset($this->optionNumber) || $this->getOptionNumber($key)!=$this->optionNumber) {

        //        $this->optionNumber=$this->getOptionNumber($key);// By incrementing by 1 there could be errors
        //    }
        //    if ($val!='true' && $val!='false') {
        //        Log::error('Bad boolean choice input');
        //        exit;
        //    }
        //    $this->specificOptioNumber=$this->getSpecificOptionNumber($key);
        //    $this->data->push(['is_correct'=>($val=='true'? True :False),'option_text'=>$this->input['option_text_number_'.substr($key,14,strlen($key))]]);

        //}
        if (!isset($this->data['option_array'])) {
            $this->data['option_array']=collect();
        }
        if (str_contains($key,"option_text_number_")) {
            $this->data['option_array']->push($val);
        }else if(str_contains($key,"correct_option_")) {
            $this->data['correct_index']=$val;
        }

        return;
    }

}
class WriteIn extends QuestionType{
    public function __toString(){
        return "write_in";
    }
    public function readOption($key,$val){
        if (str_contains($key,"option_number_")) {
            $this->data['correct_answer']=$val;
        }else {
            Log::error('$key does not contain option_number_ .Error in input key:'.$key);
        }
        return;
    }
}
class MultipleChoice extends QuestionType{
    public function __toString(){
        return "multiple_choice";
    }
    public function readOption($key,$val){

        if (!isset($this->data['column_names'])) {
            $this->data['column_names']=collect();
            $this->data['row_array']=collect();
        }
        if (str_contains($key,"column_number_")) {
            $this->data['column_names']->push($val);
        }else if (str_contains($key,"correct_option_")) {
            $rowName=$this->input['row_text_'.substr($key,15,strlen($key))];
            $this->data['row_array']->push(['row_name'=>$rowName,'correct_answer'=>$val]);
        }else if (str_contains($key,"row_text_")) {
            return;
        }else {
            Log::error('$key does not contain option_number_ .Error in input key:'.$key);
        }
        return;
    }
}
class OneFromMany extends QuestionType{
    public function __toString(){
        return "one_from_many";
    }
    public function readOption($key,$val){
        if (!isset($this->data['option_array'])) {
            $this->data['option_array']=collect();
        }
        if (str_contains($key,"option_number_")) {
            $this->data['option_array']->push($val);
        }else if (str_contains($key,"correct_option_index_")) {

            //we assume that the user will not fuck with the html..(and that the json data will be in order but i do that quite a bit here bcs i dont see reason why na) like yes you could do it very easily but omg woow gj you did something on a student for fun project ur so kewl
            $this->data['correct_option']=intval($val);
        }else{
            Log::error('$key does not contain option_number_ .Error in input key ');
        }
        return;
    }
}
class OpenAnswer extends QuestionType{
    public function __toString(){
        return "open_answer";
    }
    public function readOption($key,$val){
        return;
    }
}
