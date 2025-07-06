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
            Log::info('test id:'.$test_id);
            $questionType="";
            $input=$request->except('_token)');
            Log::info($input);
            foreach ($input as $key => $value) {
                //will be building this with future possibility of multiple types of questions per one question
                if (str_contains($key,'preceding')) {
                    $questionType=substr($key,0,strlen($key)-2);
                    $questionType=substr($questionType,15,strlen($questionType)-15);
                    Log::info($questionType);
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
    private function getQuestionType(String $string){
        return;
    }

}
