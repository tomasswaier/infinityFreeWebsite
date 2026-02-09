<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\TestController;

class McpController extends Controller
{
    public function addOpenAnswerQuestion(Request $request){
        $inputArr= array();
        foreach ($request->all() as $key => $value) {
            $inputArr[$key]=$value;
        }
        $inputArr['options']=array();
        $inputArr['options']['preceding_text_open_answer_0']='';
        $response=(new TestController)->mcpAddQuestion($inputArr);
        return $response;
    }
    public function addWriteInQuestion(Request $request){
        $inputArr= array();
        foreach ($request->all() as $key => $value) {
            $inputArr[$key]=$value;
        }

        $response=(new TestController)->mcpAddQuestion($inputArr);
        return $response;
    }
    public function addBooleanChoiceQuestionOneCorrectSimple(Request $request){
        /*
         * LLM IS UNABLE TO UTILIZE BOOLEAN CHOICE IN  CORRECT FORMAT FOR SOME REASON ({"q1"{"o1":"t/f","o2":"t/f","q2"{"o1":"t/f","o2":"t/f"}})
         * For this reason I will be using
         * Marking this as simple bcs it only accepts 1 question at a time
         */
        if (!isset($request['options'])||!isset($request['question_text'])||!isset($request['test_id'])) {
            return response()->json([
                'success'=>false,
                'message'=>'Error: Mandatory field not defined:options '.isset($request['options']).' question_text:'.isset($request['question_text']).'  test_id:'.isset($request['question_text'])
            ]);
        }
        $inputArr= array();
        if (isset($request["question_explanation"])) {
            $inputArr['question_explanation']=$request["question_explanation"];
        }
        $inputArr['question_text']=$request["question_text"];
        $inputArr['test_id']=$request["test_id"];
        $inputArr['options']=array();
        $inputArr['options']["preceding_text_boolean_choice_one_correct_0"]='';
        $i=0;
        $correctAnswerProvided=False;
        foreach($request->options as $optionText=>$isCorrect){
            $inputArr['options']["option_text_number_0_".$i]=$optionText;
            if (in_array($isCorrect,array("True","1","true","yes"))) {
                $inputArr['options']["correct_option_number_0"]="1";//was set as such before and i aint changing it to true
                $correctAnswerProvided=True;
            }
            $i++;
        }

        if ($correctAnswerProvided==False) {
            return response()->json([
                'success'=>false,
                'message'=>'Error:No corret answer provided. At least one of the options values must be set to True'
            ]);

        }

        $response=(new TestController)->mcpAddQuestion($inputArr);
        return $response;
    }
    public function addBooleanChoiceQuestionSimple(Request $request){
        /*
         * LLM IS UNABLE TO UTILIZE BOOLEAN CHOICE IN  CORRECT FORMAT FOR SOME REASON
         * this is castrated version of normal(advanced) boolean choice
         */
        if (!isset($request['options'])||!isset($request['question_text'])||!isset($request['test_id'])) {
            return response()->json([
                'success'=>false,
                'message'=>'Error: Mandatory field not defined:options '.isset($request['options']).' question_text:'.isset($request['question_text']).'  test_id:'.isset($request['question_text'])
            ]);
        }
        $inputArr= array();
        if (isset($request["question_explanation"])) {
            $inputArr['question_explanation']=$request["question_explanation"];
        }
        $inputArr['question_text']=$request["question_text"];
        $inputArr['test_id']=$request["test_id"];
        $inputArr['options']=array();
        $inputArr['options']["preceding_text_boolean_choice_0"]='';
        $i=0;
        foreach($request->options as $optionText=>$isCorrect){
            $inputArr['options']["option_number_0_".$i]=$isCorrect;
            $inputArr['options']["option_text_number_0_".$i]=$optionText;
            $i++;
        }
        $response=(new TestController)->mcpAddQuestion($inputArr);
        return $response;
    }
    public function addBooleanChoiceQuestionAdvanced(Request $request){
        /*
         * LLM IS UNABLE TO UTILIZE THIS FOR SOME REASON
         * This structure { "question1":{"option1":"True/False","option2":"True/False"}
         *                  "question2":{"option1":"True/False","option2":"True/False"}}
         *  is beyond comprehensive powers of PHD LEVEL INTELLIGENCE
         */
        if (!isset($request['options'])||!isset($request['question_text'])||!isset($request['test_id'])) {
            return response()->json([
                'success'=>false,
                'message'=>'Error: Mandatory field not defined:options '.isset($request['options']).' question_text:'.isset($request['question_text']).'  test_id:'.isset($request['question_text'])
            ]);
        }
        $inputArr= array();
        if (isset($request["question_explanation"])) {
            $inputArr['question_explanation']=$request["question_explanation"];
        }
        $inputArr['question_text']=$request["question_text"];
        $inputArr['test_id']=$request["test_id"];
        $inputArr['options']=array();
        $i=0;
        foreach($request->options as $question=>$data){
            $inputArr['options']["preceding_text_boolean_choice_".$i]=$question;
            $j=0;
            foreach ($data as $key => $value) {
                $inputArr['options']["option_number_".$i."_".$j]=$value;
                $inputArr['options']["option_text_number_".$i."_".$j]=$key;

                $j++;
            }
            $i++;

        }
        $response=(new TestController)->mcpAddQuestion($inputArr);
        return $response;
    }
}
